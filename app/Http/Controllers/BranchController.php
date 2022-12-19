<?php

namespace App\Http\Controllers;

use Validator;
use App\Branch;
use App\Country;
use App\CourierService;
use App\Division;
use App\District;
use App\Thana;
use Session;
use Redirect;
use File;
use Common;
use Auth;
use Illuminate\Http\Request;

class BranchController extends Controller {

    private $controller = 'Branch';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Branch::select('branch.*')->orderBy('name', 'asc');
        $courierList = ['0' => __('label.SELECT_COURIER_OPT')] + CourierService::orderBy('name', 'asc')->where('status', '1')
                        ->pluck('name', 'id')->toArray();
        $countryList = Country::pluck('name', 'id')->toArray();
        $divisionList = Division::pluck('name', 'id')->toArray();
        $districtList = District::pluck('name', 'id')->toArray();
        $thanaList = Thana::pluck('name', 'id')->toArray();
        $nameArr = Branch::select('name')->orderBy('name', 'asc')->get();


        $country = ['0' => __('label.SELECT_COUNTRY_OPT')] + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $division = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $district = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $thana = ['0' => __('label.SELECT_THANA_OPT')] + Thana::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('branch.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->country)) {
            $targetArr = $targetArr->where('branch.country_id', '=', $request->country);
        }
        if (!empty($request->division)) {
            $targetArr = $targetArr->where('branch.division_id', '=', $request->division);
        }
        if (!empty($request->district)) {
            $targetArr = $targetArr->where('branch.district_id', '=', $request->district);
        }
        if (!empty($request->thana)) {
            $targetArr = $targetArr->where('branch.thana_id', '=', $request->thana);
        }
        if (!empty($request->courier)) {
            $targetArr = $targetArr->where('branch.courier_id', '=', $request->courier);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/branch?page=' . $page);
        }

        return view('branch.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'courierList'
                                , 'country', 'division', 'district', 'thana', 'countryList'
                                , 'divisionList', 'districtList', 'thanaList'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $courierList = ['0' => __('label.SELECT_COURIER_OPT')] + CourierService::orderBy('name', 'asc')->where('status', '1')
                        ->pluck('name', 'id')->toArray();
        $countryArr = ['0' => __('label.SELECT_COUNTRY_OPT')] + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $divisionArr = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::where('country_id', 18)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $districtArr = ['0' => __('label.SELECT_DISTRICT_OPT')];
        $thanaArr = ['0' => __('label.SELECT_THANA_OPT')];
        return view('branch.create')->with(compact('qpArr', 'countryArr', 'divisionArr'
                                , 'districtArr', 'thanaArr', 'courierList'));
    }

    public function getDivisionToCreate(Request $request) {
        return Common::getDivision($request);
    }

    public function getDistrictToCreate(Request $request) {
        return Common::getDistrict($request,'branch');
    }

    public function getThanaToCreate(Request $request) {
        return Common::getThana($request,'branch');
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:branch,name',
                    'courier_id' => 'required|not_in:0',
                    'country_id' => 'required|not_in:0',
                    'branch_contact_no' => 'required|unique:branch,branch_contact_no',
                    'email' => 'required|unique:branch,email',
        ]);

        if ($validator->fails()) {
            return redirect('admin/branch/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Branch;
        $target->name = $request->name;
        $target->courier_id = $request->courier_id;
        $target->country_id = $request->country_id;
        $target->division_id = $request->division_id;
        $target->district_id = $request->district_id;
        $target->thana_id = $request->thana_id;
        $target->location_details = $request->location_details;
        $target->branch_contact_no = $request->branch_contact_no;
        $target->email = $request->email;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.BRANCH_CREATED_SUCCESSFULLY'));
            return redirect('admin/branch');
        } else {
            Session::flash('error', __('label.BRANCH_COULD_NOT_BE_CREATED'));
            return redirect('admin/branch/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Branch::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/branch');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $courierList = ['0' => __('label.SELECT_COURIER_OPT')] + CourierService::orderBy('name', 'asc')->where('status', '1')
                        ->pluck('name', 'id')->toArray();
        $countryArr = ['0' => __('label.SELECT_COUNTRY_OPT')] + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $divisionArr = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::where('country_id', $target->country_id)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $districtArr = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', $target->division_id)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        ;
        $thanaArr = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', $target->district_id)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        ;
        return view('branch.edit')->with(compact('target', 'qpArr', 'countryArr', 'divisionArr'
                                , 'districtArr', 'thanaArr', 'courierList'));
    }

    public function getDivisionToEdit(Request $request) {
        return Common::getDivision($request);
    }

    public function getDistrictToEdit(Request $request) {
        return Common::getDistrict($request);
    }

    public function getThanaToEdit(Request $request) {
        return Common::getThana($request);
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Branch::find($id);

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:branch,name,' . $id,
                    'courier_id' => 'required|not_in:0',
                    'country_id' => 'required|not_in:0',
                    'branch_contact_no' => 'required|unique:branch,branch_contact_no,' . $id,
                    'email' => 'required|unique:branch,email,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect('admin/branch/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all())
                            ->withErrors($validator);
        }


        $target->name = $request->name;
        $target->courier_id = $request->courier_id;
        $target->country_id = $request->country_id;
        $target->division_id = $request->division_id;
        $target->district_id = $request->district_id;
        $target->thana_id = $request->thana_id;
        $target->location_details = $request->location_details;
        $target->branch_contact_no = $request->branch_contact_no;
        $target->email = $request->email;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.BRANCH_UPDATED_SUCCESSFULLY'));
            return redirect('admin/branch' . $pageNumber);
        } else {
            Session::flash('error', __('label.BRANCH_COULD_NOT_BE_UPDATED'));
            return redirect('admin/branch/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Branch::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency check
        $dependencyArr = [
            'User' => ['1' => 'branch_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('admin/branch' . $pageNumber);
                }
            }
        }
        //end :: dependency check

        if ($target->delete()) {
            Session::flash('error', __('label.BRANCH_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.BRANCH_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/branch' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search) . '&courier=' . $request->courier
                . '&country=' . $request->country . '&division=' . $request->division
                . '&district=' . $request->district . '&thana=' . $request->thana;
        return Redirect::to('admin/branch?' . $url);
    }

}
