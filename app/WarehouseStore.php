<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class WarehouseStore extends Model {

    protected $primaryKey = 'id';
    protected $table = 'wh_store';
    public $timestamps = false;

    

}
