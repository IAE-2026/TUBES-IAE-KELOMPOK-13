# AI Prompting Log - Zidan Alianyah Shidiq (102022400220)

## Konteks
Mata Kuliah: BBK2HAB3 - Integrasi Aplikasi Enterprise
Service: JadwalDokter-Service
Tanggal: 19 Juni 2026

## Sesi 1: Integrasi ke DoctorScheduleController

### Prompt
"Tolong integrasikan SSO, SOAP, dan AMQP ke DoctorScheduleController"

### Solusi
Mengupdate `store()` method di controller dengan alur:
1. Login SSO → dapat token
2. Simpan booking ke database
3. Kirim SOAP Audit
4. Broadcast event ke RabbitMQ

### Hasil
Booking berhasil dengan response success, event muncul di dashboard RabbitMQ dosen ✅

---

## Sesi 2: Membuat AuditLog Model dan Migration

### Prompt
"AuditLog model belum ada, tolong buatkan"

### Solusi
Membuat `app/Models/AuditLog.php` dan migration `create_audit_logs_table` untuk menyimpan activity_name, receipt_number, log_data, dan success

### Hasil
AuditLog model dan migration berhasil dibuat ✅

---

## Sesi 3: Menyesuaikan Route API

### Prompt
"Route /doctor-schedules tidak sesuai kontrak kelompok, tolong ubah ke /schedules"

### Solusi
Mengupdate `routes/api.php` dari `/doctor-schedules` menjadi `/schedules`

### Hasil
Route berhasil diupdate sesuai kontrak kelompok ✅

---

## Sesi 4: Menyesuaikan Port Docker

### Prompt
"Port bentrok dengan service lain, tolong ubah"

### Solusi
Mengupdate `docker-compose.yml` dari port `8000` ke `8001` dan MySQL dari `3307` ke `3308`

### Hasil
Port berhasil diupdate, tidak bentrok dengan service lain ✅