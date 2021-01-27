<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $appends = ['delete_active'];

    public function types()
    {
        return $this->hasMany('App\Models\Type');
    }

    public function mainCategory()
    {
        return $this->belongsTo('App\Models\MainCategory');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    public function getDeleteActiveAttribute()
    {
        if (count($this->types) > 0 ) {
            return false;
        } else {
            return true;
        }
    }
}
