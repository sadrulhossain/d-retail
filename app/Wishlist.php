<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Wishlist extends Model {

    protected $primaryKey = 'id';
    protected $table = 'wishlist';
    public $timestamps = true;

    public static function boot() {
        parent::boot();

    }

}
