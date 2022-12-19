<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class SetCourier extends Model {

    protected $primaryKey = 'id';
    protected $table = 'set_courier';
    public $timestamps = false;

}
