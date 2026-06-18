# AI Prompting Log

Nama: Rayula Samina  
NIM: 102022400102  
Service: Farmasi & Obat (Service C)  
Tema: E-Healthcare  
Tools AI: Claude AI & ChatGPT
Farmasi & Obat Service – Integrasi SSO, SOAP Audit, RabbitMQ, dan Docker



1. Konfigurasi Docker dan Environment

### Prompt

Tolong bantu menjalankan aplikasi Laravel menggunakan Docker dan menghubungkannya dengan MySQL dalam container.

### Jawaban AI

Membuat konfigurasi `docker-compose.yml` dengan service Laravel dan MySQL.
Memperbaiki file `.env`.
Menjalankan:

bash
php artisan key:generate
php artisan optimize:clear


Menyesuaikan konfigurasi database:

env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root

2. Migrasi Database

### Prompt

Tolong perbaiki error migrasi dan pastikan tabel database berhasil dibuat.

### Jawaban AI

Menjalankan:
bash
php artisan migrate


Berhasil membuat tabel:

users
roles
medicines
prescriptions
prescription_items
cache
jobs

3. Konfigurasi SSO dan API Key

### Prompt

Buatkan .env dengan konfigurasi SSO dibawah ini:

TEAM-13
KEY-MHS-176


### Jawaban AI

Menambahkan konfigurasi:
env
TEAM_ID=TEAM-13
SSO_API_KEY=KEY-MHS-176

SSO_JWKS_URL=https://iae-sso.virtualfri.id/.well-known/jwks.json
SOAP_AUDIT_URL=https://iae-sso.virtualfri.id/soap/v1/audit

RABBITMQ_HOST=iae-sso.virtualfri.id
RABBITMQ_PORT=5672
RABBITMQ_USER=guest
RABBITMQ_PASSWORD=guest


4. Analisis Error SSO

### Prompt
Bantu saya menangani error:

    json
{
  "message": "Unauthorized - Missing Bearer Token"
}
    json
{
  "message": "Unauthorized - Invalid or expired token",
  "errors": "Wrong number of segments"
}

### Jawaban AI

Middleware menggunakan:

    php
$request->bearerToken()
JWT::decode()

Menjelaskan bahwa API Key bukan JWT Token.
JWT harus memiliki format:

    text
xxxxx.yyyyy.zzzzz

5. Analisis Middleware Federated SSO

### Prompt

Membantu memperbaiki mapping role pada middleware SSO.

### Jawaban AI

Membuat mapping role:

    php
admin -> admin_farmasi
dokter -> apoteker
staff -> apoteker
apoteker -> apoteker
lainnya -> pasien

Agar role dari SSO dapat disesuaikan dengan role lokal aplikasi.

6. Debugging JWT dan Log Laravel

### Prompt

Cara menganalisis error autentikasi melalui log Laravel.

### Jawaban AI

Monitoring log menggunakan:

    bash
tail -f storage/logs/laravel.log


Menemukan error:

    text
Expired token
Wrong number of segments
Syntax error, malformed JSON

dan menjelaskan penyebab masing-masing error.


7. Sinkronisasi Database Medicine

### Prompt

Cara mengatasi error saat menambahkan data obat menggunakan Tinker.

### Jawaban AI

Melakukan pengecekan struktur tabel:

    php
Schema::getColumnListing('medicines');


Menemukan bahwa kolom database menggunakan:

    php
stock


bukan:

    php
stok


Memperbaiki model dan data insert.

8. Menambahkan Data Obat

### Prompt

Menambahkan data obat untuk kebutuhan pengujian endpoint Prescription.

### Jawaban AI

Menjalankan:

App\Models\Medicine::create([
    'nama' => 'Paracetamol',
    'kategori' => 'Tablet',
    'stock' => 100,
    'harga' => 5000,
    'deskripsi' => 'Obat penurun demam',
    'satuan' => 'Strip'
]);

Data berhasil tersimpan ke database.


9. Analisis Endpoint Prescription

### Prompt

Memastikan body request POST Prescription sesuai dengan validasi controller.

### Jawaban AI

Memvalidasi request:

```json
{
  "id_pasien": 1,
  "id_kunjungan": 1001,
  "nama_dokter": "Dr. Andi",
  "items": [
    {
      "id_obat": 2,
      "jumlah": 2,
      "dosis": "3x1 sehari"
    }
  ]
}

10. Pengujian API Menggunakan Postman

### Prompt
Membuat panduan pengujian endpoint menggunakan Postman.

### Jawaban AI
Endpoint yang diuji:
- GET /api/v1/medicines
- GET /api/v1/prescriptions
- POST /api/v1/prescriptions

Konfigurasi Authorization:
- Type: Bearer Token
- Token: JWT dari SSO

Menjelaskan bahwa API Key tidak dapat digunakan sebagai Bearer Token.

11. Integrasi Akun Warga dan Pengujian Otomatis Terintegrasi (SOAP & RabbitMQ)

### Prompt
Tolong hubungkan sistem dengan akun Warga: warga25@ktp.iae.id & API-KEY: KEY-MHS-176 (simpan api key dalam .env). Buatkan pengujian otomatis untuk memvalidasi alur pemanggilan token M2M, SOAP audit sending, RabbitMQ event publishing, dan E2E pembuatan resep terotentikasi.

### Jawaban AI
1. Memperbarui file `.env` dengan kredensial warga25, `TEAM_ID=TEAM-25`, `SSO_API_KEY=KEY-MHS-176`, dan endpoint penerbitan RabbitMQ.
2. Memperbaiki logic fallback env pada `SoapAuditService.php` agar lebih fleksibel saat membaca M2M API key.
3. Membuat file unit & integration test otomatis di `tests/Feature/IntegrationTest.php` yang mensimulasikan login warga25, mengambil JWT token, memvalidasi SOAP, dan RabbitMQ.
4. Menjalankan pengujian tersebut menggunakan PHPUnit di mana seluruh asersi berhasil lulus (4 pass).

Kesimpulan

AI digunakan sebagai asisten teknis selama proses pengembangan Farmasi & Obat Service untuk membantu konfigurasi Docker, database, integrasi SSO, debugging Laravel, pengujian API, serta pembuatan pengujian integrasi otomatis. Penggunaan AI mempercepat identifikasi masalah dan meningkatkan efisiensi pengembangan aplikasi.
