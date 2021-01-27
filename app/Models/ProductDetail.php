<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    protected $appends = ['image'];

    use HasFactory;

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function getImageAttribute()
    {
        if ($this->img_path) {
            return url($this->img_path);
//            return $this->img_path;
        }else{
            return 'https://via.placeholder.com/150';
        }
    }
}
