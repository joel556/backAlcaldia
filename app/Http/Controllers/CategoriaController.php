<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
// use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::get();

        return response()->json($categorias, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validar
        $request->validate([
            "nombre" =>"required|min:2|max:30"
        ]);
        // guardar
        $cat = new Categoria();
        $cat->categoria = $request->nombre;
        $cat->detalle = $request->detalle;
        $cat->save();
        // reponder
        return response()->json(["mensaje" => "Categoria Regitrada"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoria = Categoria::find($id);

        return response()->json($categoria, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validar
        $request->validate([
            "nombre" =>"required|min:2|max:30"
        ]);
        // guardar
        $cat = Categoria::find($id);
        $cat->categoria = $request->nombre;
        $cat->detalle = $request->detalle;
        $cat->update();
        // reponder
        return response()->json(["mensaje" => "Categoria Actualizada"], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Categoria::find($id);
        $cat->delete();

        return response()->json(["mensaje" => "Categoria Eliminada"], 200);
    }
}
