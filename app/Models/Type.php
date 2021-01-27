<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $appends = ['delete_active'];

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function getDeleteActiveAttribute()
    {
        if (count($this->products) > 0) {
            return false;
        } else {
            return true;
        }
    }
}
