# Progress Report: Implementasi Artificial Intelligence pada UrbanFarm
Mekanisme, Alur Kerja, dan Integrasi Model AI dalam Sistem Pertanian Perkotaan

## Arsitektur Model AI yang Digunakan
**Multi-Model AI Approach**
Sistem UrbanFarm menggunakan pendekatan dua model AI (*Multi-Model*) untuk mengoptimalkan kecepatan, biaya, dan kapabilitas:
1. **Groq API (Model: LLaMA3-70b-8192)** 
   * Digunakan untuk pemrosesan logika teks yang cepat dan analisis data lingkungan.
   * *Use case*: Rekomendasi tanaman dan obrolan teks reguler.
2. **Google Gemini API (Model: Gemini 1.5 Flash)**
   * Digunakan karena keunggulan *Multimodal* (mampu memproses teks sekaligus gambar).
   * *Use case*: Analisis visual (misal: deteksi penyakit daun dari foto yang diunggah pengguna).

## Alur Kerja Fitur 1 - AI Crop Recommendation
**Rekomendasi Tanaman Berbasis Kondisi Lingkungan**
Bagaimana AI memberikan rekomendasi penanaman yang akurat?

1. **Input Pengguna:** Pengguna memasukkan data lokasi, luas lahan (m²), suhu rata-rata (°C), dan durasi sinar matahari per hari.
2. **Prompt Engineering:** *Backend* (Golang) secara dinamis menyusun parameter tersebut menjadi sebuah instruksi (*prompt*) yang spesifik.
3. **Pemrosesan AI:** Prompt dikirim ke **Groq API (LLaMA3)**.
4. **Parsing JSON:** AI membalas dalam format JSON terstruktur yang berisi:
   * Daftar tanaman yang paling cocok.
   * Estimasi kapasitas bibit berdasarkan luas lahan.
   * Estimasi waktu panen tercepat (dalam hari).
5. **Output:** Hasil ditampilkan di antarmuka pengguna secara *real-time*.

## Alur Kerja Fitur 2 - AI Chat Assistant (Pakar Pertanian)
**Asisten Pintar dengan Kesadaran Konteks Lingkungan (Context-Aware)**
Mekanisme percakapan AI dengan pengguna:

1. **Injeksi Konteks Cuaca (Real-time):** Sebelum membalas, sistem secara otomatis mengambil data cuaca saat ini melalui *OpenWeather API*. Cuaca (suhu & kondisi) disisipkan ke *System Prompt* agar AI memberikan saran yang relevan dengan cuaca di lokasi.
2. **Pengecekan Input (Routing):**
   * **Jika input hanya berupa teks:** Sistem melempar permintaan ke **Groq API** untuk respon yang super cepat.
   * **Jika input berisi teks + gambar (Base64):** Sistem secara otomatis memindahkan *routing* ke **Gemini API** untuk melakukan analisis visual.
3. **Penyimpanan:** Setelah AI memberikan solusi, riwayat percakapan disimpan ke dalam *Database* (PostgreSQL/MySQL) agar pengguna dapat melihat kembali histori konsultasi mereka.

## Kesimpulan dan Next Step
**Pencapaian AI Saat Ini:**
* Fitur *Smart Recommendation* dan *Multimodal Chat Assistant* sudah terintegrasi penuh di *backend* (Golang).
* *Routing* dinamis antar model AI (Groq & Gemini) berjalan dengan baik sesuai jenis input (teks vs gambar).
* Konteks lingkungan (cuaca *real-time*) berhasil diintegrasikan ke dalam otak asisten AI.

**Next Step (Tindak Lanjut):**
* Pengujian *load testing* untuk respon API AI.
* Integrasi UI/UX lebih lanjut di aplikasi mobile.
