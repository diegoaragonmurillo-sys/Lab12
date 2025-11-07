<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'name' => Auth::check() ? 'nullable' : 'required|string|max:255',
        ]);
        $userId = Auth::check() ? Auth::id() : null;

        $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $userId, 
        ]);

        return back()->with('success', 'Comentario añadido exitosamente.');
    }
    public function update(Request $request, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id || is_null($comment->user_id)) {
            return back()->with('error', 'No tienes permiso para editar este comentario. Solo el autor registrado puede hacerlo.');
        }

        $request->validate(['content' => 'required|string|max:1000']);
        $comment->update(['content' => $request->input('content')]);

        return back()->with('success', 'Comentario actualizado exitosamente.');
    }
    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id || is_null($comment->user_id)) {
            return back()->with('error', 'No tienes permiso para eliminar este comentario. Solo el autor registrado puede hacerlo.');
        }
        
        $comment->delete();

        return back()->with('success', 'Comentario eliminado exitosamente.');
    }
}