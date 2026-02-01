<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StoreProductRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        // Security: Ensure user is authenticated (Sanctum token required)
        return $this->user() !== null;
    }

  
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'], // Security: Prevents XSS via length limit
            'description' => ['nullable', 'string'], // Security: String type prevents code injection
            'price' => ['required', 'numeric', 'min:0'], // Security: Type validation prevents injection
            'stock' => ['required', 'integer', 'min:0'], // Security: Integer validation
            'image' => ['nullable', 'string', 'max:500', 'url'], // Security: URL validation prevents SSRF
            'category_id' => ['required', 'exists:categories,id'], // Security: Foreign key validation
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.max' => 'Product name cannot exceed 200 characters.',
            'price.required' => 'Product price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'category_id.exists' => 'Selected category does not exist.',
        ];
    }
}
