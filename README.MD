# TUBES-IAE-KELOMPOK-13

---
 
## 👥 Anggota Kelompok
 
| Nama | NIM | Service |
|---|---|---|
| Widia Mesra Nainggolan Mahulae | 102022430029 | Data Pasien Service |
| Zidan Aliansyah | - | Jadwal Dokter Service |
| Rayula | 102022400102 | Farmasi & Obat Service |
 
---

## 📋 Proses Bisnis
 
**Pencatatan Rawat Jalan** adalah sistem terintegrasi yang mengelola alur pasien dari registrasi hingga pengambilan obat.

## 🔌 API Endpoints

### Data Pasien
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/v1/patients` | Ambil semua data pasien |
| GET | `/api/v1/patients/{id}` | Ambil data pasien by ID |
| POST | `/api/v1/patients` | Registrasi pasien baru |
 
### Jadwal Dokter
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/v1/schedules` | Tampilkan semua slot jadwal |
| GET | `/api/v1/schedules/{id}` | Detail slot tertentu |
| POST | `/api/v1/schedules` | Konfirmasi booking slot |
 
### Farmasi
| Method | Endpoint | Deskripsi |
|---|---|---|
| POST | `/api/v1/prescriptions` | Buat resep digital |
| GET | `/api/v1/prescriptions/{id}` | Ambil resep by ID |
 

