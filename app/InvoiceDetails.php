<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model {

    protected $primaryKey = 'id';
    protected $table = 'invoice_details';
    public $timestamps = false;

}
