<?php

namespace App\Http\Controllers;

use App\Supplier;
use App\Bank;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Validator;
use \Illuminate\Support\Facades\Redirect;
use PDF;

class SupplierBankAccountReportController extends Controller {

    private $controller = 'SupplierBankAccountReport';

    public function index(Request $request) {
        $qpArr = $request->all();
        $bankList = ["0" => __('label.SELECT_BANK_OPT')] + Bank::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $supplierList = ['0' => __('label.SELECT_SUPPLIER_OPT')] + Supplier::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $targetArr = '';
        if ($request->generate == 'true') {
            $targetArr = Supplier::select('id', 'name', 'bank_id', 'branch_name', 'routing_number', 'account_name', 'account_number')->with('bank:id,name,status as bank_status');
            if (!empty($request->bank_id)) {
                $targetArr = $targetArr->where('bank_id', $request->bank_id);
            }
            $targetArr = $targetArr->get();
           if ($request->view == 'print') {
                if (!empty($userAccessArr[131][6])) {
                    return redirect('/dashboard');
                }
                return view('report.supplierBankAccountReport.print.index')->with(compact('request', 'qpArr', 'bankList', 'targetArr', 'supplierList'));
            } elseif ($request->view == 'pdf') {
                if (!empty($userAccessArr[131][9])) {
                    return redirect('/dashboard');
                }
                $pdf = PDF::loadView('report.supplierBankAccountReport.print.index', compact('request', 'qpArr', 'bankList', 'targetArr', 'supplierList'))
                        ->setPaper('a3', 'landscape')
                        ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('sales_status_report.pdf');
                return $pdf->stream();
            }
            return view('report.supplierBankAccountReport.index')->with(compact('request', 'qpArr', 'bankList', 'targetArr', 'supplierList'));
        }
        return view('report.supplierBankAccountReport.index')->with(compact('request', 'qpArr', 'bankList', 'targetArr', 'supplierList'));
    }

    public function filter(Request $request) {

        $validator = Validator::make($request->all(), [
                    'bank_id' => 'required|not_in:0',
                        ], [
                    'bank_id.not_in' => 'Bank field is required'
        ]);

        $url = '&bank_id=' . $request->bank_id
                . '&supplier_id=' . $request->supplier_id;
        if ($validator->fails()) {
            return Redirect::to('admin/supplierBankAccountReport?generate=false&' . $url)->withErrors($validator)->withInput();
        }
        return Redirect::to('admin/supplierBankAccountReport?generate=true&' . $url);
    }

}
