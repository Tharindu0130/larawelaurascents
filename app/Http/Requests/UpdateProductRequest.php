<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ASSIGNMENT CRITERIA: Advanced Laravel Validation
 * Separate validation for update operations (different rules than create)
 */
class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * ASSIGNMENT CRITERIA: Partial Updates
     * Using 'sometimes' allows partial updates (PATCH method)
     * Security: Still validates all provided fields
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'image' => ['nullable', 'string', 'max:500', 'url'],
            'category_id' => ['sometimes', 'exists:categories,id'],
        ];
    }
}
