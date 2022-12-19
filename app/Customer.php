<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Customer extends Model {

    protected $primaryKey = 'id';
    protected $table = 'customer';
    public $timestamps = true;

    public static function boot() {
        parent::boot();
        
    }

}
