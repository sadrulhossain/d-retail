<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use Redirect;
use App\Subscribe;
use App\Product;
use App\Supplier;
use App\ProductSKUCode;
use App\ProductCheckInDetails;
use App\ProductCheckInMaster;
use App\CompanyInformation;
use Helper;
use DB;
use Auth;
use Common;
use PDF;
use Excel;
use Input;
use Illuminate\Http\Request;
use App\Exports\ExcelExport;

class SubscriberLogReportController extends Controller {

    //Stock Summary Report
    public function index(Request $request) {
        $fromDate = $toDate = '';
        $targetArr = [];
        if ($request->generate == 'true') {
            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) : '';

            $targetArr = Subscribe::whereBetween('created_at', [$fromDate, $toDate])
                    ->select('id', 'created_at', 'email')
                    ->orderBy('created_at', 'desc')
                    ->get();
        }

        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';
        if (!empty($konitaInfo)) {
            $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
            $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
        }
        $userAccessArr = Common::userAccess();
        if ($request->view == 'excel') {
            return Excel::download(new ExcelExport('report.subscriberLog.print.index', compact('request', 'targetArr'
                                    , 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate')), 'subscriber_log.xlsx');
        } else {
            return view('report.subscriberLog.index')->with(compact('request', 'targetArr'
                                    , 'konitaInfo', 'phoneNumber', 'fromDate', 'toDate'));
        }
    }

    public function filter(Request $request) {
        $messages = [];
        $rules = [
            'from_date' => 'required',
            'to_date' => 'required',
        ];

        $messages = [
            'from_date.required' => __('label.THE_FROM_DATE_FIELD_IS_REQUIRED'),
            'to_date.required' => __('label.THE_TO_DATE_FIELD_IS_REQUIRED'),
        ];
        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('admin/subscriberLogReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }


        return Redirect::to('admin/subscriberLogReport?generate=true&' . $url);
    }

}
