# Panduan Menjawab Review Website PWPM DIY

Berikut jawaban untuk setiap poin review pada file `REVIEW Wensite PWPM DIY.pdf`.

## 1. Cara mengganti foto hero (bagian "Belum menemukan cara mengganti foto ini")

- Buka **Admin > Beranda**.
- Pada section **Hero**, gunakan field **Hero Foto** untuk upload foto.
- Jika tidak diisi, sistem otomatis memakai foto gallery terbaru.
- Foto lama akan diganti dan dihapus dari storage.

## 2. Gallery: foto baru muncul di posisi akhir

- Sudah diperbaiki: gallery diurutkan berdasarkan `updated_at` terbaru.
- Foto yang baru ditambahkan/diubah otomatis muncul paling atas (client & admin).

## 3. Share artikel ke WhatsApp: foto tidak muncul

- Sudah ditambahkan Open Graph meta tags (`og:title`, `og:description`, `og:url`, `og:image`).
- Pastikan artikel memiliki thumbnail.
- URL gambar harus absolut (sudah menggunakan `asset('storage/...')`).

## 4. About this product masih muncul `<div>`

- Konten produk (rich text) sekarang dirender sebagai HTML, bukan di-escape.
- Tag `<div>` tidak lagi tampil literal.

## 5. Tombol produk diganti tombol WA dan link

- Di halaman detail produk, tombol "Add to Cart/Buy Now" diganti:
  - **Chat WhatsApp** (menggunakan nomor WhatsApp organisasi)
  - **Buka Link** (URL bebas, diisi admin)
- Admin bisa mengisi **Link Tombol** di form produk.

## 6. Foto produk carousel

- Admin dapat upload beberapa gambar tambahan di form produk (field **Gambar Tambahan (Carousel)**).
- Di halaman detail produk tampil carousel dengan tombol prev/next.

## 7. Cara mengedit "Ingin berkolaborasi dengan kami?" (email & nomor)

- Teks judul/subtitle diedit di **Admin > Beranda** section **Kontak**.
- Email, nomor telepon, WhatsApp diedit di **Admin > Profil Organisasi**.
- Nomor telepon pada section kontak sekarang mengarah ke `wa.me` (WhatsApp).

## Catatan

- Jika ada poin yang belum jelas, sebutkan nama menu/halaman yang dimaksud agar bisa dibuatkan panduan langkah demi langkah.
