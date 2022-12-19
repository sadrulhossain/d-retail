<?php

namespace App\Http\Controllers;

use App\ProductContainer;
use Common;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Session;
use Validator;

class ProductContainerController extends Controller
{
    private $controller = 'ProductContainer';
    public function index(Request $request)
    {
        //passing param for custom function
        $qpArr = $request->all();
        $targetArr = ProductContainer::orderBy('order', 'asc');
        $status = ["0" => __('label.SELECT_STATUS_OPT')] + array("1" => "Active", "2" => "Inactive");
        $nameArr = ProductContainer::select('name')->orderBy('order', 'asc')->get();

        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr->where('status', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/productContainer?page=' . $page);
        }

        return view('productContainer.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status'));
    }

    public function create(Request $request)
    {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);

        return view('productContainer.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request)
    {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_container,name',
            'order' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return redirect('admin/productContainer/create' . $pageNumber)
                ->withInput()
                ->withErrors($validator);
        }

        $target = new ProductContainer;
        $target->name = $request->name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper::insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.PRODUCT_CONTAINER_CREATED_SUCCESSFULLY'));
            return redirect('admin/productContainer');
        } else {
            Session::flash('error', __('label.PRODUCT_CONTAINER_COULD_NOT_BE_CREATED'));
            return redirect('admin/productContainer/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id)
    {
        $target = ProductContainer::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/productContainer');
        }
        //calling recursive function findParentCategory

        $parentArr = Common::getAllProductCategory();
        //passing param for custom function
        $qpArr = $request->all();

        return view('productContainer.edit')->with(compact('target', 'qpArr', 'parentArr', 'orderList'));
    }

    public function update(Request $request, $id)
    { //print_r($request->all());exit;
        $target = ProductContainer::find($id);
        // return $request;
        $presentOrder = $target->order;
        // echo '<pre>';print_r($target);exit;
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_container,name,' . $id,
            'order' => 'required|not_in:0',
            'status' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return redirect('admin/productContainer/' . $id . '/edit' . $pageNumber)
                ->withInput()
                ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->order = $request->order;
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper::updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.PRODUCT_CONTAINER_UPDATED_SUCCESSFULLY'));
            return redirect('admin/productContainer' . $pageNumber);
        } else {
            Session::flash('error', __('label.PRODUCT_CONTAINER_COULD_NOT_BE_UPDATED'));
            return redirect('admin/productContainer/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id)
    {
        $target = ProductContainer::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            session()->flash('error', __('label.INVALID_DATA_ID'));
        }

         //Dependency
                $dependencyArr = [
                    'ProductCheckInMaster' => ['1' => 'product_container_id'],
                ];
                foreach ($dependencyArr as $model => $val) {
                    foreach ($val as $index => $key) {
                        $namespacedModel = '\\App\\' . $model;
                        $dependentData = $namespacedModel::where($key, $id)->first();
                        if (!empty($dependentData)) {
                            Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                            return redirect('productContainer' . $pageNumber);
                        }
                    }
                }

        if ($target->delete()) {
            Helper::deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.PRODUCT_CONTAINER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PRODUCT_CONTAINER_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/productContainer' . $pageNumber);
    }

    public function filter(Request $request)
    {
        $url = 'search=' . urlencode($request->search) . '&status=' . urlencode($request->status);
        return Redirect::to('admin/productContainer?' . $url);
    }
}
