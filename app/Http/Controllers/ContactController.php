<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $data = $request->only('name', 'email', 'subject', 'message');

        try {
            Mail::to('servicioalcliente@lagofish.store')->send(
                new ContactMail(
                    $data['name'],
                    $data['email'],
                    $data['subject'],
                    $data['message']
                )
            );

            return response()->json([
                'success' => true,
                'message' => 'Mensaje enviado con éxito. Te responderemos pronto.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el mensaje. Por favor, inténtalo de nuevo.'
            ], 500);
        }
    }
}
