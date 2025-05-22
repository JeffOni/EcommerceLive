<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function show(Category $category)
    {
        // Aquí puedes agregar la lógica para mostrar la vista de la categoría
        // Por ejemplo, podrías cargar productos destacados o cualquier otra información que desees mostrar.
        return view('categories.show', compact('category'));
    }
}
