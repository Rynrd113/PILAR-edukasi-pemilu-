# Views Real Database Integration - Dokumentasi

## 📊 Koneksi Grafik Views dengan Database Real

Grafik views di halaman admin telah berhasil dikoneksikan dengan data real dari database. Berikut adalah perubahan yang telah dilakukan:

### 🔧 Perubahan Sistem

#### 1. **Database Schema Baru**
- **Tabel**: `material_views`
- **Kolom**:
  - `id` (Primary Key)
  - `materi_id` (Foreign Key ke tabel materis)
  - `view_date` (Date - tanggal view)
  - `views_count` (Integer - jumlah views per hari)
  - `created_at`, `updated_at` (Timestamps)

#### 2. **Model Baru: MaterialView**
- **File**: `app/Models/MaterialView.php`
- **Fungsi Utama**:
  - `recordView()`: Mencatat view harian
  - `getDailyViews()`: Mengambil data views per hari
- **Relasi**: Belongs to Materi

#### 3. **Update Model Materi**
- **Method `incrementViews()`**: Diperbaharui untuk juga mencatat view harian
- **Relasi baru**: `materialViews()` untuk mengakses data views harian

#### 4. **Update DashboardController**
- Data views sekarang menggunakan `MaterialView::getDailyViews(7)`
- Tidak lagi menggunakan data random
- Cache tetap dipertahankan untuk performa

### 🚀 Fitur yang Tersedia

#### 1. **Tracking Views Real-time**
- Setiap kali user mengakses detail materi via API, views akan bertambah
- Data disimpan per hari untuk analisis trend

#### 2. **Dashboard Analytics**
- Grafik menampilkan data views 7 hari terakhir
- Data bersumber dari database real
- Update otomatis setiap ada view baru

#### 3. **Command Line Tools**
```bash
# Melihat agregasi views
php artisan views:aggregate

# Melihat views untuk rentang hari tertentu
php artisan views:aggregate --days=14
```

### 📈 Cara Kerja Sistem

1. **User mengakses materi** → API `/api/materi/{slug}`
2. **API memanggil** → `$materi->incrementViews()`
3. **Method incrementViews()** → Update total views + `MaterialView::recordView()`
4. **MaterialView::recordView()** → Simpan/update data views harian
5. **Dashboard** → Ambil data dari `MaterialView::getDailyViews()`
6. **JavaScript Chart** → Render grafik dengan data real

### 🔍 Testing & Verifikasi

#### API Testing:
```bash
# Lihat semua materi
curl http://localhost:8001/api/materi

# Akses detail materi (increment views)
curl http://localhost:8001/api/materi/pengertian-pemilu

# Lihat perubahan views
php artisan views:aggregate
```

#### Database Query:
```sql
-- Lihat total views per hari
SELECT view_date, SUM(views_count) as total_views 
FROM material_views 
GROUP BY view_date 
ORDER BY view_date DESC;
```

### 📊 Contoh Data Output

```
+--------+-------------+
| Date   | Total Views |
+--------+-------------+
| 10 Jun | 40          |
| 11 Jun | 65          |
| 12 Jun | 33          |
| 13 Jun | 40          |
| 14 Jun | 52          |
| 15 Jun | 41          |
| 16 Jun | 61          |
+--------+-------------+
Total views in the last 7 days: 332
```

### ✅ Status Koneksi

| Komponen | Status | Keterangan |
|----------|--------|------------|
| **Grafik Views Harian** | ✅ Connected | Data real dari MaterialView |
| **Total Views** | ✅ Connected | Sum dari Materi.views |
| **Materi Terbaru** | ✅ Connected | Dengan views real |
| **Materi Populer** | ✅ Connected | Berdasarkan views real |
| **API Increment** | ✅ Working | Auto increment saat akses |
| **Cache System** | ✅ Working | 5 menit cache untuk performa |

### 🔄 Maintenance

#### Seeder untuk Test Data:
```bash
php artisan db:seed --class=MaterialViewSeeder
```

#### Clear Cache:
```bash
php artisan cache:clear
```

#### Migration:
```bash
php artisan migrate
```

---

**✨ Hasil Akhir**: Grafik views di dashboard admin sekarang menampilkan data real yang tersinkron dengan aktivitas user dan tersimpan dalam database dengan proper tracking per hari.


