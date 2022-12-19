<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class OrderDetails extends Model {

    protected $primaryKey = 'id';
    protected $table = 'order_details';
    public $timestamps = false;
    
    public function product() {
        return $this->belongsTo(Product::class,'product_id')->select('id','name');
    }

}
