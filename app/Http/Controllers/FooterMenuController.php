<?php

namespace App\Http\Controllers;

use Validator;
use App\FooterMenu; //model class
use Session;
use Redirect;
use Auth;
use File;
use Input;
use PDF;
use URL;
use Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FooterMenuController extends Controller {

    private $controller = 'FooterMenu';

    public function index(Request $request) {


        //passing param for custom function
        $qpArr = $request->all();

        //get data 
        $targetArr = FooterMenu::select('*')
                ->orderBy('order', 'asc');

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/footerMenu?page=' . $page);
        }

        return view('content.footerMenu.index')->with(compact('qpArr', 'targetArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $lastOrderNumber = Helper::getLastOrder($this->controller, 1);
        return view('content.footerMenu.create')->with(compact('qpArr', 'orderList','lastOrderNumber'));
    }

    public function store(Request $request) {
        $qpArr = $request->all();
        //echo '<pre>';print_r($qpArr);exit;
        $pageNumber = $qpArr['filter'];
        $rules = [
            'order_id' => 'required',
            'title' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin/footerMenu/create')
                            ->withInput($request->except('featured_image'))
                            ->withErrors($validator);
        }

        $target = new FooterMenu;


        $target->title = !empty($request->title) ? $request->title : '';
        $target->content = !empty($request->content) ? $request->content : '';
        $target->order = $request->order_id;
        $target->status_id = $request->status_id;
       
        if ($target->save()) {
            
            Helper :: insertOrder($this->controller, $request->order_id, $target->id);
            Session::flash('success', __('label.ATTRIBUTE_HAS_BEEN_CREATED_SUCESSFULLY',['name'=>$request->title ?? 'Footer Menu']));
            return redirect('admin/footerMenu');
        } else {
            Session::flash('error', __('label.COULD_NOT_BE_CREATED_SUCESSFULLY',['name'=>$request->title ?? 'Footer Menu']));
            return redirect('admin/footerMenu/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {

        //passing param for custom function
        $qpArr = $request->all();

        //get id wise data
        $target = FooterMenu::find($id);

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('affiliations');
        }
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        return view('content.footerMenu.edit')->with(compact('qpArr', 'target', 'orderList'));
    }

    public function update(Request $request, $id) {
        
        $target = FooterMenu::find($id);
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = [
            'order_id' => 'required',
            'title' => 'required',
        ];
        $presentOrder = $target->order;
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin/footerMenu/edit')
                            ->withInput($request->except('featured_image'))
                            ->withErrors($validator);
        }

        

        $target->title = !empty($request->title) ? $request->title : '';
        $target->content = !empty($request->content) ? $request->content : '';
        $target->order = $request->order_id;
        $target->status_id = $request->status_id;
       
        if ($target->save()) {
            if ($request->order_id != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order_id, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.ATTRIBUTE_UPDATED_SUCCESSFULLY',['name'=>$request->title ?? 'Footer Menu']));
            return redirect('admin/footerMenu');
        } else {
            Session::flash('error', __('label.ATTRIBUTE_COUD_NOT_BE_UPDATED',['name'=>$request->title ?? 'Footer Menu']));
            return redirect('admin/footerMenu/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = FooterMenu::find($id);

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
            $fileName = 'public/uploads/FooterMenu/' . $target->featured_image;
            if (File::exists($fileName)) {
                File::delete($fileName);
            }
            Session::flash('error', __('label.ATTRIBUTE_HAS_BEEN_DELETED',['name'=>$target->title ?? 'Footer Menu']));
        } else {
            Session::flash('error', __('label.ATTRIBUTE_COULD_NOT_BE_DELETED',['name'=>$target->title ?? 'Footer Menu']));
        }
        return redirect('admin/footerMenu' . $pageNumber);
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
