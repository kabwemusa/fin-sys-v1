# Loan Management System — Implementation Guide

> **Purpose**: This document is the single source of truth for building the Loan Management System. It is written for Claude Code (or any AI coding agent) to follow step-by-step. Each phase has explicit commands, file paths, code structure, and acceptance criteria.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 11 |
| PHP | 8.3+ |
| Database | PostgreSQL 16 |
| Reactive UI | Livewire 3 |
| Component Library | MaryUI (v2) |
| CSS | Tailwind CSS + daisyUI |
| Icons | Blade Heroicons |
| Roles/Permissions | spatie/laravel-permission |
| Activity Log | spatie/laravel-activitylog |
| PDF Generation | barryvdh/laravel-dompdf |
| Excel Export | maatwebsite/excel |

---

## Project Conventions

- **Currency**: ZMW (Zambian Kwacha). All monetary fields use `decimal(12,2)`.
- **Phone format**: Zambian numbers, e.g. `+260 97XXXXXXX`.
- **NRC format**: `XXXXXX/XX/X` (National Registration Card).
- **Loan reference format**: `LN-{YEAR}-{5-digit-padded}` e.g. `LN-2026-00001`.
- **Naming**: Models in PascalCase, tables in snake_case plural, Livewire components in PascalCase.
- **Auth**: Customers and Admins share the `users` table with a `role` enum column.
- **No customer registration page**. Accounts are auto-created on loan application submission.
- **All admin routes** are prefixed with `/admin` and protected by `auth` + `role:admin` middleware.
- **All customer portal routes** are prefixed with `/portal` and protected by `auth` + `role:customer` middleware.

---

## PHASE 1: Project Setup & Foundation

### 1.1 Create Laravel Project

```bash
composer create-project laravel/laravel loan-system
cd loan-system
```

### 1.2 Configure PostgreSQL

Update `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=loan_system
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

Create the database:

```bash
psql -U postgres -c "CREATE DATABASE loan_system;"
```

### 1.3 Install Dependencies

```bash
# Livewire
composer require livewire/livewire

# MaryUI
composer require robsontenorio/mary

# Spatie packages
composer require spatie/laravel-permission
composer require spatie/laravel-activitylog

# PDF and Excel
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel

# Publish configs
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"

# MaryUI setup
php artisan mary:install

# Heroicons
composer require blade-ui-kit/blade-heroicons
```

### 1.4 Install Frontend Dependencies

```bash
npm install -D tailwindcss daisyui@latest postcss autoprefixer
npm install
```

Update `tailwind.config.js`:

```js
import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/View/Components/**/*.php',
        './app/Livewire/**/*.php',
        './vendor/robsontenorio/mary/src/View/Components/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [require('daisyui')],
    daisyui: {
        themes: [
            {
                loansystem: {
                    "primary": "#1B4F72",
                    "secondary": "#2E86C1",
                    "accent": "#F39C12",
                    "neutral": "#1C2833",
                    "base-100": "#FFFFFF",
                    "info": "#3498DB",
                    "success": "#27AE60",
                    "warning": "#F39C12",
                    "error": "#E74C3C",
                },
            },
        ],
    },
};
```

### 1.5 Create Livewire Layout

Create `resources/views/components/layouts/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en" data-theme="loansystem">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Loan System' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200 font-sans antialiased">
    {{-- Toast notifications --}}
    <x-mary-toast />

    {{ $slot }}
</body>
</html>
```

### 1.6 Acceptance Criteria — Phase 1

- [ ] `php artisan serve` runs without errors
- [ ] `npm run dev` compiles without errors
- [ ] Database connection works: `php artisan migrate` runs the default Laravel migrations
- [ ] MaryUI components render (test with a simple blade page using `<x-button label="Test" />`)

---

## PHASE 2: Database Schema

### 2.1 Migrations

Create migrations in this exact order. Run `php artisan make:migration` for each.

#### Migration 1: Modify users table

File: `database/migrations/xxxx_modify_users_table.php`

Add these columns to the existing `users` table:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('phone', 20)->unique()->nullable()->after('email');
    $table->enum('role', ['customer', 'admin'])->default('customer')->after('password');
    $table->boolean('must_change_password')->default(false)->after('role');
    $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
});
```

#### Migration 2: customers

```php
Schema::create('customers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('nrc_number', 20);
    $table->date('date_of_birth');
    $table->enum('gender', ['male', 'female']);
    $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed']);
    $table->text('residential_address');
    $table->string('city', 100);
    $table->string('province', 100);
    $table->string('employer_name', 255)->nullable();
    $table->text('employer_address')->nullable();
    $table->string('job_title', 255)->nullable();
    $table->date('employment_date')->nullable();
    $table->decimal('monthly_income', 12, 2)->nullable();
    $table->string('bank_name', 255)->nullable();
    $table->string('bank_account_number', 50)->nullable();
    $table->string('bank_branch', 255)->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

#### Migration 3: loan_products

```php
Schema::create('loan_products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->enum('type', ['salary_backed', 'collateral_backed']);
    $table->text('description');
    $table->decimal('min_amount', 12, 2);
    $table->decimal('max_amount', 12, 2);
    $table->integer('min_tenure_months');
    $table->integer('max_tenure_months');
    $table->decimal('interest_rate', 5, 2)->comment('Monthly percentage rate');
    $table->boolean('requires_collateral')->default(false);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

#### Migration 4: loan_applications

```php
Schema::create('loan_applications', function (Blueprint $table) {
    $table->id();
    $table->string('reference', 20)->unique();
    $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
    $table->foreignId('loan_product_id')->constrained();
    $table->decimal('amount_requested', 12, 2);
    $table->integer('tenure_months');
    $table->text('purpose')->nullable();
    $table->enum('status', [
        'pending',
        'under_review',
        'info_requested',
        'approved',
        'rejected',
        'disbursed',
        'closed',
        'defaulted',
    ])->default('pending');
    $table->decimal('interest_rate', 5, 2)->nullable();
    $table->decimal('amount_approved', 12, 2)->nullable();
    $table->decimal('monthly_repayment', 12, 2)->nullable();
    $table->text('admin_notes')->nullable();
    $table->text('rejection_reason')->nullable();
    $table->text('info_requested_note')->nullable();
    $table->foreignId('reviewed_by')->nullable()->constrained('users');
    $table->timestamp('reviewed_at')->nullable();
    $table->timestamp('disbursed_at')->nullable();
    $table->date('due_date')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

#### Migration 5: documents

```php
Schema::create('documents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('loan_application_id')->constrained()->cascadeOnDelete();
    $table->enum('type', [
        'nrc',
        'payslip',
        'bank_statement',
        'employment_letter',
        'collateral_proof',
        'selfie',
        'other',
    ]);
    $table->string('original_filename');
    $table->string('file_path', 500);
    $table->integer('file_size');
    $table->boolean('is_verified')->default(false);
    $table->foreignId('verified_by')->nullable()->constrained('users');
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

#### Migration 6: collaterals

```php
Schema::create('collaterals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('loan_application_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['vehicle', 'property', 'equipment', 'other']);
    $table->text('description');
    $table->decimal('estimated_value', 12, 2);
    $table->string('registration_number', 100)->nullable();
    $table->boolean('is_verified')->default(false);
    $table->timestamps();
});
```

#### Migration 7: repayments

```php
Schema::create('repayments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('loan_application_id')->constrained()->cascadeOnDelete();
    $table->decimal('amount', 12, 2);
    $table->date('payment_date');
    $table->enum('payment_method', ['bank_transfer', 'mobile_money', 'cash', 'other']);
    $table->string('reference_number', 100)->nullable();
    $table->foreignId('recorded_by')->constrained('users');
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

### 2.2 Run Migrations

```bash
php artisan migrate
```

### 2.3 Acceptance Criteria — Phase 2

- [ ] All migrations run without error
- [ ] `php artisan migrate:rollback` works cleanly
- [ ] Tables exist in PostgreSQL with correct columns and foreign keys

---

## PHASE 3: Models & Relationships

### 3.1 Model Definitions

Create or update each model with relationships, fillable fields, and casts.

#### `app/Models/User.php`

Update the existing User model:

```php
// Add to existing User model

protected $fillable = [
    'name', 'email', 'phone', 'password', 'role', 'must_change_password',
];

protected $casts = [
    'email_verified_at' => 'datetime',
    'phone_verified_at' => 'datetime',
    'must_change_password' => 'boolean',
];

public function customer()
{
    return $this->hasOne(Customer::class);
}

public function isAdmin(): bool
{
    return $this->role === 'admin';
}

public function isCustomer(): bool
{
    return $this->role === 'customer';
}
```

#### `app/Models/Customer.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'nrc_number', 'date_of_birth', 'gender', 'marital_status',
        'residential_address', 'city', 'province', 'employer_name',
        'employer_address', 'job_title', 'employment_date', 'monthly_income',
        'bank_name', 'bank_account_number', 'bank_branch',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'employment_date' => 'date',
        'monthly_income' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class);
    }
}
```

#### `app/Models/LoanProduct.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    protected $fillable = [
        'name', 'slug', 'type', 'description', 'min_amount', 'max_amount',
        'min_tenure_months', 'max_tenure_months', 'interest_rate',
        'requires_collateral', 'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'requires_collateral' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
```

#### `app/Models/LoanApplication.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference', 'customer_id', 'loan_product_id', 'amount_requested',
        'tenure_months', 'purpose', 'status', 'interest_rate',
        'amount_approved', 'monthly_repayment', 'admin_notes',
        'rejection_reason', 'info_requested_note', 'reviewed_by',
        'reviewed_at', 'disbursed_at', 'due_date',
    ];

    protected $casts = [
        'amount_requested' => 'decimal:2',
        'amount_approved' => 'decimal:2',
        'monthly_repayment' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'disbursed_at' => 'datetime',
        'due_date' => 'date',
    ];

    // Auto-generate reference on creation
    protected static function booted()
    {
        static::creating(function ($application) {
            if (empty($application->reference)) {
                $year = now()->year;
                $lastApp = static::withTrashed()
                    ->whereYear('created_at', $year)
                    ->orderByDesc('id')
                    ->first();

                $nextNumber = $lastApp
                    ? (int) substr($lastApp->reference, -5) + 1
                    : 1;

                $application->reference = sprintf('LN-%d-%05d', $year, $nextNumber);
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function loanProduct()
    {
        return $this->belongsTo(LoanProduct::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function collaterals()
    {
        return $this->hasMany(Collateral::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function totalRepaid(): float
    {
        return $this->repayments()->sum('amount');
    }

    public function outstandingBalance(): float
    {
        if (!$this->amount_approved) return 0;

        $totalDue = $this->monthly_repayment * $this->tenure_months;
        return max(0, $totalDue - $this->totalRepaid());
    }

    // Status helpers
    public function isPending(): bool { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isDisbursed(): bool { return $this->status === 'disbursed'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}
```

#### `app/Models/Document.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'loan_application_id', 'type', 'original_filename',
        'file_path', 'file_size', 'is_verified', 'verified_by', 'notes',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
```

#### `app/Models/Collateral.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collateral extends Model
{
    protected $fillable = [
        'loan_application_id', 'type', 'description',
        'estimated_value', 'registration_number', 'is_verified',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'is_verified' => 'boolean',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
```

#### `app/Models/Repayment.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    protected $fillable = [
        'loan_application_id', 'amount', 'payment_date',
        'payment_method', 'reference_number', 'recorded_by', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function recordedByUser()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
```

### 3.2 Acceptance Criteria — Phase 3

- [ ] All models created with correct namespaces
- [ ] `php artisan tinker` — can create and query records for each model
- [ ] Relationships work: `LoanApplication::first()->customer->user->name` returns a value
- [ ] Loan reference auto-generation works: creating a LoanApplication generates `LN-2026-00001`

---

## PHASE 4: Seeders & Auth

### 4.1 Database Seeder

Create `database/seeders/DatabaseSeeder.php`:

```php
// Seed default admin
User::create([
    'name' => 'System Admin',
    'email' => 'admin@loansystem.com',
    'phone' => '+260970000000',
    'password' => Hash::make('admin123456'),
    'role' => 'admin',
    'email_verified_at' => now(),
]);

// Seed loan products
LoanProduct::create([
    'name' => 'Salary-Backed Loan',
    'slug' => 'salary-backed-loan',
    'type' => 'salary_backed',
    'description' => 'Affordable loans secured against your verified employment and salary. Ideal for civil servants and formal sector employees.',
    'min_amount' => 500.00,
    'max_amount' => 250000.00,
    'min_tenure_months' => 3,
    'max_tenure_months' => 60,
    'interest_rate' => 4.00,
    'requires_collateral' => false,
    'is_active' => true,
]);

LoanProduct::create([
    'name' => 'Collateral-Backed Loan',
    'slug' => 'collateral-backed-loan',
    'type' => 'collateral_backed',
    'description' => 'Larger loan amounts secured against your assets such as vehicles, property, or equipment.',
    'min_amount' => 5000.00,
    'max_amount' => 500000.00,
    'min_tenure_months' => 6,
    'max_tenure_months' => 60,
    'interest_rate' => 3.50,
    'requires_collateral' => true,
    'is_active' => true,
]);
```

### 4.2 Middleware

Create `app/Http/Middleware/EnsureUserRole.php`:

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'Unauthorized.');
        }
        return $next($request);
    }
}
```

Register in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureUserRole::class,
    ]);
})
```

Create `app/Http/Middleware/ForcePasswordChange.php`:

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->must_change_password) {
            if (!$request->routeIs('password.change', 'password.change.update', 'logout')) {
                return redirect()->route('password.change');
            }
        }
        return $next($request);
    }
}
```

Register as global middleware for `auth` routes.

### 4.3 Routes Structure

File: `routes/web.php`

```php
use Illuminate\Support\Facades\Route;

// ============ PUBLIC ROUTES (no auth) ============
Route::get('/', App\Livewire\Public\Home::class)->name('home');
Route::get('/loans/{slug}', App\Livewire\Public\LoanProductDetail::class)->name('loan.detail');
Route::get('/apply/{slug}', App\Livewire\Public\ApplicationForm::class)->name('loan.apply');
Route::get('/apply/confirmation/{reference}', App\Livewire\Public\Confirmation::class)->name('loan.confirmation');

// ============ AUTH ROUTES ============
// Use Laravel's built-in auth routes (login, logout)
// Override login to support email OR phone

// ============ CUSTOMER PORTAL ============
Route::middleware(['auth', 'role:customer', 'force.password.change'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/loans', App\Livewire\Customer\MyLoans::class)->name('loans');
    Route::get('/loans/{reference}', App\Livewire\Customer\LoanDetail::class)->name('loan.detail');
    Route::get('/profile', App\Livewire\Customer\Profile::class)->name('profile');
});

// ============ ADMIN PANEL ============
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/applications', App\Livewire\Admin\ApplicationsList::class)->name('applications');
    Route::get('/applications/{id}', App\Livewire\Admin\ApplicationReview::class)->name('application.review');
    Route::get('/customers', App\Livewire\Admin\CustomersList::class)->name('customers');
    Route::get('/customers/{id}', App\Livewire\Admin\CustomerDetail::class)->name('customer.detail');
    Route::get('/products', App\Livewire\Admin\ProductsManager::class)->name('products');
    Route::get('/repayments', App\Livewire\Admin\RepaymentsManager::class)->name('repayments');
    Route::get('/reports', App\Livewire\Admin\Reports::class)->name('reports');
    Route::get('/audit-log', App\Livewire\Admin\AuditLog::class)->name('audit-log');
});

// ============ PASSWORD CHANGE ============
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', App\Livewire\Auth\ChangePassword::class)->name('password.change');
});
```

### 4.4 Acceptance Criteria — Phase 4

- [ ] `php artisan db:seed` creates admin user and 2 loan products
- [ ] Admin can log in at `/login` with `admin@loansystem.com` / `admin123456`
- [ ] Visiting `/admin` while logged in as admin works
- [ ] Visiting `/admin` while not logged in redirects to `/login`
- [ ] Visiting `/admin` as a customer returns 403

---

## PHASE 5: Services

### 5.1 AccountGeneratorService

File: `app/Services/AccountGeneratorService.php`

Responsibility: Create a User + Customer record from application form data.

```php
namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AccountGeneratorService
{
    /**
     * Create user account and customer profile from application data.
     *
     * @param array $personalData Keys: name, email, phone, nrc_number, date_of_birth, gender, marital_status, residential_address, city, province
     * @param array $employmentData Keys: employer_name, employer_address, job_title, employment_date, monthly_income
     * @param array $bankingData Keys: bank_name, bank_account_number, bank_branch
     * @return array{user: User, customer: Customer, plain_password: string}
     */
    public function createFromApplication(array $personalData, array $employmentData, array $bankingData): array
    {
        // Check if user already exists by email or phone
        $existingUser = User::where('email', $personalData['email'])
            ->orWhere('phone', $personalData['phone'])
            ->first();

        if ($existingUser && $existingUser->customer) {
            // Return existing — no new password generated
            return [
                'user' => $existingUser,
                'customer' => $existingUser->customer,
                'plain_password' => null, // Don't regenerate
            ];
        }

        $plainPassword = Str::random(10);

        $user = User::create([
            'name' => $personalData['name'],
            'email' => $personalData['email'],
            'phone' => $personalData['phone'],
            'password' => Hash::make($plainPassword),
            'role' => 'customer',
            'must_change_password' => true,
        ]);

        $customer = Customer::create([
            'user_id' => $user->id,
            'nrc_number' => $personalData['nrc_number'],
            'date_of_birth' => $personalData['date_of_birth'],
            'gender' => $personalData['gender'],
            'marital_status' => $personalData['marital_status'],
            'residential_address' => $personalData['residential_address'],
            'city' => $personalData['city'],
            'province' => $personalData['province'],
            'employer_name' => $employmentData['employer_name'] ?? null,
            'employer_address' => $employmentData['employer_address'] ?? null,
            'job_title' => $employmentData['job_title'] ?? null,
            'employment_date' => $employmentData['employment_date'] ?? null,
            'monthly_income' => $employmentData['monthly_income'] ?? null,
            'bank_name' => $bankingData['bank_name'] ?? null,
            'bank_account_number' => $bankingData['bank_account_number'] ?? null,
            'bank_branch' => $bankingData['bank_branch'] ?? null,
        ]);

        return [
            'user' => $user,
            'customer' => $customer,
            'plain_password' => $plainPassword,
        ];
    }
}
```

### 5.2 LoanCalculatorService

File: `app/Services/LoanCalculatorService.php`

```php
namespace App\Services;

class LoanCalculatorService
{
    /**
     * Calculate monthly repayment using flat interest rate.
     *
     * @param float $principal Loan amount in ZMW
     * @param float $monthlyRate Monthly interest rate as percentage (e.g. 4.0 = 4%)
     * @param int $tenureMonths Number of months
     * @return array{monthly_repayment: float, total_interest: float, total_repayment: float}
     */
    public function calculate(float $principal, float $monthlyRate, int $tenureMonths): array
    {
        $totalInterest = $principal * ($monthlyRate / 100) * $tenureMonths;
        $totalRepayment = $principal + $totalInterest;
        $monthlyRepayment = $totalRepayment / $tenureMonths;

        return [
            'monthly_repayment' => round($monthlyRepayment, 2),
            'total_interest' => round($totalInterest, 2),
            'total_repayment' => round($totalRepayment, 2),
        ];
    }
}
```

### 5.3 SmsService (Stub)

File: `app/Services/SmsService.php`

```php
namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send SMS. Currently logs only — replace with actual SMS gateway integration.
     */
    public function send(string $phone, string $message): bool
    {
        // TODO: Integrate with Zambian SMS gateway (e.g. Africa's Talking, Zamtel API)
        Log::info("SMS to {$phone}: {$message}");
        return true;
    }
}
```

Register services in `app/Providers/AppServiceProvider.php`:

```php
public function register()
{
    $this->app->singleton(AccountGeneratorService::class);
    $this->app->singleton(LoanCalculatorService::class);
    $this->app->singleton(SmsService::class);
}
```

### 5.4 Acceptance Criteria — Phase 5

- [ ] `AccountGeneratorService` can create a user + customer in tinker
- [ ] `LoanCalculatorService::calculate(10000, 4.0, 6)` returns `{ monthly_repayment: 2066.67, total_interest: 2400.00, total_repayment: 12400.00 }`
- [ ] Calling `createFromApplication` with the same email twice returns the existing user (no duplicate)

---

## PHASE 6: Notifications

### 6.1 Notification Classes

Create these notifications using `php artisan make:notification`:

#### `app/Notifications/ApplicationSubmitted.php`

- **Via**: `['mail', 'database']`
- **Mail content**: "Your loan application {reference} has been received. Your login credentials are: Email: {email}, Password: {password}. Log in at {portal_url} to track your application."
- **Also triggers**: `SmsService::send()` with a shorter message containing credentials and reference number.

#### `app/Notifications/LoanStatusChanged.php`

- **Via**: `['mail', 'database']`
- **Accepts**: `$application` (LoanApplication model), `$oldStatus`, `$newStatus`
- **Mail content varies by new status**:
  - `under_review`: "Your application {reference} is now being reviewed."
  - `info_requested`: "Additional information is required for {reference}: {info_requested_note}. Please log in to upload documents."
  - `approved`: "Congratulations! {reference} has been approved for ZMW {amount_approved}. Monthly repayment: ZMW {monthly_repayment}."
  - `rejected`: "We regret that {reference} was not approved. Reason: {rejection_reason}."
  - `disbursed`: "ZMW {amount_approved} has been disbursed to your account. First repayment due: {first_due_date}."
- **Also triggers**: `SmsService::send()` with a condensed version.

#### `app/Notifications/NewApplicationForAdmin.php`

- **Via**: `['mail', 'database']`
- **Sent to**: All admin users
- **Content**: "New loan application {reference} received. Amount: ZMW {amount_requested}. Type: {loan_product_name}."

### 6.2 Acceptance Criteria — Phase 6

- [ ] Submitting an application (in tinker or test) sends email + logs SMS
- [ ] Changing loan status triggers the appropriate notification
- [ ] Admin receives notification for new applications

---

## PHASE 7: Public Pages (Livewire Components)

### 7.1 Component List

Create with `php artisan livewire:make`:

```
php artisan livewire:make Public/Home
php artisan livewire:make Public/LoanProductDetail
php artisan livewire:make Public/ApplicationForm
php artisan livewire:make Public/Confirmation
php artisan livewire:make Public/LoanCalculator
```

### 7.2 Home Page (`Public/Home`)

**Layout**: Full-width landing page with MaryUI components.

**Sections**:
1. **Hero**: Headline "Get the loan you need — fast and easy", subtext about no registration needed, two CTA buttons linking to each loan product.
2. **Loan Products Grid**: Two `<x-card>` components, one for each active loan product. Each shows name, brief description, rate, amount range, and an "Apply Now" button.
3. **How It Works**: 3-step visual (Apply → Get Approved → Receive Funds).
4. **Loan Calculator**: Embedded `<livewire:public.loan-calculator />` component.

### 7.3 Loan Calculator (`Public/LoanCalculator`)

**Livewire properties**:
- `$amount` (default: 10000)
- `$tenure` (default: 12)
- `$productId` (default: first active product)

**Behavior**:
- Uses MaryUI `<x-input>`, `<x-select>`, `<x-range>` components.
- Calls `LoanCalculatorService` reactively on property change.
- Displays: monthly repayment, total interest, total repayment — formatted as ZMW.

### 7.4 Application Form (`Public/ApplicationForm`)

**This is the most complex component.** It is a single Livewire component with a `$currentStep` property.

**Livewire properties**:
- `$currentStep` = 1
- `$totalSteps` = 6 (salary-backed) or 7 (collateral-backed)
- `$loanProduct` — loaded from route `{slug}`
- Step 1 fields: `$name`, `$email`, `$phone`, `$nrc_number`, `$date_of_birth`, `$gender`, `$marital_status`, `$residential_address`, `$city`, `$province`
- Step 2 fields: `$employer_name`, `$employer_address`, `$job_title`, `$employment_date`, `$monthly_income`
- Step 3 (collateral only): `$collateral_type`, `$collateral_description`, `$collateral_value`, `$collateral_registration`
- Step 3/4 Banking: `$bank_name`, `$bank_account_number`, `$bank_branch`
- Step 4/5 Loan: `$amount_requested`, `$tenure_months`, `$purpose`
- Step 5/6 Documents: `$document_nrc`, `$document_payslip`, `$document_bank_statement`, `$document_employment_letter`, `$document_collateral_proof`, `$document_selfie` (Livewire file uploads)
- Step 6/7: Review (read-only summary)

**Methods**:
- `nextStep()` — validate current step fields, advance `$currentStep`
- `previousStep()` — go back
- `submit()` — calls `AccountGeneratorService`, creates `LoanApplication`, stores documents, creates `Collateral` if applicable, sends notifications, redirects to confirmation page

**Validation per step**: Use `$this->validateOnly()` or step-specific rules arrays.

**MaryUI components to use**: `<x-form>`, `<x-input>`, `<x-select>`, `<x-textarea>`, `<x-file>`, `<x-button>`, `<x-steps>`, `<x-card>`, `<x-alert>`.

### 7.5 Confirmation Page (`Public/Confirmation`)

Receives `{reference}` from route. Displays:
- Success alert with reference number
- "Your account has been created" message
- Login credentials reminder (check email/SMS)
- Button to go to login page

### 7.6 Acceptance Criteria — Phase 7

- [ ] Landing page displays both loan products from the database
- [ ] Loan calculator updates reactively when inputs change
- [ ] Multi-step form validates each step before advancing
- [ ] Submitting the form creates: 1 User, 1 Customer, 1 LoanApplication, uploaded Documents
- [ ] Customer receives email with credentials and reference number
- [ ] Confirmation page shows the reference number
- [ ] Returning applicant (same email/phone) reuses existing account

---

## PHASE 8: Customer Portal (Livewire Components)

### 8.1 Component List

```
php artisan livewire:make Auth/Login
php artisan livewire:make Auth/ChangePassword
php artisan livewire:make Customer/MyLoans
php artisan livewire:make Customer/LoanDetail
php artisan livewire:make Customer/Profile
```

### 8.2 Login (`Auth/Login`)

- Allow login with **email or phone** + password.
- Override the default authentication logic: check if input matches email format → auth by email, otherwise treat as phone.
- On successful login: redirect to `/portal/loans` (customer) or `/admin` (admin) based on role.

### 8.3 Change Password (`Auth/ChangePassword`)

- Shown when `must_change_password` is true.
- Fields: new password, confirm password.
- On submit: update password, set `must_change_password = false`, redirect to portal.

### 8.4 My Loans (`Customer/MyLoans`)

- Fetch all `LoanApplication` records for the authenticated customer.
- Display as MaryUI `<x-table>` with columns: Reference, Loan Type, Amount (ZMW), Status (badge), Date Applied.
- Status badges with colours: pending (warning), under_review (info), approved (success), rejected (error), disbursed (success), info_requested (warning).
- Click row → navigate to loan detail.

### 8.5 Loan Detail (`Customer/LoanDetail`)

- Receives `{reference}` from route. Loads the application with relationships.
- **Sections**:
  1. Status banner with current status and timeline of status changes.
  2. Loan details: product, amount requested/approved, tenure, interest rate, monthly repayment.
  3. If `info_requested`: show alert with the note and a document upload form.
  4. If `disbursed`: show repayment schedule table and payments made.
  5. Documents list with verification status.

### 8.6 Profile (`Customer/Profile`)

- Display and allow editing of: name, phone, email, residential address, city, province.
- Read-only: NRC number (cannot change after application).

### 8.7 Portal Layout

Create `resources/views/components/layouts/portal.blade.php`:

- MaryUI sidebar with: `<x-menu>` items for My Loans, Profile, Logout.
- Header with customer name and notifications bell.
- Use `<x-main>` for content area.

### 8.8 Acceptance Criteria — Phase 8

- [ ] Customer can log in with email or phone
- [ ] First login forces password change
- [ ] My Loans page shows all applications for the logged-in customer
- [ ] Loan Detail shows correct information and status
- [ ] Customer can upload additional documents when info is requested
- [ ] Profile page allows editing of permitted fields

---

## PHASE 9: Admin Panel (Livewire Components)

### 9.1 Component List

```
php artisan livewire:make Admin/Dashboard
php artisan livewire:make Admin/ApplicationsList
php artisan livewire:make Admin/ApplicationReview
php artisan livewire:make Admin/CustomersList
php artisan livewire:make Admin/CustomerDetail
php artisan livewire:make Admin/ProductsManager
php artisan livewire:make Admin/RepaymentsManager
php artisan livewire:make Admin/Reports
php artisan livewire:make Admin/AuditLog
```

### 9.2 Admin Layout

Create `resources/views/components/layouts/admin.blade.php`:

- MaryUI sidebar with `<x-menu>` items: Dashboard, Applications, Customers, Loan Products, Repayments, Reports, Audit Log.
- Use `<x-menu-item title="Dashboard" icon="o-home" link="/admin" />` pattern.
- Header with admin name and notification count badge.

### 9.3 Dashboard (`Admin/Dashboard`)

**Metrics row** using `<x-stat>`:
- Total Pending Applications (count)
- Total Disbursed This Month (ZMW sum)
- Total Overdue Loans (count where disbursed and past due_date with outstanding balance)
- Approval Rate (% approved out of total processed this month)

**Charts** (use a simple bar/line chart library or MaryUI chart component):
- Applications by month (last 6 months)
- Disbursements by month (ZMW)

**Recent applications table**: Last 5 pending applications with quick-action links.

### 9.4 Applications List (`Admin/ApplicationsList`)

**MaryUI `<x-table>`** with:
- Columns: Reference, Customer Name, Loan Type, Amount (ZMW), Status, Date Applied
- **Filters** (using MaryUI `<x-select>` and `<x-input>`):
  - Status filter (dropdown: all, pending, under_review, approved, rejected, etc.)
  - Loan type filter
  - Date range
  - Search by reference or customer name
- Sortable columns
- Click row → navigate to `admin.application.review`

### 9.5 Application Review (`Admin/ApplicationReview`)

**This is the most important admin page.** Receives `{id}` from route.

**Layout** — two-column on desktop:

**Left column** (customer info):
- Personal details card
- Employment details card
- Banking details card
- Collateral details card (if collateral-backed)

**Right column** (loan info + actions):
- Loan details card (product, amount, tenure, calculated repayment)
- Documents card — list of uploaded files with:
  - Preview/download links
  - "Verify" toggle button per document
  - Notes field
- **Action panel** (only shown if status allows):
  - **Approve button**: Opens modal/drawer with fields:
    - Amount approved (default: amount_requested)
    - Interest rate (default: product rate)
    - Tenure (default: requested)
    - Admin notes
    - Auto-calculates monthly repayment on change
    - Confirm button
  - **Reject button**: Opens modal with:
    - Rejection reason (required, visible to customer)
    - Admin notes (internal)
    - Confirm button
  - **Request Info button**: Opens modal with:
    - Note about what info is needed (required, visible to customer)
    - Confirm button
  - **Mark as Disbursed** (only if status = approved): Opens modal with:
    - Disbursement date
    - Notes
    - Confirm button
- **Status history** / audit trail for this application

**On each action**: Update status, set relevant fields, log to activity log, send notification to customer.

### 9.6 Customers List (`Admin/CustomersList`)

- Table: Name, Email, Phone, NRC, Applications Count, Total Borrowed (ZMW), Joined Date
- Search by name, email, phone, NRC
- Click → customer detail

### 9.7 Customer Detail (`Admin/CustomerDetail`)

- Full profile display
- List of all loan applications (mini table)
- Total borrowed, total repaid, outstanding balance
- Account actions: reset password, deactivate

### 9.8 Products Manager (`Admin/ProductsManager`)

- Table of all loan products
- Add/Edit via MaryUI `<x-drawer>` or `<x-modal>`:
  - Name, slug (auto-generated from name), type, description
  - Min/max amount, min/max tenure, interest rate
  - Requires collateral toggle, active toggle
- Delete (soft — just deactivate)

### 9.9 Repayments Manager (`Admin/RepaymentsManager`)

- **Record repayment form**: Select loan (by reference), amount, date, method, reference number, notes.
- **Repayments table**: Reference, Customer, Amount (ZMW), Date, Method, Recorded By.
- **Overdue loans tab**: List of disbursed loans past their due date with outstanding balance.

### 9.10 Reports (`Admin/Reports`)

- **Disbursement Report**: Date range filter → table of disbursed loans with totals. Export to Excel/PDF.
- **Repayment Report**: Date range → all repayments with totals. Export.
- **Overdue Report**: Current overdue loans. Export.
- **Summary Report**: Counts and sums by status, by loan type, by month.

### 9.11 Audit Log (`Admin/AuditLog`)

- Uses `spatie/laravel-activitylog`.
- Table: Date, User, Action, Subject, Changes (old → new).
- Filter by date range, user, action type.

### 9.12 Acceptance Criteria — Phase 9

- [ ] Dashboard shows correct metrics from the database
- [ ] Applications list filters and sorts correctly
- [ ] Admin can approve a loan — status changes, notification sent, repayment calculated
- [ ] Admin can reject a loan — reason is saved and visible to customer
- [ ] Admin can request more info — customer sees the note and can upload docs
- [ ] Admin can mark approved loan as disbursed
- [ ] Admin can record repayments against disbursed loans
- [ ] Loan products can be created, edited, and deactivated
- [ ] Reports generate correct data and can be exported
- [ ] Audit log records all admin actions

---

## PHASE 10: Polish & Hardening

### 10.1 Scheduled Tasks

In `app/Console/Kernel.php` (or `routes/console.php` in Laravel 11):

- **Daily at 8am**: Check for loans with repayments due in 3 days → send reminder notification.
- **Daily at 9am**: Check for overdue loans (past due_date with outstanding balance) → send overdue notification.
- **Daily at midnight**: Flag loans as `defaulted` if more than 90 days overdue (configurable).

### 10.2 File Storage

- Configure `config/filesystems.php` with a `documents` disk.
- Store uploads in `storage/app/documents/{customer_id}/{application_reference}/`.
- Serve files through a controller that checks authorization (customer can only see their own, admin can see all).

### 10.3 Testing Checklist

Run through these manually or write Feature tests:

- [ ] Full customer journey: visit site → apply → receive credentials → login → view loan → change password
- [ ] Full admin journey: login → review application → approve → disburse → record repayment
- [ ] Reject flow: apply → admin rejects → customer sees rejection reason
- [ ] Info request flow: apply → admin requests info → customer uploads docs → admin reviews
- [ ] Returning customer: apply again with same email → uses existing account, creates new application
- [ ] Calculator accuracy: verify repayment calculations match expected values
- [ ] Role security: customer cannot access `/admin`, admin cannot access `/portal`
- [ ] File security: customer A cannot download customer B's documents

### 10.4 Acceptance Criteria — Phase 10

- [ ] Scheduled reminders run correctly
- [ ] File uploads are stored securely and served with auth checks
- [ ] All manual test scenarios pass
- [ ] No N+1 query issues on list pages (use eager loading)
- [ ] Application form handles validation errors gracefully per step

---

## Quick Reference: Livewire + MaryUI Patterns

### Table with filters

```blade
<x-card>
    <x-slot:header>
        <div class="flex gap-2">
            <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" />
            <x-select label="Status" wire:model.live="statusFilter" :options="$statuses" placeholder="All" />
        </div>
    </x-slot:header>

    <x-table :headers="$headers" :rows="$rows" :sort-by="$sortBy" with-pagination>
        @scope('cell_status', $row)
            <x-badge :value="$row->status" class="badge-{{ $statusColor[$row->status] }}" />
        @endscope
    </x-table>
</x-card>
```

### Multi-step form

```blade
<x-steps wire:model="currentStep" class="my-4">
    <x-step step="1" text="Personal Info" />
    <x-step step="2" text="Employment" />
    <x-step step="3" text="Loan Details" />
</x-steps>

<x-card>
    @if($currentStep === 1)
        <x-input label="Full Name" wire:model="name" />
        <x-input label="Email" wire:model="email" type="email" />
        {{-- ... more fields --}}
    @elseif($currentStep === 2)
        {{-- Step 2 fields --}}
    @endif

    <x-slot:actions>
        <x-button label="Back" wire:click="previousStep" class="btn-ghost" :disabled="$currentStep === 1" />
        @if($currentStep < $totalSteps)
            <x-button label="Next" wire:click="nextStep" class="btn-primary" />
        @else
            <x-button label="Submit Application" wire:click="submit" class="btn-success" />
        @endif
    </x-slot:actions>
</x-card>
```

### Action modal (Admin)

```blade
<x-button label="Approve" wire:click="$toggle('showApproveModal')" class="btn-success" icon="o-check" />

<x-modal wire:model="showApproveModal" title="Approve Loan">
    <x-input label="Amount to Approve (ZMW)" wire:model="approveAmount" type="number" prefix="ZMW" />
    <x-input label="Interest Rate (%)" wire:model="approveRate" type="number" suffix="%" />
    <x-input label="Tenure (Months)" wire:model="approveTenure" type="number" />

    <div class="mt-4 p-3 bg-base-200 rounded-lg">
        <p class="text-sm">Monthly Repayment: <strong>ZMW {{ number_format($calculatedRepayment, 2) }}</strong></p>
    </div>

    <x-slot:actions>
        <x-button label="Cancel" wire:click="$toggle('showApproveModal')" />
        <x-button label="Confirm Approval" wire:click="approveLoan" class="btn-success" spinner />
    </x-slot:actions>
</x-modal>
```

### Stat cards (Dashboard)

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <x-stat title="Pending" :value="$pendingCount" icon="o-clock" color="text-warning" />
    <x-stat title="Disbursed (ZMW)" :value="number_format($disbursedTotal, 2)" icon="o-banknotes" color="text-success" />
    <x-stat title="Overdue" :value="$overdueCount" icon="o-exclamation-triangle" color="text-error" />
    <x-stat title="Approval Rate" :value="$approvalRate . '%'" icon="o-chart-bar" color="text-info" />
</div>
```

---

## File Tree (Final)

```
loan-system/
├── app/
│   ├── Http/Middleware/
│   │   ├── EnsureUserRole.php
│   │   └── ForcePasswordChange.php
│   ├── Livewire/
│   │   ├── Admin/
│   │   │   ├── Dashboard.php
│   │   │   ├── ApplicationsList.php
│   │   │   ├── ApplicationReview.php
│   │   │   ├── CustomersList.php
│   │   │   ├── CustomerDetail.php
│   │   │   ├── ProductsManager.php
│   │   │   ├── RepaymentsManager.php
│   │   │   ├── Reports.php
│   │   │   └── AuditLog.php
│   │   ├── Auth/
│   │   │   ├── Login.php
│   │   │   └── ChangePassword.php
│   │   ├── Customer/
│   │   │   ├── MyLoans.php
│   │   │   ├── LoanDetail.php
│   │   │   └── Profile.php
│   │   └── Public/
│   │       ├── Home.php
│   │       ├── LoanProductDetail.php
│   │       ├── ApplicationForm.php
│   │       ├── Confirmation.php
│   │       └── LoanCalculator.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Customer.php
│   │   ├── LoanProduct.php
│   │   ├── LoanApplication.php
│   │   ├── Document.php
│   │   ├── Collateral.php
│   │   └── Repayment.php
│   ├── Notifications/
│   │   ├── ApplicationSubmitted.php
│   │   ├── LoanStatusChanged.php
│   │   └── NewApplicationForAdmin.php
│   └── Services/
│       ├── AccountGeneratorService.php
│       ├── LoanCalculatorService.php
│       └── SmsService.php
├── database/
│   ├── migrations/
│   │   ├── xxxx_modify_users_table.php
│   │   ├── xxxx_create_customers_table.php
│   │   ├── xxxx_create_loan_products_table.php
│   │   ├── xxxx_create_loan_applications_table.php
│   │   ├── xxxx_create_documents_table.php
│   │   ├── xxxx_create_collaterals_table.php
│   │   └── xxxx_create_repayments_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── components/layouts/
│   │   ├── app.blade.php          (base layout)
│   │   ├── admin.blade.php        (admin sidebar layout)
│   │   └── portal.blade.php       (customer sidebar layout)
│   └── livewire/
│       ├── admin/                  (auto-generated by livewire:make)
│       ├── auth/
│       ├── customer/
│       └── public/
└── routes/
    └── web.php
```

---

## Environment Variables to Configure

```env
# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=loan_system
DB_USERNAME=postgres
DB_PASSWORD=

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=noreply@loansystem.com
MAIL_FROM_NAME="Loan System"

# SMS Gateway (stub — configure when integrating)
SMS_GATEWAY_URL=
SMS_GATEWAY_API_KEY=
SMS_GATEWAY_SENDER_ID=

# File storage
FILESYSTEM_DISK=local

# App
APP_NAME="Loan System"
APP_URL=http://localhost:8000
```
