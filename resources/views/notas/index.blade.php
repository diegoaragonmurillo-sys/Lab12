@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Título y botón de nueva nota --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Listado de Notas</h2>
        <a href="{{ route('notas.create') }}" class="btn btn-success">+ Nueva Nota</a>
    </div>

    {{-- Listado de usuarios con sus notas --}}
    @foreach ($users as $user)
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Usuario: {{ $user->name }}</strong>
                <span class="badge bg-info text-dark">{{ $user->total_notas }} Notas activas</span>
            </div>

            <div class="card-body">
                @forelse ($user->notas as $nota)
                    @if($nota->recordatorio && !$nota->recordatorio->completado)
                        <div class="p-3 mb-3 border rounded bg-white">
                            <h5 class="fw-semibold mb-1">{{ $nota->titulo }}</h5>
                            <p class="text-muted mb-2">{{ $nota->contenido }}</p>

                            {{-- Mostrar fecha del recordatorio --}}
                            @if ($nota->recordatorio)
                                <p class="mb-1">
                                    <strong>Vence:</strong>
                                    {{ \Carbon\Carbon::parse($nota->recordatorio->fecha_vencimiento)->format('Y-m-d H:i') }}
                                    <span class="badge {{ $nota->recordatorio->completado ? 'bg-secondary' : 'bg-warning text-dark' }}">
                                        {{ $nota->recordatorio->completado ? 'Completado' : 'Pendiente' }}
                                    </span>
                                </p>
                            @endif

                            {{-- Botones de acciones --}}
                            <div class="mt-3">
                                {{-- Solo el dueño puede editar o eliminar --}}
                                @if (auth()->check() && auth()->id() === $nota->user_id)
                                    <a href="{{ route('notas.show', $nota->id) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                                    <a href="{{ route('notas.edit', $nota->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>

                                    <form action="{{ route('notas.destroy', $nota->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Seguro que deseas eliminar esta nota?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                @else
                                    {{-- Usuarios no dueños solo pueden ver --}}
                                    <a href="{{ route('notas.show', $nota->id) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                                @endif
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-center text-muted fst-italic">No hay notas activas para este usuario.</p>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection
