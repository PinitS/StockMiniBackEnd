<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    use HasFactory;

    protected $appends = ['delete_active'];


    public function categories()
    {
        return $this->hasMany('App\Models\Category');
    }

    public function getDeleteActiveAttribute()
    {
        if (count($this->categories) > 0 ) {
            return false;
        } else {
            return true;
        }
    }
}
