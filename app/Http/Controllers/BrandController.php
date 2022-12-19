<?php

namespace App\Http\Controllers;

use Validator;
use App\Brand;
use App\Product;
use App\Country;
use Session;
use Redirect;
use File;
use Auth;
use Response;
use Illuminate\Http\Request;

class BrandController extends Controller {

    private $controller = 'Brand';
    private $fileSize = '1024';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $productArr = Product::orderBy('name', 'asc')->where('status', '1')->pluck('name', 'id')->toArray();
        
        $targetArr = Brand::leftJoin('country', 'country.id', '=', 'brand.origin')
                ->leftJoin('product','product.id','=','brand.manufactured_product')
                ->select('brand.*', 'country.name as origin','product.name as product')->orderBy('brand.name', 'asc');
        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('brand.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('brand.status', '=', $request->status);
        }
        if (!empty($request->origin)) {
            $targetArr = $targetArr->where('brand.origin', $request->origin);
        }


        //end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        $nameArr = Brand::select('name')->orderBy('name', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $originArr = array('0' => __('label.SELECT_ORIGIN_OPT')) + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/brand?page=' . $page);
        }

        return view('brand.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status', 'originArr','productArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $originArr = array('0' => __('label.SELECT_ORIGIN_OPT')) + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productArr = Product::orderBy('name', 'asc')->where('status', '1')->pluck('name', 'id')->toArray();
        return view('brand.create')->with(compact('qpArr', 'originArr', 'productArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update
        $message = [];
        $rules = [
            'name' => 'required|unique:brand',
            'brand_code' => 'required|unique:brand',
            'origin' => 'required|not_in:0',
//            'manufactured_product' => 'required',
        ];

        if (!empty($request->logo)) {
            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
        }



        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }


        //file upload
        $file = $request->file('logo');
        if (!empty($file)) {
            $logoName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/brand', $logoName);
        }

        $target = new Brand;
        $target->name = $request->name;
        $target->brand_code = $request->brand_code;
        $target->manufactured_product = !empty($request->manufactured_product) ? implode(',',$request->manufactured_product):'';
        $target->description = $request->description;
        $target->logo = !empty($logoName) ? $logoName : '';
        $target->origin = $request->origin;
        $target->status = $request->status;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.BRAND_CREATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.BRAND_COULD_NOT_BE_CREATED')), 401);
        }
    }

    public function edit(Request $request, $id) {
        $target = Brand::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/brand');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $productArr = Product::orderBy('name', 'asc')->where('status', '1')->pluck('name', 'id')->toArray();
        $originArr = array('0' => __('label.SELECT_ORIGIN_OPT')) + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        return view('brand.edit')->with(compact('target', 'qpArr', 'originArr','productArr'));
    }

    public function update(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        exit;

        $target = Brand::find($request->id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $message = [];
        $rules = [
            'name' => 'required|unique:brand,name,' . $request->id,
            'brand_code' => 'required|unique:brand,brand_code,' . $request->id,
            'origin' => 'required|not_in:0',
//            'manufactured_product' => 'required',
        ];

        if (!empty($request->logo)) {
            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
        }


        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }


        //ENDOF PREV FILE EXISTS

        if (!empty($request->logo)) {
            $prevLogoName = 'public/uploads/brand/' . $target->logo;

            if (File::exists($prevLogoName)) {
                File::delete($prevLogoName);
            }
        }


        $file = $request->file('logo');
        if (!empty($file)) {
            $logoName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/brand', $logoName);
        }
        $target->name = $request->name;
        $target->brand_code = $request->brand_code;
        $target->description = $request->description;
        $target->manufactured_product = !empty($request->manufactured_product) ? implode(',',$request->manufactured_product):'';
        $target->logo = !empty($logoName) ? $logoName : $target->logo;
        $target->origin = $request->origin;
        $target->status = $request->status;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.BRAND_UPDATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.BRAND_COULD_NOT_BE_UPDATED')), 401);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Brand::find($id);


        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency check
        $dependencyArr = [
            'ProductToBrand' => ['1' => 'brand_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('brand' . $pageNumber);
                }
            }
        }
        //end :: dependency check
        //If Previous Logo Attached and Moved to public folder,Please Delete it from public folder
        $fileName = 'public/uploads/brand/' . $target->logo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }



        if ($target->delete()) {
            Session::flash('error', __('label.BRAND_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.BRAND_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/brand' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status . '&origin=' . $request->origin;
        return Redirect::to('admin/brand?' . $url);
    }

}
