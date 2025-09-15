<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'paragraphs' => 'required|array|min:1',
            'paragraphs.*.title' => 'required|string|max:255',
            'paragraphs.*.body' => 'required|string',
            'paragraphs.*.order' => 'required|integer|min:1',
            // الصور الخاصة بكل فقرة ممكن تكون موجودة أو لا، لذلك نستخدم sometimes
            'paragraphs.*.image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ];
    }
}
