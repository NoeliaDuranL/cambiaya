<?php

namespace App\Http\Controllers;

use App\Models\Mensaje;
use Illuminate\Http\Request;

class MensajeController extends Controller
{
    public function index()
    {
        return response()->json(Mensaje::all());
    }

    public function show($id)
    {
        $mensaje = Mensaje::findOrFail($id);
        return response()->json($mensaje);
    }

    public function store(Request $request)
    {
        $mensaje = Mensaje::create($request->all());
        return response()->json($mensaje, 201);
    }

    public function update(Request $request, $id)
    {
        $mensaje = Mensaje::findOrFail($id);
        $mensaje->update($request->all());
        return response()->json($mensaje);
    }

    public function destroy($id)
    {
        Mensaje::destroy($id);
        return response()->json(['message' => 'Mensaje eliminado con éxito']);
    }
}
