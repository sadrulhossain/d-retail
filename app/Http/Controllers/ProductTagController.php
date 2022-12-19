<?php

namespace App\Http\Controllers;

use Validator;
use App\ProductTag;
use App\Invoice;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class ProductTagController extends Controller {

    private $controller = 'ProductTag';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = ProductTag::select('product_tag.*')->orderBy('name', 'asc');
        $nameArr = ProductTag::select('name')->orderBy('name', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');

        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('product_tag.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/productTag?page=' . $page);
        }

        return view('productTag.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('productTag.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:product_tag',
        ]);

        if ($validator->fails()) {
            return redirect('admin/productTag/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new ProductTag;
        $target->name = $request->name;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.PRODUCT_TAG_CREATED_SUCCESSFULLY'));
            return redirect('admin/productTag');
        } else {
            Session::flash('error', __('label.PRODUCT_TAG_COULD_NOT_BE_CREATED'));
            return redirect('admin/productTag/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = ProductTag::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/productTag');
        }
        //passing param for custom function
        $qpArr = $request->all();
        return view('productTag.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = ProductTag::find($id);

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:product_tag,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect('admin/productTag/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.PRODUCT_TAG_UPDATED_SUCCESSFULLY'));
            return redirect('admin/productTag' . $pageNumber);
        } else {
            Session::flash('error', __('label.PRODUCT_TAG_COULD_NOT_BE_UPDATED'));
            return redirect('admin/productTag/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = ProductTag::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'ProductToTag' => ['1' => 'product_tag_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('productTag' . $pageNumber);
                }
            }
        }

//        $invoiceInfo = Invoice::select('order_no_history')->get();
//        $invoiceArr = $ProductTagIdArr = [];
//        if (!$invoiceInfo->isEmpty()) {
//            foreach ($invoiceInfo as $item) {
//                $invoiceArr[] = json_decode($item->order_no_history, true);
//            }
//
//            foreach ($invoiceArr as $values) {
//                foreach ($values as $val) {
//                    if (!empty($val['unit_wise_gty'])) {
//                        foreach ($val['unit_wise_gty'] as $unitId => $item) {
//                            $ProductTagIdArr[$unitId] = $unitId;
//                        }
//                    }
//                }
//            }
//
//            if (array_key_exists($id, $ProductTagIdArr)) {
//                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => 'Invoice']));
//                return redirect('productTag' . $pageNumber);
//            }
//        }

        if ($target->delete()) {
            Session::flash('error', __('label.PRODUCT_TAG_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PRODUCT_TAG_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/productTag' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('admin/productTag?' . $url);
    }

}
