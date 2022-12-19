<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use URL;
use Session;
use Redirect;
use Helper;
use File;
use Validator;
use Response;
use App\User;
use App\Hotline;

class HotLineController extends Controller {
    
    public function index(Request $request) {
        
        $hotlineArr = Hotline::first();
        
        // load the view and pass the rank index
        return view('content.hotline.index', compact('hotlineArr'));
    }
    
   

    public function update(Request $request) {
        $hotlineInfo = HotLine::first();
        if(empty($hotlineInfo)){
           $target = new HotLine;
        }else{
            $target = HotLine::find($hotlineInfo->id);  
        }
        $target->hotline =  $request->hotline;
        
        if ($target->save()) {
            Session::flash('success', __('label.UPDATED_SUCCESSFULLY'));
            return Redirect::to('hotline');
        } else {
            Session::flash('error',  @__('label.COUD_NOT_BE_UPDATED'));
            return Redirect::to('hotline');
        }
        
    }
    
}
?>