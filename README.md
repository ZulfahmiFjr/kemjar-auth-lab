# Kemjar Auth Lab

Proyek Akhir Praktikum Keamanan Jaringan 2026

**Kelompok 18**

| Nama | NPM |
| --- | --- |
| Zulfahmi Fajri | 2406345425 |
| Muhammad Hashif Jade | 2406396786 |

**Topik**

- No API Authentication
- Insecure Change Password Feature

## Tujuan

bikin aplikasi web sederhana sendiri, trus ngikutin alur SOP dari pembuatan target, enumerasi, eksploitasi, perbaikan, sampe pengujian ulang

## Rencana Sistem

- backend pake PHP native
- database pake sqlite
- tampilan pake HTML sama CSS sederhana
- interaksi API pake javascript bawaan browser
- aplikasi jalan di laptop masing masing, github buat simpen kode sama catatan perubahan

## Fitur yang Direncanain

- dua akun uji dgan data fiktif
- login sama logout pake sesi
- dashboard dgan daftar anggota
- formulir ubah kata sandi
- versi rentan sama versi perbaikan

## Progres Pengerjaan

- [x] nyiapin lingkungan PHP sama ekstensi PDO sqlite
- [x] bikin kerangka halaman awal
- [x] bikin database sama dua akun uji
- [ ] bikin login sama logout
- [ ] bikin dashboard sama API daftar anggota versi rentan
- [ ] bikin ubah sandi versi rentan
- [ ] nyatet enumerasi sama bukti eksploitasi
- [ ] nambah autentikasi API sama perbaikan ubah sandi
- [ ] nguji ulang serangan sama alur pengguna yg sah
- [ ] nyusun laporan sama nyiapin demo

## Jalanin Lokal

butuh PHP versi `8.1` atau yg lebih baru, modul session, sama ekstensi PDO sqlite

buka terminal di folder proyek, trus jalanin server dgan PHP standar

```powershell
& "C:\Dev\PHP-Kemjar\php.exe" -S 127.0.0.1:8000 -t vulnerable/public
```

buka [portal anggota](http://127.0.0.1:8000) di browser, lokasi PHP disesuaiin sama laptop masing masing

buat brentiin server, pencet `Ctrl+C` di terminal

## Ruang Lingkup Uji

pengujian cuma buat aplikasi lokal milik kelompok dgan akun fiktif, bukti hasil dicatet sesuai kondisi yg beneran ditemuin
