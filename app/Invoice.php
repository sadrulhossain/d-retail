<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Invoice extends Model {

    protected $primaryKey = 'id';
    protected $table = 'invoice';
    public $timestamps = false;
    

}
