<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pedidos = Pedido::with('cliente', 'productos')->get();

        return response()->json($pedidos, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // crear un nuevo pedido
            $pedido = new Pedido();
            $pedido->fecha_pedido = date("Y-m-d H:i:s");
            // asignar el cliente al pedido
            $pedido->cliente_id = $request->cliente_id;
            $pedido->save();

            // asignar productos al pedido
            /*
            [
                [
                    "producto_id" => 4,
                    "cantidad" => 2
                ],
                [
                    "producto_id" => 8,
                    "cantidad" => 1
                ],
            ]
            */
            foreach ($request->productos as $producto) {
                $producto_id = $producto["producto_id"];
                $cantidad = $producto["cantidad"];
                $pedido->productos()->attach($producto_id, ["cantidad" => $cantidad]);
            }

            $pedido->estado = 2;
            $pedido->update();
            
            // actualizar pedido como (Completado)

            DB::commit();
            // all good
            return response()->json(["mensaje" => "Pedido Completado"], 201);

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(["mensaje" => "Ocurrió un problema al registrar el Pedido", "error" => $e], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pedido = Pedido::with('cliente', 'productos')->findOrFail($id);
        return response()->json($pedido, 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function generarPDF($id)
    {

        $pedido = Pedido::with('cliente', 'productos')->find($id);

        
    $pdf = Pdf::loadView('pdfs.pedido', compact("pedido"));
    return $pdf->stream('pedido.pdf');

    }
}
