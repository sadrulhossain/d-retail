<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductReturnDetails extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_return_details';
    public $timestamps = false;

    

}
