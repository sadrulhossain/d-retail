<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
class Advertisement extends Authenticatable
{
        protected $table = 'advertisement';
        public $timestamps = true;
        
        
        public static function boot()
        {
            parent::boot();
            static::creating(function($post)
            {
                $post->created_by = Auth::user()->id;
                $post->updated_by = Auth::user()->id;
               
                               
            });

            static::updating(function($post)
            {
                $post->updated_by = Auth::user()->id;
            });
            
            
        }
}