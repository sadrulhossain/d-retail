<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToWarehouse extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'user_to_warehouse';
    public $timestamps = false;
}
