@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Encabezado --}}
    <div class="row mb-4">
        <div class="col-lg-10 mx-auto">
            <h1 class="h3 mb-1">Actividades de la nota</h1>
            <p class="text-muted mb-0">
                <strong>{{ $nota->titulo ?? 'Sin título' }}</strong>
            </p>
        </div>
    </div>

    {{-- Mensajes --}}
    <div class="row">
        <div class="col-lg-10 mx-auto">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        {{-- Card: Nueva actividad --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-2">
                    <h5 class="mb-0">Nueva actividad</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('actividades.store', $nota->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" rows="3"
                                      class="form-control @error('descripcion') is-invalid @enderror"
                                      placeholder="Describe la actividad..." required>{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select name="estado"
                                    class="form-select @error('estado') is-invalid @enderror">
                                <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="en_progreso" {{ old('estado') == 'en_progreso' ? 'selected' : '' }}>En progreso</option>
                                <option value="completada" {{ old('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha inicio</label>
                            <input type="date" name="fecha_inicio"
                                   class="form-control @error('fecha_inicio') is-invalid @enderror"
                                   value="{{ old('fecha_inicio') }}">
                            @error('fecha_inicio')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha fin</label>
                            <input type="date" name="fecha_fin"
                                   class="form-control @error('fecha_fin') is-invalid @enderror"
                                   value="{{ old('fecha_fin') }}">
                            @error('fecha_fin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Guardar actividad
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <a href="{{ route('notas.index') }}" class="btn btn-link mt-3">
                ← Volver a notas
            </a>
        </div>

        {{-- Card: Listado de actividades --}}
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light py-2">
                    <h5 class="mb-0">Listado de actividades</h5>
                </div>
                <div class="card-body p-0">
                    @if($actividades->count())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Fecha inicio</th>
                                        <th>Fecha fin</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($actividades as $actividad)
                                        <tr>
                                            <td>{{ $actividad->id }}</td>
                                            <td>{{ $actividad->descripcion }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = match($actividad->estado) {
                                                        'completada' => 'success',
                                                        'en_progreso' => 'warning',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $badgeClass }}">
                                                    {{ ucfirst(str_replace('_', ' ', $actividad->estado)) }}
                                                </span>
                                            </td>
                                            <td>{{ $actividad->fecha_inicio ? \Carbon\Carbon::parse($actividad->fecha_inicio)->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $actividad->fecha_fin ? \Carbon\Carbon::parse($actividad->fecha_fin)->format('d/m/Y') : '-' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('actividades.edit', $actividad->id) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    Editar
                                                </a>

                                                <form action="{{ route('actividades.destroy', $actividad->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('¿Eliminar esta actividad?')">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <p class="mb-0">No hay actividades registradas para esta nota.</p>
                            <small>Agrega la primera usando el formulario de la izquierda.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
