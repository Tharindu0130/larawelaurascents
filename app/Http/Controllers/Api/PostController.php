<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /** Display a listing of the resource.*/
    public function index()
    {
        return PostResource::collection(Post::with(['category', 'user', 'tags', 'comments'])->get());
    }

    /**Store a newly created resource in storage*/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $validated['user_id'] = $request->user()->id;
        $tagIds = $validated['tag_ids'] ?? [];
        unset($validated['tag_ids']);

        $post = Post::create($validated);
        if (!empty($tagIds)) {
            $post->tags()->sync($tagIds);
        }

        return (new PostResource($post->load(['category', 'user', 'tags', 'comments'])))
            ->response()
            ->setStatusCode(201);
    }

    /**Display the specified resource.*/
    public function show(string $id)
    {
        $post = Post::with(['category', 'user', 'tags', 'comments'])->findOrFail($id);
        return new PostResource($post);
    }

    /**Update the specified resource in storage.*/
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'body' => 'nullable|string',
            'category_id' => 'sometimes|exists:categories,id',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        if (isset($validated['tag_ids'])) {
            $post->tags()->sync($validated['tag_ids']);
            unset($validated['tag_ids']);
        }
        $post->update($validated);

        return new PostResource($post->load(['category', 'user', 'tags', 'comments']));
    }

    /** Remove the specified resource from storage.*/
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->noContent();
    }
}
