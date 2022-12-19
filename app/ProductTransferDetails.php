<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTransferDetails extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'product_transfer_details';

    public $timestamps = false;
}
