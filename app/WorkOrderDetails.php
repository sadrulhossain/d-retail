<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class WorkOrderDetails extends Model {

    protected $primaryKey = 'id';
    protected $table = 'work_order_details';
    public $timestamps = false;

    

}
