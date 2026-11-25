# kemjar-auth-lab

proyek akhir praktikum keamanan jaringan 2026, fokus ke no API authentication sama insecure change password feature

## tujuan

bikin aplikasi web sederhana sendiri, trus ngikutin alur SOP dari pembuatan target, enumerasi, eksploitasi, perbaikan, sampe pengujian ulang

## rencana sistem

- backend pake PHP native
- database pake sqlite
- tampilan pake HTML sama CSS sederhana
- interaksi API pake javascript bawaan browser
- aplikasi jalan di laptop masing masing, github buat simpen kode sama catatan perubahan

## fitur yg direncanain

- dua akun uji dgan data fiktif
- login sama logout pake sesi
- dashboard dgan daftar anggota
- formulir ubah kata sandi
- versi rentan sama versi perbaikan

## checklist pengerjaan

centang bagian setelah beneran selesai dan dicek bareng

- [ ] nyiapin lingkungan PHP sama ekstensi PDO sqlite
- [ ] bikin kerangka halaman awal
- [ ] bikin database sama dua akun uji
- [ ] bikin login sama logout
- [ ] bikin dashboard sama API daftar anggota versi rentan
- [ ] bikin ubah sandi versi rentan
- [ ] nyatet enumerasi sama bukti eksploitasi
- [ ] nambah autentikasi API sama perbaikan ubah sandi
- [ ] nguji ulang serangan sama alur pengguna yg sah
- [ ] nyusun laporan sama nyiapin demo

## aturan kerja

- commit tiap bagian yg udah bisa dicek, tulis perubahan yg beneran dikerjain
- masing masing pake akun git sendiri buat kontribusinya
- tarik perubahan terbaru sbelum mulai, kabarin kalo mau ngubah file yg sama
- jangan masukin database, cookie sesi, data pribadi, atau hasil log yg sensitif ke repo
- perintah jalanin aplikasi ditambahin setelah kerangka server selesai

## gaya coding

- PHP pake strict types tepat di baris ketiga
- indentasi empat spasi, kurung kurawal pembuka sebaris dgan deklarasi atau kondisi
- ga ada baris kosong di dalem fungsi
- statement, kondisi, string, sama array PHP ditulis utuh satu baris
- concatenation rapet dgan operand
- komentar sama commit pake bahasa indonesia santai, huruf kecil kecuali singkatan teknis, tanpa tanda titik
- kata atau sama walaupun tetep ditulis lengkap
- README ngikutin gaya bahasa yg sama, nama file sama perintah tetep sesuai sintaks aslinya

## ruang lingkup uji

pengujian cuma buat aplikasi lokal milik kelompok dgan akun fiktif, bukti hasil dicatet sesuai kondisi yg beneran ditemuin
