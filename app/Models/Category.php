<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

    protected $fillable = [
        'name',
        'family_id'
    ];

    //Relacion inversa  uno a muchos con Family

    public function family()
    { //la funcion se ponen en singular cuando es una relacion uno a muchos con la que no tiene la llave foranea
        return $this->belongsTo(Family::class);
    }

    //Relacion uno a muchos con Subcategory

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}
