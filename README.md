# PILAR-edukasi-pemilu

Website edukasi pemilu berbasis Laravel untuk KPU Nduga, fokus pada literasi pemilih pemula dan pengelolaan materi edukasi.

---

## Instalasi & Setup

1. **Clone repository**
   ```zsh
git clone <repo-url>
cd "edukasi-api (0.1) (awal)"
```
2. **Install dependencies**
   ```zsh
composer install
```
3. **Copy file environment**
   ```zsh
cp .env.example .env
```
   Lalu sesuaikan konfigurasi database di file `.env`.
4. **Generate key & migrate database**
   ```zsh
php artisan key:generate
php artisan migrate --seed
```
5. **Jalankan aplikasi**
   ```zsh
php artisan serve
```

---

## Fitur Utama
- Manajemen materi edukasi (CRUD)
- Kategori materi
- Statistik interaksi pengguna (views)
- Manajemen user & admin
- Dashboard admin
- Responsive UI (CSS/JS di `public/` dan `resources/`)

---

## Contoh Kode Penting

### Model Materi
```php
// app/Models/Materi.php
class Materi extends Model {
    // ...existing code...
    public function incrementViews(): void {
        $this->timestamps = false;
        $this->increment('views');
        $this->timestamps = true;
        \App\Models\MaterialView::recordView($this->id);
    }
    // ...existing code...
}
```

### Model Kategori
```php
// app/Models/Kategori.php
class Kategori extends Model {
    // ...existing code...
    public function materis(): HasMany {
        return $this->hasMany(Materi::class);
    }
    // ...existing code...
}
```

### Statistik Interaksi
```php
// app/Models/MaterialView.php
class MaterialView extends Model {
    // ...existing code...
    public static function recordView(int $materiId, ?string $date = null): void {
        // ...existing code...
    }
    // ...existing code...
}
```

---

## Hasil Pengujian

| Fitur         | Hasil Uji | Keterangan                |
|---------------|-----------|---------------------------|
| Login         | Berhasil  | User & admin bisa login   |
| Statistik     | Berhasil  | Data views tercatat       |
| Materi CRUD   | Berhasil  | Validasi & relasi berjalan|
| API Materi    | Berhasil  | Endpoint responsif        |

Pengujian dilakukan dengan `test_views_api.php` dan manual pada aplikasi.

---

## Refleksi Tantangan & Solusi
- **Kendala migrasi**: Pernah terjadi error pada migrasi tabel views karena field belum sesuai, diperbaiki dengan update migrasi dan rollback.
- **Validasi data**: Beberapa validasi input materi sempat gagal, diselesaikan dengan menambah rules pada FormRequest.
- **Feedback user**: Ada permintaan fitur statistik harian, diakomodasi dengan model `MaterialView`.
- **Dokumentasi**: Awalnya dokumentasi teknis kurang detail, lalu ditambah README dan panduan penggunaan untuk staf.

---

## Lampiran
- Screenshot aplikasi: lihat file `WhatsApp Image 2025-06-22 at 18.34.19.jpeg`
- Contoh pengujian: lihat `test_views_api.php`
- Struktur database: lihat folder `database/migrations/`

---

Untuk pertanyaan atau pengembangan lebih lanjut, silakan hubungi pengembang utama.
