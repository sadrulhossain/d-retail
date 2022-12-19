<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductReturn extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_return';
    public $timestamps = false;
    

}
