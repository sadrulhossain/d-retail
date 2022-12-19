<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Illuminate\Support\Str;

class Subscribe extends Authenticatable {

    protected $table = 'subscribe';
    public $timestamps = false;


}
