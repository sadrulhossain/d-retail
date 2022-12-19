<?php

namespace App\Http\Controllers;

use Validator;
use App\News;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class NewsController extends Controller {

    private $controller = 'News';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = News::select('*')->orderBy('created_at', 'desc');
        $nameArr = News::select('title')->orderBy('created_at', 'desc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');

        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('title', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('product_unit.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/news?page=' . $page);
        }

        return view('news.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('news.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
//        echo '<pre>';        print_r($qpArr);exit;
        
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'description' => 'required',
                    'publication_date' => 'required',
        ]);

        
        if (!empty($request->photo)) {
            $rules['photo'] = 'max:1024|mimes:jpeg,png,jpg';
        }
        
        if ($validator->fails()) {
            return redirect('admin/news/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }
        
        //file upload
        $file = $request->file('photo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/news', $fileName);
        }

        $target = new News;
        $target->title = $request->title;
        $target->description = $request->description;
        $target->image = !empty($fileName) ? $fileName : '';
        $target->publication_date = Helper::dateFormatConvert($request->publication_date);
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.PRODUCT_UNIT_CREATED_SUCCESSFULLY'));
            return redirect('admin/news');
        } else {
            Session::flash('error', __('label.PRODUCT_UNIT_COULD_NOT_BE_CREATED'));
            return redirect('admin/news/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = ProductUnit::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/productUnit');
        }
        //passing param for custom function
        $qpArr = $request->all();
        return view('productUnit.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = ProductUnit::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:product_unit,name,' . $id,
                    'order' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return redirect('admin/productUnit/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->order = $request->order;
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.PRODUCT_UNIT_UPDATED_SUCCESSFULLY'));
            return redirect('admin/productUnit' . $pageNumber);
        } else {
            Session::flash('error', __('label.PRODUCT_UNIT_COULD_NOT_BE_UPDATED'));
            return redirect('admin/productUnit/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = ProductUnit::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'Product' => ['1' => 'product_unit_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('admin/productUnit' . $pageNumber);
                }
            }
        }

//        $invoiceInfo = Invoice::select('order_no_history')->get();
//        $invoiceArr = $productUnitIdArr = [];
//        if (!$invoiceInfo->isEmpty()) {
//            foreach ($invoiceInfo as $item) {
//                $invoiceArr[] = json_decode($item->order_no_history, true);
//            }
//
//            foreach ($invoiceArr as $values) {
//                foreach ($values as $val) {
//                    if (!empty($val['unit_wise_gty'])) {
//                        foreach ($val['unit_wise_gty'] as $unitId => $item) {
//                            $productUnitIdArr[$unitId] = $unitId;
//                        }
//                    }
//                }
//            }
//
//            if (array_key_exists($id, $productUnitIdArr)) {
//                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => 'Invoice']));
//                return redirect('productUnit' . $pageNumber);
//            }
//        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.PRODUCT_UNIT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PRODUCT_UNIT_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/productUnit' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('admin/productUnit?' . $url);
    }

}
