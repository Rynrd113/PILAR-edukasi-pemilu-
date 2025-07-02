# 🔌 API Reference - PILAR Edukasi Pemilu

> Dokumentasi lengkap untuk semua endpoint API yang tersedia dalam sistem PILAR Edukasi Pemilu.

**Base URL**: `http://localhost:8001/api`  
**Authentication**: Bearer Token (Laravel Sanctum)  
**Content-Type**: `application/json`

---

## 🔐 Authentication

### Register
Mendaftarkan pengguna baru ke dalam sistem.

```http
POST /api/register
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response Success (201):**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe", 
            "email": "john@example.com",
            "is_admin": false,
            "created_at": "2025-07-02T10:00:00.000000Z"
        },
        "token": "1|abc123def456..."
    }
}
```

**Response Error (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password confirmation does not match."]
    }
}
```

### Login
Masuk ke dalam sistem dengan kredensial yang valid.

```http
POST /api/login
```

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com", 
            "is_admin": false
        },
        "token": "1|abc123def456..."
    }
}
```

**Response Error (401):**
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

### Logout
Keluar dari sistem dan menginvalidasi token.

```http
POST /api/logout
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

---

## 🏷️ Kategori

### Get All Kategori
Mengambil daftar semua kategori yang aktif.

```http
GET /api/kategori
```

**Response Success (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nama": "Pemilu Presiden",
            "deskripsi": "Materi edukasi tentang pemilihan presiden",
            "slug": "pemilu-presiden",
            "is_active": true,
            "created_at": "2025-07-02T10:00:00.000000Z",
            "materis_count": 5
        }
    ]
}
```

### Get Single Kategori
Mengambil detail kategori beserta materi-materinya.

```http
GET /api/kategori/{id}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nama": "Pemilu Presiden",
        "deskripsi": "Materi edukasi tentang pemilihan presiden",
        "slug": "pemilu-presiden",
        "is_active": true,
        "created_at": "2025-07-02T10:00:00.000000Z",
        "materis": [
            {
                "id": 1,
                "judul": "Cara Memilih Presiden",
                "slug": "cara-memilih-presiden",
                "views": 150
            }
        ]
    }
}
```

### Create Kategori (Admin Only)
Membuat kategori baru.

```http
POST /api/kategori
Authorization: Bearer {admin_token}
```

**Request Body:**
```json
{
    "nama": "Pemilu DPR",
    "deskripsi": "Materi tentang pemilihan DPR"
}
```

**Response Success (201):**
```json
{
    "success": true,
    "message": "Kategori created successfully",
    "data": {
        "id": 2,
        "nama": "Pemilu DPR",
        "deskripsi": "Materi tentang pemilihan DPR",
        "slug": "pemilu-dpr",
        "is_active": true,
        "created_at": "2025-07-02T10:00:00.000000Z"
    }
}
```

### Update Kategori (Admin Only)
Memperbarui kategori yang sudah ada.

```http
PUT /api/kategori/{id}
Authorization: Bearer {admin_token}
```

**Request Body:**
```json
{
    "nama": "Pemilu DPR Updated",
    "deskripsi": "Deskripsi yang sudah diperbarui",
    "is_active": true
}
```

### Delete Kategori (Admin Only)
Menghapus kategori (hanya jika tidak ada materi).

```http
DELETE /api/kategori/{id}
Authorization: Bearer {admin_token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Kategori deleted successfully"
}
```

---

## 📚 Materi

### Get All Materi
Mengambil daftar semua materi yang sudah dipublikasi.

```http
GET /api/materi
```

**Query Parameters:**
- `page` (integer): Nomor halaman (default: 1)
- `per_page` (integer): Jumlah item per halaman (default: 10, max: 50)

**Response Success (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "judul": "Cara Memilih Presiden",
            "slug": "cara-memilih-presiden",
            "konten": "Panduan lengkap tentang cara memilih presiden...",
            "gambar_path": "/storage/images/materi1.jpg",
            "views": 150,
            "is_published": true,
            "published_at": "2025-07-02T10:00:00.000000Z",
            "kategori": {
                "id": 1,
                "nama": "Pemilu Presiden",
                "slug": "pemilu-presiden"
            }
        }
    ],
    "meta": {
        "pagination": {
            "current_page": 1,
            "last_page": 5,
            "per_page": 10,
            "total": 45,
            "from": 1,
            "to": 10
        }
    }
}
```

### Get Single Materi
Mengambil detail materi (otomatis increment views).

```http
GET /api/materi/{slug}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "judul": "Cara Memilih Presiden",
        "slug": "cara-memilih-presiden",
        "konten": "Panduan lengkap tentang cara memilih presiden...",
        "gambar_path": "/storage/images/materi1.jpg",
        "views": 151, // Increment after request
        "is_published": true,
        "published_at": "2025-07-02T10:00:00.000000Z",
        "kategori": {
            "id": 1,
            "nama": "Pemilu Presiden",
            "slug": "pemilu-presiden"
        }
    }
}
```

### Get Materi by Kategori
Mengambil materi berdasarkan kategori tertentu.

```http
GET /api/kategori/{kategori_id}/materi
```

**Query Parameters:**
- `page` (integer): Nomor halaman
- `per_page` (integer): Jumlah item per halaman

**Response Success (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "judul": "Cara Memilih Presiden",
            "slug": "cara-memilih-presiden",
            "views": 150,
            "kategori": {
                "id": 1,
                "nama": "Pemilu Presiden"
            }
        }
    ],
    "meta": {
        "pagination": {...}
    }
}
```

### Create Materi (Admin Only)
Membuat materi baru.

```http
POST /api/materi
Authorization: Bearer {admin_token}
```

**Request Body:**
```json
{
    "kategori_id": 1,
    "judul": "Pemilihan Umum 2024",
    "konten": "Panduan lengkap tentang pemilu 2024...",
    "gambar_path": "/storage/images/pemilu2024.jpg",
    "is_published": true
}
```

**Response Success (201):**
```json
{
    "success": true,
    "message": "Materi created successfully",
    "data": {
        "id": 2,
        "judul": "Pemilihan Umum 2024",
        "slug": "pemilihan-umum-2024",
        "konten": "Panduan lengkap tentang pemilu 2024...",
        "gambar_path": "/storage/images/pemilu2024.jpg",
        "views": 0,
        "is_published": true,
        "published_at": "2025-07-02T10:00:00.000000Z",
        "kategori": {
            "id": 1,
            "nama": "Pemilu Presiden"
        }
    }
}
```

**Validation Rules:**
- `kategori_id`: required, must exist in kategoris table
- `judul`: required, string, max 255 characters
- `konten`: required, string
- `gambar_path`: optional, string, max 255 characters
- `is_published`: optional, boolean (default: false)

### Update Materi (Admin Only)
Memperbarui materi yang sudah ada.

```http
PUT /api/materi/{id}
Authorization: Bearer {admin_token}
```

**Request Body:**
```json
{
    "kategori_id": 1,
    "judul": "Pemilihan Umum 2024 - Updated",
    "konten": "Panduan yang sudah diperbarui...",
    "is_published": true
}
```

### Delete Materi (Admin Only)
Menghapus materi.

```http
DELETE /api/materi/{id}
Authorization: Bearer {admin_token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Materi deleted successfully"
}
```

---

## 📊 Analytics & Statistics

### Dashboard Stats
Mengambil statistik untuk dashboard admin.

```http
GET /api/admin/stats
Authorization: Bearer {admin_token}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "total_materis": 45,
        "total_views": 2150,
        "total_users": 128,
        "daily_views": [
            {"date": "2025-06-26", "views": 45},
            {"date": "2025-06-27", "views": 52},
            {"date": "2025-06-28", "views": 38},
            {"date": "2025-06-29", "views": 67},
            {"date": "2025-06-30", "views": 71},
            {"date": "2025-07-01", "views": 58},
            {"date": "2025-07-02", "views": 43}
        ],
        "popular_materis": [
            {
                "id": 5,
                "judul": "Hak dan Kewajiban Pemilih",
                "views": 245
            }
        ]
    }
}
```

---

## 🚨 Error Handling

### HTTP Status Codes
- **200**: OK - Request berhasil
- **201**: Created - Resource berhasil dibuat
- **400**: Bad Request - Request tidak valid
- **401**: Unauthorized - Token tidak valid/expired
- **403**: Forbidden - Tidak memiliki akses
- **404**: Not Found - Resource tidak ditemukan
- **422**: Validation Error - Data tidak valid
- **500**: Internal Server Error - Error server

### Error Response Format
```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field_name": [
            "Specific error message"
        ]
    }
}
```

### Common Error Messages
```json
// Unauthorized
{
    "success": false,
    "message": "Unauthenticated"
}

// Forbidden (Non-admin trying admin action)
{
    "success": false, 
    "message": "Unauthorized. Admin access required."
}

// Validation Error
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}

// Not Found
{
    "success": false,
    "message": "Resource not found"
}
```

---

## 🔧 Rate Limiting

### Limits
- **Authentication endpoints**: 5 requests per minute
- **General API endpoints**: 60 requests per minute
- **Admin endpoints**: 100 requests per minute

### Rate Limit Headers
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1625097600
```

### Rate Limit Exceeded Response
```json
{
    "success": false,
    "message": "Too Many Attempts"
}
```

---

## 📋 Testing Examples

### Using cURL

#### Login
```bash
curl -X POST http://localhost:8001/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'
```

#### Get Materi List
```bash
curl -X GET http://localhost:8001/api/materi \
  -H "Accept: application/json"
```

#### Create Materi (Admin)
```bash
curl -X POST http://localhost:8001/api/materi \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "kategori_id": 1,
    "judul": "Test Materi",
    "konten": "Konten test materi"
  }'
```

### Using JavaScript (Axios)

```javascript
// Setup axios with base configuration
const api = axios.create({
  baseURL: 'http://localhost:8001/api',
  headers: {
    'Content-Type': 'application/json'
  }
});

// Login
const login = async (email, password) => {
  try {
    const response = await api.post('/login', { email, password });
    const { token } = response.data.data;
    
    // Set token for future requests
    api.defaults.headers.Authorization = `Bearer ${token}`;
    
    return response.data;
  } catch (error) {
    console.error('Login failed:', error.response.data);
  }
};

// Get materials
const getMaterials = async () => {
  try {
    const response = await api.get('/materi');
    return response.data;
  } catch (error) {
    console.error('Failed to get materials:', error.response.data);
  }
};
```

---

## 📞 Support

Untuk pertanyaan mengenai API atau melaporkan bug:

- **GitHub Issues**: [Create new issue](https://github.com/your-repo/issues)
- **Email**: developer@kpu-nduga.go.id
- **Documentation**: Lihat `DEVELOPER_GUIDE.md` untuk detail implementasi

---

> 🚀 **API Version**: v1.0  
> 🔄 **Last Updated**: July 2, 2025  
> 📋 **Status**: Production Ready
