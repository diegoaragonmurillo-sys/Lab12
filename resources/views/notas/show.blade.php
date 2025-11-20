@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-3">{{ $nota->titulo }}</h2>

    <div class="card shadow-sm p-4">
        <p><strong>Contenido:</strong></p>
        <p>{{ $nota->contenido }}</p>

        @if ($nota->recordatorio)
            <p class="mt-3">
                <strong>Vencimiento:</strong>
                {{ \Carbon\Carbon::parse($nota->recordatorio->fecha_vencimiento)->format('Y-m-d H:i') }} <br>
                <strong>Estado:</strong>
                {{ $nota->recordatorio->completado ? 'Completado' : 'Pendiente' }}
            </p>
        @endif
    </div>

    <div class="mt-3">
        <a href="{{ route('notas.edit', $nota->id) }}" class="btn btn-secondary">Editar</a>
        <a href="{{ route('notas.index') }}" class="btn btn-outline-primary">Volver al listado</a>
        <a href="{{ route('actividades.index', $nota->id) }}" class="btn btn-info ms-2">
            Ver Actividades
        </a>


    </div>
</div>
@endsection
