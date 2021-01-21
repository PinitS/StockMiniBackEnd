<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
//    protected $appends = ['type_value', 'detail_image'];

    use HasFactory;
    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }
    public function productDetail()
    {
        return $this->hasOne('App\Models\ProductDetail');
    }
    public function productHistory()
    {
        return $this->hasMany('App\Models\ProductHistory');
    }

//    public function getTypeValueAttribute()
//    {
//        if ($this->type_id) {
//            return $this->type ? $this->type->name : 'No Data';
//        }else{
//            return 'No Data';
//        }
//    }
//
//    public function getDetailImageAttribute()
//    {
//        if ($this->productDetail) {
//            return $this->productDetail->image;
//        }else{
//            return 'No Data';
//        }
//    }
}
