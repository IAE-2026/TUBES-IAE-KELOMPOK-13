# Service A - Data Pasien (e-Healthcare)

Service Laravel untuk Tugas 2 mata kuliah BBK2HAB3 - Integrasi Aplikasi Enterprise. Service ini mencatat data pasien rawat jalan pada sistem e-Healthcare, mencakup registrasi pasien baru dan verifikasi pasien yang sudah melakukan booking. Repository ini berisi hanya Service A dari ekosistem e-Healthcare.

## Identitas

| Parameter | Nilai |
|-----------|-------|
| Mata Kuliah | BBK2HAB3 - Integrasi Aplikasi Enterprise |
| Nama | Widia Mesra Nainggolan Mahulae |
| NIM / X-IAE-KEY | 102022430029 |

---

## Endpoint REST

Semua endpoint wajib menyertakan header:
X-IAE-KEY: 102022430029
Content-Type: application/json

| Method | Path | Fungsi |
|--------|------|--------|
| GET | /api/v1/patients | Mengambil daftar seluruh pasien terdaftar |
| GET | /api/v1/patients/{id} | Verifikasi data pasien yang sudah booking |
| POST | /api/v1/patients | Registrasi pasien baru yang belum terdaftar |

Format respon mengikuti Standard Integration Contract (IAE-T2).

**Contoh body POST /api/v1/patients**
```json
{
    "name": "Widia Mesra Nainggolan Mahulae",
    "nik": "102022430029",
    "phone": "082276162672",
    "birth_date": "2007-01-13",
    "address": "Bandung",
    "allergies": "Milk"
}
```

---

## Dokumentasi API & GraphQL

| Halaman | URL |
|---------|-----|
| Swagger UI | http://localhost:8000/api/datapatient |
| GraphQL Playground | http://localhost:8000/graphql-playground |

**Contoh query GraphQL:**
```graphql
{
    patients {
        id
        name
        nik
        phone
        birth_date
        address
        allergies
    }
}
```

---

## Menjalankan dengan Docker (Direkomendasikan)

Pastikan Docker Desktop sudah aktif.

```bash
docker compose up -d --build
```

Setelah container berjalan, akses:
- http://localhost:8000/api/v1/patients
- http://localhost:8000/api/datapatient
- http://localhost:8000/graphql-playground

Stack Docker terdiri dari dua container:

| Container | Image | Port host |
|-----------|-------|-----------|
| datapatient-service-app | Build dari Dockerfile | 8000 |
| datapatient-service-db | mysql:8.0 | 3307 |

---

## Integrasi dengan Service Lain

Service A dirancang untuk berkomunikasi dengan:
- **Service Jadwal Dokter** → menyediakan data pasien untuk proses booking dan penjadwalan
- **Service Farmasi & Obat** → menyediakan data pasien termasuk informasi alergi untuk persiapan resep

Jika service lain belum berjalan, Service A tetap dapat berfungsi penuh secara standalone untuk keperluan penilaian REST dan GraphQL.