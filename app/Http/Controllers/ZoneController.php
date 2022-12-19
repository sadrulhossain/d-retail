<?php

namespace App\Http\Controllers;

use Validator;
use App\Cluster;
use App\Zone;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class ZoneController extends Controller {

    private $controller = 'Zone';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $clusterList = array('0' => __('label.SELECT_CLUSTER_OPT')) + Cluster::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
//        $targetArr = Zone::select('zone.*')->orderBy('order', 'asc');
        $targetArr = Zone::join('cluster', 'cluster.id', '=', 'zone.cluster_id')
                ->select('zone.id', 'zone.name', 'zone.short_description', 'zone.order', 'zone.status', 'zone.cluster_id', 'cluster.name as cluster')
                ->orderBy('zone.order', 'asc');
        

  //begin filtering
        $searchText = $request->search;
        $nameArr = Zone::select('name')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('zone.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->cluster_id)) {
            $targetArr = $targetArr->where('zone.cluster_id', '=', $request->cluster_id);
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('zone.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) &&  ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/admin/zone?page=' . $page);
        }

        return view('zone.index')->with(compact('targetArr', 'qpArr', 'status','nameArr','clusterList'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $clusterList = array('0' => __('label.SELECT_CLUSTER_OPT')) + Cluster::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        return view('zone.create')->with(compact('qpArr', 'orderList','clusterList'));
    }

    public function store(Request $request) {
//        return $request;
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:zone',
                    'order' => 'required|not_in:0',
                    'cluster_id' => 'required|not_in:0',
                    'short_description' => 'required|not_in:0'
                    
        ]);

        if ($validator->fails()) {
            return redirect('admin/zone/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Zone;
        $target->name = $request->name;
        $target->cluster_id = $request->cluster_id;
        $target->short_description = $request->short_description;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.ZONE_CREATED_SUCCESSFULLY'));
            return redirect('admin/zone');
        } else {
            Session::flash('error', __('label.ZONE_COULD_NOT_BE_CREATED'));
            return redirect('admin/zone/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
       
        $target = Zone::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        $clusterList = array('0' => __('label.SELECT_CLUSTER_OPT')) + Cluster::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('admin/zone');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('zone.edit')->with(compact('target', 'qpArr', 'orderList','clusterList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Zone::find($id);
        $presentOrder = $target->order;
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:zone,name,' . $id,
                    'order' => 'required|not_in:0',
                    'cluster_id' => 'required|not_in:0',
                    'short_description' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('admin/zone/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->cluster_id = $request->cluster_id;
        $target->short_description = $request->short_description;
        $target->order = $request->order;
        $target->status = $request->status;
        
        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.ZONE_UPDATED_SUCCESSFULLY'));
            return redirect('admin/zone' . $pageNumber);
        } else {
            Session::flash('error', __('label.ZONE_COULD_NOT_BE_UPDATED'));
            return redirect('admin/zone/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Zone::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'User' => ['1' => 'id']
        ];
//        foreach ($dependencyArr as $model => $val) {
//            foreach ($val as $index => $key) {
//                $namespacedModel = '\\App\\' . $model;
//                $dependentData = $namespacedModel::where($key, $id)->first();
//                if (!empty($dependentData)) {
//                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
//                    return redirect('admin/zone' . $pageNumber);
//                }
//            }
//        }
        
        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.ZONE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.ZONE_COULD_NOT_BE_DELETED'));
        }
        return redirect('admin/zone' . $pageNumber);
    }

    public function filter(Request $request) {
//        return $request;
        $url = 'search=' . urlencode($request->name) 
                .'&cluster_id=' . $request->cluster_id .
                 '&status=' . $request->status;
        return Redirect::to('admin/zone?' . $url);
    }

}