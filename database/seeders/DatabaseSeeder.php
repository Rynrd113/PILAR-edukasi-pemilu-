<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Materi;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        // Create sample regular users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);

        // Create sample categories
        $kategori1 = Kategori::create([
            'nama' => 'Pengetahuan Pemilu',
            'deskripsi' => 'Informasi mengenai sistem pemilu di Indonesia',
            'slug' => 'pengetahuan-pemilu',
            'is_active' => true,
        ]);

        $kategori2 = Kategori::create([
            'nama' => 'Tahapan Pemilu',
            'deskripsi' => 'Penjelasan mengenai tahapan pelaksanaan pemilu',
            'slug' => 'tahapan-pemilu',
            'is_active' => true,
        ]);

        $kategori3 = Kategori::create([
            'nama' => 'Lembaga Penyelenggara',
            'deskripsi' => 'Informasi tentang lembaga-lembaga penyelenggara pemilu',
            'slug' => 'lembaga-penyelenggara',
            'is_active' => true,
        ]);

        // Create sample materials
        Materi::create([
            'kategori_id' => $kategori1->id,
            'judul' => 'Pengertian Pemilu',
            'slug' => 'pengertian-pemilu',
            'konten' => "# Pengertian Pemilu\n\nPemilihan Umum (Pemilu) adalah sarana pelaksanaan kedaulatan rakyat dalam Negara Kesatuan Republik Indonesia yang berdasarkan Pancasila dan Undang-Undang Dasar 1945.\n\n## Tujuan Pemilu\n\n1. Memilih wakil rakyat dan wakil daerah\n2. Membentuk pemerintahan yang demokratis\n3. Melaksanakan kedaulatan rakyat",
            'is_published' => true,
            'published_at' => now(),
            'views' => 25,
        ]);

        Materi::create([
            'kategori_id' => $kategori2->id,
            'judul' => 'Pendaftaran Pemilih',
            'slug' => 'pendaftaran-pemilih',
            'konten' => "# Pendaftaran Pemilih\n\nPendaftaran pemilih adalah tahapan penting dalam pemilu untuk memastikan seluruh warga negara yang memenuhi syarat dapat menggunakan hak pilihnya.\n\n## Syarat Pemilih\n\n1. WNI yang berusia 17 tahun atau lebih\n2. Sudah/pernah kawin\n3. Tidak sedang dicabut hak pilihnya",
            'is_published' => true,
            'published_at' => now(),
            'views' => 18,
        ]);

        Materi::create([
            'kategori_id' => $kategori3->id,
            'judul' => 'Tugas dan Wewenang KPU',
            'slug' => 'tugas-dan-wewenang-kpu',
            'konten' => "# KPU - Komisi Pemilihan Umum\n\nKPU adalah lembaga penyelenggara pemilu yang bersifat nasional, tetap, dan mandiri.\n\n## Tugas dan Wewenang\n\n1. Merencanakan dan mempersiapkan pelaksanaan Pemilu\n2. Menetapkan peserta Pemilu\n3. Menetapkan daerah pemilihan\n4. Menetapkan hasil Pemilu",
            'is_published' => false,
            'views' => 0,
        ]);
    }
}
