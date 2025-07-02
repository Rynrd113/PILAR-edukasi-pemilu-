# 👨‍💻 Panduan Developer - PILAR Edukasi Pemilu

> Dokumentasi teknis lengkap untuk developer yang bekerja dengan sistem PILAR Edukasi Pemilu KPU Nduga.

---

## 🏗️ Struktur Project

### Directory Structure
```
edukasi-api/
├── app/
│   ├── Console/Commands/        # Artisan commands custom
│   ├── Http/
│   │   ├── Controllers/         # API & Web controllers
│   │   │   └── Api/            # API controllers
│   │   ├── Middleware/         # Custom middleware
│   │   ├── Requests/           # Form request validation
│   │   └── Resources/          # API resources/transformers
│   ├── Models/                 # Eloquent models
│   └── Providers/              # Service providers
├── bootstrap/                  # Application bootstrap
├── config/                     # Configuration files
├── database/
│   ├── factories/              # Model factories
│   ├── migrations/             # Database migrations  
│   └── seeders/               # Database seeders
├── public/                     # Web accessible files
├── resources/
│   ├── css/                    # Sass/CSS files
│   ├── js/                     # JavaScript files
│   └── views/                  # Blade templates
├── routes/                     # Route definitions
├── storage/                    # File storage & logs
└── tests/                      # PHPUnit tests
```

---

## 🔧 Development Setup

### Prerequisites
```bash
# Required tools
- PHP 8.2+
- Composer 2.x
- Node.js 18+ (untuk asset compilation)
- MySQL/PostgreSQL
- Git
```

### Local Development
```bash
# 1. Clone & setup
git clone <repository-url>
cd "edukasi-api (0.1) (awal)"
composer install

# 2. Environment configuration
cp .env.example .env
php artisan key:generate

# 3. Database setup
php artisan migrate --seed

# 4. Development server
php artisan serve --port=8001
```

### Asset Development
```bash
# Install frontend dependencies (jika menggunakan npm)
npm install

# Compile assets for development
npm run dev

# Watch for changes
npm run watch

# Production build
npm run build
```

---

## 🗄️ Database Design

### Entity Relationship Diagram
```sql
-- Core Tables
users
├── id (PK)
├── name
├── email (unique)
├── password
├── is_admin (boolean)
└── timestamps

kategoris
├── id (PK)  
├── nama
├── deskripsi
├── slug (unique)
├── is_active (boolean)
└── timestamps

materis
├── id (PK)
├── kategori_id (FK → kategoris.id)
├── judul
├── slug (unique)
├── konten (text)
├── gambar_path
├── is_published (boolean)
├── published_at
├── views (integer)
└── timestamps

material_views
├── id (PK)
├── materi_id (FK → materis.id)
├── view_date (date)
├── views_count (integer)
└── timestamps
```

### Indexes & Performance
```sql
-- Recommended indexes untuk performance
CREATE INDEX idx_materis_kategori_id ON materis(kategori_id);
CREATE INDEX idx_materis_slug ON materis(slug);
CREATE INDEX idx_materis_published ON materis(is_published, published_at);
CREATE INDEX idx_material_views_date ON material_views(view_date);
CREATE INDEX idx_material_views_materi_date ON material_views(materi_id, view_date);
```

---

## 🚀 API Development Guide

### Response Format Standard
```php
// Success Response
{
    "success": true,
    "message": "Success message",
    "data": {
        // Actual data
    },
    "meta": {
        "pagination": {
            "current_page": 1,
            "total_pages": 5,
            "per_page": 10,
            "total": 50
        }
    }
}

// Error Response  
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field_name": ["Validation error messages"]
    }
}
```

### Controller Best Practices
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMateriRequest;
use App\Http\Resources\MateriResource;
use App\Models\Materi;

class MateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materis = Materi::with('kategori')
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(10);
            
        return MateriResource::collection($materis);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Materi $materi)
    {
        // Increment views counter
        $materi->incrementViews();
        
        $materi->load('kategori');
        
        return new MateriResource($materi);
    }
}
```

### Request Validation
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMateriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }
    
    public function rules(): array
    {
        return [
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar_path' => 'nullable|string|max:255',
            'is_published' => 'boolean'
        ];
    }
    
    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori harus dipilih',
            'kategori_id.exists' => 'Kategori tidak valid',
            'judul.required' => 'Judul materi harus diisi',
            'konten.required' => 'Konten materi harus diisi'
        ];
    }
}
```

---

## 🛠️ Custom Commands

### Available Artisan Commands
```bash
# Views aggregation
php artisan views:aggregate

# Generate test data
php artisan db:seed --class=MaterialViewSeeder

# Clear all caches
php artisan optimize:clear

# Generate API documentation
php artisan l5-swagger:generate
```

### Creating Custom Commands
```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MaterialView;

class ViewsAggregateCommand extends Command
{
    protected $signature = 'views:aggregate {--days=7}';
    protected $description = 'Aggregate material views for specified days';
    
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Aggregating views for last {$days} days...");
        
        $views = MaterialView::getDailyViews($days);
        
        $this->table(
            ['Tanggal', 'Total Views'],
            $views->groupBy('view_date')->map(function ($dayViews, $date) {
                return [$date, $dayViews->sum('views_count')];
            })
        );
        
        $this->info('Aggregation completed!');
    }
}
```

---

## 🧪 Testing Guidelines

### Test Structure
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Materi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MateriApiTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_can_get_materi_list()
    {
        // Arrange
        Materi::factory()->count(3)->create();
        
        // Act
        $response = $this->get('/api/materi');
        
        // Assert
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'judul', 'slug', 'views']
                    ]
                ]);
    }
    
    public function test_viewing_materi_increments_views()
    {
        // Arrange
        $materi = Materi::factory()->create(['views' => 5]);
        
        // Act
        $this->get("/api/materi/{$materi->slug}");
        
        // Assert
        $this->assertEquals(6, $materi->fresh()->views);
    }
}
```

### Running Tests
```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/MateriApiTest.php

# Run specific test method
php artisan test --filter test_can_get_materi_list
```

---

## 🔒 Security Implementation

### Authentication Flow
```php
// Login process
POST /api/login
{
    "email": "user@example.com", 
    "password": "password"
}

// Response
{
    "success": true,
    "data": {
        "user": {...},
        "token": "sanctum_token_here"
    }
}

// Subsequent requests
Authorization: Bearer sanctum_token_here
```

### Middleware Implementation
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }
        
        return $next($request);
    }
}
```

---

## 📈 Performance Optimization

### Database Optimization
```php
// Eager loading untuk menghindari N+1 queries
$materis = Materi::with(['kategori', 'materialViews'])
    ->where('is_published', true)
    ->get();

// Query optimization dengan indexes
$popularMateris = Materi::select('id', 'judul', 'views')
    ->where('views', '>', 100)
    ->orderBy('views', 'desc')
    ->limit(10)
    ->get();
```

### Caching Strategy
```php
// Cache frequently accessed data
$categories = Cache::remember('active_categories', 3600, function () {
    return Kategori::where('is_active', true)->get();
});

// Cache API responses
$materis = Cache::remember("materis_page_{$page}", 1800, function () use ($page) {
    return Materi::with('kategori')
        ->published()
        ->paginate(10, ['*'], 'page', $page);
});
```

---

## 🐛 Debugging & Troubleshooting

### Common Issues

#### Database Connection
```bash
# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Test specific query
>>> App\Models\User::count();
```

#### Permission Issues
```bash
# Fix storage permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### Cache Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Or clear everything at once
php artisan optimize:clear
```

### Debug Tools
```php
// Enable query logging
DB::enableQueryLog();
// ... run your code
dd(DB::getQueryLog());

// Debug specific model
$materi = Materi::find(1);
dump($materi->toArray());

// Check loaded relations
dump($materi->getRelations());
```

---

## 📚 Code Style & Standards

### PHP Code Style (PSR-12)
```php
<?php

declare(strict_types=1);

namespace App\Services;

class MateriService
{
    private const MAX_VIEWS_PER_DAY = 10000;
    
    public function __construct(
        private readonly MaterialRepository $repository
    ) {
    }
    
    public function incrementViews(int $materiId): bool
    {
        $materi = $this->repository->find($materiId);
        
        if (!$materi) {
            throw new ModelNotFoundException('Materi not found');
        }
        
        return $materi->incrementViews();
    }
}
```

### Naming Conventions
- **Classes**: PascalCase (`MateriController`)
- **Methods**: camelCase (`incrementViews`)
- **Variables**: camelCase (`$materiData`)
- **Constants**: SCREAMING_SNAKE_CASE (`MAX_VIEWS_PER_DAY`)
- **Database tables**: snake_case (`material_views`)
- **Routes**: kebab-case (`/api/materi-kategori`)

---

## 🔄 Git Workflow

### Branch Strategy
```bash
# Main branches
main          # Production-ready code
develop       # Integration branch

# Feature branches  
feature/user-authentication
feature/views-analytics
feature/admin-dashboard

# Release branches
release/v1.0.0

# Hotfix branches
hotfix/critical-bug-fix
```

### Commit Messages
```bash
# Format: type(scope): description

feat(api): add materi views increment endpoint
fix(auth): resolve login token expiration issue  
docs(readme): update API documentation
test(materi): add unit tests for views functionality
refactor(models): optimize database queries
```

---

## 📞 Support & Contact

### Developer Team
- **Lead Developer**: [Nama Developer]
- **Backend Developer**: [Nama Developer]
- **Frontend Developer**: [Nama Developer]

### Resources
- **Documentation**: `/docs`
- **API Postman Collection**: `/postman/edukasi-api.json`
- **Issue Tracker**: GitHub Issues
- **Code Review**: Pull Request process

---

> 🚀 **Happy Coding!** - Ikuti best practices dan jangan ragu untuk berkontribusi pada pengembangan sistem ini.
