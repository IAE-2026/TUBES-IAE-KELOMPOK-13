# Analisis Individu Tugas 3 - Integrasi Aplikasi Enterprise
**Nama:** Widia Mesra Nainggolan Mahulae  
**NIM:** 102022430029  
**Service:** Data Pasien Service  

---

## 1. Transaksi Kritis

Dalam sistem E-Healthcare, layanan *Data Pasien Service* memiliki beberapa endpoint, yaitu:
1. GET /api/v1/patients : Digunakan untuk mengambil semua data pasien yang telah terdaftar di dalam sistem.
2. GET /api/v1/patients/{id} : Digunakan untuk mengambil detail data spesifik dari pasien yang sudah melakukan booking.
3. POST /api/v1/patients : Digunakan untuk mendaftarkan data pasien baru yang pertama kali datang dan belum pernah terdaftar sebelumnya.

dari ketiga endpoint di atas, endpoint **POST /api/v1/patients (Registrasi Pasien Baru)** dipilih sebagai transaksi kritis utama dalam analisis ini.

---

### 2. Alasan

Adapun alasannya adalah karena sifatnya yang *state-changing*, yaitu sebuah proses bisnis yang secara permanen mengubah status data di dalam sistem, membuat entitas baru, dan bersifat tidak dapat dikembalikan. Pada saat data identitas pasien pertama kali masuk dan terdaftar di dalam database lokal *Data Pasien Service*, data tersebut yang akan menentukan jalannya proses di semua endpoint maupun layanan lain setelahnya, seperti *Service Jadwal Dokter* (untuk proses *booking* konsultasi) dan *Service Farmasi & Obat* (untuk penulisan resep digital oleh dokter). Akibatnya, jika terjadi kegagalan pada endpoint ini, akan terjadi kesalahan data yang merusak proses pekerjaan seluruh bagian rumah sakit.

Lalu jika dianalisis lebih lanjut, transaksi ini berfungsi untuk menyatukan sistem yang sebelumnya terpisah atau memiliki *Information Silo*. Pada perusahaan berskala besar dan kompleks seperti *Enterprise*, setiap departemen seringkali mengembangkan sistemnya sendiri sendiri tanpa memikirkan integrasi lebih lanjut. Jadi melalui integrasi data pada transaksi `POST /api/v1/patients` ini, batasan antar sistem dapat dihilangkan sehingga data pasien bisa berjalan tanpa terhambat batasan antar departemen.

Lalu alasan mengapa transaksi ini dinilai penting dan wajib menggunakan SOAP adalah karena data yang dicatat dalam pendaftaran ini sangat sensitif, seperti NIK, nama lengkap, dan riwayat alergi pasien. Sistem wajib menjamin keamanan dan kepatuhan hukum melalui sistem Audit Legacy sehingga setiap kali ada pasien baru yang mendaftar, riwayat aktivitas akan otomatis dikirim ke server audit pusat untuk mendapatkan nomor receipt sebagai bukti hukum yang sah.

Lalu alasan mengapa transaksi ini harus disebarkan menggunakan RabbitMQ adalah karena pendaftaran pasien merupakan trigger atau pemicu yang harus diketahui oleh service lain dengan cepat. Sehingga dengan AMQP Message Broker (RabbitMQ), *Data Pasien Service* dapat menyebarkan notifikasi pemberitahuan secara secara mandiri tanpa harus menunggu respon dari sistem lain. Jadi jika salah satu layanan di departemen lain mengalami gangguan (*down*), proses utama pendaftaran pasien di bagian depan tidak akan ikut macet dan tetap bisa berjalan sendiri.



