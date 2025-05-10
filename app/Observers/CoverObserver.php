<?php

namespace App\Observers;

use App\Models\Cover;

class CoverObserver
{
    //
    public function creating($cover)
    {
        // Asignar el orden de la portada al crearla
        // Esto asegura que cada nueva portada tenga un orden único y secuencial
        // max('order') obtiene el valor máximo del campo 'order' en la tabla 'covers'
        // y le suma 1 para asignar un nuevo orden a la portada que se está creando.
        // Esto es útil para mantener un orden lógico de las portadas.
        $cover->order = Cover::max('order') + 1;

        // Aquí puedes agregar más lógica si es necesario
    }
}
