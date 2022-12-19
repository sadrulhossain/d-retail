<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ProductCheckInDetails;

class ProductCheckInMaster extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_checkin_master';
    public $timestamps = false;

    public  function productCheckInDetails() {
        return $this->hasMany(ProductCheckInDetails::class, 'master_id');
    }
    
    
    

}
