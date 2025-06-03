<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function index()
    {
        // Aquí puedes retornar la vista del carrito
        return view('cart.index');
    }

}
