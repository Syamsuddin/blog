<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'min:10'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tags' => ['array', 'max:10'],
            'tags.*' => ['exists:tags,id'],
            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,webp',
                'max:2048',
                'dimensions:max_width=2000,max_height=2000'
            ],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'published' => ['boolean'],
            'featured' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.min' => 'Title must be at least 3 characters.',
            'body.required' => 'Content is required.',
            'body.min' => 'Content must be at least 10 characters.',
            'featured_image.dimensions' => 'Image dimensions should not exceed 2000x2000 pixels.',
            'tags.max' => 'Maximum 10 tags allowed.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'published' => $this->boolean('published'),
            'featured' => $this->boolean('featured'),
        ]);
    }
}
