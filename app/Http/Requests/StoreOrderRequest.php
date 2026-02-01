<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'], // Security: Email validation
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'zip' => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'string', 'in:credit_card,paypal,bank_transfer'], // Security: Enum validation
            'cart_items' => ['required', 'array', 'min:1'], // Security: Ensures at least one item
            'cart_items.*.product_id' => ['required', 'integer', 'exists:products,id'], // Security: Foreign key validation
            'cart_items.*.quantity' => ['required', 'integer', 'min:1', 'max:100'], // Security: Prevents quantity manipulation
        ];
    }
}
