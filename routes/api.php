<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["prefix" => "v1/auth"], function(){
    
    Route::post("registro", [AuthController::class, "registrar"]);
    Route::post("login", [AuthController::class, "ingresar"]);

    Route::group(["middleware" => "auth:sanctum"], function(){
        
        Route::get("perfil", [AuthController::class, "getPerfil"]);
        Route::post("logout", [AuthController::class, "salir"]);
    });
});

Route::post("producto/{id}/subir-imagen", [ProductoController::class, "subirImagen"]);

Route::get("/pedido/{id}/pdf", [PedidoController::class, "generarPDF"]);

Route::group(["middleware" => "auth:sanctum"], function(){
    Route::apiResource("categoria", CategoriaController::class);
    Route::apiResource("usuario", UsuarioController::class);
    Route::apiResource("producto", ProductoController::class);
    Route::apiResource("pedido", PedidoController::class);
    Route::apiResource("cliente", ClienteController::class);
    Route::apiResource("persona", PersonaController::class);
});

// Route::post('/documentos', 'DocumentoController@store');

// // Controlador para manejar la subida del documento
// class DocumentoController extends Controller
// {
//     public function store(Request $request)
//     {
//         // Obtener el archivo PDF subido
//         $archivo = $request->file('archivo');

//         // Guardar el archivo en la base de datos
//         $documento = new Documento;
//         $documento->nombre = $archivo->getClientOriginalName();
//         $documento->archivo = file_get_contents($archivo);
//         $documento->save();

//         // Opcionalmente, puedes redirigir o devolver una respuesta de éxito
//         return redirect('/')->with('success', 'Documento subido exitosamente.');
//     }
