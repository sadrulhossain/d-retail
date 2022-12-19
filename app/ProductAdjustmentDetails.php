<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAdjustmentDetails extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_adjustment_details';

    public $timestamps = false;

}
