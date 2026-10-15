<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only admins can submit this form
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];

        // If this is a POST request (creating a new product), image is required
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,webp|max:2048';
        }
        // If this is a PUT/PATCH request (updating), image is optional
        else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048';
        }

        return $rules;
    }
}
