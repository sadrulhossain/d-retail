<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name','description','unit_price','stock','status',
    ];
    
    public function Category(){
        return $this->belongsTo(Category::class);
    }
}
