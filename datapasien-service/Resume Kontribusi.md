# Resume Kontribusi Individu
## Tugas Besar - Integrasi Aplikasi Enterprise (IAE)

---

### Identitas Mahasiswa
* **Nama Lengkap:** Widia Mesra Nainggolan Mahulae
* **NIM:** 102022430029
* **Service Utama:** Data Pasien Service (Service A)
* **Kelompok:** Kelompok 13 

---

## 1. Deskripsi Pekerjaan & Kontribusi

### A. Pengembangan Data Pasien Service (Service A)
Saya merancang dan mengimplementasikan **Data Pasien Service** yang dimana service ini bertanggung jawab untuk menyimpan dan mengelola data registrasi pasien baru serta menyebarkan notifikasi event ke service lain. 
* **Endpoints API yang Dikembangkan:**
  * GET /api/v1/patients: Mengambil seluruh daftar pasien di database
  * GET /api/v1/patients/{id}: Mengambil data detail pasien berdasarkan ID
  * POST /api/v1/patients: Mendaftarkan pasien baru

---

## 2. Daftar Log Commit (Kontribusi Individu)

Berdasarkan riwayat repositori Git, berikut adalah kontribusi nyata saya (total **23 commit**) yang dibagi berdasarkan kategori pengerjaan:

### A. Pengembangan Service Data Pasien (Service A)
* Melakukan inisialisasi service Data Pasien
* Menambahkan konfigurasi yang diperlukan untuk integrasi Single Sign-On (SSO)

### B. Integrasi Sistem
* Menyusun konfigurasi Docker Compose untuk menjalankan seluruh microservice dan database secara terintegrasi
* Mengonfigurasi Nginx sebagai API Gateway untuk mengatur alur antar service
* Membantu perbaikan Docker dan troubleshooting deployment pada service lain agar seluruh sistem dapat berjalan dengan baik

### C. Mengatur Repository
* Membersihkan file dan dependency yang tidak seharusnya terunggah ke repo
* Merapikan struktur repo dan file conf


### D. Dokumentasi Proyek
* Menggabungkan hasil pengembangan dari anggota kelompok dengan merge
* Menyelesaikan konflik kode (merge conflict)

### E. Dokumentasi & Administrasi Proyek
* Menyusun dan memperbarui dokumentasi proyek seperti README, log prompt, serta ringkasan kontribusi anggota kelompok
* Melakukan penyesuaian dan perbaikan dokumentasi dan kode

---

## 3. Kesimpulan Kontribusi
Selama pengerjaan Tugas Besar ini, saya berhasil mengimplementasikan **Service Data Pasien (Service A)** secara mandiri dan mengintegrasikannya dengan protokol enterprise seperti SSO, SOAP Audit Trail, dan RabbitMQ Broker (AMQP). Selain itu, kami juga berhasil menyusun Docker Compose yang terintegrasi, mengonfigurasi API Gateway menggunakan Nginx, serta mengelola proses merge untuk menggabungkan kode dari seluruh anggota kelompok.