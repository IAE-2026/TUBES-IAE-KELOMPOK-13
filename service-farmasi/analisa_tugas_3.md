Nama: Rayula Samina
Kelas: SI4809
NIM: 102022400102
Alasan Penggunaan Endpoint POST `/prescriptions`

Endpoint POST /api/v1/prescriptions digunakan untuk mendukung proses bisnis utama pada layanan Farmasi-Obat, yaitu pembuatan resep obat oleh tenaga kesehatan yang berwenang.

Pemilihan metode HTTP POST dilakukan karena proses ini menghasilkan data baru pada sistem, yaitu data resep beserta detail obat yang diresepkan kepada pasien. Selain menyimpan data resep, endpoint ini juga menjalankan beberapa proses bisnis penting, antara lain:

1. Membuat data resep baru berdasarkan informasi pasien, kunjungan, dokter, dan daftar obat.
2. Memvalidasi ketersediaan stok obat sebelum resep disimpan.
3. Mengurangi stok obat secara otomatis sesuai jumlah obat yang diresepkan.
4. Mencatat aktivitas ke sistem audit legacy melalui SOAP untuk kebutuhan pelacakan dan kepatuhan.
5. Mempublikasikan event ke RabbitMQ menggunakan pola event-driven agar layanan lain dapat menerima notifikasi pembuatan resep.

Endpoint ini dilindungi menggunakan middleware Federated SSO sehingga hanya pengguna dengan peran tertentu, seperti apoteker atau admin farmasi, yang dapat membuat resep. Dengan demikian keamanan dan integritas data dapat terjaga.

Contoh Request

http
POST /api/v1/prescriptions

{
  "id_pasien": 1,
  "id_kunjungan": 1001,
  "nama_dokter": "Dr. Andi",
  "items": [
    {
      "id_obat": 1,
      "jumlah": 2,
      "dosis": "3x1 sehari"
    }
  ]
}
```
Hasil yang Diharapkan

Sistem akan menghasilkan data resep baru, memperbarui stok obat, mengirim audit ke layanan SOAP, dan menerbitkan event `prescription.created` ke RabbitMQ.