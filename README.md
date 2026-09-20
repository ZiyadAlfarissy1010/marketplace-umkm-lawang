**Cara Menjalankan Project**
Pastikan sudah menginstal XAMPP (Apache & MySQL) dan Composer.
Download/Clone project ini, lalu buka di VS Code.
Buka Terminal, jalankan perintah: composer install
Duplikat file .env.example, lalu rename menjadi .env.
Nyalakan MySQL di XAMPP, buat database kosong bernama db_umkm_lawang di phpMyAdmin.
Sesuaikan bagian DB di file .env (DB_DATABASE=db_umkm_lawang, DB_USERNAME=root, DB_PASSWORD=).
Jalankan perintah di Terminal:
php artisan key:generate
php artisan migrate:fresh
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=DummyDataSeeder
php artisan storage:link
Jalankan server dengan perintah php artisan serve.
Buka browser dan akses http://localhost:8000.

**Akun Default**
Admin: admin@gmail.com / admin123
Penjual: Penjual1010@gmail.com / Oke1010288
Penjual: anto123@gmail.com / Oke1010288
Penjual: jaja@gmail.com / Oke1010288
Penjual: Kedaijarot12@gmail.com / Oke1010288
Pembeli: Pembeli1010@gmail.com / Oke1010288
