<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        return response()->json(Notificacion::all());
    }

    public function show($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        return response()->json($notificacion);
    }

    public function store(Request $request)
    {
        $notificacion = Notificacion::create($request->all());
        return response()->json($notificacion, 201);
    }

    public function update(Request $request, $id)
    {
        $notificacion = Notificacion::findOrFail($id);
        $notificacion->update($request->all());
        return response()->json($notificacion);
    }

    public function destroy($id)
    {
        Notificacion::destroy($id);
        return response()->json(['message' => 'Notificación eliminada con éxito']);
    }
}
