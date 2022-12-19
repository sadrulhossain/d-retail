<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ReturnProductMaster extends Model {

    protected $primaryKey = 'id';
    protected $table = 'return_product_master';
    public $timestamps = false;


}