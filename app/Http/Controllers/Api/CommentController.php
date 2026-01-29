<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CommentResource::collection(Comment::with(['user', 'product', 'post'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'post_id' => 'nullable|exists:posts,id',
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);
        if (empty($validated['product_id']) && empty($validated['post_id'])) {
            abort(422, 'Either product_id or post_id is required.');
        }
        $validated['user_id'] = $request->user()->id;

        $comment = Comment::create($validated);

        return (new CommentResource($comment->load(['user', 'product', 'post'])))
            ->response()
            ->setStatusCode(201);
    }

    /** Display the specified resource.*/
    public function show(string $id)
    {
        $comment = Comment::with(['user', 'product', 'post'])->findOrFail($id);
        return new CommentResource($comment);
    }

    /** Update the specified resource in storage.*/
    public function update(Request $request, string $id)
    {
        $comment = Comment::findOrFail($id);

        $validated = $request->validate([
            'content' => 'sometimes|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $comment->update($validated);

        return new CommentResource($comment->load(['user', 'product', 'post']));
    }

    /*** Remove the specified resource from storage.*/
    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->noContent();
    }
}
