<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::with('categoria')->orderBy('id', 'desc')->paginate(15);
        return response()->json($productos, 200);
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
            "nombre" =>"required",
            "categoria_id" => "required"
        ]);

        // guardar
        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->categoria_id = $request->categoria_id;
        $producto->stock = $request->stock;
        $producto->precio = $request->precio;
        $producto->descripcion = $request->descripcion;
        $producto->save();

        return response()->json(["mensaje" => "Producto registrado"], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);

        return response()->json($producto, 200);

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
            "nombre" =>"required",
            "categoria_id" => "required"
        ]);

        // guardar
        $producto = Producto::findOrFail($id);
        $producto->nombre = $request->nombre;
        $producto->categoria_id = $request->categoria_id;
        $producto->stock = $request->stock;
        $producto->precio = $request->precio;
        $producto->descripcion = $request->descripcion;
        $producto->save();

        return response()->json(["mensaje" => "Producto actualizado"], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return response()->json(["mensaje" => "Producto eliminado"], 200);
    }

    public function subirImagen($id, Request $request)
    {
        if($file = $request->file("imagen")){

            $direccion_archivo = time() . "-".$file->getClientOriginalName();
            $file->move("imagenes/", $direccion_archivo);

            $nombre_imagen = "imagenes/". $direccion_archivo;

            $producto = Producto::find($id);
            $producto->imagen = $nombre_imagen;
            $producto->update();
            return response()->json(["mensaje" => "Imagen Actualizada"], 200);

        }
        return response()->json(["mensaje" => "Error al subir la imagen"], 422);
    }
}
