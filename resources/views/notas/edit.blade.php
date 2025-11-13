@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Editar Nota</h2>

    <form action="{{ route('notas.update', $nota->id) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="{{ $nota->titulo }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contenido</label>
            <textarea name="contenido" rows="4" class="form-control" required>{{ $nota->contenido }}</textarea>
        </div>

        @if ($nota->recordatorio)
        <div class="mb-3">
            <label class="form-label">Fecha de Vencimiento</label>
            <input type="datetime-local" name="fecha_vencimiento"
                class="form-control"
                value="{{ \Carbon\Carbon::parse($nota->recordatorio->fecha_vencimiento)->format('Y-m-d\TH:i') }}">
        </div>
        @endif

        <div class="form-check mb-3">
            <input type="checkbox" name="completado" id="completado" class="form-check-input"
                   {{ $nota->recordatorio && $nota->recordatorio->completado ? 'checked' : '' }}>
            <label class="form-check-label" for="completado">Marcar como completado</label>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('notas.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
