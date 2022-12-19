<?php

namespace App\Http\Controllers;

use Validator;
use App\User; //model class
use App\Menu; //model class
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

class MenuController extends Controller {

    private $controller = 'Menu';

    public function index(Request $request) {


        //passing param for custom function
        $qpArr = $request->all();

        //get data 
        $targetArr = Menu::select('*')
                ->orderBy('order', 'asc');

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/menu?page=' . $page);
        }

        return view('content.menu.index')->with(compact('qpArr', 'targetArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $lastOrderNumber = Helper::getLastOrder($this->controller, 1);


        return view('content.menu.create')->with(compact('qpArr', 'orderList', 'lastOrderNumber'));
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
            'url' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin/menu/create')
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

        $target = new Menu;
        $target->title = $request->title;
        $target->url = $request->url;
        $target->order = $request->order_id;
        $target->for_logged_in_users = $request->for_logged_in_users;
        $target->status_id = $request->status_id;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order_id, $target->id);
            Session::flash('success', __('label.ATTRIBUTE_HAS_BEEN_CREATED_SUCESSFULLY',['name'=>$request->title ?? 'Item']));
            return redirect('admin/menu');
        } else {
            Session::flash('error', __('label.COULD_NOT_BE_CREATED_SUCESSFULLY',['name'=>$request->title ?? 'Item']));
            return redirect('admin/menu/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {

        //passing param for custom function
        $qpArr = $request->all();

        //get id wise data
        $target = Menu::find($id);
        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('admin/menu');
        }
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        return view('content.menu.edit')->with(compact('qpArr', 'target', 'orderList'));
    }

    public function update(Request $request, $id) {

        $target = Menu::find($id);
        
        $presentOrder = $target->order;
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $rules = [
            'order_id' => 'required|not_in:0',
            'title' => 'required',
            'url' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin/menu/'.$id.'/edit')
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

       

        $target->title = $request->title;
        $target->url = $request->url;
        $target->order = $request->order_id;
        $target->for_logged_in_users = $request->for_logged_in_users;
        $target->status_id = $request->status_id;

        if ($target->save()) {
            if ($request->order_id != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order_id, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.ATTRIBUTE_UPDATED_SUCCESSFULLY',['name'=>$request->title ?? 'Item']));
            return redirect('admin/menu' . $pageNumber);
        } else {
            Session::flash('error', __('label.ATTRIBUTE_COUD_NOT_BE_UPDATED',['name'=>$request->title ?? 'Item']));
            return redirect('admin/menu/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Menu::find($id);

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
            
            Session::flash('error', __('label.ATTRIBUTE_HAS_BEEN_DELETED',['name'=>$target->title ?? '']));
        } else {
            Session::flash('error', __('label.ATTRIBUTE_COULD_NOT_BE_DELETED',['name'=>$target->title ?? '']));
        }
        return redirect('admin/menu' . $pageNumber);
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
