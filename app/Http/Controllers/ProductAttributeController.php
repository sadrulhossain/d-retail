<?php

namespace App\Http\Controllers;

use Validator;
use App\ProductAttribute;
use App\AttributeType;
use App\Invoice;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller {

    private $controller = 'ProductAttribute';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = ProductAttribute::join('attribute_type','attribute_type.id','=','product_attribute.attribute_type_id')->select('product_attribute.*','attribute_type.name as type')->orderBy('order', 'asc');
        $nameArr = ProductAttribute::select('name')->orderBy('order', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        // echo'<pre>';
        // print_r($targetArr);
        // exit;
        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('product_attribute.name', 'LIKE', '%' . $searchText . '%')
                        ->orwhere('attribute_type.name', 'LIKE', '%' . $searchText . '%')
                        ->orwhere('product_attribute.product_attribute_code', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('product_attribute.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/productAttribute?page=' . $page);
        }
        return view('productAttribute.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $productAttributeList = AttributeType:: where('status','1')->orderBy('name','asc')->pluck('name','id')->toArray();
        $productAttributeArr = ['0' => __('label.SELECT_ATTRIBUTE_TYPE_OPT')] + $productAttributeList;
        return view('productAttribute.create')->with(compact('qpArr', 'orderList', 'productAttributeArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:product_attribute',
                    'product_attribute_code' => 'required|unique:product_attribute',
                    'order' => 'required|not_in:0',
                    'attributeType' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return redirect('admin/productAttribute/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new ProductAttribute;
        $target->name = $request->name;
        $target->product_attribute_code = $request->product_attribute_code;
        $target->attribute_type_id = $request->attributeType;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.PRODUCT_UNIT_CREATED_SUCCESSFULLY'));
            return redirect('admin/productAttribute');
        } else {
            Session::flash('error', __('label.PRODUCT_UNIT_COULD_NOT_BE_CREATED'));
            return redirect('admin/productAttribute/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = ProductAttribute::find($id);
        $productAttributeList = AttributeType:: where('status','1')->orderBy('name','asc')->pluck('name','id')->toArray();
        $productAttributeArr = ['0' => __('label.SELECT_ATTRIBUTE_TYPE_OPT')] + $productAttributeList;
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/productAttribute');
        }
        //passing param for custom function
        $qpArr = $request->all();
        return view('productAttribute.edit')->with(compact('target', 'qpArr','productAttributeArr','orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = ProductAttribute::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; 
        !empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:product_attribute,name,' . $id,
                    'product_attribute_code' => 'required|unique:product_attribute,product_attribute_code,'. $id,
                    'order' => 'required|not_in:0',
                    'attribute_type_id' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return redirect('admin/productAttribute/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->product_attribute_code = $request->product_attribute_code;
        $target->order = $request->order;
        $target->attribute_type_id = $request->attribute_type_id;
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.PRODUCT_UNIT_UPDATED_SUCCESSFULLY'));
            return redirect('admin/productAttribute' . $pageNumber);
        } else {
            Session::flash('error', __('label.PRODUCT_UNIT_COULD_NOT_BE_UPDATED'));
            return redirect('admin/productAttribute/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = ProductAttribute::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
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
                    return redirect('admin/productAttribute' . $pageNumber);
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
//                return redirect('productAttribute' . $pageNumber);
//            }
//        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.PRODUCT_UNIT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PRODUCT_UNIT_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/productAttribute' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('admin/productAttribute?' . $url);
    }

}
