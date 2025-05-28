<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate as FacadesGate;

class PostController extends Controller implements HasMiddleware
{
    public static function Middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        // $post = Post::create($fields);
        $post = $request->user()->posts()->create($fields);

        return $post;
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return ['post' => $post];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {

        FacadesGate::authorize('modify', $post);

        $fields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        $post->update($fields);

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        FacadesGate::authorize('modify', $post);

        $post->delete();

        return ['message' => 'the post is delete.'];
    }
}
