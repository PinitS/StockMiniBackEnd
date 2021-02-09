<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;

    protected $appends = ['delete_active'];

    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function productDetail()
    {
        return $this->hasOne('App\Models\ProductDetail');
    }

    public function orderDetails()
    {
        return $this->hasMany('App\Models\OrderDetails');
    }

    public function productHistory()
    {
        return $this->hasMany('App\Models\ProductHistory');
    }

    public function carts()
    {
        return $this->hasMany('App\Models\Cart');
    }

    public function getDeleteActiveAttribute()
    {
        if (count($this->productHistory) > 0) {
            return false;
        } else {
            return true;
        }
    }

}
