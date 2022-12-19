<?php

namespace App\Http\Controllers;

use Validator;
use App\User; //model class
use App\ContactInfo; //model class
use Session;
use Redirect;
use Auth;
use File;
//use Image;
use Input;
use PDF;
use URL;
use Helper;
use Illuminate\Http\Request;

class ContactInfoController extends Controller {

    private $controller = 'ContactInfo';

    public function index(Request $request) {


        //passing param for custom function
        $qpArr = $request->all();

        //get data 
        $targetArr = ContactInfo::select('*')
                ->orderBy('contact_info.order', 'asc');

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/contact-info?page=' . $page);
        }

        return view('content.contactInfo.index')->with(compact('qpArr', 'targetArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $lastOrderNumber = Helper::getLastOrder($this->controller, 1);
        $iconList = [
            '0' => '-- Select Icon --',
            'fa fa-map-marker' => '<i class="fa fa-map-marker"></i> Map Marker',
            'fa fa-envelope' => '<i class="fa fa-envelope"></i> Envelope',
            'fa fa-phone-square' => '<i class="fa fa-phone-square"></i> Phone',
            'fa fa-bars' => '<i class="fa fa-bars"></i> Bars'
        ];

        return view('content.contactInfo.create')->with(compact('qpArr', 'orderList', 'iconList', 'lastOrderNumber'));
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
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/contact-info/create')
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

        $target = new ContactInfo;
        $target->title = $request->title;
        $target->icon = $request->icon;
        $target->order = $request->order_id;
        $target->status_id = $request->status_id;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order_id, $target->id);
            Session::flash('success', $request->title . __('label.HAS_BEEN_CREATED_SUCESSFULLY'));
            return redirect('contact-info');
        } else {
            Session::flash('error', $request->title . __('label.COULD_NOT_BE_CREATED_SUCESSFULLY'));
            return redirect('contact-info/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {

        //passing param for custom function
        $qpArr = $request->all();

        //get id wise data
        $target = ContactInfo::find($id);
        $iconList = [
            '0' => '-- Select Icon --',
            'fa fa-map-marker' => '<i class="fa fa-map-marker"></i> Map Marker',
            'fa fa-envelope' => '<i class="fa fa-envelope"></i> Envelope',
            'fa fa-phone-square' => '<i class="fa fa-phone-square"></i> Phone',
            'fa fa-bars' => '<i class="fa fa-bars"></i> Bars'
        ];
        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('atAGlance');
        }
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        return view('content.contactInfo.edit')->with(compact('qpArr', 'target', 'orderList','iconList'));
    }

    public function update(Request $request, $id) {

        $target = ContactInfo::find($id);
        
        $presentOrder = $target->order;
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $rules = [
            'order_id' => 'required|not_in:0',
            'title' => 'required',
            'icon' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/ContactInfo/'.$id.'/edit')
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

       

        $target->title = $request->title;
        $target->icon = $request->icon;
        $target->order = $request->order_id;
        $target->status_id = $request->status_id;

        if ($target->save()) {
            if ($request->order_id != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order_id, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.UPDATED_SUCCESSFULLY'));
            return redirect('contact-info' . $pageNumber);
        } else {
            Session::flash('error', __('label.COUD_NOT_BE_UPDATED'));
            return redirect('contact-info/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = ContactInfo::find($id);

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
            
            Session::flash('error', __('label.ITEM_HAS_BEEN_DELETED'));
        } else {
            Session::flash('error', __('label.COULD_NOT_BE_DELETED'));
        }
        return redirect('contact-info' . $pageNumber);
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
