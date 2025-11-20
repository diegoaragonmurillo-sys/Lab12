<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Actividad;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    /**
     * Mostrar todas las actividades de una nota
     */
    public function index($nota_id)
    {
        $nota = Nota::findOrFail($nota_id);
        $actividades = $nota->actividades()->orderBy('id', 'desc')->get();

        return view('actividades.index', compact('nota', 'actividades'));
    }

    /**
     * Guardar una actividad nueva
     */
    public function store(Request $request, $nota_id)
    {
        $request->validate([
            'descripcion'   => 'required|string',
            'estado'        => 'required|string',
            'fecha_inicio'  => 'nullable|date',
            'fecha_fin'     => 'nullable|date',
        ]);

        Actividad::create([
            'nota_id'      => $nota_id,
            'descripcion'  => $request->descripcion,
            'estado'       => $request->estado,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
        ]);

        return redirect()->back()->with('success', 'Actividad creada correctamente.');
    }

    /**
     * Mostrar el formulario de edición
     */
    public function edit($id)
    {
        $actividad = Actividad::findOrFail($id);

        return view('actividades.edit', compact('actividad'));
    }

    /**
     * Actualizar actividad
     */
    public function update(Request $request, $id)
    {
        $actividad = Actividad::findOrFail($id);

        $request->validate([
            'descripcion'   => 'required|string',
            'estado'        => 'required|string',
            'fecha_inicio'  => 'nullable|date',
            'fecha_fin'     => 'nullable|date',
        ]);

        $actividad->update([
            'descripcion'  => $request->descripcion,
            'estado'       => $request->estado,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
        ]);

        return redirect()
            ->route('actividades.index', $actividad->nota_id)
            ->with('success', 'Actividad actualizada correctamente.');
    }

    /**
     * Eliminar actividad
     */
    public function destroy($id)
    {
        $actividad = Actividad::findOrFail($id);
        $notaId = $actividad->nota_id;

        $actividad->delete();

        return redirect()
            ->route('actividades.index', $notaId)
            ->with('success', 'Actividad eliminada correctamente.');
    }
}
