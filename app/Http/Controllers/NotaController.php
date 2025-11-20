<?php

namespace App\Http\Controllers;
use App\Models\Nota;
use App\Models\User;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    public function index()
    {
        $users = User::with(['notas.recordatorio'])
            ->addSelect([
                'total_notas' => Nota::selectRaw('count(*)')
                    ->whereColumn('user_id', 'users.id')
                    ->whereHas('recordatorio', fn($q) => $q->where('fecha_vencimiento', '>=', now()))
            ])
            ->get();

        return view('notas.index', compact('users'));
    }
    public function create()
    {
        $users = User::all();
        return view('notas.create', compact('users'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'fecha_vencimiento' => 'required|date|after:now',
        ]);

        if (auth()->id() != $validated['user_id']) {
            abort(403, 'No puedes crear notas para otros usuarios.');
        }

        $nota = Nota::create([
            'user_id' => $validated['user_id'],
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'],
        ]);

        $nota->recordatorio()->create([
            'fecha_vencimiento' => $validated['fecha_vencimiento'],
            'completado' => false,
        ]);

        return redirect()->route('notas.index')->with('success', 'Nota creada correctamente');
    }

    public function show(Nota $nota)
    {
        if (auth()->id() !== $nota->user_id) {
            abort(403, 'No tienes permiso para ver esta nota.');
        }

        return view('notas.show', compact('nota'));
    }

    public function edit(Nota $nota)
    {
        if (auth()->id() !== $nota->user_id) {
            abort(403, 'No tienes permiso para editar esta nota.');
        }

        return view('notas.edit', compact('nota'));
    }

    public function update(Request $request, Nota $nota)
    {
        if (auth()->id() !== $nota->user_id) {
            abort(403, 'No tienes permiso para modificar esta nota.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'fecha_vencimiento' => 'nullable|date',
            'completado' => 'nullable|boolean',
        ]);

        $nota->update([
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'],
        ]);

        if ($nota->recordatorio) {
            $nota->recordatorio->update([
                'fecha_vencimiento' => $validated['fecha_vencimiento'] ?? $nota->recordatorio->fecha_vencimiento,
                'completado' => $request->has('completado'),
            ]);
        }

        return redirect()->route('notas.show', $nota)->with('success', 'Nota actualizada correctamente');
    }
    public function destroy(Nota $nota)
    {
        if (auth()->id() !== $nota->user_id) {
            abort(403, 'No tienes permiso para eliminar esta nota.');
        }

        \DB::transaction(function () use ($nota) {


            $nota->actividades()->delete();


            if ($nota->recordatorio) {

                $nota->recordatorio()->delete();
            }

            $nota->forceDelete();  
        });

        return redirect()
            ->route('notas.index')
            ->with('success', 'Nota, recordatorio y actividades eliminadas correctamente');
    }


}
