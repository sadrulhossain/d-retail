<?php

namespace App\Http\Controllers;

use Validator;
use App\User; //model class
use App\Speciality; //model class
use Session;
use Redirect;
use Auth;
use File;
use Image;
use Input;
use PDF;
use URL;
use Helper;
use Illuminate\Http\Request;

class SpecialityController extends Controller {

    private $controller = 'Speciality';

    public function index(Request $request) {


        //passing param for custom function
        $qpArr = $request->all();

        //get data 
        $targetArr = Speciality::select('*')
                ->orderBy('order', 'asc');

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/speciality?page=' . $page);
        }

        return view('content.speciality.index')->with(compact('qpArr', 'targetArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $lastOrderNumber = Helper::getLastOrder($this->controller, 1);
         $iconList = [
            '0' => '-- Select Icon --',
            'fa fa-truck' => '<i class="fa fa-truck"></i> Truck',
            'fa fa-recycle' => '<i class="fa fa-recycle"></i> Recycle',
            'fa fa-credit-card' => '<i class="fa fa-credit-card"></i> Credit Card',
            'fa fa-life-ring' => '<i class="fa fa-life-ring"></i> Ring',
        ];

        return view('content.speciality.create')->with(compact('qpArr', 'orderList', 'iconList', 'lastOrderNumber'));
    }

    public function store(Request $request) {

        //passing param for custom function
        $qpArr = $request->all();

//        print_r($request->all());exit;
//        use for back default page after operation
        $pageNumber = $qpArr['filter'];


        $rules = [
            'order_id' => 'required|not_in:0',
            'title' => 'required',
            'icon' => 'required|not_in:0',
            'subtitle' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin/speciality/create')
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

        $target = new Speciality;
        $target->title = $request->title;
        $target->icon = $request->icon;
        $target->subtitle = $request->subtitle;
        $target->order = $request->order_id;
        $target->status_id = $request->status_id;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order_id, $target->id);
            Session::flash('success', __('label.ATTRIBUTE_HAS_BEEN_CREATED_SUCESSFULLY',['name'=>$request->title ?? '']));
            return redirect('admin/speciality');
        } else {
            Session::flash('error', __('label.COULD_NOT_BE_CREATED_SUCESSFULLY',['name'=>$request->title ?? '']));
            return redirect('admin/speciality/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {

        //passing param for custom function
        $qpArr = $request->all();

        //get id wise data
        $target = Speciality::find($id);
         $iconList = [
            '0' => '-- Select Icon --',
            'fa fa-truck' => '<i class="fa fa-truck"></i> Truck',
            'fa fa-recycle' => '<i class="fa fa-recycle"></i> Recycle',
            'fa fa-credit-card' => '<i class="fa fa-credit-card"></i> Credit Card',
            'fa fa-life-ring' => '<i class="fa fa-life-ring"></i> Ring',
        ];
        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('admin/speciality');
        }
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        return view('content.speciality.edit')->with(compact('qpArr', 'target', 'orderList','iconList'));
    }

    public function update(Request $request, $id) {

        $target = Speciality::find($id);
        
        $presentOrder = $target->order;
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $rules = [
            'order_id' => 'required|not_in:0',
            'title' => 'required',
            'icon' => 'required|not_in:0',
            'subtitle' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin/speciality/'.$id.'/edit')
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

       

        $target->title = $request->title;
        $target->icon = $request->icon;
        $target->subtitle = $request->subtitle;
        $target->order = $request->order_id;
        $target->status_id = $request->status_id;

        if ($target->save()) {
            if ($request->order_id != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order_id, $target->id, $presentOrder);
            }
            Session::flash('success',  __('label.ATTRIBUTE_UPDATED_SUCCESSFULLY',['name'=>$request->title ?? '']));
            return redirect('admin/speciality' . $pageNumber);
        } else {
            Session::flash('error', __('label.ATTRIBUTE_COUD_NOT_BE_UPDATED',['name'=>$request->title ?? '']));
            return redirect('admin/speciality/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Speciality::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }


        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            //delete data related file
            
            Session::flash('error', __('label.ATTRIBUTE_HAS_BEEN_DELETED',['name'=>$target->title]));
        } else {
            Session::flash('error', __('label.ATTRIBUTE_COULD_NOT_BE_DELETED',['name'=>$target->title]));
        }
        return redirect('admin/speciality' . $pageNumber);
    }

    public function setRecordPerPage(Request $request) {

        $referrerArr = explode('?', URL::previous());
        $queryStr = '';
        if (!empty($referrerArr[1])) {
            $queryParam = explode('&', $referrerArr[1]);
            foreach ($queryParam as $item) {
                $valArr = explode('=', $item);
                if ($valArr[0] != 'page') {
                    $queryStr .= $item . '&';
                }
            }
        }

        $url = $referrerArr[0] . '?' . trim($queryStr, '&');

        if ($request->record_per_page > 999) {
            Session::flash('error', __('label.NO_OF_RECORD_MUST_BE_LESS_THAN_999'));
            return redirect($url);
        }

        if ($request->record_per_page < 1) {
            Session::flash('error', __('label.NO_OF_RECORD_MUST_BE_GREATER_THAN_1'));
            return redirect($url);
        }

        $request->session()->put('paginatorCount', $request->record_per_page);
        return redirect($url);
    }

}
