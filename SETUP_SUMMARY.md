# 📋 Project Setup Summary - Leave Management System

## ✅ What Has Been Created

Kami telah membangun struktur lengkap Leave Management System API dengan Clean Architecture. Berikut adalah ringkasan file dan folder yang telah dibuat:

---

## 📁 Struktur Folder yang Telah Dibuat

### 1. **Business Logic Layer**
- `app/Services/LeaveRequestService.php` - Service untuk operasi leave request
- `app/DTO/CreateLeaveRequestDTO.php` - Data Transfer Object untuk create
- `app/DTO/ApproveLeaveRequestDTO.php` - DTO untuk approval
- `app/DTO/RejectLeaveRequestDTO.php` - DTO untuk rejection

### 2. **Database Models & Enums**
- `app/Models/User.php` (Updated) - User model dengan relationships
- `app/Models/LeaveRequest.php` - Leave request model
- `app/Models/LeaveBalance.php` - Leave balance per year
- `app/Enums/UserRole.php` - Enum untuk roles
- `app/Enums/LeaveStatus.php` - Enum untuk status

### 3. **HTTP Layer**
#### Controllers
- `app/Http/Controllers/Api/AuthController.php` - Authentication endpoints
- `app/Http/Controllers/Api/LeaveRequestController.php` - Leave management endpoints

#### Request Validation
- `app/Http/Requests/StoreLeaveRequestRequest.php` - Validasi create request
- `app/Http/Requests/ApproveLeaveRequestRequest.php` - Validasi approval
- `app/Http/Requests/RejectLeaveRequestRequest.php` - Validasi rejection

#### Response Resources
- `app/Http/Resources/UserResource.php` - User response format
- `app/Http/Resources/LeaveRequestResource.php` - Leave request response format
- `app/Http/Resources/LeaveBalanceResource.php` - Balance response format

#### Middleware
- `app/Http/Middleware/IsAdmin.php` - Check admin role
- `app/Http/Middleware/IsEmployee.php` - Check employee role

### 4. **Database Layer**
- `database/migrations/2026_04_15_000000_add_oauth_columns_to_users_table.php` - Add role, oauth columns
- `database/migrations/2026_04_15_000001_create_leave_requests_table.php` - Leave requests table
- `database/migrations/2026_04_15_000002_create_leave_balances_table.php` - Leave balances table
- `database/seeders/UserSeeder.php` - Sample data seeder

### 5. **Routes**
- `routes/api.php` (Updated) - API routes dengan middleware

### 6. **Storage**
- `storage/app/public/leave-attachments/` - Direktori untuk file attachment

### 7. **Documentation**
- `README.md` (Comprehensive) - Dokumentasi lengkap proyek
- `Leave-Management-API.postman_collection.json` - Postman collection untuk testing

---

## 🚀 Quick Start Guide

### 1. Install Project
```bash
cd c:\laragon\www\magang
composer install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Database
Buka `.env` dan ubah:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=magang  # Sesuaikan nama database
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations
```bash
php artisan migrate
php artisan db:seed --class=UserSeeder
```

### 5. Link Storage
```bash
php artisan storage:link
```

### 6. Start Server
```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

---

## 🧪 Testing dengan Postman

### Import Collection
1. Buka Postman
2. Click `Import`
3. Pilih file: `Leave-Management-API.postman_collection.json`
4. Collection akan ter-import dengan semua endpoints

### Setup Variables
Di Postman, set variables:
- `base_url`: `http://localhost:8000`
- `token`: (akan diisi otomatis setelah login)

### Test Workflow

**1. Register Employee**
```
POST /api/auth/register
{
  "name": "Test Employee",
  "email": "test@example.com",
  "password": "password",
  "password_confirmation": "password",
  "role": "employee"
}
```

**2. Login**
```
POST /api/auth/login
{
  "email": "test@example.com",
  "password": "password"
}
```
Copy token dari response ke variable `token`

**3. Get Leave Balance**
```
GET /api/leave-balance
Headers: Authorization: Bearer {token}
```

**4. Create Leave Request**
```
POST /api/leave-requests
Body: 
  - start_date: 2026-05-01
  - end_date: 2026-05-05
  - reason: Liburan keluarga
  - attachment: (optional file)
Headers: Authorization: Bearer {token}
```

**5. Login as Admin**
```
POST /api/auth/login
{
  "email": "admin@example.com",
  "password": "password"
}
```

**6. Approve Leave Request**
```
POST /api/leave-requests/{id}/approve
Body: {"notes": "Disetujui"}
Headers: Authorization: Bearer {admin_token}
```

---

## 📊 Sample Data

Setelah seed, Anda akan memiliki:

### Admin User
- Email: `admin@example.com`
- Password: `password`
- Leave Quota: 12 days (2026)

### Employee Users (5 Users)
- Email: `employee1@example.com` - `employee5@example.com`
- Password: `password` (untuk semua)
- Leave Quota: 12 days (2026) untuk masing-masing

---

## 📚 API Endpoints Summary

### Authentication
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/auth/register` | Register user baru |
| POST | `/api/auth/login` | Login user |
| GET | `/api/auth/me` | Get current user |
| POST | `/api/auth/logout` | Logout user |
| POST | `/api/auth/refresh` | Refresh token |

### Leave Requests
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/leave-requests` | Get all/own leave requests |
| POST | `/api/leave-requests` | Create new leave request |
| GET | `/api/leave-requests/{id}` | Get specific request |
| POST | `/api/leave-requests/{id}/approve` | Approve request (admin) |
| POST | `/api/leave-requests/{id}/reject` | Reject request (admin) |

### Leave Balance
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/leave-balance` | Get leave balance |

---

## 🔐 Authorization Rules

### Employee
- ✅ Can register & login
- ✅ Can create leave request
- ✅ Can view own leave requests
- ✅ Can view own leave balance
- ❌ Cannot approve/reject requests
- ❌ Cannot view others' requests

### Admin
- ✅ Can login
- ✅ Can view ALL leave requests
- ✅ Can view ALL leave balances
- ✅ Can approve leave requests
- ✅ Can reject leave requests
- ❌ Cannot create leave requests

---

## 📝 Important Notes

### File Upload
- Format: PDF, DOC, DOCX, JPG, JPEG, PNG
- Max Size: 5MB
- Stored in: `storage/app/public/leave-attachments/`
- Access: `http://localhost:8000/storage/leave-attachments/{filename}`

### Database Transactions
- Operasi approve/reject menggunakan database transactions
- Memastikan consistency antara LeaveRequest dan LeaveBalance
- Jika ada error, semua perubahan akan di-rollback

### Date Validation
- Start date tidak boleh di masa lalu
- End date harus >= start_date
- Sistem otomatis menghitung days_requested

### Quota Management
- Initial quota: 12 days per tahun
- Quota hanya dikurangi saat request di-approve
- Jika request di-reject, quota tetap sama

---

## 🐛 Troubleshooting

### Database Connection Error
- Pastikan MySQL berjalan
- Cek credentials di `.env`
- Pastikan database sudah dibuat

### Storage Link Error
Jika error saat `php artisan storage:link`:
```bash
php artisan storage:link --force
```

### Migration Error
Jika ada error saat migrate, cek:
```bash
php artisan migrate:status
php artisan migrate:reset  # Reset semua (hati-hati!)
php artisan migrate
```

### Token Expiration
Token Sanctum tidak expired, tapi Anda bisa logout & login ulang untuk refresh token.

---

## 📦 Dependencies yang Digunakan

- **Laravel 10.x** - Web Framework
- **Laravel Sanctum** - API Authentication
- **Eloquent ORM** - Database
- **Form Request** - Validation
- **API Resources** - Response Formatting

Semua sudah ter-include di `composer.json`

---

## 🎯 Next Steps (Optional)

### Untuk Production:
1. Implement OAuth integration (Google/GitHub login)
2. Add email notifications
3. Create frontend application
4. Implement role-based access control (RBAC) dengan policies
5. Add audit logging
6. Create API documentation dengan Swagger/OpenAPI

### Untuk Testing:
1. Add unit tests
2. Add feature tests
3. Add API endpoint tests

---

## 📞 Support

Jika ada questions atau issues:
1. Baca file `README.md` untuk dokumentasi lengkap
2. Check API responses untuk error messages
3. Inspect database untuk data consistency

---

**Project Status**: ✅ Ready for Testing
**Last Updated**: April 15, 2026
**Framework**: Laravel 10.x
**Auth Method**: Laravel Sanctum (Token-based)
