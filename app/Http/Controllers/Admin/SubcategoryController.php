<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Models\Category;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $subcategories = Subcategory::orderBy('id', 'desc') //ordena los registros de la tabla por el id de forma descendente
            ->with('category.family') //carga la relacion con la tabla category y family con antelacion para no hacer consultas adicionales y evitar el n+1
            ->paginate(10); //muestra todos los registros en la tabla de forma paginada es decir carga los datos como se los va necesitando
        return view('admin.subcategories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        //$categories = Category::all(); //obtiene todos los registros de la tabla category perono es necesario porques estamos obteniendo de livewire
        return view('admin.subcategories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        request()->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
        ]);

        //crea un nuevo registro en la tabla subcategory con los datos que se envian por el formulario

        Subcategory::create($request->all());
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '!Bien hecho!',
            'text' => 'Subcategoría Creada correctamente',
            'timeout' => 3000
        ]);

        return redirect()->route('admin.subcategories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subcategory $subcategory)
    {
        //
        return view('admin.subcategories.edit', compact('subcategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        //
        if ($subcategory->products->count() > 0) //verifica si la subcategoria tiene productos relacionados
        {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '!Error!',
                'text' => 'No se puede eliminar la subcategoría porque tiene productos relacionados',
                'timeout' => 3000
            ]);

            return redirect()->route('admin.subcategories.edit', $subcategory);
        }

        $subcategory->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '!Bien hecho!',
            'text' => 'Subcategoría Eliminada correctamente',
            'timeout' => 3000
        ]);

        return redirect()->route('admin.subcategories.index');
    }
}
