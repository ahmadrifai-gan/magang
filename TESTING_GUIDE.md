# 🚀 Quick Start Guide - Testing Dashboard & API

**Updated**: April 15, 2026  
**Status**: ✅ Ready to Test

---

## 📋 Table of Contents

1. [Setup & Prerequisites](#setup--prerequisites)
2. [Step-by-Step Testing](#step-by-step-testing)
3. [Testing Employee Flow](#testing-employee-flow)
4. [Testing Admin Flow](#testing-admin-flow)
5. [API Testing](#api-testing-with-curl--postman)
6. [Troubleshooting](#troubleshooting)

---

## 🔧 Setup & Prerequisites

### Required
- [ ] PHP 8.1+
- [ ] MySQL/Database running
- [ ] Laravel 10.x installed
- [ ] Composer installed
- [ ] Node.js & npm installed

### Environment Setup

```bash
# 1. Copy environment file
cp .env.example .env

# 2. Generate app key
php artisan key:generate

# 3. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=magang
DB_USERNAME=root
DB_PASSWORD=

# 4. Run migrations
php artisan migrate

# 5. (Optional) Seed demo data
php artisan db:seed --class=UserSeeder

# 6. Install frontend dependencies
npm install && npm run dev

# 7. Start Laravel server
php artisan serve
# Or use Laragon/Docker
```

---

## 🧪 Step-by-Step Testing

### 1. First Time Access - Home Page

**URL**: `http://localhost:8000` (or your domain)

**Expected**:
- ✅ Home page displays
- ✅ Navigation bar visible
- ✅ Hero section with features
- ✅ Call-to-action buttons (Login/Register)

**Buttons to Test**:
- [ ] "Masuk Sekarang" → Redirects to `/login`
- [ ] "Daftar Sekarang" → Redirects to `/register`

---

### 2. Register New Account

**URL**: `http://localhost:8000/register`

**Test Data**:
```
Nama: John Doe
Email: john.doe@example.com
Password: SecurePassword123
Konfirmasi: SecurePassword123
Role: Employee (dropdown)
```

**Expected**:
- ✅ Form displays with all fields
- ✅ Email validation works
- ✅ Password match validation
- ✅ Role dropdown has options
- ✅ After submit:
  - User created in database
  - Auto-login succeeds
  - Redirected to `/dashboard`
  - Session created

**Form Validations to Test**:
- [ ] Empty name → "Nama harus diisi"
- [ ] Invalid email → "Format email tidak valid"
- [ ] Duplicate email → "Email sudah terdaftar"
- [ ] Password < 8 chars → Error message
- [ ] Password mismatch → Validation error
- [ ] No role selected → "Role harus dipilih"

---

### 3. Employee Dashboard (First Login)

**After Registration, you should see**:

#### Header
- ✅ "Selamat Datang, John Doe!"
- ✅ Current date & time

#### Sidebar Menu
```
📌 MENU UTAMA
├── 🏠 Dashboard (highlighted)
├── 📄 Pengajuan Saya
├── ➕ Ajukan Cuti
└── 📅 Sisa Cuti
```

#### Stat Cards (3 columns)
1. **Sisa Cuti Tahun Ini**: Should show 12 hari (auto-created)
2. **Pengajuan Pending**: 0
3. **Disetujui**: 0

#### Main Content
- **Ajukan Cuti Baru** form (left column)
  - Tanggal Mulai (date picker)
  - Tanggal Akhir (date picker)
  - Alasan Cuti (textarea)
  - Lampiran (file upload)
  - "Ajukan Cuti" button

- **Informasi Cuti** card (right column)
  - Progress bar showing 12/12 hari
  - Tersedia: 12 hari
  - Terpakai: 0 hari
  - Tips section

#### Table
- **Pengajuan Cuti Saya** (empty - no requests yet)
- Refresh button

---

### 4. Test Leave Request Creation

**In the "Ajukan Cuti Baru" form, fill**:
```
Tanggal Mulai: 2026-05-15
Tanggal Akhir: 2026-05-17
Alasan Cuti: Liburan keluarga
Lampiran: (optional - can skip)
```

**Click**: "Ajukan Cuti"

**Expected**:
- ✅ Loading spinner appears
- ✅ Form submits successfully
- ✅ Toast notification: "Pengajuan cuti berhasil dibuat!"
- ✅ Form resets to empty
- ✅ Table "Pengajuan Cuti Saya" updates:
  - Shows the new request
  - Status: **pending** (orange badge)
  - Created date shown
  - Period: 15-17 May 2026 (3 hari kerja)

**After Creation**:
- [ ] Stats update: Pengajuan Pending = 1
- [ ] Progress bar still shows 12 hari (not yet approved)
- [ ] Refresh button works
- [ ] Can create another request

---

### 5. Create Second Request (for Admin Testing)

**Repeat step 4** with different dates to have data for admin testing:
```
Tanggal Mulai: 2026-06-01
Tanggal Akhir: 2026-06-05
Alasan Cuti: Pemeriksaan kesehatan
```

Now you have:
- 1 pending request (from step 4)
- 1 pending request (from this step)

---

### 6. Logout & Test Admin Account

**Click User Avatar** → Dropdown → **Logout**

**Login with Admin**:
```
URL: http://localhost:8000/login
Email: admin@example.com
Password: password
```

Or use different admin account:
```
Email: admin@magang.local
Password: password
```

**Expected**:
- ✅ Logged in as admin
- ✅ Redirected to `/dashboard`
- ✅ Dashboard loads admin version

---

### 7. Admin Dashboard Overview

#### Sidebar Menu (Different from Employee)
```
📌 DASHBOARD ADMIN
├── 🏠 Dashboard
├── ⏳ Persetujuan Pending (badge showing count)
└── 📋 Semua Pengajuan

📌 MANAJEMEN
├── 👥 Daftar Karyawan
└── 📊 Laporan (Coming Soon)
```

#### Stat Cards (4 columns)
1. **Pending Approval**: 2 (requests created from employee)
2. **Disetujui Hari Ini**: 0
3. **Ditolak**: 0
4. **Total Karyawan**: 1+ (depends on seeding)

#### Main Content

**Pengajuan Menunggu Persetujuan** (Highest Priority)
- Shows John Doe's 2 pending requests
- Columns:
  - Karyawan: John Doe
  - Periode: 15-17 May & 01-05 June
  - Alasan: Liburan keluarga & Pemeriksaan kesehatan
  - Tanggal Pengajuan: Today
  - Aksi: 3 buttons
    - ✅ Green (Approve)
    - ❌ Red (Reject)
    - 👁️ Blue (View Detail)

#### Recent Requests Table
- Shows last 10 requests
- Columns: Karyawan, Periode, Status, Tanggal
- Color-coded status badges

#### Statistics Card
- Disetujui: 0
- Pending: 2
- Ditolak: 0

---

### 8. Test Approve Request

**In the "Pengajuan Menunggu Persetujuan" table**:

**Find the first request** → Click **✅ Approve** button

**Expected**:
- ✅ Loading spinner appears
- ✅ Button gets disabled
- ✅ Toast: "Pengajuan cuti disetujui!"
- ✅ Table updates:
  - Request disappears from pending
  - Counter updates: Pending = 1
- ✅ "Riwayat Pengajuan Terbaru" shows status = **approved**
- ✅ Stats update: Disetujui = 1, Pending = 1

---

### 9. Test Reject Request

**In the "Pengajuan Menunggu Persetujuan" table**:

**Find the second request** → Click **❌ Reject** button

**Expected**:
- ✅ Prompt dialog: "Alasan penolakan:"
- ✅ Enter reason: "Tanggal tidak sesuai"
- ✅ Loading spinner
- ✅ Toast: "Pengajuan cuti ditolak!"
- ✅ Request disappears from pending
- ✅ Stats update:
  - Pending = 0
  - Ditolak = 1
- ✅ "Riwayat Pengajuan Terbaru" shows status = **rejected**

---

### 10. Switch Back to Employee to See Result

**Logout from Admin** → **Login as Employee (John)**

**Expected in Employee Dashboard**:
- ✅ First request shows status = **approved** (green badge)
- ✅ Second request shows status = **rejected** (red badge)
- ✅ Stat cards:
  - Pengajuan Pending = 0
  - Disetujui = 1
- ✅ Progress bar updates (if approved requests affect balance)
- ✅ Can create new requests

---

## 🎯 Testing Employee Flow

### Complete Employee Journey

1. **Register** (if not exists)
   - Email: employee1@example.com
   - Password: password
   - Role: Employee

2. **View Dashboard**
   - [ ] See leave balance (12 hari)
   - [ ] See 0 pending & 0 approved

3. **Create Request**
   - [ ] Fill form
   - [ ] Submit successfully
   - [ ] See in table with pending status

4. **View Request History**
   - [ ] Click refresh button
   - [ ] Table updates with new request

5. **Logout** & **Re-login**
   - [ ] Data persists
   - [ ] All stats unchanged

### Employee Features to Verify
- [ ] Leave balance accuracy
- [ ] Form validation (client-side)
- [ ] File upload (optional)
- [ ] Toast notifications
- [ ] Table sorting/filtering (if implemented)
- [ ] Responsive on mobile
- [ ] Auto-refresh functionality
- [ ] Data persists on logout/login

---

## 🔐 Testing Admin Flow

### Complete Admin Journey

1. **Login as Admin**
   - Email: admin@example.com
   - Password: password

2. **View Dashboard**
   - [ ] See pending requests count
   - [ ] See all stats

3. **Approve Request**
   - [ ] Click approve
   - [ ] Choose one from pending table
   - [ ] See confirmation toast
   - [ ] Stats update
   - [ ] Request moves to approved

4. **Reject Request**
   - [ ] Click reject
   - [ ] Enter reason
   - [ ] See confirmation toast
   - [ ] Stats update
   - [ ] Request moves to rejected

5. **View Recent History**
   - [ ] Recent table shows all statuses
   - [ ] Color-coded badges

### Admin Features to Verify
- [ ] Correct permissions (only see all requests)
- [ ] Approval workflow
- [ ] Rejection with reason
- [ ] Statistics accuracy
- [ ] Notification badge
- [ ] Table auto-refresh
- [ ] Responsive design
- [ ] Role-based menu visibility

---

## 🔗 API Testing with cURL & Postman

### Prerequisites for API Testing
1. Get auth token first
2. Store token in variable
3. Use for all protected endpoints

---

### 1. Register User (Open API)

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "employee"
  }'
```

**Expected Response** (200):
```json
{
  "message": "Registration successful",
  "data": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com",
    "role": "employee",
    "created_at": "2026-04-15T10:00:00.000000Z"
  },
  "token": "token_here"
}
```

---

### 2. Login (Get Token)

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

**Expected Response** (200):
```json
{
  "message": "Login successful",
  "data": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com",
    "role": "employee"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

**Save token**:
```bash
export TOKEN="eyJ0eXAiOiJKV1QiLCJhbGc..."
```

---

### 3. Get Leave Balance

```bash
curl -X GET http://localhost:8000/api/leave-balance \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

**Expected** (200):
```json
{
  "data": {
    "id": 1,
    "user_id": 1,
    "total_days": 12,
    "used_days": 0,
    "year": 2026
  }
}
```

---

### 4. Create Leave Request

```bash
curl -X POST http://localhost:8000/api/leave-requests \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "start_date": "2026-05-15",
    "end_date": "2026-05-17",
    "reason": "Liburan keluarga"
  }'
```

**Expected** (201):
```json
{
  "message": "Pengajuan cuti berhasil dibuat",
  "data": {
    "id": 1,
    "user_id": 1,
    "start_date": "2026-05-15",
    "end_date": "2026-05-17",
    "reason": "Liburan keluarga",
    "status": "pending",
    "created_at": "2026-04-15T10:00:00Z"
  }
}
```

---

### 5. Get Leave Requests (Employee)

```bash
curl -X GET http://localhost:8000/api/leave-requests \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

**Expected** (200) - Employee sees only own requests:
```json
{
  "data": [
    {
      "id": 1,
      "user": {
        "id": 1,
        "name": "Test User",
        "email": "test@example.com"
      },
      "start_date": "2026-05-15",
      "end_date": "2026-05-17",
      "reason": "Liburan keluarga",
      "status": "pending",
      "created_at": "2026-04-15T10:00:00Z"
    }
  ]
}
```

---

### 6. Get All Requests (Admin Only)

```bash
curl -X GET http://localhost:8000/api/leave-requests \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Accept: application/json"
```

**Expected** (200) - Admin sees all requests:
```json
{
  "data": [
    {
      "id": 1,
      "user": { ... },
      "status": "pending"
    },
    {
      "id": 2,
      "user": { ... },
      "status": "approved"
    }
  ]
}
```

---

### 7. Approve Request (Admin Only)

```bash
curl -X POST http://localhost:8000/api/leave-requests/1/approve \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "notes": "Disetujui"
  }'
```

**Expected** (200):
```json
{
  "message": "Pengajuan cuti berhasil disetujui",
  "data": {
    "id": 1,
    "status": "approved",
    "approved_at": "2026-04-15T10:05:00Z",
    "approved_by": 2
  }
}
```

---

### 8. Reject Request (Admin Only)

```bash
curl -X POST http://localhost:8000/api/leave-requests/1/reject \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "reason": "Tanggal tidak sesuai"
  }'
```

**Expected** (200):
```json
{
  "message": "Pengajuan cuti berhasil ditolak",
  "data": {
    "id": 1,
    "status": "rejected",
    "rejected_reason": "Tanggal tidak sesuai"
  }
}
```

---

## 🚨 Troubleshooting

### Issue: Dashboard shows loading forever

**Solution**:
1. Check browser console (F12 → Console)
2. Look for error messages
3. Verify token exists: `localStorage.getItem('api_token')`
4. Check Network tab → see if API calls are made
5. Verify API response status (should be 200)

---

### Issue: Leave balance shows 0 or -

**Solution**:
1. Verify user has LeaveBalance record
2. Check database: `SELECT * FROM leave_balances WHERE user_id = 1`
3. If missing, manually create:
   ```sql
   INSERT INTO leave_balances (user_id, total_days, used_days, year, created_at, updated_at)
   VALUES (1, 12, 0, 2026, NOW(), NOW());
   ```

---

### Issue: Can't create request - form won't submit

**Solution**:
1. Check all fields are filled
2. Verify dates are valid (start <= end)
3. Check browser console for JavaScript errors
4. Verify API token is valid
5. Check API returns 201 status

---

### Issue: Approve/Reject buttons not working

**Solution**:
1. Verify user is admin
2. Check button click in console
3. Verify request ID exists
4. Check API response for errors
5. Try refreshing page

---

### Issue: Login redirects in circle

**Solution**:
1. Check CSRF token is valid
2. Verify session configuration
3. Clear browser cookies
4. Try different browser
5. Check `.env` settings

---

### Issue: File upload not working

**Solution**:
1. Verify file size < 5MB
2. Check file type is allowed (pdf, doc, docx, jpg, png)
3. Verify storage permissions
4. Check API response for upload errors
5. Verify storage link exists: `php artisan storage:link`

---

## ✅ Final Verification Checklist

- [ ] Home page loads
- [ ] Register works
- [ ] Login works
- [ ] Dashboard with sidebar displays
- [ ] Employee can create request
- [ ] Admin can approve/reject
- [ ] Notifications work
- [ ] Data persists
- [ ] API returns valid responses
- [ ] Responsive on mobile
- [ ] No console errors

---

**Ready to Test!** 🎉

Start with `php artisan serve` and visit `http://localhost:8000`
