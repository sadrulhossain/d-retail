<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use Session;
use Helper;
use Common;
use App\Country;
use App\CourierService;
use App\Division;
use App\District;
use App\Thana;
use Redirect;
use Validator;
use Response;

class WarehouseController extends Controller {

    private $controller = "Warehouse";

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
//        dd($qpArr);

        $targetArr = Warehouse::select('warehouse.*')->orderBy('order', 'asc');
        $divisionList = Division::pluck('name', 'id')->toArray();
        $districtList = District::pluck('name', 'id')->toArray();
        $thanaList = Thana::pluck('name', 'id')->toArray();

        $thana = ['0' => __('label.SELECT_THANA_OPT')] + Thana::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        //begin filtering

        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;
        $nameArr = Warehouse::select('name')->orderBy('order', 'asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('warehouse.status', '=', $request->status);
        }

        if (!empty($request->thana)) {
            $targetArr = $targetArr->where('warehouse.thana_id', '=', $request->thana);
        }

        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

//        echo "<pre>";
//        print_r($targetArr[0]['allowed_for_central_warehouse']);
//        exit;
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/warehouse?page=' . $page);
        }

        return view('warehouse.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr', 'divisionList', 'districtList', 'thanaList', 'thana'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::where('country_id', 18)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $districtList = ['0' => __('label.SELECT_DISTRICT_OPT')];
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')];
        return view('warehouse.create')->with(compact('qpArr', 'orderList', 'divisionList', 'districtList', 'thanaList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
//        echo "<pre>";
//        print_r($qpArr);
//        exit;
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';

        $rules = $message = array();
        $rules = [
            'name' => 'required|unique:warehouse',
            'address' => 'required',
            'order' => 'required|not_in:0'
        ];

        $rules['division_id'] = 'required|not_in:0';
        $rules['district_id'] = 'required|not_in:0';
        $message['division_id.not_in'] = __('label.THE_DIVISION_FIELD_IS_REQUIRED');
        $message['district_id.not_in'] = __('label.THE_DISTRICT_FIELD_IS_REQUIRED');

        $validator = Validator::make($request->all(), $rules, $message);
//        echo "<pre>";
//        print_r($validator->errors());
//        exit;

        if ($validator->fails()) {
            return redirect('admin/warehouse/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Warehouse;
        $target->name = $request->name;
        $target->division_id = $request->division_id;
        $target->district_id = $request->district_id;
        $target->thana_id = $request->thana_id;
        $target->address = $request->address;
        $target->allowed_for_central_warehouse = !empty($request->allowed_for_central_warehouse) ? $request->allowed_for_central_warehouse : '0';
        $target->order = 0;
        $target->status = $request->status;

        //Make One Warehouse as Central Warehouse
        if (!empty($request->allowed_for_central_warehouse)) {
            $prevCwh = Warehouse::where('allowed_for_central_warehouse', '1')->first();
        }

        if ($target->save()) {
            //IF Alreday Any Warehouse Assigned as Central Warehouse
            if (!empty($request->allowed_for_central_warehouse)) {
                if (!empty($prevCwh)) {
                    Warehouse::where('id', $prevCwh->id)->update(['allowed_for_central_warehouse' => '0']);
                }
            }
            Helper :: insertOrder($this->controller, $request->order, $target->id);

//            if (!empty($prevCwh)) {
//                Session::flash('error', __('label.PREVIOUS_CENTRAL_WAREHOUSE_DIVISION_DISTRICT_THANA_NEED_TO_UPDATE'));
//                return redirect('admin/warehouse/' . $prevCwh->id . '/edit' . $pageNumber);
//            }

            Session::flash('success', __('label.WAREHOUSE_CREATED_SUCCESSFULLY'));
            return redirect('admin/warehouse');
        } else {
            Session::flash('error', __('label.WAREHOUSE_COULD_NOT_BE_CREATED'));
            return redirect('admin/warehouse/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Warehouse::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::where('country_id', 18)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $districtList = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', $target->division_id)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', $target->district_id)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/warehouse');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('warehouse.edit')->with(compact('target', 'qpArr', 'orderList', 'divisionList', 'districtList', 'thanaList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Warehouse::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $rules = $message = array();
        $rules = [
            'name' => 'required|unique:warehouse,name,' . $id,
            'address' => 'required',
            'order' => 'required|not_in:0'
        ];

        $rules['division_id'] = 'required|not_in:0';
        $rules['district_id'] = 'required|not_in:0';
        $message['division_id.not_in'] = __('label.THE_DIVISION_FIELD_IS_REQUIRED');
        $message['district_id.not_in'] = __('label.THE_DISTRICT_FIELD_IS_REQUIRED');


        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect('admin/warehouse/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->division_id = $request->division_id;
        $target->district_id = $request->district_id;
        $target->thana_id = $request->thana_id;

        $target->address = $request->address;
        $target->allowed_for_central_warehouse = !empty($request->allowed_for_central_warehouse) ? $request->allowed_for_central_warehouse : '0';
        $target->order = $request->order;
        $target->status = $request->status;

        //Make One Warehouse as Central Warehouse
        if (!empty($request->allowed_for_central_warehouse)) {
            $prevCwh = Warehouse::where('allowed_for_central_warehouse', '1')->first();
//            dd($prevCwh);
        }

        if ($target->save()) {
            //IF Alreday Any Warehouse Assigned as Central Warehouse
            if (!empty($request->allowed_for_central_warehouse)) {
                if (!empty($prevCwh)) {
                    Warehouse::where('id', $prevCwh->id)->where('id', '!=', $target->id)->update(['allowed_for_central_warehouse' => '0']);
                }
            }
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }

//            if (!empty($prevCwh)) {
//                Session::flash('error', __('label.PREVIOUS_CENTRAL_WAREHOUSE_DIVISION_DISTRICT_THANA_NEED_TO_UPDATE'));
//                return redirect('admin/warehouse/' . $prevCwh->id . '/edit' . $pageNumber);
//            }

            Session::flash('success', __('label.WAREHOUSE_UPDATED_SUCCESSFULLY'));
            return redirect('admin/warehouse' . $pageNumber);
        } else {
            Session::flash('error', __('label.WAREHOUSE_COULD_NOT_BE_UPDATED'));
            return redirect('admin/warehouse/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {

        $target = Warehouse::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'UserToWarehouse' => ['1' => 'warehouse_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('admin/warehouse' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.WAREHOUSE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.WAREHOUSE_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/warehouse' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status . '&thana=' . $request->thana;
        return Redirect::to('admin/warehouse?' . $url);
    }

    public function getDistrictToCreate(Request $request) {
        return Common::getDistrict($request, 'warehouse');
    }

    public function getThanaToCreate(Request $request) {
        return Common::getThana($request, 'warehouse');
    }

    public function getCheckCwh(Request $request) {
        $target = Warehouse::where('allowed_for_central_warehouse', '1')->first();
        $name = $target->name;
//        dd($name);
        return response()->json(['name' => $name]);
    }
    public function changeCwh(Request $request) {
        $id = $request->id;
        $setNewCwh = Warehouse::where('id', $id)->update(['allowed_for_central_warehouse' => '1']);
        $removeOldCwh = Warehouse::where('id', '<>',  $id)->update(['allowed_for_central_warehouse' => '0']);
//        dd($name);
        if ($setNewCwh && $removeOldCwh) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.WH_HAS_BEEN_MARKED_AS_CENTRAL_WH_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_MARK_WH_AS_CENTRAL_WH')), 401);
        }
    }

}
