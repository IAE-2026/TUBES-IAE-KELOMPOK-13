# AI Prompting Log — Service Farmasi & Obat

**Nama:** Rayula Samina  
**NIM:** 102022400102  
**Service:** Farmasi & Obat (Service C)  
**Tema:** E-Healthcare  
**Tools AI:** Claude AI
---

## Log Prompting

### 1. Integrasi Antar Service
**Prompt:**
 "Sebutkan gimana caranya membuat kodingan 3 service yang nantinya akan saling nyambung menyambung"

**Respons AI:**
AI menjelaskan cara menyambungkan 3 service menggunakan HTTP Request antar service dengan `id_pasien` dan `id_kunjungan` sebagai penghubung. Alurnya:
- Service A (Data Pasien) menghasilkan `id_pasien`
- Service B (Jadwal Dokter) menerima `id_pasien` dan menghasilkan `id_kunjungan`
- Service C (Farmasi) menerima `id_pasien` dan `id_kunjungan` saat dokter tulis resep

Saat POST /api/v1/prescriptions, request body wajib menyertakan `id_pasien` dan `id_kunjungan` yang berasal dari Service A dan B.

### 2. Analisis Proses Bisnis
**Prompt:**
> "Coba buat probis apa yang dapat menyambungkan ketiga service (Data Pasien, Jadwal Dokter, Farmasi & Obat) dan apa turunan endpoint dari tiap service tersebut?"

**Respons AI:**
AI memberikan gambaran proses bisnis "Pencatatan Rawat Jalan" yang menghubungkan ketiga service dengan alur: Pasien daftar → Pilih dokter → Konsultasi → Terima resep → Farmasi proses obat. Endpoint yang disarankan untuk Service Farmasi: POST /api/v1/prescriptions, GET /api/v1/prescriptions, GET /api/v1/prescriptions/{id}.

---

### 3. Setup Project Laravel
**Prompt:**
> "Tutorial kan untuk vscode nya, step awal install Laravel untuk service farmasi"

**Respons AI:**
AI memberikan panduan lengkap setup Laravel dari install composer, konfigurasi .env, generate key, hingga setup database MySQL.

---

### 4. Pembuatan Migration Database
**Prompt:**
> "Buatkan migration untuk tabel medicines, prescriptions, dan prescription_items sesuai ketentuan endpoint farmasi"

**Respons AI:**
AI memberikan struktur migration untuk 3 tabel dengan relasi foreignId antara prescriptions dan medicines melalui prescription_items.

---

### 5. Pembuatan Controller dan Route
**Prompt:**
> "Buatkan backend lengkap untuk service farmasi dengan bahasa indonesia untuk variabel nya, sesuai ketentuan: POST /api/v1/prescriptions, GET /api/v1/prescriptions, GET /api/v1/prescriptions/{id}"

**Respons AI:**
AI memberikan kode lengkap PrescriptionController dan MedicineController dengan response wrapper sesuai Standard Integration Contract IAE-T2.

---

### 6. Pembuatan Middleware API Key
**Prompt:**
> "Buatkan middleware CheckApiKey untuk validasi header X-IAE-KEY sesuai Standard Integration Contract IAE-T2"

**Respons AI:**
AI memberikan kode middleware yang memvalidasi header `X-IAE-KEY` dan mengembalikan response error 401 jika tidak valid.
Menggunakan middleware tersebut dan mendaftarkannya di `bootstrap/app.php` dengan alias `auth.apikey`.

---

### 7. Setup Docker
**Prompt:**
> "Buatkan Dockerfile dan docker-compose.yml untuk Laravel Service Farmasi dengan MySQL"

**Respons AI:**
AI memberikan konfigurasi Dockerfile dengan PHP 8.2 dan docker-compose.yml dengan service app dan MySQL.

---

### 8. Pembuatan Dokumentasi Swagger
**Prompt:**
> "Buatkan file swagger api-docs.json untuk dokumentasi endpoint service farmasi sesuai ketentuan IAE-T2"

**Respons AI:**
AI memberikan file `api-docs.json` lengkap dengan dokumentasi 3 endpoint resep menggunakan OpenAPI 3.0.0 dan security X-IAE-KEY.

---

### 9. Pembuatan Schema GraphQL
**Prompt:**
> "Buatkan schema GraphQL untuk service farmasi dengan query resep dan obat"

**Respons AI:**
AI memberikan schema GraphQL dengan type Obat, ItemResep, Resep dan Query untuk mengambil data.
Menggunakan schema GraphQL yang disarankan dan menyimpannya di folder `graphql/schema.graphql`.
