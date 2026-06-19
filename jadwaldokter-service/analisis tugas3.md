GAMBARAN PROSES BISNIS SERVICE JADWAL DOKTER:

User (Pasien) akan melakukan proses utama yaitu melihat jadwal dokter hingga melakukan booking jadwal melalui service JadwalDokter dengan beberapa endpoint utama seperti:

GET /api/v1/schedules → melihat daftar jadwal dokter
GET /api/v1/schedules/{id} → melihat detail jadwal dokter
POST /api/v1/schedules → melakukan booking jadwal dokter

Pada saat user melakukan booking (POST /api/v1/schedules), sistem akan melakukan validasi ketersediaan jadwal terlebih dahulu, kemudian jika valid maka status jadwal akan diubah menjadi DIPESAN (booked).

ALASAN KENAPA ENDPOINT POST /api/v1/schedules MENEMAKAN kE DATABASE

Karena pada proses booking jadwal dokter, perubahan data bersifat transaksional dan kritikal, yaitu perubahan status jadwal dari tersedia menjadi DIPESAN.

Proses ini harus langsung disimpan ke database agar

Data jadwal tidak double booking
Status jadwal selalu konsisten (single source of truth)
Perubahan status tercatat secara permanen

Sehingga database berperan sebagai komponen utama untuk memastikan integritas data booking jadwal dokter tetap valid dan tidak bentrok antar user.

ALASAN PENGGUNAAN RABBITMQ

RabbitMQ digunakan untuk mengirimkan event booking setelah status jadwal berubah menjadi DIPESAN, sehingga service lain dapat menerima informasi tersebut secara real-time tanpa harus melakukan pengecekan langsung ke database atau service JadwalDokter.