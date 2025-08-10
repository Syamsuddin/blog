# Konfigurasi Akses Blog di http://localhost/blog

## Masalah Saat Ini
Blog saat ini dikonfigurasi untuk diakses melalui port 8888, tetapi Anda ingin mengaksesnya melalui `http://localhost/blog`.

## Solusi 1: Konfigurasi MAMP Default Port 80

### 1. Ubah Port MAMP
1. Buka MAMP
2. Klik "Preferences" > "Ports"
3. Ubah Apache Port dari 8888 ke 80
4. Ubah MySQL Port tetap 8889 (sudah benar)
5. Klik "OK" dan restart MAMP

### 2. Set Document Root ke htdocs
1. Klik "Preferences" > "Web Server"
2. Set Document Root ke: `/Applications/MAMP/htdocs`
3. Klik "OK"

### 3. Akses Blog
- Sekarang blog bisa diakses di: `http://localhost/blog/public`
- Atau buat symbolic link (lihat Solusi 2)

## Solusi 2: Membuat Symbolic Link (Recommended)

```bash
# Masuk ke folder htdocs MAMP
cd /Applications/MAMP/htdocs

# Buat symbolic link dari blog/public ke blog-public
ln -s blog/public blog-public

# Sekarang akses melalui: http://localhost/blog-public
```

## Solusi 3: Virtual Host (Advanced)

### 1. Edit httpd.conf MAMP
Tambahkan di `/Applications/MAMP/conf/apache/httpd.conf`:

```apache
# Virtual Host untuk blog
<VirtualHost *:80>
    DocumentRoot "/Applications/MAMP/htdocs/blog/public"
    ServerName blog.local
    <Directory "/Applications/MAMP/htdocs/blog/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 2. Edit hosts file
```bash
sudo nano /etc/hosts
```

Tambahkan:
```
127.0.0.1 blog.local
```

### 3. Akses melalui: http://blog.local

## Solusi 4: Fix Current Setup

Jika ingin tetap menggunakan setup saat ini dengan port 8888:

```bash
cd /Applications/MAMP/htdocs/blog
php artisan serve --host=localhost --port=8080
```

Kemudian akses di: `http://localhost:8080`

## File yang Sudah Diupdate

✅ `.env` - APP_URL sudah diubah ke `http://localhost/blog`
✅ `.htaccess` - Sudah dibuat untuk redirect ke public folder
✅ Layout views - Sudah diperbaiki
✅ Config cache - Sudah di-clear

## Testing
1. Restart MAMP setelah mengubah konfigurasi
2. Test akses URL sesuai solusi yang dipilih
3. Login admin: admin@example.com / password

## Recommended: Gunakan Solusi 2 (Symbolic Link)
Paling mudah dan tidak mengubah konfigurasi MAMP yang ada.
