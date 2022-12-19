<?php

namespace App\Http\Controllers;

use Validator;
use App\User; //model class
use App\Banner; //model class
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

class BannerController extends Controller {

    private $controller = 'Banner';

    public function index(Request $request) {
        $qpArr = $request->all();
        $targetArr = Banner::select('*')
                ->orderBy('banner.order', 'asc');
        $postionArr = array('slide-3' => __('label.LEFT'), 'slide-1' => __('label.CENTER'));
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/banner?page=' . $page);
        }

        return view('content.banner.index')->with(compact('qpArr', 'targetArr', 'postionArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $postionArr = array('slide-3' => __('label.LEFT'), 'slide-1' => __('label.CENTER'));
        $lastOrderNumber = Helper::getLastOrder($this->controller, 1);
        return view('content.banner.create')->with(compact('qpArr', 'orderList', 'lastOrderNumber', 'postionArr'));
    }

    public function store(Request $request) {
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = [
            'order_id' => 'required',
            'banner_image' => 'required|max:2024|mimes:jpeg,jpg,png,gif',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin/banner/create')
                            ->withInput($request->except('banner_image'))
                            ->withErrors($validator);
        }

        if ($request->hasFile('banner_image')) {
            $image = $request->file('banner_image');
            $name = 'banner_image' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/content/banner/');
            $image->move($destinationPath, $name);
            $this->load(public_path() . '/uploads/content/banner/' . $name);
            $this->resize(1170, 500);
            $this->save(public_path() . '/uploads/content/banner/' . $name);
        }
        $target = new Banner;
        if (!empty($request->caption)) {
            $target->caption = $request->caption;
        }
        $target->order = $request->order_id;
        $target->status_id = $request->status_id;
        $target->title = $request->title;
        $target->subtitle = $request->subtitle;
        $target->price_info = $request->price_info;
        $target->price = $request->price;
        $target->position = $request->position;
        $target->url = $request->url;
        $target->img_d_x = $name;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order_id, $target->id);
            Session::flash('success', __('label.ATTRIBUTE_HAS_BEEN_CREATED_SUCESSFULLY',['name'=>$request->title ?? '']));
            return redirect('admin/banner');
        } else {
            Session::flash('error', __('label.COULD_NOT_BE_CREATED_SUCESSFULLY',['name'=>$request->title ?? '']));
            return redirect('admin/banner/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {

        //passing param for custom function
        $qpArr = $request->all();

        //get id wise data
        $target = Banner::find($id);

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('admin/banner');
        }
        $postionArr = array('slide-3' => __('label.LEFT'), 'slide-1' => __('label.CENTER'));
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        return view('content.banner.edit')->with(compact('qpArr', 'target', 'orderList', 'postionArr'));
    }

    public function update(Request $request, $id) {

        $target = Banner::find($id);
        $previouseFileName = $target->img_d_x;
        $presentOrder = $target->order;
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $rules = [
            'order_id' => 'required|not_in:0'
        ];

        if (!empty($request->image)) {
            $rules['image'] = 'max:2024|mimes:jpeg,jpg,png,gif';
        }

        $validator = Validator::make($request->all(), $rules);
        $name = '';
        if ($request->hasFile('banner_image')) {
            $image = $request->file('banner_image');
            $name = 'banner_image' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/content/banner/');
            $image->move($destinationPath, $name);
            $this->load(public_path() . '/uploads/content/banner/' . $name);
            $this->resize(1170, 500);
            $this->save(public_path() . '/uploads/content/banner/' . $name);

            $prvPhotoName = 'public/uploads/content/banner/' . $target->img_d_x;
            if (File::exists($prvPhotoName)) {
                File::delete($prvPhotoName);
            }
            $target->img_d_x = $name;
        }

        if (!empty($request->caption)) {
            $target->caption = $request->caption;
        }
        $target->order = $request->order_id;
        $target->title = $request->title;
        $target->subtitle = $request->subtitle;
        $target->price_info = $request->price_info;
        $target->price = $request->price;
        $target->position = $request->position;
        $target->url = $request->url;
        $target->status_id = $request->status_id;
        if ($target->save()) {
            if ($request->order_id != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order_id, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.ATTRIBUTE_UPDATED_SUCCESSFULLY',['name'=>$request->title ?? '']));
            return redirect('admin/banner' . $pageNumber);
        } else {
            Session::flash('error', __('label.ATTRIBUTE_COUD_NOT_BE_UPDATED',['name'=>$request->title ?? '']));
            return redirect('admin/banner/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Banner::find($id);

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
            $fileName = 'public/uploads/content/banner/' . $target->img_d_x;
            if (File::exists($fileName)) {
                File::delete($fileName);
            }
            Session::flash('error', __('label.ATTRIBUTE_HAS_BEEN_DELETED',['name'=>$target->title ?? '']));
        } else {
            Session::flash('error', __('label.ATTRIBUTE_COULD_NOT_BE_DELETED',['name'=>$target->title ?? '']));
        }
        return redirect('admin/banner' . $pageNumber);
    }

    //***************************************  Thumbnails Generating Functions :: Start *****************************
    public function load($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    public function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
        if ($permissions != null) {
            chmod($filename, $permissions);
        }
    }

    public function output($image_type = IMAGETYPE_JPEG) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image);
        }
    }

    public function getWidth() {
        return imagesx($this->image);
    }

    public function getHeight() {
        return imagesy($this->image);
    }

    public function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    public function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    public function resize($width, $height) {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
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

//***************************************  Thumbnails Generating Functions :: End *****************************
}
