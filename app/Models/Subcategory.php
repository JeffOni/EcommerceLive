<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    //
    protected $fillable = [
        'name',
        'category_id'
    ];

    //Relacion uno a muchos inversa con Category

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //Relacion uno a muchos con Product

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
