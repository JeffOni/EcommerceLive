<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    //
    public function show(Subcategory $subcategory)
    {
        // Aquí puedes agregar la lógica para mostrar la vista de la subcategoría
        // Por ejemplo, podrías cargar productos destacados o cualquier otra información que desees mostrar.
        return view('subcategories.show', compact('subcategory'));
    }
}
