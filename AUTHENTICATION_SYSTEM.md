# 🔐 Authentication System Documentation

**Framework**: Laravel 10.x  
**Auth Type**: Session-based (Web) + Token-based (API)  
**Status**: ✅ Fully Implemented

---

## 📚 Complete File Reference

### Created Files

#### 1. Layout Template
**File**: `resources/views/layouts/app.blade.php`  
**Type**: Blade Master Layout  
**Purpose**: Shared layout for all authentication pages

**Features**:
- Bootstrap 5.3 integration
- Font Awesome 6.4 icons
- Custom CSS styling (300+ lines)
- Gradient background design
- Toast notification system
- Form styling
- Navigation bar
- Footer

**Usage**:
```blade
@extends('layouts.app')

@section('content')
    <!-- Your page content here -->
@endsection
```

**Key CSS Classes**:
- `.auth-container` - Main form wrapper
- `.gradient-bg` - Background gradient
- `.form-input` - Styled input fields
- `.btn-auth` - Authentication buttons
- `.toast-notification` - Notification design

---

#### 2. Login View
**File**: `resources/views/auth/login.blade.php`  
**Type**: Blade Template  
**Purpose**: User login interface

**Form Fields**:
```
Email (required, email format)
Password (required, min 6 chars)
Remember Me (checkbox, optional)
```

**Features**:
- Email validation with error display
- Password visibility toggle
- Remember me functionality
- Demo credentials helper box
- Error message alerts
- Loading state on submit
- Link to registration page
- Session success messages
- CSRF token protection

**Controller Method**: `AuthController@login()`  
**Route**: `POST /login`

**Form Inputs**:
```html
<input type="email" name="email" required>
<input type="password" name="password" required>
<input type="checkbox" name="remember" value="1">
<button type="submit">Login</button>
```

---

#### 3. Register View
**File**: `resources/views/auth/register.blade.php`  
**Type**: Blade Template  
**Purpose**: User registration interface

**Form Fields**:
```
Name (required, max 255)
Email (required, unique, email format)
Role (required, employee or admin)
Password (required, min 8 chars)
Password Confirmation (required, match password)
```

**Features**:
- Multiple field validation
- Real-time error display
- Email uniqueness checking
- Role selector dropdown
- Password confirmation matching
- Password visibility toggles
- Client-side validation
- Loading state
- Link to login page
- CSRF token protection

**Controller Method**: `AuthController@register()`  
**Route**: `POST /register`

**Special Features**:
- Auto-creates leave balance (12 days)
- Auto-login after registration
- Validation messages in Indonesian

---

#### 4. Dashboard View
**File**: `resources/views/dashboard.blade.php`  
**Type**: Blade Template  
**Purpose**: Post-login welcome page

**Displays**:
- User greeting with name
- User email
- User role
- Quick action buttons
- Logout button

**Requirements**: User must be authenticated  
**Middleware**: `auth`  
**Route**: `GET /dashboard`

**Extends**: `layouts.app`

---

#### 5. Home Page
**File**: `resources/views/index.blade.php`  
**Type**: Blade Template  
**Purpose**: Public landing page

**Sections**:
1. Navigation header
2. Hero section with CTA buttons
3. Six feature cards:
   - 🔒 Security & Encryption
   - 👥 Role-Based Access
   - 📅 Leave Calendar
   - 📎 File Uploads
   - ✅ Multi-Level Approvals
   - 📊 Analytics Dashboard
4. Call-to-action section
5. Footer

**Features**:
- Responsive design (mobile-first)
- Gradient backgrounds
- Feature cards with icons
- Conditional navigation (auth vs guest)
- Professional styling

**Accessibility**: Public route, no auth required

---

#### 6. Authentication Controller
**File**: `app/Http/Controllers/AuthController.php`  
**Type**: Laravel Controller  
**Purpose**: Handle all web authentication logic

**Public Methods**:

##### `showLogin()`
```php
public function showLogin()
```
- Display login form
- Redirect if already authenticated
- No validation
- Returns login view

##### `login(Request $request)`
```php
public function login(Request $request)
```

**Validates**:
```
email: required, email format
password: required, min 6 chars
remember: optional boolean
```

**Process**:
1. Validate input
2. Attempt authentication with credentials
3. Check "remember me" checkbox
4. Regenerate session on success
5. Redirect to intended page or dashboard
6. Return to login with errors on failure

**Error Messages** (Indonesian):
- "Email atau password salah."
- "Validation error messages"

**Redirect**: Success → `/dashboard`, Failure → `/login`

##### `showRegister()`
```php
public function showRegister()
```
- Display registration form
- Redirect if already authenticated
- No validation
- Returns register view

##### `register(Request $request)`
```php
public function register(Request $request)
```

**Validates**:
```
name: required, max 255
email: required, unique, email
password: required, min 8, confirmed
role: required, in:employee,admin
```

**Process**:
1. Validate input
2. Create user with hashed password
3. Create leave balance (12 days, current year)
4. Auto-login the new user
5. Regenerate session
6. Redirect to dashboard

**Returns**: User authenticated, redirects to `/dashboard`

##### `logout(Request $request)`
```php
public function logout(Request $request)
```
- Logout authenticated user
- Invalidate session
- Regenerate CSRF token
- Redirect to home page

---

#### 7. Web Routes
**File**: `routes/web.php`  
**Type**: Route Registration  
**Purpose**: Define all web routes

**Route Definitions**:

```php
// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest Only Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
```

**Route Details**:

| Route | Method | Auth | Purpose | Controller |
|-------|--------|------|---------|-----------|
| / | GET | No | Home page | Closure |
| /login | GET | Guest | Show login form | AuthController@showLogin |
| /login | POST | Guest | Process login | AuthController@login |
| /register | GET | Guest | Show register form | AuthController@showRegister |
| /register | POST | Guest | Process registration | AuthController@register |
| /dashboard | GET | Yes | User dashboard | Closure (view) |
| /logout | POST | Yes | Logout user | AuthController@logout |

---

## 🔄 Authentication Flow

### User Registration
```
1. User visits GET /register
   ↓
2. Shows registration form (name, email, password, role)
   ↓
3. User fills form & submits POST /register
   ↓
4. AuthController@register validates input:
   - Name required
   - Email unique
   - Password min 8 chars
   - Password confirmed
   - Role valid
   ↓
5. If valid:
   - Hash password with bcrypt
   - Create User record
   - Create LeaveBalance (12 days)
   - Auth::login() - auto-login
   - Session regeneration
   - Redirect to /dashboard
   
6. If invalid:
   - Return to form with errors
   - Preserve input
   - Show error messages
```

### User Login
```
1. User visits GET /login
   ↓
2. Shows login form (email, password, remember)
   ↓
3. User enters credentials & submits POST /login
   ↓
4. AuthController@login validates input:
   - Email required
   - Password required (min 6)
   ↓
5. Auth::attempt() checks database:
   - Find user by email
   - Verify password
   - Check "remember me"
   ↓
6. If valid:
   - Session created
   - Remember token set (if checked)
   - Session regenerated
   - Redirect to /dashboard (or intended page)
   - Show success message
   
7. If invalid:
   - Redirect back to login
   - Show error message
   - Preserve email input
```

### User Logout
```
1. User clicks logout button
   ↓
2. POST /logout submitted
   ↓
3. AuthController@logout:
   - Auth::logout()
   - $request->session()->invalidate()
   - $request->session()->regenerateToken()
   ↓
4. Redirect to home page
   ↓
5. User is logged out
```

---

## 💾 Database Tables (Auth-related)

### users table
```
id: bigint (primary key)
name: string
email: string (unique)
email_verified_at: timestamp (nullable)
password: string (hashed)
role: enum('employee', 'admin')
remember_token: string (nullable)
created_at: timestamp
updated_at: timestamp
```

### personal_access_tokens table
```
(For API token authentication - not used in web auth)
id: bigint (primary key)
tokenable_type: string
tokenable_id: bigint
name: string
token: string (hashed)
abilities: json
last_used_at: timestamp
expires_at: timestamp
created_at: timestamp
updated_at: timestamp
```

---

## 🎨 UI Components

### Form Components

#### Email Input
```blade
<div class="form-group mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" 
           id="email" name="email" value="{{ old('email') }}" required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
```

#### Password Input
```blade
<div class="form-group mb-3">
    <label for="password" class="form-label">Password</label>
    <div class="input-group">
        <input type="password" class="form-control" id="password" name="password" required>
        <button class="btn btn-outline-secondary" type="button" id="toggle-password">
            <i class="fas fa-eye"></i>
        </button>
    </div>
</div>
```

#### Role Selector
```blade
<div class="form-group mb-3">
    <label for="role" class="form-label">Role</label>
    <select class="form-control @error('role') is-invalid @enderror" 
            id="role" name="role" required>
        <option value="">Select Role</option>
        <option value="employee">Employee</option>
        <option value="admin">Administrator</option>
    </select>
</div>
```

### Styling

**Gradient Background**:
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

**Form Card**:
```css
background: white;
border-radius: 15px;
box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
padding: 40px;
```

**Input Fields**:
```css
border-radius: 8px;
padding: 10px 15px;
border: 1px solid #ddd;
transition: all 0.3s ease;
```

**Button**:
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
border: none;
border-radius: 8px;
color: white;
padding: 10px 20px;
```

---

## 🔐 Security Features

### Password Security
- **Hashing**: Laravel bcrypt (configurable in config/hashing.php)
- **Minimum Length**: 8 characters for registration
- **Confirmation**: Required on registration
- **Verification**: Hash comparison on login

### Session Security
```php
// Session configuration in config/session.php
'secure' => env('SESSION_SECURE_COOKIES', false),
'http_only' => true,
'same_site' => 'lax',
'lifetime' => 120, // 2 hours
```

### CSRF Protection
```blade
<!-- Every form includes CSRF token -->
@csrf
<!-- or -->
<input type="hidden" name="_token" value="{{ csrf_token() }}">
```

### SQL Injection Prevention
```php
// Using Eloquent ORM prevents SQL injection
User::where('email', $email)->first();
// Parameterized queries automatically used
```

### XSS Prevention
```blade
<!-- Variables are escaped by default in Blade -->
{{ $user->email }}  <!-- Outputs escaped HTML -->
{!! $html !!}       <!-- Only for trusted HTML content -->
```

---

## 🚀 Starting the Application

### Step 1: Configure Environment
```bash
# Copy example .env file
cp .env.example .env

# Generate app key
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=magang
DB_USERNAME=root
DB_PASSWORD=
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 3: Setup Database
```bash
# Run migrations
php artisan migrate

# Seed test data (optional)
php artisan db:seed --class=UserSeeder

# Create storage link for file uploads
php artisan storage:link
```

### Step 4: Build Frontend Assets
```bash
# Build Vue/JS assets
npm run dev

# Or for production
npm run build
```

### Step 5: Start Application
```bash
# Start development server
php artisan serve

# Application runs at http://localhost:8000
```

---

## 🧪 Testing Credentials

### After Database Seeding
```
Admin Account:
Email: admin@example.com
Password: password

Employee Account:
Email: employee1@example.com
Password: password
```

### Manual Test
```
Register new account at: /register

Fill form:
Name: John Doe
Email: john.doe@example.com
Password: MySecurePass123
Password Confirmation: MySecurePass123
Role: Employee

Then login with those credentials
```

---

## 📊 Authentication Architecture

### Web vs API Authentication

**Web Authentication (Session-based)**:
- Uses Laravel sessions
- CSRF token protection
- Cookie-based
- Suitable for browser requests
- Creates server-side session

**API Authentication (Token-based)**:
- Uses Laravel Sanctum
- Authorization header
- Token-based
- Suitable for mobile/SPA apps
- No CSRF needed

**This System**:
- Web routes use session authentication
- API routes use token authentication
- Both coexist in the same application
- User can login via web or request API token

---

## 🛠️ Customization

### Change Styles
Edit `resources/views/layouts/app.blade.php` CSS section:
```css
/* Modify colors */
:root {
    --primary-color: #667eea;
    --secondary-color: #764ba2;
}
```

### Change Validation Messages
Edit `app/Http/Controllers/AuthController.php`:
```php
$validated = $request->validate([
    'email' => 'required|email',
], [
    'email.required' => 'Custom message here',
]);
```

### Add More Fields
1. Add field to form (register.blade.php)
2. Add validation to controller
3. Add column to users table migration
4. Update User model fillable array

### Change Redirect After Login
Edit `AuthController@login()`:
```php
return redirect()->intended(route('dashboard'));
// Change to:
return redirect()->route('leave-request');
```

---

## 🎯 Features Summary

| Feature | Status | Location |
|---------|--------|----------|
| Login Form | ✅ Complete | `resources/views/auth/login.blade.php` |
| Register Form | ✅ Complete | `resources/views/auth/register.blade.php` |
| Login Logic | ✅ Complete | `AuthController@login()` |
| Register Logic | ✅ Complete | `AuthController@register()` |
| Logout Logic | ✅ Complete | `AuthController@logout()` |
| Password Hashing | ✅ Complete | Laravel bcrypt |
| Session Management | ✅ Complete | Laravel sessions |
| CSRF Protection | ✅ Complete | `@csrf` directive |
| Remember Me | ✅ Complete | Auth::attempt() |
| Form Validation | ✅ Complete | Request validation |
| Error Messages | ✅ Complete | Indonesian messages |
| Responsive Design | ✅ Complete | Bootstrap 5 |
| Auto-Balance Creation | ✅ Complete | Register method |
| Auto-Login After Register | ✅ Complete | Register method |

---

## 📞 Troubleshooting

### Login Not Working
1. Check `.env` database configuration
2. Verify users table exists: `php artisan migrate`
3. Check browser console for JS errors
4. Clear session: Delete cookies or `php artisan tinker` → `DB::table('sessions')->delete()`

### Register Failing
1. Check email is unique
2. Password minimum 8 characters
3. Password confirmation matches
4. Role is valid (employee or admin)

### Styles Not Showing
1. Run `npm install && npm run dev`
2. Clear browser cache (Ctrl+Shift+Delete)
3. Check Bootstrap CDN is loaded in layout

### Session Not Persisting
1. Check `config/session.php` settings
2. Verify COOKIE_PATH is /
3. Check encryption in `.env`

---

## 📚 Related Documentation

- Laravel Authentication: https://laravel.com/docs/10.x/authentication
- Blade Templating: https://laravel.com/docs/10.x/blade
- Form Validation: https://laravel.com/docs/10.x/validation
- Sessions: https://laravel.com/docs/10.x/session
- Sanctum API Auth: https://laravel.com/docs/10.x/sanctum

---

**Status**: ✅ **COMPLETE & PRODUCTION-READY**

All authentication features implemented and tested. Ready for production deployment.
