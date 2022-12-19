<?php

namespace App\Http\Controllers;

use App\District;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Division;
use App\User;
use Illuminate\Http\Request;
use App\Retailer;
use App\Cluster;
use App\Zone;
use App\Thana;
use App\WarehouseToRetailer;
use App\Order;
use Session;
use Helper;
use File;
use Response;
use Auth;
use Common;
use Redirect;
use Validator;
use DB;

class RetailerController extends Controller {

    private $controller = "Retailer";

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Retailer::with('warehouseToRetailer','srToRetailer','user','sr')->select('retailer.*')->orderBy('order', 'asc');

        //begin filtering

        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');

        $searchText = $request->search;
        $nameArr = Retailer::select('code')->orderBy('order', 'asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('code', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('retailer.status', '=', $request->status);
        }

        $registeredBy = array('0' => __('label.ALL'), '1' => 'Admin', '2' => 'Retailer/Distribution');

        if (!empty($request->registered_by) && $request->registered_by != '0') {
            if ($request->registered_by == '1') {
                $targetArr = $targetArr->where('retailer.by_rtl_dist', '=', '0');
            }
            if ($request->registered_by == '2') {
                $targetArr = $targetArr->where('retailer.by_rtl_dist', '=', '1');
            }
        }

        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/retailer?page=' . $page);
        }

        return view('retailer.index')->with(compact('targetArr', 'qpArr', 'status', 'registeredBy', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();

        $clusterList = array('0' => __('label.SELECT_CLUSTER_OPT')) + Cluster::orderBy('order')->where('status', 1)->pluck('name', 'id')->toArray();
        $zoneList = array('0' => __('label.SELECT_ZONE_OPT'));
        $typeList = ['0' => __('label.SELECT_TYPE_OPT'), '1' => __('label.RETAILER_'), '2' => __('label.DISTRIBUTOR_')];
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('retailer.create')->with(compact('qpArr', 'orderList', 'typeList', 'clusterList', 'zoneList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $rules = $message = array();
        $rules = [
            'name' => 'required|unique:retailer',
            'code' => 'required|unique:retailer',
            'address' => 'required',
            'order' => 'required|not_in:0',
            'type' => 'required|not_in:0'
        ];

        if (!empty($request->logo)) {
            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
        }


        if (!empty($request->contact_name)) {
            $row = 0;
            foreach ($request->contact_name as $key => $name) {
                $rules['contact_name.' . $key] = 'required';
                $rules['contact_phone.' . $key] = 'required';

                //set messages for error
                $message['contact_name.' . $key . '.required'] = __('label.NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_phone.' . $key . '.required'] = __('label.PHONE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);

                $row++;
            }
        }



        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        //logo upload
        $file = $request->file('logo');
        if (!empty($file)) {
            $logoName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/retailer', $logoName);
        }


        $contactPersonDataArr = [];

        //Prepare Contact Person Data as Array
        if (!empty($request->contact_name)) {
            foreach ($request->contact_name as $uniqueKey => $name) {
                $contactPersonDataArr[$uniqueKey]['name'] = $name;
                $contactPersonDataArr[$uniqueKey]['phone'] = !empty($request->contact_phone[$uniqueKey]) ? $request->contact_phone[$uniqueKey] : '';
                $contactPersonDataArr[$uniqueKey]['remarks'] = !empty($request->remarks[$uniqueKey]) ? $request->remarks[$uniqueKey] : '';
            }
        }

        $target = new Retailer;
        $target->name = $request->name;
        $target->code = $request->code;
        $target->longitude = $request->longitude;
        $target->type = $request->type ?? '1';
        $target->cluster_id = $request->cluster_id ?? 0;
        $target->zone_id = $request->zone_id ?? 0;
        $target->latitude = $request->latitude;
        $target->address = $request->address;
        $target->logo = !empty($logoName) ? $logoName : null;
        $target->order = 0;
        $target->status = $request->status;
        $target->approval_status = '1';
        $target->approved_by = Auth::id();
        $target->by_rtl_dist = '0';
        $target->contact_person_data = !empty($contactPersonDataArr) ? json_encode($contactPersonDataArr) : [];

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.RETAILER_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.RETAILER_COULD_NOT_BE_CREATED')], 401);
        }
    }

    public function edit(Request $request, $id) {
        $target = Retailer::find($id);
        $clusterList = array('0' => __('label.SELECT_CLUSTER_OPT')) + Cluster::orderBy('order')->where('status', 1)->pluck('name', 'id')->toArray();
        $zoneList = array('0' => __('label.SELECT_ZONE_OPT')) + Zone::where('cluster_id', $target->cluster_id)->pluck('name', 'id')->toArray();
        $typeList = ['0' => __('label.SELECT_TYPE_OPT'), '1' => __('label.RETAILER_'), '2' => __('label.DISTRIBUTOR_')];
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/retailer');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $prevContactPersonArr = !empty($target->contact_person_data) ? json_decode($target->contact_person_data, true) : [];
        return view('retailer.edit')->with(compact('target', 'qpArr', 'orderList', 'prevContactPersonArr', 'typeList', 'clusterList', 'zoneList'));
    }

    public function update(Request $request) {
        $id = $request->id;
        //begin back same page after update
        $qpArr = $request->all();

        $target = Retailer::find($id);

        $presentOrder = $target->order;
        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $rules = $message = array();
        $rules = [
            'name' => 'required|unique:retailer,name,' . $id,
            'code' => 'required|unique:retailer,code,' . $id,
            'address' => 'required',
            'order' => 'required|not_in:0'
        ];

        if (!empty($request->logo)) {
            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
        }


        if (!empty($request->contact_name)) {
            $row = 0;
            foreach ($request->contact_name as $key => $name) {
                $rules['contact_name.' . $key] = 'required';
                $rules['contact_phone.' . $key] = 'required';

                $message['contact_name.' . $key . '.required'] = __('label.NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_phone.' . $key . '.required'] = __('label.PHONE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        if (!empty($request->logo)) {
            $prevfileName = 'public/uploads/retailer/' . $target->logo;

            if (File::exists($prevfileName)) {
                File::delete($prevfileName);
            }
        }

        //logo upload
        $file = $request->file('logo');
        if (!empty($file)) {
            $logoName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/retailer', $logoName);
        }


        $contactPersonDataArr = [];
        //Prepare Contact Person Data as Array
        if (!empty($request->contact_name)) {
            foreach ($request->contact_name as $identifier => $name) {
                $contactPersonDataArr[$identifier]['name'] = $name;
                $contactPersonDataArr[$identifier]['phone'] = !empty($request->contact_phone[$identifier]) ? $request->contact_phone[$identifier] : '';
                $contactPersonDataArr[$identifier]['remarks'] = !empty($request->remarks[$identifier]) ? $request->remarks[$identifier] : '';
            }
        }


        $target->name = $request->name;
        $target->code = $request->code;
        $target->type = $request->type ?? $target->type;
        $target->cluster_id = $request->cluster_id ?? $target->cluster_id;
        $target->zone_id = $request->zone_id ?? $target->zone_id;
        $target->longitude = $request->longitude;
        $target->latitude = $request->latitude;
        $target->address = $request->address;
        $target->logo = !empty($logoName) ? $logoName : $target->logo;
        $target->order = $request->order;
        $target->status = $request->status;
        $target->contact_person_data = !empty($contactPersonDataArr) ? json_encode($contactPersonDataArr) : [];

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper::updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.RETAILER_UPDATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.RETAILER_COULD_NOT_BE_UPDATED')], 401);
        }
    }

    public function destroy(Request $request, $id) {

        $target = Retailer::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'WarehouseToRetailer' => ['1' => 'retailer_id'],
            'Order' => ['1' => 'retailer_id'],
            'Invoice' => ['1' => 'retailer_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('admin/retailer' . $pageNumber);
                }
            }
        }

        $fileName = 'public/uploads/retailer/' . $target->logo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }

        if ($target->delete()) {
            !empty($target->user_id) ? User::find($target->user_id)->delete() : '';
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.RETAILER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.RETAILER_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/retailer' . $pageNumber);
    }

    public function newContactPersonToCreate() {
        return Common::retailerContactPerson();
    }

    public function newContactPersonToEdit() {
        return Common::retailerContactPerson();
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) .
                '&status=' . $request->status .
                '&registered_by=' . $request->registered_by;
        return Redirect::to('admin/retailer?' . $url);
    }

    public function getDetailsOfContactPerson(Request $request) {
        $target = Retailer::find($request->retailer_id);
        $retailerName = $target->name;
        $contactPersonArr = !empty($target->contact_person_data) ? json_decode($target->contact_person_data, true) : [];
        $view = view('retailer.showContactPersonDetails', compact('contactPersonArr', 'request', 'retailerName'))->render();
        return Response::json(['html' => $view]);
    }

    public function getRetailerAdditionalInfo(Request $request) {

        $target = Retailer::find($request->retailerId);
        $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::where('country_id', 18)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $districtList = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::select('name', 'id')->pluck('name', 'id')->toArray();
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')] + Thana::select('name', 'id')->pluck('name', 'id')->toArray();
        $infrastructureTypeList = ['0' => __('label.SELECT_INFRASTRUCTURE_TYPE_OPT'), '1' => __('label.PERMANENT'), '2' => __('label.TEMPORARY')];
        //rendering view
        $html = view('retailer.addAdditionalInfo', compact('target', 'divisionList', 'infrastructureTypeList', 'thanaList', 'districtList'))->render();
        return Response::json(['html' => $html]);
    }

    public function getDistrict(Request $request) {
        $districtList = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', $request->divisionId)->pluck('name', 'id')->toArray();
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')];
        //rendering views
        $html = view('retailer.showDistrict', compact('districtList'))->render();
        $html2 = view('retailer.showThana', compact('thanaList'))->render();
        return Response::json(['html' => $html, 'html2' => $html2]);
    }

    public function getThana(Request $request) {
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', $request->thanaId)->pluck('name', 'id')->toArray();
        //rendering view
        $html = view('retailer.showThana', compact('thanaList'))->render();
        return Response::json(['html' => $html]);
    }

    public function getZone(Request $request) {
        $zoneList = ['0' => __('label.SELECT_ZONE_OPT')] + Zone::where('cluster_id', $request->clusterId)->pluck('name', 'id')->toArray();
        //rendering view
        $html = view('retailer.showZone', compact('zoneList'))->render();
        return Response::json(['html' => $html]);
    }

    public function setRetailerAdditionalInfo(Request $request) {
        $rules = [
            'owner_name' => 'required',
        ];
        $messages = array();

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }

        $target = Retailer::find($request->id);
        $target->owner_name = $request->owner_name;
        $target->infrastructure_type = $request->infrastructure_type ?? '1';
        $target->avg_monthly_transaction_value = $request->avg_monthly_transaction_value;
        $target->has_bank_account = !empty($request->has_bank_account) ? $request->has_bank_account : '0';
        $target->nid_passport = $request->nid_passport;
        $target->division = $request->division;
        $target->district = $request->district;
        $target->thana = $request->thana;

        if ($target->save()) {
            return Response::json(array('heading' => 'Success', 'message' => __('label.RETAILER_INFO_UPDATED_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('heading' => 'Error', 'message' => __('label.RETAILER_INFO_COULD_NOT_UPDATED')), 401);
        }
    }

    public function getRetailerLoginInformation(Request $request) {
        $target = Retailer::find($request->retailer_id);
        $html = view('retailer.showLoginInformation', compact('target'))->render();
        return Response::json(['html' => $html]);
    }

    public function approve(Request $request) {

        $retailer = Retailer::with('warehouseToRetailer','srToRetailer')->where('id', $request->approved_id)->first();

        // Checking retailer has relation with both
        if ($retailer->warehouseToRetailer && $retailer->srToRetailer) {
            $retailer->approval_status = '1'; // 0=Pending, 1= Approved
            $retailer->by_rtl_dist = '0';   // 0= No, 1=Yes
            $retailer->approved_by = Auth::user()->id;
            $retailer->approved_at = date('Y-m-d H:i:s');
            DB::beginTransaction();
            try {
                if ($retailer->save()) {
                    $updateUser = User::where('id', $retailer->user_id)->update(["status" => "1"]);
                    DB::commit();
                    return Response::json(['success' => true, 'message' => __('label.RETAILER_HAS_BEEN_APPROVED')], 200);
                }
            } catch (Exception $ex) {
                DB::rollback();
                return Response::json(array('success' => false, 'message' => __('label.RETAILER_COULD_NOT_BE_APPROVED')), 401);
            }
        } else {
            Session::flash('error', __('label.PLEASE_ASSIGN_THIS_RETAILER_TO_WAREHOUSE_AND_TO_A_SR'));
            return Response::json(array('success' => false, 'message' => __('label.RETAILER_COULD_NOT_BE_APPROVED_PLEASE_COMPLETE_PROFILE')), 401);
        }
    }

    public function deny(Request $request) {

        $id = $request->retailerId;
        $target = Retailer::find($id);

        if (empty($target)) {
            return Response::json(array('success' => false, 'message' => __('label.INVALID_DATA_ID')), 401);
        }
        //delete image
        $fileName = 'public/uploads/retailer/' . $target->logo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }

        if ($target->delete()) {
            !empty($target->user_id) ? User::find($target->user_id)->delete() : '';
            Helper :: deleteOrder($this->controller, $target->order);
            return Response::json(['success' => true, 'message' => __('label.RETAILER_DENIED_SUCCESSFULLY')], 200);
//            Session::flash('error', __('label.RETAILER_DENIED_SUCCESSFULLY'));
        } else {
            return Response::json(array('success' => false, 'message' => __('label.RETAILER_COULD_NOT_BE_DENIED')), 401);
//            Session::flash('error', __('label.RETAILER_COULD_NOT_BE_DENIED'));
        }
        return redirect('admin/retailer' . $pageNumber);
    }

    public function setRetailerLoginInformation(Request $request) {
        $target = Retailer::find($request->id);
//        $mobile = null;
        $password = Hash::make($request->password);

//        if (!empty($target->contact_person_data)) {
//            foreach (json_decode($target->contact_person_data,false) as $contact) {
//                $mobile = $contact->phone;
//                break;
//            }
//        }
        $validator = Validator::make($request->all(), [
                    "username" => "required|alpha_num|unique:retailer,username," . $request->id,
                    "password" => "required",
                    "conf_password" => "required|same:password"
                        ], [
                    "conf_password.required" => "Confirm password field is required.",
                    "conf_password.same" => "Password does not match."
        ]);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        $userCount = User::where('username', $request->username)->get();
        if ($userCount->count() > 1) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.USERNAME_ALREADY_EXISTS')), 400);
        }
        $target->username = $request->username;
        $target->password = $password;
        try {
            DB::beginTransaction();
            if ($target->save()) {
                $user = !empty($target->user_id) ? User::find($target->user_id) : New User;
                $user->group_id = ($target->type == '1') ? 19 : 18;
                $user->first_name = $target->name;
                $user->nick_name = $target->code;
                $user->username = $target->username;
                $user->password = $password;
                $user->photo = $target->logo;
//                $user->phone = $mobile ?? null;
                $user->nid_passport = $target->nid_passport;

                if ($user->save()) {
                    Retailer::where('id', $request->id)->update(['user_id' => $user->id]);
                }
                DB::commit();
                return Response::json(array('success' => false, 'heading' => 'Success', 'message' => __('label.RETAILER_LOGIN_INFORMATION_SAVED_SUCCESSFULLY')), 200);
            }
        } catch (\Throwable $e) {
            DB::rollback();
//            print_r(json_encode($e));
            return Response::json(array('success' => false, 'heading' => 'Error', 'message' => json_encode($e) . __('label.RETAILER_LOGIN_INFORMATION_COULD_NOT_BE_SAVED')), 401);
        }
    }

    public function showProfileCompitionStatus(Request $request) {
        $id = $request->retailer_id ?? 0;

        $target = Retailer::with('warehouseToRetailer','srToRetailer','user','sr')->find($id);
        if ($target) {
            $html = view('retailer.showProfileStatus', compact('target'))->render();
            return Response::json(['html' => $html]);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.INVALID_DATA_ID')), 401);
        }
    }

}
