@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">Tambah Kategori</h2>
        <a href="{{ route('admin.kategori.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.kategori.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                       class="w-full px-3 py-2 border @error('nama') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                @error('nama')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                       class="w-full px-3 py-2 border @error('slug') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                <p class="mt-1 text-xs text-gray-500">Biarkan kosong untuk dibuat otomatis dari nama. Contoh: kategori-berita</p>
                @error('slug')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                          class="w-full px-3 py-2 border @error('deskripsi') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="is_active" class="inline-flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                           class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Aktif</span>
                </label>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Auto-generate slug from name
    const nameInput = document.getElementById('nama');
    const slugInput = document.getElementById('slug');

    nameInput.addEventListener('keyup', function() {
        if (!slugInput.value) {
            slugInput.value = this.value
                .toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .substring(0, 60);
        }
    });
</script>
@endpush
