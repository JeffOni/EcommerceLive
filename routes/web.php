<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\WelcomeController;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Facades\Route;


Route::get('/',[WelcomeController::class,'index'])->name('welcome.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// El método scopeBindings() en la ruta aplica el enlace automático de modelos anidados en rutas.
// Por ejemplo, si tienes una ruta como /families/{family}/products/{product},
// scopeBindings() asegura que el modelo {product} se busque solo entre los productos de la familia {family},
// evitando acceder a productos que no pertenezcan a esa familia.
Route::get('/families/{family}',[FamilyController::class,'show'])
    ->name('families.show')
    ->scopeBindings();

Route::get('/categories/{category}',[CategoryController::class,'show'])
    ->name('categories.show')
    ->scopeBindings();

Route::get('/subcategories/{subcategory}',[SubcategoryController::class,'show'])
    ->name('subcategories.show')
    ->scopeBindings();
