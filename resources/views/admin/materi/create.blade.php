@extends('admin.layouts.app')

@section('title', 'Tambah Materi')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">Tambah Materi</h2>
        <a href="{{ route('admin.materi.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.materi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left column -->
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Materi <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                               class="w-full px-3 py-2 border @error('judul') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('judul')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                               class="w-full px-3 py-2 border @error('slug') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        <p class="mt-1 text-xs text-gray-500">Biarkan kosong untuk dibuat otomatis dari judul</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="konten" class="block text-sm font-medium text-gray-700 mb-1">Konten <span class="text-red-500">*</span></label>
                        <textarea name="konten" id="konten" rows="15" required
                                  class="w-full px-3 py-2 border @error('konten') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">{{ old('konten') }}</textarea>
                        @error('konten')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right column -->
                <div class="space-y-6">
                    <div>
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori_id" id="kategori_id" required
                                class="w-full px-3 py-2 border @error('kategori_id') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gambar" class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
                        <input type="file" name="gambar" id="gambar" accept="image/*"
                               class="w-full px-3 py-2 border @error('gambar') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Ukuran maks: 2MB</p>
                        @error('gambar')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror

                        <div id="image-preview" class="mt-3 hidden">
                            <img id="preview-image" src="#" alt="Preview" class="max-w-full h-auto rounded-md">
                        </div>
                    </div>

                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi</label>
                        <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
                               class="w-full px-3 py-2 border @error('published_at') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        <p class="mt-1 text-xs text-gray-500">Biarkan kosong untuk menggunakan waktu saat ini jika dipublikasikan</p>
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="is_published" class="inline-flex items-center">
                            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') == '1' ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Publikasikan Sekarang</span>
                        </label>
                        @error('is_published')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Simpan Materi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
<style>
.editor-toolbar {
    border-top: 1px solid #bbb;
    border-left: 1px solid #bbb;
    border-right: 1px solid #bbb;
}
.CodeMirror {
    border: 1px solid #bbb;
}
.editor-preview-side {
    border: 1px solid #bbb;
}
/* Pastikan textarea tetap accessible */
textarea[name="konten"] {
    opacity: 1 !important;
    position: static !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script>
    // Initialize Markdown editor
    const easyMDE = new EasyMDE({
        element: document.getElementById('konten'),
        spellChecker: false,
        autosave: {
            enabled: true,
            uniqueId: 'materi-content',
            delay: 1000,
        },
        toolbar: [
            'bold', 'italic', 'heading', '|',
            'quote', 'unordered-list', 'ordered-list', '|',
            'link', 'image', '|',
            'preview', 'side-by-side', 'fullscreen', '|',
            'guide'
        ],
        forceSync: true, // Sinkronkan otomatis ke textarea
        hideIcons: ['side-by-side', 'fullscreen'], // Hindari mode yang bisa menyembunyikan textarea
    });

    // Auto-generate slug from title
    const titleInput = document.getElementById('judul');
    const slugInput = document.getElementById('slug');

    titleInput.addEventListener('keyup', function() {
        if (!slugInput.value) {
            slugInput.value = this.value
                .toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .substring(0, 60);
        }
    });

    // Image preview
    const imageInput = document.getElementById('gambar');
    const imagePreview = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-image');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                imagePreview.classList.remove('hidden');
            }

            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
        }
    });

    // Pastikan value EasyMDE tersimpan ke textarea sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        // Sinkronkan value dari EasyMDE ke textarea
        const kontenTextarea = document.getElementById('konten');
        kontenTextarea.value = easyMDE.value();
        
        // Validasi konten tidak kosong
        if (!kontenTextarea.value.trim()) {
            e.preventDefault();
            alert('Konten materi wajib diisi!');
            easyMDE.codemirror.focus();
            return false;
        }
        
        // Hapus atribut required dari textarea untuk menghindari validasi browser
        kontenTextarea.removeAttribute('required');
    });
</script>
@endpush
