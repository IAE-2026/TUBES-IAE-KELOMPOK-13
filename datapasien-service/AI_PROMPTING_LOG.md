# Log Prompt AI

Dokumen ini merangkum penggunaan asisten AI selama pengembangan **Service Data Pasien** pada Tugas 2 mata kuliah BBK2HAB3 - Integrasi Aplikasi Enterprise.

## Informasi Proyek

**Nama:** Widia Mesra Nainggolan Mahulae  
**NIM:** 102022430029  
**Service:** Data Pasien Service  
**Tools AI:** Claude (claude.ai)

---

## Prompt 1 - Diskusi Arsitektur Microservice

Diskusi awal mengenai struktur project dalam arsitektur microservice dan bagaimana setiap service berdiri secara independen.

**Prompt:**
jadi ini emang satu project langsung data pasien service ya? ga ada project besar dulu baru di dalamnya ada service-service gitu?

---

## Prompt 2 - Diskusi Integrasi Antar Service

Diskusi mengenai bagaimana service-service yang berbeda bisa saling berkomunikasi tanpa harus berada dalam satu repository.

**Prompt:**
kalo service aku sama service temen beda repo, gimana caranya biar bisa saling integrasi nanti? harus ada yang push dulu ke github bersama atau gimana?

---

## Prompt 3 - Error composer create-project Permission Denied

Analisis error permission denied yang muncul saat proses instalasi Laravel via composer create-project.

**Prompt:**
ini pas composer create-project ada error Failed to download dari dist terus Permission denied. terus dia syncing lama banget ga gerak. ini kenapa dan harus ngapain?

---

## Prompt 4 - Error Apache Failed di Laragon

Analisis error Apache yang gagal start di Laragon dan apakah berpengaruh terhadap jalannya project Laravel.

**Prompt:**
ini laragon apache nya merah failed terus, ngaruh ga ke project laravel aku? apa yang harus aku lakuin biar bisa lanjut?

---

## Prompt 5 - Error Koneksi Database saat Migrate

Analisis error koneksi MySQL yang muncul saat pertama kali menjalankan migrate padahal konfigurasi .env sudah diisi.

**Prompt:**
pas aku jalanin php artisan migrate ini kenapa error? SQLSTATE[HY000] [2002] No connection could be made. padahal .env udah aku isi dengan bener

---

## Prompt 6 - Error routes/api.php Tidak Ada

Diskusi mengenai kenapa file api.php tidak ditemukan di folder routes di Laravel 11.

**Prompt:**
kok di folder routes ga ada file api.php? yang ada cuma web.php sama console.php. ini normal atau ada yang salah pas installasi?

---

## Prompt 7 - Error Test Postman Masih 401

Analisis kenapa endpoint masih return 401 di Postman padahal API Key sudah ditambahkan.

**Prompt:**
ini kenapa masih unauthorized padahal udah aku tambahin X-IAE-KEY di postman? aku udah isi valuenya dengan bener loh

---

## Prompt 8 - Penyesuaian Standard Integration Contract

Analisis penyesuaian implementasi yang diperlukan setelah membaca dokumen Standard Integration Contract dari dosen.

**Prompt:**
eh aku mau ngubah lagi nih, baru baca kontrak dari dosen, kayaknya ada yang ga sesuai di kode aku. coba cek apa aja yang perlu diubah berdasarkan kontrak ini

---

## Prompt 9 - Debugging Error Swagger Required @OA\Info()

Analisis error yang muncul berulang kali saat generate dokumentasi Swagger meskipun anotasi sudah ditulis di controller.

**Prompt:**
ini swagger terus error Required @OA\Info() not found padahal udah aku tulis di controller. udah dicoba berkali-kali tetap ga ke detect. ini kenapa?

---

## Prompt 10 - Error Swagger Versi 6 Tidak Support PHPDoc

Analisis lebih lanjut kenapa anotasi Swagger tetap tidak terdeteksi meski sudah dipindah ke file terpisah.

**Prompt:**
udah aku bikin SwaggerController sendiri, udah aku pindahin @OA\Info ke sana, tapi masih error terus. ini beneran kenapa? apa yang salah?

---

## Prompt 11 - Error Install Lighthouse Timeout GitLab

Analisis error timeout yang muncul saat menginstall library Lighthouse karena koneksi ke GitLab gagal dan minta credentials.

**Prompt:**
ini pas install nuwave/lighthouse dia minta credentials GitLab terus, padahal aku ga punya akun GitLab. kenapa bisa gitu dan gimana solusinya?

---

## Prompt 12 - Error Docker Service App Tidak Running

Analisis error saat menjalankan perintah docker-compose exec karena container app belum berjalan.

**Prompt:**
ini pas aku jalanin docker-compose exec app php artisan migrate malah error service app is not running. padahal docker-compose up udah aku jalanin

---

## Prompt 13 - Error 500 Internal Server Error di Docker

Analisis error 500 yang muncul di semua endpoint setelah container Docker berhasil jalan.

**Prompt:**
ini kenapa docker containernya udah jalan hijau semua tapi pas aku hit endpoint malah 500 internal server error? sebelumnya di lokal udah jalan normal

---

## Prompt 14 - Error SQLSTATE Sessions Table Not Found di Docker

Analisis error tabel sessions tidak ditemukan di database container Docker.

**Prompt:**
muncul error SQLSTATE[42S02] Base table or view not found: 1146 Table patient_service.sessions doesn't exist. ini kenapa? di lokal ga pernah error gini

---

## Prompt 15 - Error 500 Docker setelah Restart

Analisis error 500 yang muncul kembali setelah container Docker dihentikan dan dijalankan ulang.

**Prompt:**
ini kenapa docker tiba-tiba 500 lagi? padahal kemarin udah jalan normal. aku cuma docker-compose down terus up lagi, kok bisa error lagi?

---

## Prompt 16 - Diskusi Persistensi Data Docker Volume

Diskusi mengenai kenapa data database hilang setiap kali container Docker dihentikan dan bagaimana solusi permanennya.

**Prompt:**
jadi tiap docker-compose down itu data ilang semua? berarti harus migrate terus dong setiap mau jalanin? ada cara biar datanya ga ilang?

---

## Prompt 17 - Error Connection Refused saat Migrate di Docker

Analisis error connection refused yang muncul saat menjalankan migrate di dalam container Docker meski kedua container sudah running.

**Prompt:**
ini pas docker-compose exec app php artisan migrate malah error SQLSTATE[HY000] [2002] Connection refused. padahal docker-compose ps udah nunjukin keduanya running

---

## Prompt 18 - Error 500 saat Try It Out di Swagger

Analisis error 500 yang muncul saat mencoba endpoint langsung dari Swagger UI menggunakan fitur Try it out.

**Prompt:**
ini kenapa pas aku cobain endpoint di swagger malah 500? response body nya malah ngeluarin HTML panjang banget bukan JSON. padahal di postman udah bisa

---

## Prompt 19 - Error Duplicate NIK saat POST

Analisis error yang muncul saat mencoba POST data pasien dengan NIK yang sudah ada di database.

**Prompt:**
ini kenapa pas aku POST lagi malah error 500? data yang aku masukin mirip yang sebelumnya sih, apa itu yang bikin error?