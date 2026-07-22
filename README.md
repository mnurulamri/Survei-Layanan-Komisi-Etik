# Survei-Layanan-Komisi-Etik
MODUL SURVEI KEPUASAN CLEARANCE ETIK FISIP UI v1.0*

*1. DESKRIPSI*
Modul ini untuk mengelola Survei Kepuasan Layanan Clearance Etik di FISIP UI.
Fitur: Form Survei, Rekap Otomatis, Grafik, Export Excel/PDF, Notifikasi WA, Reminder Otomatis, Log Pengiriman, Mode Anonim.

Framework: CodeIgniter 3 + AdminLTE + http://Chart.js + PHPSpreadsheet + DomPDF

*2. FITUR UTAMA*

No	Fitur	Keterangan
1	Form Survei Online	10 pertanyaan: 8 Skala Likert 1-4 + 2 Esai
2	Dashboard Rekap	Skor rata-rata, IKM, Grafik Batang per pertanyaan
3	Grafik Bulanan	Tren jumlah LOLOS vs Jumlah Isi Survei per bulan
4	Export Data	Export ke Excel dan PDF untuk laporan
5	Notifikasi WA Otomatis	Kirim WA "LOLOS" + Link Survei via Meta API
6	Reminder WA	Cronjob H+3 jika belum isi survei
7	Log Pengiriman WA	Pantau status terkirim/gagal + respon API
8	Mode Anonim	Saran peneliti disembunyikan identitasnya
9	Toggle Identitas	Khusus Admin/Ketua bisa buka identitas saat audit

*3. INSTALASI*

*A. Upload File*
Copy folder `survei` ke `application/modules/`

*B. Import Database*
Jalankan file `database.sql` di phpMyAdmin

*C. Install Composer Package*

composer require phpoffice/phpspreadsheet dompdf/dompdf

Aktifkan di `application/config.php`:

$config['composer_autoload'] = TRUE;

*D. Konfigurasi WA Gateway*
Edit `application/modules/survei/libraries/Whatsappsender.php`

private $token = 'ISI_TOKEN_META_KAMU';
private $phone_number_id = 'ISI_PHONE_NUMBER_ID';

Buat 2 Template di Meta Business > Template Messages:
1. `notifikasi_survei_etik` - Kategori: UTILITY
2. `reminder_survei_etik` - Kategori: UTILITY
Status harus `Approved`

*E. Setting Cronjob Reminder*
Jalan tiap hari jam 09:00 WIB

0 9 * php /home/user/public_html/index.php cron reminder_survei fisipui2026

*F. Konfigurasi Akses*
Edit di setiap function controller:
`if($this->session->userdata('level') != 'admin')` sesuaikan dengan level di sistem

*4. DAFTAR URL / MENU*

URL	Akses	Fungsi
`/survei/form/{id_pengajuan}`	Peneliti	Mengisi form survei
`/survei/rekap`	Admin	Dashboard rekap + grafik
`/survei/rekap?show=1`	Admin	Buka identitas di tabel saran
`/survei/log_wa`	Admin	Log pengiriman WA
`/survei/export_excel`	Admin	Download Excel
`/survei/export_pdf`	Admin	Download PDF

*5. STRUKTUR TABEL DATABASE*
1. `etk_survei_pertanyaan`: Master pertanyaan
2. `etk_survei_jawaban`: Header jawaban per peneliti. Ada field `is_anonim`
3. `etk_survei_detail`: Detail jawaban per pertanyaan
4. `etk_log_wa`: Log pengiriman notifikasi dan reminder
5. Alter `pengajuan`: tambah `tgl_lolos` dan `status_reminder_survei`

*6. ALUR KERJA*
1. Admin klik "LOLOS" di modul pengajuan
2. Otomatis: Status update + `tgl_lolos` terisi + WA notifikasi terkirim
3. Peneliti klik link -> isi survei -> submit
4. Cron H+3 cek yg belum isi -> kirim reminder WA
5. Admin buka `/survei/rekap` untuk lihat grafik, IKM, dan export laporan

*7. TROUBLESHOOTING*

Masalah	Solusi
WA tidak terkirim	Cek `etk_log_wa`. Lihat `response_api`. Pastikan format no: 62812xxx
Chart tidak muncul	Pastikan sudah load `Chart.js` di template footer
Export PDF error	Pastikan folder `vendor/dompdf` ada dan writable
Reminder spam	Cek field `status_reminder_survei` sudah ke-set 1 setelah kirim

*8. PENGEMBANG*
Tim IT FISIP UI
Versi: 1.0 | Tanggal Rilis: April 2026

---
