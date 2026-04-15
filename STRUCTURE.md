# 🎯 Struktur Proyek & Penjelasan Clean Architecture

Dokumen ini menjelaskan struktur proyek dan bagaimana setiap komponen saling bekerja.

## 📐 Clean Architecture Overview

```
┌─────────────────────────────────────────────────────────┐
│                   Routes / HTTP Layer                    │
│          (Request → Controller → Response)               │
├─────────────────────────────────────────────────────────┤
│                   Business Logic Layer                   │
│              (Services, Actions, Validation)             │
├─────────────────────────────────────────────────────────┤
│                   Data Access Layer                      │
│          (Models, DTOs, Repositories Query)             │
├─────────────────────────────────────────────────────────┤
│                   Database Layer                         │
│           (Eloquent ORM, Migrations, Seeders)           │
└─────────────────────────────────────────────────────────┘
```

---

## 📁 Complete Project Structure

```
magang/
│
├── app/
│   │
│   ├── Enums/                          🎯 Type-safe Constants
│   │   ├── LeaveStatus.php              - pending, approved, rejected
│   │   └── UserRole.php                 - employee, admin
│   │
│   ├── Models/                          📊 Database Models
│   │   ├── User.php                     - User dengan roles & relationships
│   │   ├── LeaveRequest.php             - Leave request dengan status workflow
│   │   └── LeaveBalance.php             - Annual leave quota tracking
│   │
│   ├── Services/                        ⚙️  Business Logic Layer
│   │   └── LeaveRequestService.php      - Operasi: create, approve, reject
│   │
│   ├── DTO/                             📦 Data Transfer Objects
│   │   ├── CreateLeaveRequestDTO.php    - Transfer data untuk create request
│   │   ├── ApproveLeaveRequestDTO.php   - Transfer data untuk approval
│   │   └── RejectLeaveRequestDTO.php    - Transfer data untuk rejection
│   │
│   ├── Http/
│   │   │
│   │   ├── Controllers/Api/             🎮 HTTP Controllers
│   │   │   ├── AuthController.php       - Auth: register, login, logout
│   │   │   └── LeaveRequestController.php - CRUD operations
│   │   │
│   │   ├── Middleware/                  🛡️  Authorization Middleware
│   │   │   ├── IsAdmin.php              - Check if user is admin
│   │   │   └── IsEmployee.php           - Check if user is employee
│   │   │
│   │   ├── Requests/                    ✅ Form Request Validation
│   │   │   ├── StoreLeaveRequestRequest.php - Validate create request
│   │   │   ├── ApproveLeaveRequestRequest.php - Validate approval
│   │   │   └── RejectLeaveRequestRequest.php - Validate rejection
│   │   │
│   │   └── Resources/                   📋 API Response Formatting
│   │       ├── UserResource.php         - Format user response
│   │       ├── LeaveRequestResource.php - Format leave request response
│   │       └── LeaveBalanceResource.php - Format balance response
│   │
│   ├── Policies/                        🔐 Authorization Policies (Optional)
│   ├── Actions/                         🎬 Reusable Actions (Optional)
│   └── Repositories/                    🗄️  Data Access Pattern (Optional)
│
├── database/
│   │
│   ├── migrations/                      🔧 Schema Definition
│   │   ├── 2026_04_15_000000_add_oauth_columns_to_users_table.php
│   │   │   └── Add: role, oauth_id, oauth_provider
│   │   │
│   │   ├── 2026_04_15_000001_create_leave_requests_table.php
│   │   │   └── Table: id, user_id, start_date, end_date, status, etc
│   │   │
│   │   └── 2026_04_15_000002_create_leave_balances_table.php
│   │       └── Table: id, user_id, year, used_quota, remaining_quota
│   │
│   └── seeders/                         🌱 Sample Data
│       └── UserSeeder.php               - Create 1 admin + 5 employees
│
├── routes/
│   ├── api.php                          🛣️  API Routes dengan middleware
│   │   ├── POST   /api/auth/register
│   │   ├── POST   /api/auth/login
│   │   ├── GET    /api/auth/me
│   │   ├── POST   /api/auth/logout
│   │   ├── POST   /api/auth/refresh
│   │   ├── GET    /api/leave-requests
│   │   ├── POST   /api/leave-requests
│   │   ├── GET    /api/leave-requests/{id}
│   │   ├── POST   /api/leave-requests/{id}/approve
│   │   ├── POST   /api/leave-requests/{id}/reject
│   │   └── GET    /api/leave-balance
│   │
│   └── web.php                          (Web routes, not used for API)
│
├── storage/
│   └── app/
│       └── public/
│           └── leave-attachments/       📎 Uploaded Files Storage
│
├── config/
│   ├── app.php                          ⚙️  App Configuration
│   ├── database.php                     (Database Configuration)
│   ├── filesystems.php                  (File Storage Configuration)
│   └── ...
│
├── public/
│   └── index.php                        🌐 Entry Point
│
├── README.md                            📖 Comprehensive Documentation
├── SETUP_SUMMARY.md                     📋 Setup Summary & Quick Guide
├── STRUCTURE.md                         (This file)
├── .env.example                         💾 Environment Template
├── .env                                 💾 Environment (after setup)
│
├── composer.json                        📦 PHP Dependencies
├── composer.lock
│
├── Leave-Management-API.postman_collection.json 📨 Postman Collection
└── ...
```

---

## 🔄 How Components Interact

### 1️⃣ HTTP Request Flow

```
Client Request (POST /api/leave-requests)
    ↓
routes/api.php (Route definition)
    ↓
App\Http\Controllers\Api\LeaveRequestController
    ↓
App\Http\Requests\StoreLeaveRequestRequest (Validation)
    ↓
[Valid] → LeaveRequestService::create()
    ↓
App\DTO\CreateLeaveRequestDTO (Data Transfer)
    ↓
Database Transaction:
    - Check LeaveBalance quota
    - Create LeaveRequest record
    - Save attachment file
    ↓
LeaveRequest Model (Database interaction)
    ↓
App\Http\Resources\LeaveRequestResource (Format response)
    ↓
JSON Response to Client
```

### 2️⃣ Authentication Flow

```
User Login (POST /api/auth/login)
    ↓
AuthController::login()
    ↓
Validate credentials
    ↓
Create API Token (Sanctum)
    ↓
Return Token & User Data
    ↓
Client stores token
    ↓
Subsequent requests use Authorization header:
Bearer {token}
    ↓
Sanctum middleware verifies token
    ↓
Request continues if valid, 401 if invalid
```

### 3️⃣ Role-Based Authorization Flow

```
Request with token
    ↓
Sanctum auth middleware (auth:sanctum)
    ↓
Get authenticated user
    ↓
For protected endpoints:
    ├─ Employee endpoints:
    │   └─ Check: $request->user()->isEmployee()
    │
    └─ Admin endpoints:
        └─ Check: $request->user()->isAdmin()
    ↓
If authorized: Process request
If not: Return 403 Unauthorized
```

---

## 🎯 Key Components Explained

### 📊 Models (app/Models/)

#### User Model
```php
Relationships:
- hasMany('LeaveRequest')
- hasMany('LeaveBalance')
- hasMany('approvals') [as admin approver]

Methods:
- isAdmin() / isEmployee()
- leaveRequests()
- getCurrentYearBalance()
```

#### LeaveRequest Model
```php
Attributes:
- id, user_id, start_date, end_date
- reason, attachment_path, status
- approved_by, approval_notes, approved_at

Methods:
- getDaysRequestedAttribute()
- approve($admin_id, $notes)
- reject($admin_id, $notes)
- isPending() / isApproved() / isRejected()
- getAttachmentUrl()
```

#### LeaveBalance Model
```php
Attributes:
- id, user_id, year (PK: user_id + year)
- total_quota (default: 12)
- used_quota, remaining_quota

Methods:
- deductQuota($days) - decrease remaining
- restoreQuota($days) - increase remaining
```

### ⚙️ Services (app/Services/)

#### LeaveRequestService
```php
Public Methods:
- create(CreateLeaveRequestDTO): LeaveRequest
  * Validate quota
  * Create request
  * Save attachment
  
- approve(ApproveLeaveRequestDTO): LeaveRequest
  * Check if pending
  * Update status
  * Deduct quota
  
- reject(RejectLeaveRequestDTO): LeaveRequest
  * Check if pending
  * Update status
  * Keep quota unchanged
  
- getUserLeaveRequests(int, ?string): Collection
- getAllLeaveRequests(?string, ?int): Collection
- getOrCreateBalance(int, int): LeaveBalance
```

### 📦 DTOs (app/DTO/)

```php
CreateLeaveRequestDTO
- user_id, start_date, end_date
- reason, attachment_path
- Methods: getDaysRequested(), isValid()

ApproveLeaveRequestDTO
- leave_request_id, approved_by, notes

RejectLeaveRequestDTO
- leave_request_id, rejected_by, reason
```

### 🎮 Controllers (app/Http/Controllers/Api/)

#### AuthController
```php
Methods:
- register(Request): JsonResponse
- login(Request): JsonResponse
- me(Request): JsonResponse
- logout(Request): JsonResponse
- refresh(Request): JsonResponse
```

#### LeaveRequestController
```php
Methods:
- index(Request): Collection
- store(StoreLeaveRequestRequest): JsonResponse
- show(LeaveRequest, Request): JsonResponse
- approve(LeaveRequest, ApproveLeaveRequestRequest): JsonResponse
- reject(LeaveRequest, RejectLeaveRequestRequest): JsonResponse
- getBalance(Request): JsonResponse
```

### ✅ Form Requests (app/Http/Requests/)

```php
StoreLeaveRequestRequest
- Validasi: start_date, end_date, reason
- File validation: type, size
- Authorization: isEmployee()

ApproveLeaveRequestRequest
- Validasi: notes (optional)
- Authorization: isAdmin()

RejectLeaveRequestRequest
- Validasi: reason (required)
- Authorization: isAdmin()
```

### 📋 Resources (app/Http/Resources/)

Format model data untuk API response dengan:
- Selective fields
- Nested data (loaded relations)
- Computed properties
- Formatted dates

### 🎯 Enums (app/Enums/)

```php
UserRole: employee, admin
- Methods: label(), isAdmin(), isEmployee()

LeaveStatus: pending, approved, rejected
- Methods: label()
```

---

## 🗄️ Database Schema

### users table
```sql
id, name, email, email_verified_at, password
role (enum), oauth_id, oauth_provider
remember_token, created_at, updated_at
```

### leave_requests table
```sql
id, user_id (FK), start_date, end_date
reason, attachment_path, status (enum)
approved_by (FK), approval_notes, approved_at
deleted_at [soft delete], created_at, updated_at
```

### leave_balances table
```sql
id, user_id (FK), year (4-digit)
total_quota, used_quota, remaining_quota
UNIQUE(user_id, year)
created_at, updated_at
```

---

## 🔐 Security Implementation

### 1. Authentication
- **Sanctum**: Token-based API authentication
- **Passwords**: Hashed using bcrypt
- **Token Validation**: On every protected request

### 2. Authorization
- **Role Checking**: User role checked for sensitive operations
- **Middleware**: IsAdmin, IsEmployee middleware
- **Form Request**: Authorize checks in form requests

### 3. Validation
- **Form Request**: Laravel form request validation
- **Business Logic**: Service layer validation
- **Database**: Constraints & indexes

### 4. File Upload
- **Type Validation**: Only allowed file types
- **Size Validation**: 5MB max
- **Storage**: Private folder, served via URL

---

## 📈 Data Flow Examples

### Example 1: Create Leave Request

```
User Input:
{
  "start_date": "2026-05-01",
  "end_date": "2026-05-05",
  "reason": "Liburan",
  "attachment": <file>
}
    ↓
StoreLeaveRequestRequest::validate()
    ├─ Check dates valid
    ├─ Check dates not in past
    ├─ Check file format & size
    └─ Check user is employee
    ↓
LeaveRequestController::store()
    ├─ Save file → storage/leave-attachments/
    ├─ Create DTO
    └─ Call service
    ↓
LeaveRequestService::create(DTO)
    ├─ Check: isValid()
    ├─ Get quota: getOrCreateBalance()
    ├─ Check: remaining >= requested?
    ├─ Create: LeaveRequest record
    └─ Return model
    ↓
LeaveRequestResource::toArray()
    └─ Format for API response
    ↓
JSON Response:
{
  "message": "...",
  "data": {
    "id": 1,
    "status": "pending",
    "days_requested": 5,
    "attachment_url": "..."
  }
}
```

### Example 2: Approve Leave Request

```
Admin Action:
{
  "notes": "Approved"
}
    ↓
ApproveLeaveRequestRequest::validate()
    └─ Check user is admin
    ↓
LeaveRequestController::approve()
    ├─ Get leave request
    └─ Call service
    ↓
LeaveRequestService::approve(DTO)
    ├─ Check: isPending()
    ├─ Update status → "approved"
    ├─ Get balance
    ├─ deductQuota(5)
    │   ├─ used_quota += 5
    │   └─ remaining_quota -= 5
    └─ Return updated model
    ↓
Database Transaction (ACID):
- All changes or nothing (rollback on error)
    ↓
LeaveRequestResource::toArray()
    └─ Format response
    ↓
JSON Response:
{
  "message": "Approved",
  "data": {
    "status": "approved",
    "approved_by": 1,
    "approved_at": "2026-04-15..."
  }
}
```

---

## 🚀 How to Extend

### Add New Endpoint

1. **Create Request Class** (validation)
   ```php
   app/Http/Requests/NewActionRequest.php
   ```

2. **Add Controller Method**
   ```php
   app/Http/Controllers/Api/LeaveRequestController.php
   ```

3. **Define Route**
   ```php
   routes/api.php
   ```

4. **Add Resource** (if needed)
   ```php
   app/Http/Resources/NewResource.php
   ```

### Add New Business Logic

1. **Add Service Method**
   ```php
   app/Services/LeaveRequestService.php
   ```

2. **Create DTO** (if needed)
   ```php
   app/DTO/NewActionDTO.php
   ```

3. **Add Validation** in service

4. **Call from Controller**

### Add Role

1. **Update UserRole enum**
   ```php
   app/Enums/UserRole.php
   ```

2. **Update User model** with methods
   ```php
   app/Models/User.php
   ```

3. **Create Middleware** (optional)
   ```php
   app/Http/Middleware/IsRole.php
   ```

4. **Update Routes** with middleware

---

## 🔍 Testing Checklist

- [ ] Register user successfully
- [ ] Login with credentials
- [ ] Get current user info
- [ ] View leave balance
- [ ] Create leave request with file
- [ ] Get own leave requests
- [ ] Admin view all requests
- [ ] Admin approve request
- [ ] Admin reject request
- [ ] Quota updated after approval
- [ ] Unauthorized access blocked
- [ ] Validation errors returned

---

**Document Version**: 1.0
**Last Updated**: April 15, 2026
**Framework**: Laravel 10.x
