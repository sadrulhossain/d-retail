<?php

namespace App\Http\Controllers;

use Validator;
use App\Supplier; //model class
use App\Bank;
use App\Country; //model class
use App\ContactDesignation; //model class
use Common;
use Session;
use Redirect;
use Auth;
use File;
use Response;
use Image;
use Helper;
use Illuminate\Http\Request;

class SupplierController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Supplier::select('supplier.*')->orderBy('name', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = Supplier::select('name')->orderBy('name', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('supplier.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('supplier.status', '=', $request->status);
        }


        //end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/supplier?page=' . $page);
        }


        return view('supplier.index')->with(compact('qpArr', 'targetArr', 'nameArr', 'status'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')->pluck('name', 'id')->toArray();
        return view('supplier.create')->with(compact('qpArr', 'designationList'));
    }

    public function store(Request $request) {
        //passing param for custom function

        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = $message = array();
        $rules = [
            'name' => 'required|unique:supplier,name',
        ];

        if (!empty($request->contact_name)) {
            $row = 0;
            foreach ($request->contact_name as $key => $name) {
                $rules['contact_name.' . $key] = 'required';
                $rules['contact_email.' . $key] = 'required';
                $rules['contact_phone.' . $key] = 'required';

                //set messages for error

                $message['contact_name.' . $key . '.required'] = __('label.NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_email.' . $key . '.required'] = __('label.EMAIL_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_phone.' . $key . '.required'] = __('label.PHONE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $contactPersonDataArr = [];
        //Prepare Contact Person Data as Array
        if (!empty($request->contact_name)) {
            foreach ($request->contact_name as $uniqueKey => $name) {
                $contactPersonDataArr[$uniqueKey]['name'] = $name;
                $contactPersonDataArr[$uniqueKey]['designation_id'] = !empty($request->designation_id[$uniqueKey]) ? $request->designation_id[$uniqueKey] : '';
                $contactPersonDataArr[$uniqueKey]['email'] = !empty($request->contact_email[$uniqueKey]) ? $request->contact_email[$uniqueKey] : '';
                $contactPersonDataArr[$uniqueKey]['phone'] = !empty($request->contact_phone[$uniqueKey]) ? $request->contact_phone[$uniqueKey] : '';
                $contactPersonDataArr[$uniqueKey]['note'] = !empty($request->special_note[$uniqueKey]) ? $request->special_note[$uniqueKey] : '';
            }
        }

        $target = new Supplier;
        $target->name = $request->name;
        $target->code = $request->code;
        $target->address = $request->address;
        $target->contact_person_data = json_encode($contactPersonDataArr);
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.SUPPLIER_CREATED_SUCCESSFULLY'));
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.SUPPLIER_CREATED_SUCCESSFULLY')], 200);
        } else {
//            Session::flash('error', __('label.SUPPLIER_NOT_BE_CREATED'));
//            return redirect('supplier/create' . $pageNumber);
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.SUPPLIER_NOT_BE_CREATED')], 401);
        }
    }

    public function edit(Request $request, $id) {
        //passing param for custom function
        $qpArr = $request->all();

        $target = Supplier::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/supplier');
        }
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')->pluck('name', 'id')->toArray();
        $prevContactPersonArr = json_decode($target->contact_person_data, true);
        return view('supplier.edit')->with(compact('qpArr', 'target', 'designationList', 'prevContactPersonArr'));
    }

    public function update(Request $request) {
        //sleep(3);
//        echo '<pre>';
//        print_r($request->all());
//        exit;

        $target = Supplier::find($request->id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update
        $rules = $message = array();
        $rules = [
            'name' => 'required|unique:supplier,name,' . $request->id,
        ];


        if (!empty($request->contact_name)) {
            $row = 0;
            foreach ($request->contact_name as $key => $name) {
                $rules['contact_name.' . $key] = 'required';
                $rules['contact_email.' . $key] = 'required';
                $rules['contact_phone.' . $key] = 'required';

                $message['contact_name.' . $key . '.required'] = __('label.NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_email.' . $key . '.required'] = __('label.EMAIL_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_phone.' . $key . '.required'] = __('label.PHONE_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);

                $row++;
            }
        }


        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $contactPersonDataArr = [];
        //Prepare Contact Person Data as Array
        if (!empty($request->contact_name)) {
            foreach ($request->contact_name as $identifier => $name) {
                $contactPersonDataArr[$identifier]['name'] = $name;
                $contactPersonDataArr[$identifier]['designation_id'] = !empty($request->designation_id[$identifier]) ? $request->designation_id[$identifier] : '';
                $contactPersonDataArr[$identifier]['email'] = !empty($request->contact_email[$identifier]) ? $request->contact_email[$identifier] : '';
                $contactPersonDataArr[$identifier]['phone'] = !empty($request->contact_phone[$identifier]) ? $request->contact_phone[$identifier] : '';
                $contactPersonDataArr[$identifier]['note'] = !empty($request->special_note[$identifier]) ? $request->special_note[$identifier] : '';
            }
        }

        $target->name = $request->name;
        $target->code = $request->code;
        $target->address = $request->address;
        $target->contact_person_data = json_encode($contactPersonDataArr);
        $target->status = $request->status;
        if ($target->save()) {
            Session::flash('success', __('label.SUPPLIER_UPDATED_SUCCESSFULLY'));
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.SUPPLIER_UPDATED_SUCCESSFULLY')], 200);
        } else {
            //Session::flash('error', __('label.SUPPLIER_NOT_BE_UPDATED'));
            // return redirect('supplier/' . $id . '/edit' . $pageNumber);
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.SUPPLIER_NOT_BE_UPDATED')], 401);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Supplier::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        $dependencyArr = [
            'ProductCheckInDetails' => ['1' => 'supplier_id'],
            'SupplierToProduct' => ['1' => 'supplier_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('admin/supplierClassification' . $pageNumber);
                }
            }
        }
        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        //END OF Dependency
        if ($target->delete()) {
            Session::flash('error', __('label.SUPPLIER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.SUPPLIER_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/supplier' . $pageNumber);
    }

    public function newContactPersonToCreate() {
        return Common::newContactPerson();
    }

    public function newContactPersonToEdit() {
        return Common::newContactPerson();
    }

    public function getDetailsOfContactPerson(Request $request) {
        $target = Supplier::find($request->supplier_id);
        $supplierName = $target->name;
        $contactPersonArr = json_decode($target->contact_person_data, true);
        $view = view('supplier.showContactPersonDetails', compact('contactPersonArr', 'request', 'supplierName'))->render();
        return response()->json(['html' => $view]);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('admin/supplier?' . $url);
    }

    public function addPhoneNumber(Request $request) {
        $view = view('supplierContactPerson.addPhoneNumber', compact('request'))->render();
        return response()->json(['html' => $view]);
    }

    //****************************** start :: buyer profile ********************************//
    public function profile(Request $request, $id) {
        $loadView = 'supplier.profile.show';
        return Common::supplierProfile($request, $id, $loadView);
    }

    public function printProfile(Request $request, $id) {
        $loadView = 'supplier.profile.print.show';
        $modueId = 13;
        return Common::supplierPrintProfile($request, $id, $loadView, $modueId);
    }

    public static function getInvolvedOrderList(Request $request) {
        $loadView = 'supplier.profile.showInvolvedOrderList';
        return Common::getSupplierInvolvedOrderList($request, $loadView);
    }

    public static function printInvolvedOrderList(Request $request) {
        $loadView = 'supplier.profile.print.showInvolvedOrderList';
        $modueId = 13;
        return Common::printSupplierInvolvedOrderList($request, $loadView, $modueId);
    }

    //****************************** end :: buyer profile *********************************//

    public function getSupplierAdditionalInfo(Request $request) {
        $target = Supplier::find($request->supplierId);
        $bankList = ['0' => __('label.SELECT_BANK_OPT')] + Bank::select('name', 'id')->orderBY('name', 'asc')->get()->pluck('name', 'id')->toArray();
        $supplierTypes = ['0' => __('label.SELECT_SUPPLIER_TYPE'), '1' => __('label.CASH'), '2' => __('label.CREDIT')];
        $returnTypes = ['0' => __('label.SELECT_RETURN_TYPE'), '1' => __('label.EXCHANGE'), '2' => __('label.CASH')];
        $html = view('supplier.addAdditionalInfo', compact('target', 'bankList', 'supplierTypes', 'returnTypes'))->render();
        return response()->json(['html' => $html], 200);
    }

    public function setSupplierAdditionalInfo(Request $request) {
        $target = Supplier::find($request->id);
        $target->details_info = $request->details_info;
        $target->business_hour = $request->business_hour;
        $target->holidays = $request->holidays;
        $target->supplier_type = $request->supplier_type;
        $target->return_option = $request->return_option;
        $target->return_timeline = $request->return_timeline;
        $target->credit_period = $request->credit_period;
        $target->bank_id = $request->bank_id;
        $target->branch_name = $request->branch_name;
        $target->routing_number = $request->routing_number;
        $target->account_name = $request->account_name;
        $target->account_number = $request->account_number;

        if ($target->save()) {
//            Session::flash('success', __('label.SUPPLIER_UPDATED_SUCCESSFULLY'));
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.SUPPLIER_UPDATED_SUCCESSFULLY')], 200);
        } else {
            //Session::flash('error', __('label.SUPPLIER_NOT_BE_UPDATED'));
            // return redirect('supplier/' . $id . '/edit' . $pageNumber);
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.SUPPLIER_NOT_BE_UPDATED')], 401);
        }
    }

}
