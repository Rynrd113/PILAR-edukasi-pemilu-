<?php

namespace App\Http\Requests\Materi;

use Illuminate\Foundation\Http\FormRequest;

class StoreMateriRequest extends FormRequest
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
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'judul' => ['required', 'string', 'max:255', 'unique:materis,judul'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:materis,slug'],
            'konten' => ['required', 'string'],
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
