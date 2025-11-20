@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar actividad #{{ $actividad->id }}</h2>

    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('actividades.update', $actividad->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Descripción</label><br>
            <textarea name="descripcion" rows="3" required>{{ old('descripcion', $actividad->descripcion) }}</textarea>
            @error('descripcion')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label>Estado</label><br>
            <input type="text" name="estado" value="{{ old('estado', $actividad->estado) }}" required>
            @error('estado')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label>Fecha inicio</label><br>
            <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $actividad->fecha_inicio) }}">
        </div>

        <div>
            <label>Fecha fin</label><br>
            <input type="date" name="fecha_fin" value="{{ old('fecha_fin', $actividad->fecha_fin) }}">
        </div>

        <button type="submit">Actualizar</button>
    </form>

    <br>
    <a href="{{ route('actividades.index', $actividad->nota_id) }}">Volver a actividades</a>
</div>
@endsection
