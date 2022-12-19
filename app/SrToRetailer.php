<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class SrToRetailer extends Model {

    protected $primaryKey = 'id';
    protected $table = 'sr_to_retailer';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = Auth::user()->id;
            $post->created_at = date('Y-m-d H:i:s');
        });
    }

    public function retailer() {
        return $this->belongsTo(Retailer::class, "retailer_id");
    }
    public function user() {
        return $this->belongsTo(user::class, "sr_id");
    }

}
