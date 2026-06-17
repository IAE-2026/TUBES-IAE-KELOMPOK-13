**Nama: Widia Mesra Nainggolan mahulae**  
**NIM: 102022430029**  
**Service**: Data Pasien Service  
**Tanggal**: 11 Juni 2026   

---

1. Tolong analisis kode saya dan kenapa ada bug yang menyebabkan error di endpoint /api/v1/patients. Fokus pada SSOService.php, SoapAuditService.php, dan PatientController.php.

2. PatientController@store ku belum memanggil SoapAuditService dan AMQPPublisherService. Tolong bantu tambahkan integrasi keduanya dengan alur yang benar.

3. Saat menjalankan php artisan migrate muncul error: Base table or view already exists: 1050 Table 'sso_users' already exists. Bagaimana cara mengatasinya tanpa menghapus tabel yang sudah ada?

4. loginM2M() return null karena access_token tidak ada. Sudah dicek SSO_M2M_KEY=KEY-MHS-88 tapi tetap gagal. ini kenapa?

5. Setelah semua perubahan, hasil POST /api/v1/patients mengembalikan 201 dengan audit_receipt: "IAE-LOG-2026-04FD1DC6" dan amqp_published: true. udh bener blm ini?





