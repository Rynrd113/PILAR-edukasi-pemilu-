# 🗳️ PILAR Edukasi Pemilu

> Website edukasi pemilu berbasis Laravel untuk KPU Nduga, fokus pada literasi pemilih pemula dan pengelolaan materi edukasi.

---

## 🚀 Instalasi & Setup

### 1. Clone Repository
```bash
git clone <repo-url>
cd "edukasi-api (0.1) (awal)"
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
```
> ⚠️ **Penting**: Sesuaikan konfigurasi database di file `.env`

### 4. Setup Database
```bash
php artisan key:generate
php artisan migrate --seed
```

### 5. Jalankan Aplikasi
```bash
php artisan serve
```

---

## ✨ Fitur Utama

- 📚 **Manajemen Materi Edukasi** - CRUD lengkap untuk konten edukasi
- 🏷️ **Kategori Materi** - Organisasi konten berdasarkan topik
- 📊 **Statistik Interaksi** - Tracking views dan engagement pengguna
- 👥 **Manajemen User & Admin** - Sistem role-based access
- 📈 **Dashboard Admin** - Panel kontrol administrasi
- 📱 **Responsive UI** - Interface yang mobile-friendly

---

## 💻 Kode Penting

### Model Materi
```php
// app/Models/Materi.php
class Materi extends Model {
    /**
     * Increment views counter untuk materi
     */
    public function incrementViews(): void {
        $this->timestamps = false;
        $this->increment('views');
        $this->timestamps = true;
        \App\Models\MaterialView::recordView($this->id);
    }
}
```

### Model Kategori
```php
// app/Models/Kategori.php
class Kategori extends Model {
    /**
     * Relasi ke model Materi
     */
    public function materis(): HasMany {
        return $this->hasMany(Materi::class);
    }
}
```

### Statistik Views
```php
// app/Models/MaterialView.php
class MaterialView extends Model {
    /**
     * Record view untuk statistik
     */
    public static function recordView(int $materiId, ?string $date = null): void {
        // Implementation code here
    }
}
```

---

## ✅ Hasil Pengujian

| Fitur | Status | Keterangan |
|-------|--------|------------|
| 🔐 Login | ✅ Berhasil | User & admin dapat login dengan lancar |
| 📊 Statistik | ✅ Berhasil | Data views tercatat dengan akurat |
| 📝 Materi CRUD | ✅ Berhasil | Validasi & relasi database berfungsi |
| 🔌 API Materi | ✅ Berhasil | Endpoint responsif dan stabil |

> 🧪 **Metode Pengujian**: Menggunakan `test_views_api.php` dan pengujian manual

---

## 🔧 Refleksi & Solusi

### 🚨 Tantangan yang Dihadapi

#### **Migrasi Database**
- **Masalah**: Error pada migrasi tabel views karena struktur field yang tidak sesuai
- **Solusi**: Update migrasi dan rollback untuk memperbaiki struktur

#### **Validasi Input**
- **Masalah**: Beberapa validasi input materi gagal
- **Solusi**: Menambahkan rules validation pada FormRequest

#### **Fitur Statistik**
- **Masalah**: Permintaan fitur statistik harian dari pengguna
- **Solusi**: Implementasi model `MaterialView` untuk tracking detail

#### **Dokumentasi**
- **Masalah**: Dokumentasi teknis yang kurang lengkap
- **Solusi**: Penambahan README dan panduan penggunaan untuk staf

---

## 📋 Lampiran

| 📁 Dokumen | 📝 Deskripsi | 📂 Lokasi |
|------------|--------------|-----------|
| 📸 **Screenshot** | Dokumentasi visual aplikasi | `WhatsApp Image 2025-06-22 at 18.34.19.jpeg` |
| 🧪 **Testing File** | Script pengujian API views | `test_views_api.php` |
| 🗃️ **Database Schema** | File migrasi database | `database/migrations/` |


---

## 📞 Kontak

Untuk pertanyaan atau pengembangan lebih lanjut, silakan hubungi **pengembang utama**.

> 🚀 **Made with ❤️ for KPU Nduga**
