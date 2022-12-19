<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Facades\DB;

class Order extends Model {

    protected $primaryKey = 'id';
    protected $table = 'order';
    public $timestamps = false;

    public function orderDetails() {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }

    public function retialer() {
        return $this->belongsTo(Retailer::class, 'retailer_id')->select('id','name','type')->where('approval_status', '1');
    }

    public function sr() {
        return $this->belongsTo(User::class, 'sr_id')->select('id','first_name','last_name');
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id')->select('name','id');
    }

}
