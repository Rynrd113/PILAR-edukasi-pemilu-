<?php

namespace App\Http\Requests\Materi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMateriRequest extends FormRequest
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
            'kategori_id' => ['sometimes', 'required', 'exists:kategoris,id'],
            'judul' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('materis', 'judul')->ignore($this->materi)
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('materis', 'slug')->ignore($this->materi)
            ],
            'konten' => ['sometimes', 'required', 'string'],
            'gambar' => ['nullable', 'image', 'max:2048'], // Max 2MB
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'judul.required' => 'Judul materi wajib diisi.',
            'judul.unique' => 'Judul materi sudah digunakan.',
            'konten.required' => 'Konten materi wajib diisi.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'slug.unique' => 'Slug materi sudah digunakan.',
        ];
    }
}
