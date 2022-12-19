<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model {

    protected $primaryKey = 'id';
    protected $table = 'retailer';
    public $timestamps = true;
    protected $fillable = [
        'password', 'conf_password',
    ];

    public static function boot() {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = Auth::user()->id ?? 0;
            $post->updated_by = Auth::user()->id ?? 0;
        });

        static::updating(function ($post) {
            $post->updated_by = Auth::user()->id ?? 0;
        });
    }

    public function warehouseToRetailer() {
        return $this->hasOne(WarehouseToRetailer::class, "retailer_id");
    }

    public function srToRetailer() {
        return $this->hasOne(SrToRetailer::class, "retailer_id");
    }

    public function user() {
        return $this->belongsTo(User::class, "user_id");
    }
    public function sr() {
        return $this->hasone(SrToRetailer::class, "retailer_id");
    }

}
