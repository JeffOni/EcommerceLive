<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    //
    protected $fillable = [
        'name'
    ];

    //Relacion uno a muchos con Category

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
