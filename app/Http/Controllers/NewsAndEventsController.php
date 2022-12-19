<?php

namespace App\Http\Controllers;

use Validator;
use App\NewsAndEvents; //model class
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

class NewsAndEventsController extends Controller {

    private $controller = 'NewsAndEvents';

    public function index(Request $request) {


        //passing param for custom function
        $qpArr = $request->all();

        //get data 
        $targetArr = NewsAndEvents::select('*')
                ->orderBy('news_and_events.order', 'asc');

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/newsAndEvents?page=' . $page);
        }

        return view('content.newsAndEvents.index')->with(compact('qpArr', 'targetArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $lastOrderNumber = Helper::getLastOrder($this->controller, 1);
        return view('content.newsAndEvents.create')->with(compact('qpArr', 'orderList','lastOrderNumber'));
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
            return redirect('/admin/newsAndEvents/create')
                            ->withInput($request->except('featured_image'))
                            ->withErrors($validator);
        }

        $target = new NewsAndEvents;
        if ($request->hasFile('featured_image')) {
            $rules = [
            'featured_image' => 'max:2024|mimes:jpeg,jpg,png,gif',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect('/admin/newsAndEvents/create')
                                ->withInput($request->except('featured_image'))
                                ->withErrors($validator);
            }
            
            $image = $request->file('featured_image');
            $imageName = 'news_and_events.'. uniqid() . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/NewsAndEvents/');
            $image->move($destinationPath, $imageName);
            $target->featured_image = $imageName;
        }

        $target->title = !empty($request->title) ? $request->title : '';
        $target->content = !empty($request->content) ? $request->content : '';
        $target->order = $request->order_id;
        $target->status_id = $request->status_id;
        $target->location = $request->location;
        $target->publish_date = !empty($request->publish_date) ? Helper::dateTimeFormatConvert($request->publish_date) : null;
        $target->duration_from_date = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : null;
        $target->duration_to_date = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : null;
        //echo '<pre>';print_r($target);exit;
        if ($target->save()) {
            
            Helper :: insertOrder($this->controller, $request->order_id, $target->id);
            Session::flash('success', __('label.ATTRIBUTE_HAS_BEEN_CREATED_SUCESSFULLY',['name'=>$request->title ?? 'News']));
            return redirect('admin/newsAndEvents');
        } else {
            Session::flash('error', __('label.COULD_NOT_BE_CREATED_SUCESSFULLY',['name'=>$request->title ?? 'News']));
            return redirect('admin/newsAndEvents/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {

        //passing param for custom function
        $qpArr = $request->all();

        //get id wise data
        $target = NewsAndEvents::find($id);

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('affiliations');
        }
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        return view('content.newsAndEvents.edit')->with(compact('qpArr', 'target', 'orderList'));
    }

    public function update(Request $request, $id) {
        
        $target = NewsAndEvents::find($id);
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = [
            'order_id' => 'required',
            'title' => 'required',
        ];
        $presentOrder = $target->order;
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin/newsAndEvents/edit')
                            ->withInput($request->except('featured_image'))
                            ->withErrors($validator);
        }

        if ($request->hasFile('featured_image')) {
            $rules = [
            'featured_image' => 'max:2024|mimes:jpeg,jpg,png,gif',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect('/admin/newsAndEvents/create')
                                ->withInput($request->except('featured_image'))
                                ->withErrors($validator);
            }
            
            $image = $request->file('featured_image');
            $imageName = 'news_and_events.'. uniqid() .'.'. $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/NewsAndEvents/');
            $image->move($destinationPath, $imageName);
            if(!empty($target->featured_image)){
                $prvFileName = 'public/uploads/NewsAndEvents/' . $target->featured_image;
                if (File::exists($prvFileName)) {
                    File::delete($prvFileName);
                } 
            }
            $target->featured_image = $imageName;
        }

        $target->title = !empty($request->title) ? $request->title : '';
        $target->content = !empty($request->content) ? $request->content : '';
        $target->order = $request->order_id;
        $target->status_id = $request->status_id;
        $target->location = $request->location;
        $target->publish_date = !empty($request->publish_date) ? Helper::dateTimeFormatConvert($request->publish_date) : null;
        $target->duration_from_date = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : null;
        $target->duration_to_date = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : null;
        
        if ($target->save()) {
            if ($request->order_id != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order_id, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.ATTRIBUTE_UPDATED_SUCCESSFULLY',['name'=>$request->title ?? 'News']));
            return redirect('admin/newsAndEvents');
        } else {
            Session::flash('error', __('label.ATTRIBUTE_COUD_NOT_BE_UPDATED',['name'=>$request->title ?? 'News']));
            return redirect('admin/newsAndEvents/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = NewsAndEvents::find($id);

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
            $fileName = 'public/uploads/NewsAndEvents/' . $target->featured_image;
            if (File::exists($fileName)) {
                File::delete($fileName);
            }
            Session::flash('error', __('label.ATTRIBUTE_HAS_BEEN_DELETED',['name'=>$target->title ?? 'News']));
        } else {
            Session::flash('error', __('label.ATTRIBUTE_COULD_NOT_BE_DELETED',['name'=>$target->title ?? 'News']));
        }
        return redirect('admin/newsAndEvents' . $pageNumber);
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
