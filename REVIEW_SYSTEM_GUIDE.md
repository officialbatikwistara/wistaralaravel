# 📝 PANDUAN SISTEM REVIEW PRODUK

## 🎯 FITUR YANG SUDAH DIBUAT

### 1. **Review API (Backend)**
- ✅ GET `/api/reviews` - Ambil semua review (publik)
- ✅ GET `/api/reviews/{id}` - Detail review (publik)
- ✅ POST `/api/reviews` - Tambah review (butuh login)
- ✅ PATCH `/api/reviews/{id}` - Update review (butuh login)
- ✅ DELETE `/api/reviews/{id}` - Hapus review (butuh login)

### 2. **Halaman Detail Produk**
- ✅ Route: `/produk/{slug}`
- ✅ Menampilkan info produk lengkap
- ✅ Menampilkan rating summary
- ✅ Menampilkan daftar review yang approved
- ✅ Form tulis review (untuk user yang login)

### 3. **Integrasi dengan Pesanan**
- ✅ Tombol "Tulis Review" muncul di detail pesanan yang sudah selesai
- ✅ Klik tombol langsung scroll ke form review di halaman produk
- ✅ Cek otomatis apakah user sudah review produk tersebut

### 4. **Admin Review Management**
- ✅ Route: `/admin/reviews`
- ✅ Approve/Reject review
- ✅ Hapus review
- ✅ Filter berdasarkan status

## 🔄 ALUR KERJA SISTEM REVIEW

### **Untuk User:**
1. User beli produk → Checkout → Bayar
2. Admin verifikasi pembayaran → Ubah status jadi "Selesai"
3. User buka "Detail Pesanan" → Lihat tombol "Tulis Review"
4. Klik "Tulis Review" → Redirect ke halaman detail produk
5. Scroll otomatis ke form review
6. Isi rating (1-5 bintang), komentar, upload foto/video (opsional)
7. Submit → Review masuk dengan status "Pending"
8. Setelah admin approve → Review muncul di halaman produk

### **Untuk Admin:**
1. Login admin → Buka `/admin/reviews`
2. Lihat semua review pending
3. Klik "Setujui" atau "Tolak"
4. Review yang disetujui akan muncul di halaman produk

## 📂 FILE-FILE PENTING

### **Backend:**
- `app/Http/Controllers/Api/ReviewController.php` - API Controller
- `app/Http/Controllers/Admin/ReviewAdminController.php` - Admin Controller
- `app/Models/Review.php` - Model Review
- `app/Models/Produk.php` - Model Produk (dengan relasi review)
- `routes/api.php` - API Routes
- `routes/web.php` - Web Routes

### **Frontend:**
- `resources/views/produk/show.blade.php` - Halaman detail produk
- `resources/views/components/product-review.blade.php` - Komponen review
- `resources/views/user/pesanan/show.blade.php` - Detail pesanan (dengan tombol review)
- `resources/views/admin/reviews/index.blade.php` - Admin review management

### **Database:**
- `database/migrations/*_create_reviews_table.php` - Migration review

## 🧪 CARA TEST

### **1. Test Halaman Detail Produk**
```
http://127.0.0.1:8000/produk/batik-parang-klasik
```
Harus muncul:
- Info produk
- Rating summary
- Form review (jika login)
- Daftar review yang approved

### **2. Test Submit Review**
1. Login sebagai user
2. Buka halaman detail produk
3. Isi form review
4. Submit
5. Cek database: `SELECT * FROM reviews ORDER BY id DESC LIMIT 1;`
6. Status harus "pending"

### **3. Test Admin Approve**
1. Login sebagai admin
2. Buka `http://127.0.0.1:8000/admin/reviews`
3. Klik "Setujui" pada review pending
4. Refresh halaman detail produk
5. Review harus muncul

### **4. Test Integrasi Pesanan**
1. Login sebagai user
2. Beli produk → Selesaikan pembayaran
3. Admin ubah status jadi "Selesai"
4. User buka detail pesanan
5. Harus ada tombol "Tulis Review"
6. Klik tombol → Redirect ke halaman produk
7. Scroll otomatis ke form review

## 🐛 TROUBLESHOOTING

### **Review tidak muncul:**
- Pastikan review sudah di-approve oleh admin
- Cek database: `SELECT * FROM reviews WHERE status='approved';`
- Clear cache: `php artisan view:clear`

### **Error saat submit review:**
- Pastikan user sudah login
- Cek console browser (F12) untuk error JavaScript
- Pastikan CSRF token ada di meta tag

### **Gambar tidak muncul:**
- Jalankan: `php artisan storage:link`
- Pastikan folder `storage/app/public/reviews` ada

## 📊 DATABASE STRUCTURE

```sql
reviews
├── id (PK)
├── user_id (FK → users.id)
├── id_produk (FK → produk.id_produk)
├── rating (1-5)
├── comment (text)
├── photos (json array)
├── video (string path)
├── status (pending/approved/rejected)
├── created_at
└── updated_at
```

## 🚀 NEXT STEPS

1. Test semua fitur
2. Approve beberapa review untuk testing
3. Test integrasi dengan pesanan
4. Commit ke Git jika sudah OK

