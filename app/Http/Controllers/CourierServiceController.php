<?php

namespace App\Http\Controllers;

use Validator;
use App\ContactDesignation;
use App\CourierService;
use Common;
use Auth;
use Session;
use Redirect;
use Helper;
use Response;
use Illuminate\Http\Request;

class CourierServiceController extends Controller {

    private $controller = 'CourierService';

    public function index(Request $request) {

        $qpArr = $request->all();
        $targetArr = CourierService::select('courier_service.*');
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;

        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('courier_service.status', '=', $request->status);
        }


        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/courierService?page=' . $page);
        }

        return view('courierService.index')->with(compact('targetArr', 'qpArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();

        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')->orderby('order', 'asc')->pluck('name', 'id')->toArray();
        return view('courierService.create')->with(compact('qpArr', 'designationList'));
    }

    public function store(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
        $rules = $message = array();
        $rules = [
            'name' => 'required',
            'address' => 'required',
            'number' => 'required|courier_service:branch,number',
            'email' => 'required|courier_service:branch,email',
        ];

        if (!empty($request->contact_name)) {
            $row = 0;
            foreach ($request->contact_name as $key => $name) {
                $rules['contact_name.' . $key] = 'required';
                $rules['contact_email.' . $key] = 'required';

                //set messages for error
                $message['contact_name.' . $key . '.required'] = __('label.NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
//                $message['contact_email.' . $key . '.required'] = __('label.EMAIL_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $contactPhone = $request->contact_phone;
                if (!empty($contactPhone[$key])) {
                    $row2 = 0;
                    foreach ($contactPhone[$key] as $key2 => $name) {
                        $rules['contact_phone.' . $key . '.' . $key2] = 'required';
                        $message['contact_phone.' . $key . '.' . $key2 . '.required'] = __('label.PHONE_IS_REQUIRED_FOR_THIS_BLOCK_NO_IN_THIS_ROW_NO', ['block' => ($row2 + 1), 'row' => ($row + 1)]);
                        $row2++;
                    }
                }
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

        $target = new CourierService;
        $target->name = $request->name;
        $target->address = $request->address;
        $target->number = $request->number;
        $target->email = $request->email;
        $target->contact_person_data = json_encode($contactPersonDataArr);
        $target->status = $request->status;

        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.COURIER_SERVICE_CREATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.COURIER_SERVICE_COULD_NOT_BE_CREATED')], 401);
        }
    }

    public function edit(Request $request, $id) {
        //passing param for custom function
        $qpArr = $request->all();
        $target = CourierService::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/courierService');
        }
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + ContactDesignation::where('status', '1')->pluck('name', 'id')->toArray();

        $prevContactPersonArr = json_decode($target->contact_person_data, true);
        return view('courierService.edit')->with(compact('qpArr', 'target', 'designationList', 'prevContactPersonArr'));
    }

    public function update(Request $request) {
//        print_r($request->all());exit;
        $target = CourierService::find($request->id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update
        $rules = $message = array();
        $rules = [
            'name' => 'required',
            'address' => 'required',
            'number' => 'required|unique:courier_service,number,' . $request->id,
            'email' => 'required|unique:courier_service,email,' . $request->id,
        ];

        if (!empty($request->contact_name)) {
            $row = 0;
            foreach ($request->contact_name as $key => $name) {
                $rules['contact_name.' . $key] = 'required';
                $rules['contact_email.' . $key] = 'required';
                $message['contact_name.' . $key . '.required'] = __('label.NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $message['contact_email.' . $key . '.required'] = __('label.EMAIL_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $contactPhone = $request->contact_phone;
                if (!empty($contactPhone[$key])) {
                    $row2 = 0;
                    foreach ($contactPhone[$key] as $key2 => $name) {
                        $rules['contact_phone.' . $key . '.' . $key2] = 'required';
                        $message['contact_phone.' . $key . '.' . $key2 . '.required'] = __('label.PHONE_IS_REQUIRED_FOR_THIS_BLOCK_NO_IN_THIS_ROW_NO', ['block' => ($row2 + 1), 'row' => ($row + 1)]);
                        $row2++;
                    }
                }
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
        $target->address = $request->address;
        $target->number = $request->number;
        $target->email = $request->email;
        $target->contact_person_data = json_encode($contactPersonDataArr);
        $target->status = $request->status;


        if ($target->save()) {
            return Response::json(['success' => true, 'heading' => __('label.SUCCESS'), 'message' => __('label.COURIER_SERVICE_UPDATED_SUCCESSFULLY')], 200);
        } else {
            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.COURIER_SERVICE_COULD_NOT_BE_UPDATED')], 401);
        }
    }

    public function destroy(Request $request, $id) {
        $target = CourierService::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }


        //Dependency
//        $dependencyArr = [
//            'BuyerFactory' => ['1' => 'buyer_id'],
//            'BuyerToGsmVolume' => ['1' => 'buyer_id'],
//            'BuyerToProduct' => ['1' => 'buyer_id'],
//            'SalesPersonToBuyer' => ['1' => 'buyer_id'],
//            'Lead' => ['1' => 'buyer_id'],
//        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
//                    return redirect('buyer' . $pageNumber);
//                }
//            }
//        }
//        $fileName = 'public/uploads/buyer/' . $target->logo;
//        if (File::exists($fileName)) {
//            File::delete($fileName);
//        }

        if ($target->delete()) {
//            BuyerToGsmVolume::where('buyer_id', $id)->delete();
//            BuyerFactory::where('buyer_id', $id)->delete();
//            BuyerToProduct::where('buyer_id', $id)->delete();
//            SalesPersonToBuyer::where('buyer_id', $id)->delete();
            Session::flash('error', __('label.COURIER_SERVICE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.COURIER_SERVICE_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/courierService' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&status=' . $request->status;
        return Redirect::to('admin/courierService?' . $url);
    }

    public function newContactPersonToCreate() {
        return Common::courierServiceContactPerson();
    }

    public function newContactPersonToEdit() {
        return Common::courierServiceContactPerson();
    }

    public function addPhoneNumber(Request $request) {
        $view = view('courierServiceContactPerson.addPhoneNumber', compact('request'))->render();
        return response()->json(['html' => $view]);
    }

    public function getDetailsOfContactPerson(Request $request) {
//        echo '<pre>';
//        print_r($request->all());
//        exit();
        $target = CourierService::find($request->courierService_id);
        $hotlineNum = $target->number;
        $courierServiceName = $target->name;
        $contactPersonArr = json_decode($target->contact_person_data, true);

        $view = view('courierService.showContactPersonDetails', compact('contactPersonArr', 'hotlineNum', 'request', 'courierServiceName'))->render();
        return response()->json(['html' => $view]);
    }

}
