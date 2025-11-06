@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Manejo de Mensajes de Éxito o Error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    {{-- Fin de Mensajes --}}
    
    <div class="card-body">
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->content }}</p>
        <p><small>Por: {{ $post->user->name }}</small></p>

        {{-- ZONA DE BOTONES (Volver y Comentar) --}}
        <div class="d-flex align-items-center mt-4 mb-4">
            {{-- Botón Volver --}}
            <a href="{{ route('posts.index') }}" class="btn btn-secondary me-2">Volver</a>

            {{-- Botón Comentar (Visible para TODOS) --}}
            <button type="button" 
                    class="btn btn-success" 
                    data-bs-toggle="modal" {{-- ¡SINTAXIS CORRECTA BS5! --}}
                    data-bs-target="#commentModal"> {{-- ¡SINTAXIS CORRECTA BS5! --}}
                Comentar
            </button>
        </div>

        {{-- SECCIÓN PARA MOSTRAR COMENTARIOS --}}
        <hr>
        <h4 class="mb-3">Comentarios ({{ $post->comments->count() }})</h4>
        @forelse ($post->comments as $comment)
            <div class="card mb-2 bg-light">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="mb-0">{{ $comment->content }}</p>

                        {{-- BOTONES DE ACCIÓN: SOLO SI EL USUARIO LOGUEADO ES EL AUTOR --}}
                        @auth
                            @if (Auth::id() === $comment->user_id)
                                <div class="btn-group btn-group-sm flex-shrink-0">
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $comment->id }}">
                                        Editar
                                    </button>
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este comentario?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                    
                    <footer class="blockquote-footer mt-1 text-end">
                        {{ $comment->user->name ?? 'Invitado' }} 
                        <cite title="Fecha">{{ $comment->created_at->diffForHumans() }}</cite>
                    </footer>
                </div>
            </div>
            
            {{-- MODAL DE EDICIÓN (SOLO SI ES EL AUTOR) --}}
            @auth
                @if (Auth::id() === $comment->user_id)
                <div class="modal fade" id="editCommentModal{{ $comment->id }}" tabindex="-1" aria-labelledby="editCommentModalLabel{{ $comment->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCommentModalLabel{{ $comment->id }}">Editar Comentario</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> {{-- ¡SINTAXIS CORRECTA BS5! --}}
                            </div>
                            <form action="{{ route('comments.update', $comment) }}" method="POST">
                                @csrf 
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="content{{ $comment->id }}">Tu Comentario</label>
                                        <textarea class="form-control" id="content{{ $comment->id }}" name="content" rows="3" required>{{ $comment->content }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @endauth

        @empty
            <p>Sé el primero en comentar esta publicación.</p>
        @endforelse

    </div>
</div>

{{-- MODAL PRINCIPAL DE CREACIÓN DE COMENTARIO (Funciona para todos) --}}
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">Añadir Comentario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> {{-- ¡SINTAXIS CORRECTA BS5! --}}
            </div>
            <form action="{{ route('comments.store', $post) }}" method="POST">
                @csrf 
                <div class="modal-body">
                    {{-- Si no está logueado, pedimos un nombre --}}
                    @guest
                        <div class="form-group mb-3">
                            <label for="name">Tu Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    @endguest
                    
                    <div class="form-group">
                        <label for="content">Tu Comentario</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Enviar Comentario</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection