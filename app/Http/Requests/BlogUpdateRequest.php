<?php

namespace App\Http\Requests;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;

class BlogUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $blogs = Blog::with('attachments')->where('id', $this->route('id'))->first();

        $validation = $blogs->attachments->count() > 0 ? "nullable" : 'required';

        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'images' => $validation,
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
