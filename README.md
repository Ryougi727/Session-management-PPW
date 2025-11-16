Nama: Muhammad Abdul Hadi Amrul  
NPM:2315061078  
PPWD  

# 1. login.php
File ini digunakan untuk halaman login sebagai bagian dari session management.
Fungsinya:
Menerima input username & password dari user
Melakukan pengecekan kredensial (dalam contoh: admin / 123456)
Jika benar → membuat session $_SESSION['logged_in'] = true lalu redirect ke index.php
Jika salah → menampilkan pesan error
Menangani proses logout melalui parameter ?logout=1 dengan cara session_destroy() dan redirect ulang ke halaman login
Singkatnya: Gerbang masuk aplikasi + tempat logout.

# 2. index.php
Ini adalah halaman utama setelah user login, berisi:
Form untuk menambah kontak baru (validasi nama & email)
Membaca dan menyimpan kontak ke file uploads/contacts.json
Menampilkan data kontak dalam bentuk tabel
Tombol Edit untuk ubah data kontak ke edit.php
Tombol Hapus yang menghapus index tertentu dari JSON lalu rewrite file
Link Logout menuju login.php?logout=1

# 3. edit.php
File ini dipakai untuk mengedit data kontak yang sudah tersimpan.
Fungsinya:
Mengambil parameter id dari URL
Membaca data kontak yang sesuai dari file JSON
Menampilkan form dengan value sebelumnya
Setelah update, menyimpan kembali keseluruhan array kontak ke JSON dan redirect ke index.php
