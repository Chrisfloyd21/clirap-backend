<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Public : Liste des articles
    public function index()
    {
        return Post::where('is_published', true)->latest()->paginate(6);
    }

    // Public : Voir un seul article
    public function show($slug)
    {
        return Post::where('slug', $slug)->firstOrFail();
    }

    // Admin : Créer un article
    public function store(Request $request)
    {
        $data = $request->validate([
            'title_it' => 'required',
            'content_it' => 'required',
            'image_url' => 'nullable|url',
        ]);

        $data['slug'] = Str::slug($data['title_it']) . '-' . time();
        $data['excerpt_it'] = Str::limit($data['content_it'], 100);
        $data['user_id'] = $request->user()->id; // L'admin connecté
        $data['is_published'] = true;

        return Post::create($data);
    }

    // Admin : Supprimer
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->noContent();
    }
}