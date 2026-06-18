Analisis Tugas 3 - Farmasi dan Obat

1. Identifikasi Service Farmasi dan Obat

Service Farmasi dan Obat bertanggung jawab untuk mengelola data obat, stok obat, serta proses distribusi obat kepada pasien. Service ini menjadi bagian penting dalam sistem e-Healthcare karena berkaitan langsung dengan ketersediaan obat dan pelayanan kesehatan.

Fitur utama yang dimiliki:

- Manajemen data obat
- Monitoring stok obat
- Pengurangan stok setelah transaksi obat berhasil
- Riwayat transaksi obat
- Notifikasi aktivitas distribusi obat

---

## 2. Identifikasi Transaksi Kritis

Transaksi yang dipilih sebagai transaksi kritis adalah **pemberian atau penjualan obat kepada pasien**.

Alasan transaksi ini dikategorikan sebagai transaksi kritis:

1. Mengubah data stok obat (state-changing transaction).
2. Berpengaruh terhadap ketersediaan obat bagi pasien lain.
3. Memiliki dampak operasional dan finansial bagi fasilitas kesehatan.
4. Membutuhkan jejak audit untuk kebutuhan monitoring dan pelaporan.

Ketika transaksi berhasil dilakukan, sistem akan:

- Mengurangi jumlah stok obat.
- Mencatat aktivitas ke sistem audit pusat menggunakan SOAP.
- Menyebarkan event transaksi melalui RabbitMQ.

---

## 3. Skema Role Lokal

Setelah pengguna berhasil login melalui SSO, sistem akan memetakan pengguna ke role lokal pada service Farmasi dan Obat.

| Role | Hak Akses |
|--------|------------|
| Admin Farmasi | Mengelola data obat, stok, dan seluruh transaksi |
| Apoteker | Melakukan distribusi obat dan melihat stok |
| Petugas Gudang | Menambah stok dan memperbarui data persediaan |
| Auditor | Melihat laporan dan riwayat transaksi |

Contoh pemetaan JWT dari SSO:

| Email | Role Lokal |
|---------|------------|
| admin@farmasi.local | Admin Farmasi |
| apoteker@farmasi.local | Apoteker |
| gudang@farmasi.local | Petugas Gudang |
| auditor@farmasi.local | Auditor |

---

## 4. Sequence Diagram (Penjelasan Alur)

### Skenario: Distribusi Obat kepada Pasien

1. Pengguna melakukan login melalui sistem SSO terpusat.
2. SSO mengembalikan JWT Token yang berisi identitas pengguna.
3. Service Farmasi melakukan validasi token menggunakan JWKS dari server SSO.
4. Sistem memetakan pengguna ke role lokal.
5. Apoteker melakukan transaksi distribusi obat.
6. Service Farmasi memvalidasi ketersediaan stok.
7. Sistem mengurangi stok obat sesuai jumlah yang diberikan.
8. Data transaksi dikirim ke layanan Audit menggunakan SOAP XML.
9. Sistem menerima `ReceiptNumber` sebagai bukti audit berhasil dicatat.
10. Event transaksi dipublikasikan ke RabbitMQ dalam format JSON.
11. Departemen atau layanan lain dapat menerima informasi transaksi secara asinkron.
12. Sistem mengembalikan status sukses kepada pengguna.

---

## 5. Justifikasi Penggunaan SOAP

SOAP digunakan untuk proses audit transaksi karena:

1. Sistem audit pusat yang disediakan dosen merupakan sistem legacy berbasis SOAP/XML.
2. SOAP memiliki struktur pesan yang ketat sehingga cocok untuk kebutuhan audit.
3. Format XML memastikan konsistensi data yang dikirim.
4. Mendukung integritas dan validitas data transaksi penting.
5. Setiap transaksi memperoleh Receipt Number sebagai bukti pencatatan audit.

Data yang dikirim ke SOAP Audit meliputi:

- Team ID
- Nama aktivitas
- Informasi transaksi obat
- Waktu transaksi
- Informasi pengguna yang melakukan transaksi

---

## 6. Justifikasi Penggunaan RabbitMQ

RabbitMQ digunakan untuk penyebaran event bisnis karena:

1. Komunikasi berlangsung secara asynchronous.
2. Tidak membebani proses utama transaksi farmasi.
3. Memungkinkan banyak layanan menerima informasi yang sama secara bersamaan.
4. Mendukung arsitektur microservices yang loosely coupled.
5. Meningkatkan skalabilitas sistem enterprise.

Event yang dipublikasikan:

```json
{
  "event": "MedicineDistributed",
  "medicine_id": "OBT001",
  "medicine_name": "Paracetamol",
  "quantity": 10,
  "user": "apoteker",
  "timestamp": "2026-05-20T10:15:00"
}
```

---

## 7. Kesimpulan

Transaksi distribusi obat dipilih sebagai transaksi kritis karena mengubah stok dan memengaruhi operasional layanan kesehatan. Untuk memenuhi kebutuhan integrasi enterprise, transaksi tersebut dicatat ke sistem audit pusat menggunakan SOAP dan disebarkan ke layanan lain menggunakan RabbitMQ. Pendekatan ini mendukung kebutuhan keamanan, akuntabilitas, serta integrasi antar layanan dalam arsitektur enterprise digital city.