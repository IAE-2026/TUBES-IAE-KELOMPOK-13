# Resume Kontribusi Tim - TUBES-IAE-KELOMPOK-13
**Proses Bisnis:** Pencatatan Rawat Jalan 

---

## 1. Widia Mesra Nainggolan Mahulae (widiamsr)
**Service:** Data Pasien  
**Total Commit:** 12 commit

### Kontribusi Utama:
- inisialisasi service-a data pasien kelompok 13 : Setup awal project Laravel untuk service data pasien
- Inisialisasi service a data pasien : Penambahan struktur awal endpoint REST API pasien
- tambah field SSO_M2M_NIM pada env example datapasien-service : Penyesuaian konfigurasi SSO M2M dengan penambahan NIM sesuai requirement dosen
- hapus folder vendor yang tidak sengaja ter-commit : Cleanup repository dari file yang tidak perlu
- setup Docker, API Gateway Nginx, dan fix routing semua service : Implementasi utama integrasi sistem, yang terdiri dari:
  - Membuat `docker-compose.yml` root untuk menggabungkan semua service
  - Konfigurasi Nginx sebagai API Gateway
  - Perbaikan Dockerfile semua service (fix vendor/autoload issue)
  - Fix routing jadwaldokter-service sesuai kontrak kelompok

### Peran dalam Integrasi:
Widia berperan sebagai integrator utama yang mengerjakan penggabungan seluruh service ke dalam satu sistem Docker, konfigurasi API Gateway Nginx, serta menyelesaikan berbagai conflict dan bug yang muncul selama proses integrasi.

## 2. Zidan Aliansyah Shidiq (zidanaliansyah11-tech)
**Service:** Jadwal Dokter  
**Total Commit:** 4 commit

### Kontribusi Utama:
- tambah service jadwal dokter : Implementasi service jadwal dokter dengan endpoint untuk melihat dan booking slot dokter
- add docker-compose for jadwaldokter-service : Penambahan konfigurasi Docker untuk service jadwal dokter
- add docker for jadwaldokter-service : Penambahan Dockerfile untuk containerisasi service
- add missing project files after move to C drive : Perbaikan file project yang hilang saat perpindahan environment

### Peran dalam Integrasi:
Zidan bertanggung jawab atas service penjadwalan dokter yang menangani alur booking slot konsultasi, service  yang menghubungkan proses registrasi pasien dengan pembuatan resep.

---

## 3. Rayula Samina  (rrayulaaa)
**Service:** Farmasi & Obat  
**Total Commit:** 2 commit

### Kontribusi Utama:
- Create service-farmasi : Inisialisasi repository service farmasi dengan struktur Laravel lengkap beserta Dockerfile dan docker-compose
- inisialisasi service farmasi : Implementasi endpoint resep digital (POST, GET prescriptions) dengan autentikasi Bearer Token

### Peran dalam Integrasi:
Rayula mengerjakan service farmasi sebagai endpoint terakhir dalam alur bisnis rawat jalan yang menangani penulisan dan pengambilan resep digital setelah pemeriksaan dokter selesai.

---

## Statistik Commit per Anggota
 
| Anggota | Jumlah Commit | Service |
|---|---|---|
| widiamsr | 12 | Data Pasien + Integrator |
| zidanaliansyah11-tech | 4 | Jadwal Dokter |
| rrayulaaa | 2 | Farmasi |
| **Total** | **21** | **3 Services** |