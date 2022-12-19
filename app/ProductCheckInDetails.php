<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductCheckInDetails extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_checkin_details';
    public $timestamps = false;

    public function product() {
        $this->belongsTo(Product::class, 'product_id')->select('name');
    }
    public function productSkuCode() {
        $this->belongsTo(ProductSKUCode::class, 'slu_id')->select('name');
    }
    public function master() {
        return $this->belongsTo(ProductCheckInMaster::class,'master_id');
    }
    
    

}
