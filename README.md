# Leave Management System - Backend API

Sistem manajemen cuti karyawan yang dibangun dengan Laravel dengan arsitektur Clean Architecture. API ini menyediakan fitur untuk pengajuan cuti, persetujuan, serta manajemen kuota cuti karyawan.

## 📋 Daftar Isi

1. [Fitur Utama](#fitur-utama)
2. [Tech Stack](#tech-stack)
3. [Struktur Proyek](#struktur-proyek)
4. [Persyaratan Sistem](#persyaratan-sistem)
5. [Instalasi & Setup](#instalasi--setup)
6. [Konfigurasi Environment](#konfigurasi-environment)
7. [Migrasi Database](#migrasi-database)
8. [Penggunaan API](#penggunaan-api)
9. [Dokumentasi Endpoint](#dokumentasi-endpoint)
10. [Arsitektur Sistem](#arsitektur-sistem)
11. [Penjelasan Alur Bisnis](#penjelasan-alur-bisnis)
12. [Testing](#testing)

## ✨ Fitur Utama

### 1. **Otentikasi (Authentication)**
- ✅ Login konvensional dengan email & password
- ✅ Registrasi user baru dengan role assignment
- ✅ Token-based authentication menggunakan Laravel Sanctum
- ✅ Logout & Token Refresh
- ✅ Endpoint for retrieving current user

### 2. **Manajemen Role & Akses (Authorization)**
- ✅ **Employee Role**: Pengajuan cuti, lihat status pengajuan sendiri
- ✅ **Admin Role**: Lihat semua pengajuan, approve/reject pengajuan
- ✅ Role-based middleware untuk proteksi endpoint

### 3. **Logika Bisnis Leave Request**
- ✅ **Kuota Cuti**: 12 hari per tahun per employee
- ✅ **Status Workflow**: Pending → Approved/Rejected
- ✅ **Input Pengajuan**: 
  - Start date & End date (validasi tanggal)
  - Reason (alasan cuti)
  - Attachment (file dokumen pendukung)
- ✅ **Perhitungan Hari**: Otomatis menghitung jumlah hari cuti yang diajukan
- ✅ **Deduction Quota**: Mengurangi kuota ketika pengajuan di-approve

### 4. **File Management**
- ✅ Upload attachment (PDF, DOC, DOCX, JPG, PNG)
- ✅ Storage organization dengan folder `leave-attachments`
- ✅ File size limit: 5MB per file

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Framework** | Laravel 10.x |
| **Database** | MySQL / PostgreSQL |
| **Authentication** | Laravel Sanctum |
| **API Response Format** | JSON |
| **Validation** | Laravel Form Request |
| **File Storage** | Local Storage (Public disk) |
| **Environment** | PHP 8.1+ |

## 📁 Struktur Proyek

```
magang/
├── app/
│   ├── Enums/                    # Enumeration classes
│   │   ├── LeaveStatus.php       # Status: pending, approved, rejected
│   │   └── UserRole.php          # Role: employee, admin
│   ├── Models/                   # Eloquent Models
│   │   ├── User.php              # User model dengan relationships
│   │   ├── LeaveRequest.php       # Leave request model
│   │   └── LeaveBalance.php       # Leave balance per year
│   ├── Services/                 # Business Logic Layer
│   │   └── LeaveRequestService.php # Service untuk leave request operations
│   ├── DTO/                      # Data Transfer Objects
│   │   ├── CreateLeaveRequestDTO.php
│   │   ├── ApproveLeaveRequestDTO.php
│   │   └── RejectLeaveRequestDTO.php
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── AuthController.php       # Authentication endpoints
│   │   │   └── LeaveRequestController.php # Leave request endpoints
│   │   ├── Requests/              # Form Request Validation
│   │   │   ├── StoreLeaveRequestRequest.php
│   │   │   ├── ApproveLeaveRequestRequest.php
│   │   │   └── RejectLeaveRequestRequest.php
│   │   ├── Resources/             # API Resource classes
│   │   │   ├── UserResource.php
│   │   │   ├── LeaveRequestResource.php
│   │   │   └── LeaveBalanceResource.php
│   │   └── Middleware/            # Custom Middleware
│   │       ├── IsAdmin.php
│   │       └── IsEmployee.php
│   ├── Policies/                 # Authorization Policies (optional)
│   ├── Actions/                  # Reusable action classes (optional)
│   └── Repositories/             # Repository pattern (optional)
├── database/
│   ├── migrations/               # Database migrations
│   │   ├── 2026_04_15_000000_add_oauth_columns_to_users_table.php
│   │   ├── 2026_04_15_000001_create_leave_requests_table.php
│   │   └── 2026_04_15_000002_create_leave_balances_table.php
│   └── seeders/
│       └── UserSeeder.php        # Seeder untuk user & leave balance
├── routes/
│   ├── api.php                   # API routes
│   └── web.php
├── storage/
│   └── app/
│       └── public/
│           └── leave-attachments/ # Storage untuk file attachment
├── config/
│   ├── app.php
│   ├── database.php
│   ├── filesystems.php           # Konfigurasi storage
│   └── ...
├── README.md                     # Dokumentasi ini
├── .env.example                  # Contoh environment file
├── composer.json
└── ...
```

## 🖥 Persyaratan Sistem

- PHP >= 8.1
- Composer
- MySQL 5.7+ atau PostgreSQL 10+
- Node.js & npm (untuk asset compilation, opsional)

## 📦 Instalasi & Setup

### 1. Clone Repository
```bash
git clone <repository-url>
cd magang
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Copy Environment File
```bash
cp .env.example .env
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Konfigurasi Database
Edit file `.env` dan sesuaikan kredensial database Anda.

### 6. Jalankan Migrations
```bash
php artisan migrate
```

### 7. (Opsional) Jalankan Seeder
```bash
php artisan db:seed --class=UserSeeder
```

Ini akan membuat:
- 1 user Admin dengan email `admin@example.com`
- 5 user Employee dengan email `employee1@example.com` - `employee5@example.com`
- Password untuk semua: `password`
- Leave balance 12 hari untuk setiap user

### 8. Link Storage
```bash
php artisan storage:link
```

### 9. Jalankan Development Server
```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

## 🔐 Konfigurasi Environment

Berikut variabel penting dalam `.env`:

```env
# App
APP_NAME="Leave Management System"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=magang
DB_USERNAME=root
DB_PASSWORD=

# File Storage
FILESYSTEM_DISK=public

# Sanctum - API Token Settings
SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:8000
```

## 🗄 Migrasi Database

### Tabel Migration yang Dibuat

#### 1. Users Table (dari default Laravel + tambahan)
```sql
- id (PK)
- name
- email (UNIQUE)
- email_verified_at
- password
- role (enum: 'employee', 'admin') -- DEFAULT: 'employee'
- oauth_id (nullable, UNIQUE)
- oauth_provider (nullable)
- remember_token
- created_at
- updated_at
```

#### 2. Leave Requests Table
```sql
- id (PK)
- user_id (FK → users)
- start_date (DATE)
- end_date (DATE)
- reason (TEXT)
- attachment_path (nullable) -- Path ke file di storage
- status (enum: 'pending', 'approved', 'rejected') -- DEFAULT: 'pending'
- approved_by (nullable FK → users)
- approval_notes (nullable TEXT)
- approved_at (nullable TIMESTAMP)
- deleted_at (soft delete)
- created_at
- updated_at
```

#### 3. Leave Balances Table
```sql
- id (PK)
- user_id (FK → users)
- year (YEAR) -- Tahun balance
- total_quota (INT) -- Total cuti per tahun (fixed 12)
- used_quota (INT) -- Cuti yang sudah digunakan
- remaining_quota (INT) -- Sisa cuti
- UNIQUE(user_id, year)
- created_at
- updated_at
```

## 📚 Penggunaan API

Semua endpoint API menerima dan mengembalikan format JSON.

### Base URL
```
http://localhost:8000/api
```

### Response Format

**Success Response:**
```json
{
  "message": "Operation successful",
  "data": {...},
  "token": "..." // Jika ada
}
```

**Error Response:**
```json
{
  "message": "Error description",
  "error": "Detailed error message"
}
```

## 📖 Dokumentasi Endpoint

### 1. Authentication Endpoints

#### Register User
**Endpoint:** `POST /api/auth/register`

**Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password",
  "role": "employee" // atau "admin"
}
```

**Response (201):**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "employee",
    "role_label": "Karyawan",
    ...
  },
  "token": "1|abc123def456..."
}
```

---

#### Login User
**Endpoint:** `POST /api/auth/login`

**Body:**
```json
{
  "email": "john@example.com",
  "password": "password"
}
```

**Response (200):**
```json
{
  "message": "Login berhasil",
  "user": {...},
  "token": "1|abc123def456..."
}
```

---

#### Get Current User
**Endpoint:** `GET /api/auth/me`

**Headers:** 
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "user": {...}
}
```

---

#### Logout User
**Endpoint:** `POST /api/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Logout berhasil"
}
```

---

#### Refresh Token
**Endpoint:** `POST /api/auth/refresh`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Token refreshed successfully",
  "user": {...},
  "token": "1|new_token_here..."
}
```

---

### 2. Leave Request Endpoints

#### Get Leave Requests
**Endpoint:** `GET /api/leave-requests`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `status` (optional): Filter by status (pending, approved, rejected)
- `user_id` (optional): Filter by user ID (admin only for others)

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 2,
      "user": {...},
      "start_date": "2026-05-01",
      "end_date": "2026-05-05",
      "days_requested": 5,
      "reason": "Liburan keluarga",
      "attachment_url": "http://localhost:8000/storage/leave-attachments/...",
      "status": "pending",
      "status_label": "Menunggu Persetujuan",
      "approved_by": null,
      "approver": null,
      "approval_notes": null,
      "approved_at": null,
      "created_at": "2026-04-15T10:00:00Z",
      "updated_at": "2026-04-15T10:00:00Z"
    }
  ]
}
```

---

#### Create Leave Request
**Endpoint:** `POST /api/leave-requests`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Body (Form Data):**
```
- start_date: 2026-05-01 (required, date, after today)
- end_date: 2026-05-05 (required, date, >= start_date)
- reason: Liburan keluarga (required, max 500 chars)
- attachment: file (optional, pdf/doc/docx/jpg/jpeg/png, max 5MB)
```

**Response (201):**
```json
{
  "message": "Pengajuan cuti berhasil dibuat",
  "data": {...}
}
```

---

#### Get Specific Leave Request
**Endpoint:** `GET /api/leave-requests/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "data": {...}
}
```

---

#### Approve Leave Request
**Endpoint:** `POST /api/leave-requests/{id}/approve`

**Headers:**
```
Authorization: Bearer {token}
```

**Body (JSON):**
```json
{
  "notes": "Disetujui. Selamat berlibur!" // optional
}
```

**Response (200):**
```json
{
  "message": "Pengajuan cuti berhasil disetujui",
  "data": {
    ...
    "status": "approved",
    "approved_by": 1,
    "approval_notes": "Disetujui. Selamat berlibur!",
    "approved_at": "2026-04-15T10:30:00Z"
  }
}
```

---

#### Reject Leave Request
**Endpoint:** `POST /api/leave-requests/{id}/reject`

**Headers:**
```
Authorization: Bearer {token}
```

**Body (JSON):**
```json
{
  "reason": "Alasan penolakan harus berupa detail" // required
}
```

**Response (200):**
```json
{
  "message": "Pengajuan cuti berhasil ditolak",
  "data": {
    ...
    "status": "rejected",
    "approved_by": 1,
    "approval_notes": "Alasan penolakan harus berupa detail",
    "approved_at": "2026-04-15T10:30:00Z"
  }
}
```

---

### 3. Leave Balance Endpoint

#### Get Leave Balance
**Endpoint:** `GET /api/leave-balance`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `year` (optional): Default = current year

**Response (200):**
```json
{
  "data": {
    "id": 1,
    "user_id": 2,
    "year": 2026,
    "total_quota": 12,
    "used_quota": 5,
    "remaining_quota": 7,
    "percentage_used": 41.67,
    "created_at": "2026-01-01T00:00:00Z",
    "updated_at": "2026-04-15T10:30:00Z"
  }
}
```

---

## 🏗 Arsitektur Sistem

### Clean Architecture Layers

```
┌─────────────────────────────────────┐
│   HTTP Layer (Routes, Controllers)  │
├─────────────────────────────────────┤
│     Business Logic Layer (Services)  │
├─────────────────────────────────────┤
│   Data Access Layer (Models, DTOs)  │
├─────────────────────────────────────┤
│      Database Layer (Eloquent)       │
└─────────────────────────────────────┘
```

### 1. **HTTP Layer**
- **Controllers**: Menerima request, memanggil service, return response
- **Requests**: Form request validation
- **Resources**: API response formatting
- **Routes**: Endpoint definition dengan middleware

### 2. **Business Logic Layer (Services)**
- **LeaveRequestService**: Menghandle create, approve, reject leave requests
- **Validation**: Pengecekan kuota, validasi tanggal, etc
- **Transaction**: Memastikan konsistensi data

### 3. **Data Access Layer**
- **DTOs**: Transfer data antar layer
- **Models**: Eloquent models dengan business logic methods
- **Enums**: Type-safe constants (LeaveStatus, UserRole)

### 4. **Database Layer**
- **Migrations**: Schema definition
- **Seeders**: Sample data
- **Relationships**: Model relationships

---

## 📊 Penjelasan Alur Bisnis

### A. User Registration & Role Assignment

```
User Input (Register Form)
    ↓
AuthController.register() 
    ↓
Create User with Role (employee/admin)
    ↓
Create Leave Balance (12 days/year) automatically
    ↓
Return Token & User Data
```

### B. Leave Request Flow (Normal Scenario)

```
Employee
    ↓
Cek Leave Balance (GET /leave-balance)
    ↓
Submit Leave Request (POST /leave-requests)
    ↓ [Validation: date, attachment, quota check]
    ↓
Create LeaveRequest with status = 'pending'
    ↓
Attach file jika ada & save to storage
    ↓
Return LeaveRequest data dengan status pending
    ├─────────────────────────────────────┤
    ↓                                      ↓
Admin Approve                      Admin Reject
    ↓                                      ↓
Update status = 'approved'         Update status = 'rejected'
    ↓                                      ↓
Deduct quota from LeaveBalance     Maintain quota (no change)
    ↓                                      ↓
Set approved_by & approved_at       Set rejected_by & approval_notes
    ↓                                      ↓
Return notification to employee   Return notification to employee
```

### C. Quota Management

```
New Year (Jan 1)
    ↓
Create/Reset LeaveBalance
    ├─ total_quota = 12 days (fixed)
    ├─ used_quota = 0
    └─ remaining_quota = 12

Employee Request Days
    ↓
Check: remaining_quota >= days_requested ?
    ├─ YES: Allow request
    └─ NO: Reject request

Admin Approves Request
    ↓
used_quota += days_requested
remaining_quota -= days_requested
(transaction-safe update)
```

### D. Authorization Checks

```
Every Request
    ├─ Is user authenticated?
    │   ├─ NO: 401 Unauthorized
    │   └─ YES: Continue
    │
    ├─ For Employee Endpoints:
    │   ├─ Is user an employee?
    │   ├─ Can only access own leave requests
    │   └─ Can only view own balance
    │
    └─ For Admin Endpoints:
        ├─ Is user an admin?
        ├─ Can view all leave requests
        ├─ Can approve/reject requests
        └─ Can view any user's balance
```

---

## 🧪 Testing

### Manual Testing dengan Postman

1. **Import Postman Collection**
   - Download file Postman collection dari dokumentasi
   - atau buat manual dengan endpoint di atas

2. **Register & Login Test**
   ```bash
   POST /api/auth/register
   POST /api/auth/login
   ```

3. **Leave Request Test (Employee)**
   ```bash
   GET /api/leave-balance
   POST /api/leave-requests (with attachment)
   GET /api/leave-requests
   GET /api/leave-requests/{id}
   ```

4. **Leave Request Management (Admin)**
   ```bash
   GET /api/leave-requests?status=pending
   POST /api/leave-requests/{id}/approve
   POST /api/leave-requests/{id}/reject
   ```

### Unit Testing (Future)
```bash
php artisan test
```

---

## 🔄 Workflow Lengkap (End-to-End)

### Skenario: Employee Mengajukan Cuti

**1. Employee Login**
```bash
POST /api/auth/login
{
  "email": "employee1@example.com",
  "password": "password"
}
```
Response: `token = "1|abc123..."`

**2. Cek Quota**
```bash
GET /api/leave-balance
Authorization: Bearer 1|abc123...
```
Response: 12 days remaining

**3. Upload & Ajukan Cuti**
```bash
POST /api/leave-requests
Authorization: Bearer 1|abc123...
Content-Type: multipart/form-data

start_date: 2026-05-01
end_date: 2026-05-05
reason: Liburan ke Bali bersama keluarga
attachment: <file.pdf>
```
Response: Created dengan status = pending

**4. Admin Login & Lihat Pengajuan**
```bash
POST /api/auth/login
{
  "email": "admin@example.com",
  "password": "password"
}
```

```bash
GET /api/leave-requests?status=pending
Authorization: Bearer 1|admin_token...
```

**5. Admin Approve**
```bash
POST /api/leave-requests/1/approve
Authorization: Bearer 1|admin_token...

{
  "notes": "Disetujui. Enjoy your leave!"
}
```

**6. Employee Cek Status**
```bash
GET /api/leave-requests/1
Authorization: Bearer 1|abc123...
```
Response: status = approved, remaining_quota = 7

---

## 📝 Environment Variables Checklist

```
☑ APP_NAME=Leave Management System
☑ APP_ENV=local
☑ APP_DEBUG=true
☑ APP_URL=http://localhost:8000
☑ APP_KEY=base64:xxx

☑ DB_CONNECTION=mysql
☑ DB_HOST=127.0.0.1
☑ DB_DATABASE=magang
☑ DB_USERNAME=root
☑ DB_PASSWORD=

☑ FILESYSTEM_DISK=public
☑ SANCTUM_STATEFUL_DOMAINS=localhost:8000
```

---

## 🚀 Deployment (Production Notes)

```bash
# 1. Setup environment
cp .env.production .env

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Generate key
php artisan key:generate

# 4. Run migrations
php artisan migrate --force

# 5. Run seeders
php artisan db:seed --class=UserSeeder --force

# 6. Link storage
php artisan storage:link

# 7. Clear cache
php artisan config:cache
php artisan route:cache
```

---

## 📞 Support & Dokumentasi

- **Laravel**: https://laravel.com/docs
- **Sanctum**: https://laravel.com/docs/sanctum
- **Eloquent**: https://laravel.com/docs/eloquent

---

## 📄 Lisensi

Private Project - Do not redistribute

---

**Last Updated**: April 15, 2026
**API Version**: 1.0
**Framework**: Laravel 10.x

