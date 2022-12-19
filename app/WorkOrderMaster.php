<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class WorkOrderMaster extends Model {

    protected $primaryKey = 'id';
    protected $table = 'work_order_master';
    public $timestamps = true;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
            $post->created_at = date('Y-m-d H:i:s');
        });

        static::updating(function($post) {
            $post->updated_by = Auth::user()->id;
        });
    }

}

