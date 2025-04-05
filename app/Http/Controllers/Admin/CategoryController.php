<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Family;

use function Ramsey\Uuid\v1;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::orderBy('id', 'desc') //ordena los registros de la tabla por el id de forma descendente
            ->with('family') //carga la relacion con la tabla family con antelacion para no hacer consultas adicionales y evitar el n+1
            ->paginate(); //muestra todos los registros en la tabla de forma paginada es decir carga los datos como se los va necesitando
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $families = Family::all(); //obtiene todos los registros de la tabla family
        return view('admin.categories.create', compact('families'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //return $request->all();//muestra todos los datos que se envian por el formulario para saber si esta llegando la informacion
        request()->validate([
            'family_id' => 'required|exists:families,id',
            'name' => 'required',
        ]);

        //crea un nuevo registro en la tabla category con los datos que se envian por el formulario

        Category::create($request->only('name', 'family_id'));


        //mensaje de confirmacion de creacion de categoria con sweetalert
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '!Bien hecho!',
            'text' => 'Categoría Creada correctamente',
            'timeout' => 3000
        ]);

        return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
        $families = Family::all(); //obtiene todos los registros de la tabla family
        return view('admin.categories.edit', compact('category', 'families')); //se recupera familias por si se quiere cambiar la familia de la categoria
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
        // return $request->all();muestra todos los datos que se envian por el formulario para saber si esta llegando la informacion

        request()->validate([
            'family_id' => 'required|exists:families,id',
            'name' => 'required',
        ]);

        //actualiza el registro en la tabla category con los datos que se envian por el formulario
        $category->update($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '!Bien hecho!',
            'text' => 'Categoría Actualizada correctamente',
            'timeout' => 3000
        ]);

        return redirect()->route('admin.categories.edit', $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        if ($category->subcategories->count() > 0) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '!Error!',
                'text' => 'No se puede eliminar la categoría porque tiene subcategorias asociadas',
                'timeout' => 3000
            ]);
            return redirect()->route('admin.categories.edit', $category);
        }

        $category->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '!Bien hecho!',
            'text' => 'Categoría Eliminada correctamente',
            'timeout' => 3000
        ]);

        return redirect()->route('admin.categories.index');
    }
}
