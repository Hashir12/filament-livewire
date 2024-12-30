<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','slug','parent_key','is_visible','description'];

    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_key');
    }

    public function child()
    {
        return $this->hasMany(Category::class,'parent_key');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
