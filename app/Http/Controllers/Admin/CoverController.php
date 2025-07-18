<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $covers = Cover::orderBy('order')->get();
        return view('admin.covers.index', compact('covers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.covers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,gif,bmp,webp,svg,avif',
                'max:2048',
                // Validación personalizada de nombre de archivo (máx 100 chars)
                function ($attribute, $value, $fail) use ($request) {
                    $file = $request->file('image');
                    if ($file) {
                        $original = $file->getClientOriginalName();
                        if (mb_strlen($original) > 100) {
                            return $fail('El nombre del archivo es demasiado largo. Máximo 100 caracteres.');
                        }
                        if (!preg_match('/^[a-zA-Z0-9._\-\s]{1,100}\.(jpg|jpeg|png|gif|bmp|webp|svg|avif)$/i', $original)) {
                            return $fail('El nombre del archivo contiene caracteres no permitidos o extensión inválida.');
                        }
                    }
                }
            ],

            // Título: regla de validación para el título de la portada
            // - required: campo obligatorio
            // - string: debe ser una cadena de texto
            // - max:255: longitud máxima de 255 caracteres
            'title' => 'required|string|max:255',

            // Fecha de inicio: cuando la portada comenzará a mostrarse
            // - required: campo obligatorio
            // - date: debe ser un formato de fecha válido
            'start_at' => 'required|date',

            // Fecha de fin: cuando la portada dejará de mostrarse
            // - required: campo obligatorio
            // - date: debe ser un formato de fecha válido
            // - after_or_equal:start_at: debe ser igual o posterior a la fecha de inicio
            'end_at' => 'nullable|date|after_or_equal:start_at',

            // Estado de la portada (activa o inactiva)
            // - required: campo obligatorio
            // - boolean: debe ser un valor booleano (true/false, 1/0)
            'is_active' => 'required|boolean',
        ], [
            // Array de mensajes de error personalizados (vacío en este caso)
            // Laravel usará los mensajes de error predeterminados
        ], [
            // Array de nombres personalizados para los campos
            // Esto mejora la legibilidad de los mensajes de error
            'image' => 'Imagen',
            'title' => 'Título',
            'start_at' => 'Fecha de inicio',
            'end_at' => 'Fecha de fin',
            'is_active' => 'Estado',
        ]);

        // Asegura que la carpeta covers exista antes de guardar la imagen
        Storage::disk('public')->makeDirectory('covers');
        $data['image_path'] = $request->file('image')->store('covers', 'public');

        // Crea el registro de la portada en la base de datos con los datos validados
        // El método create inserta los datos y devuelve el modelo creado
        $cover = Cover::create($data);

        // Configura un mensaje flash para mostrar una notificación al usuario
        // Utiliza SweetAlert (swal) para mostrar un mensaje de éxito
        // Este mensaje aparecerá en la siguiente página cargada
        session()->flash('swal', [
            'icon' => 'success',           // Icono a mostrar (check verde)
            'title' => '¡Portada creada!', // Título del mensaje
            'text' => 'La portada se ha creado correctamente', // Descripción
        ]);

        // Redirecciona al usuario a la página de edición de la portada recién creada
        // Esto permite al usuario realizar ajustes adicionales si es necesario
        return redirect()->route('admin.covers.edit', $cover);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cover $cover)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cover $cover)
    {
        //
        return view('admin.covers.edit', compact('cover'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cover $cover)
    {
        // Validar los datos del formulario de actualización
        // Similar a la validación del método store, pero con algunas diferencias:
        // - La imagen es opcional (nullable) ya que el usuario puede mantener la imagen existente
        // - Se verifican los mismos criterios de validación para los demás campos
        $data = $request->validate([
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif,bmp,webp,svg,avif',
                'max:2048',
                // Validación personalizada de nombre de archivo (máx 100 chars)
                function ($attribute, $value, $fail) use ($request) {
                    $file = $request->file('image');
                    if ($file) {
                        $original = $file->getClientOriginalName();
                        if (mb_strlen($original) > 100) {
                            return $fail('El nombre del archivo es demasiado largo. Máximo 100 caracteres.');
                        }
                        if (!preg_match('/^[a-zA-Z0-9._\-\s]{1,100}\.(jpg|jpeg|png|gif|bmp|webp|svg|avif)$/i', $original)) {
                            return $fail('El nombre del archivo contiene caracteres no permitidos o extensión inválida.');
                        }
                    }
                }
            ],
            'title' => 'required|string|max:255',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'required|boolean',
        ], [
            // Mensajes de error personalizados (vacío)
        ], [
            // Alias para los nombres de campos en los mensajes de error
            'image' => 'Imagen',
            'title' => 'Título',
            'start_at' => 'Fecha de inicio',
            'end_at' => 'Fecha de fin',
            'is_active' => 'Estado',
        ]);

        // Verificar si se ha enviado una nueva imagen
        if ($request->hasFile('image')) {
            Storage::disk('public')->makeDirectory('covers');
            // Eliminar la imagen anterior del sistema de archivos
            // Esto evita acumular archivos no utilizados y libera espacio de almacenamiento
            Storage::delete($cover->image_path);

            // Almacenar la nueva imagen en el sistema de archivos
            // Se guarda en la carpeta 'covers' y se obtiene la ruta relativa
            $data['image_path'] = $request->file('image')->store('covers', 'public');
        }

        // Actualizar el modelo con los datos validados
        // Solo se actualizarán los campos presentes en el array $data
        // Si no hay nueva imagen, image_path mantendrá su valor original
        $cover->update($data);

        // Configurar mensaje flash de éxito
        // Se utiliza SweetAlert para mostrar un mensaje visual al usuario
        // Indicando que la operación se completó correctamente
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Portada actualizada!',
            'text' => 'La portada se ha modificado correctamente',
        ]);

        // Redireccionar de vuelta a la página de edición
        // Esto permite al usuario continuar realizando cambios si es necesario
        // O verificar que los cambios se aplicaron correctamente
        return redirect()->route('admin.covers.edit', $cover);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cover $cover)
    {
        //
    }
}
