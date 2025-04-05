<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Family;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $families = Family::orderBy('id', 'desc') //ordena los registros de la tabla por el id de forma descendente
            ->paginate(); //muestra todos los registros en la tabla de forma paginada es decir carga los datos como se los va necesitando
        return view('admin.families.index', compact('families'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.families.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
        ]);

        Family::create($request->all());

        //mensaje de confirmacion de creacion de familia con sweetalert
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '!Bien hecho!',
            'text' => 'Familia Creada correctamente',
            'timeout' => 3000
        ]);


        return redirect()->route('admin.families.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Family $family)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Family $family)
    {
        //retorna la vista de edicion de familias con los datos de la familia a editar obtenidos en el metodo family especificado en la funcion
        return view('admin.families.edit', compact('family'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Family $family)
    {
        //
        $request->validate([
            'name' => 'required',
        ]);

        $family->update($request->all());

        //mensaje de confirmacion de actualizacion de familia con sweetalert
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '!Bien hecho!',
            'text' => 'Familia actualizada correctamente',
            'timeout' => 3000
        ]);

        return redirect()->route('admin.families.edit', $family);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Family $family)
    {
        //
        if ($family->categories()->count() > 0) { //verifica si la familia tiene categorias asociadas
            //mensaje de error de eliminacion de familia con sweetalert
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '!Error!',
                'text' => 'La familia no se puede eliminar porque tiene categorias asociadas',
                'timeout' => 3000
            ]);
            return redirect()->route('admin.families.edit', $family); //redirecciona a la vista de edicion de familias
        }
        //mensaje de error de eliminacion de familia con sweetalert
        $family->delete(); //elimina el registro de la familia

        //mensaje de confirmacion de eliminacion de familia con sweetalert
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '!Bien hecho!',
            'text' => 'Familia eliminada correctamente',
            'timeout' => 3000
        ]);
        return redirect()->route('admin.families.index'); //redirecciona a la vista de familias
    }
}
