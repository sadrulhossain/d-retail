<?php

namespace App\Http\Controllers;

use Validator;
use App\User; //model class
use App\Advertisement; //model class
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

class AdvertisementController extends Controller {

    private $controller = 'Advertisement';

    public function index(Request $request) {
        $qpArr = $request->all();
        $targetArr = Advertisement::select('*')
                ->orderBy('advertisement.order', 'asc');
         $showAddArr = array('1' => __('label.HOME_PAGE'), '2' => __('label.SHOP'), '3' => __('label.PRODUCT_DETAIL'));
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/advertisement?page=' . $page);
        }

        return view('content.advertisement.index')->with(compact('qpArr', 'targetArr', 'showAddArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $showAddArr = array('1' => __('label.HOME_PAGE'), '2' => __('label.SHOP'), '3' => __('label.PRODUCT_DETAIL'));
        $lastOrderNumber = Helper::getLastOrder($this->controller, 1);
        return view('content.advertisement.create')->with(compact('qpArr', 'orderList', 'lastOrderNumber', 'showAddArr'));
    }

    public function store(Request $request) {
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = [
            'order_id' => 'required',
            'advertisement_image' => 'required|max:2024|mimes:jpeg,jpg,png,gif',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin/advertisement/create')
                            ->withInput($request->except('advertisement_image'))
                            ->withErrors($validator);
        }

        if ($request->hasFile('advertisement_image')) {
            $image = $request->file('advertisement_image');
            $name = 'advertisement_image' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/content/advertisement/');
            $image->move($destinationPath, $name);

        }
        $target = new Advertisement;
        if (!empty($request->caption)) {
            $target->caption = $request->caption;
        }
        $target->order = $request->order_id;
        $target->status_id = $request->status_id;
        $target->url = $request->url;
        $target->show_advertise = $request->show_advertise;
        $target->img_d_x = $name;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order_id, $target->id);
            Session::flash('success', $request->caption . __('label.HAS_BEEN_CREATED_SUCESSFULLY'));
            return redirect('admin/advertisement');
        } else {
            Session::flash('error', $request->caption . __('label.COULD_NOT_BE_CREATED_SUCESSFULLY'));
            return redirect('admin/advertisement/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {

        //passing param for custom function
        $qpArr = $request->all();

        //get id wise data
        $target = Advertisement::find($id);

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('admin/advertisement');
        }
        $showAddArr = array('1' => __('label.HOME_PAGE'), '2' => __('label.SHOP'), '3' => __('label.PRODUCT_DETAIL'));
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        return view('content.advertisement.edit')->with(compact('qpArr', 'target', 'orderList', 'showAddArr'));
    }

    public function update(Request $request, $id) {

        $target = Advertisement::find($id);
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
        if ($request->hasFile('advertisement_image')) {
            $image = $request->file('advertisement_image');
            $name = 'advertisement_image' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/content/advertisement/');
            $image->move($destinationPath, $name);
           

            $prvPhotoName = 'public/uploads/content/advertisement/' . $target->img_d_x;
            if (File::exists($prvPhotoName)) {
                File::delete($prvPhotoName);
            }
            $target->img_d_x = $name;
        }

        if (!empty($request->caption)) {
            $target->caption = $request->caption;
        }
        $target->order = $request->order_id;
        $target->url = $request->url;
        $target->status_id = $request->status_id;
        $target->show_advertise = $request->show_advertise;
        if ($target->save()) {
            if ($request->order_id != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order_id, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.SUCCESSFULLY_UPDATED_ADVERTISEMENT'));
            return redirect('admin/advertisement' . $pageNumber);
        } else {
            Session::flash('error', __('label.SLIDE_COULD_NOT_BE_UPDATED'));
            return redirect('admin/advertisement/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Advertisement::find($id);

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
            $fileName = 'public/uploads/content/advertisement/' . $target->img_d_x;
            if (File::exists($fileName)) {
                File::delete($fileName);
            }
            Session::flash('error', __('label.ADVERTISEMENT_HAS_BEEN_DELETED'));
        } else {
            Session::flash('error', __('label.COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/advertisement' . $pageNumber);
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
