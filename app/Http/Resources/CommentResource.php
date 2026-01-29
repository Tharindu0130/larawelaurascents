<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'post_id' => $this->post_id,
            'user_id' => $this->user_id,
            'content' => $this->content,
            'rating' => $this->rating,
            'user' => $this->whenLoaded('user'),
            'product' => $this->whenLoaded('product'),
            'post' => $this->whenLoaded('post'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
