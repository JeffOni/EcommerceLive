<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    //
    public function index()
    {
        // Aquí puedes implementar la lógica para mostrar el formulario de checkout
        return view('checkout.index');
    }
}
