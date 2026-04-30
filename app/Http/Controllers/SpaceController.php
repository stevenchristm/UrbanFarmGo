<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\KatalogTanaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SpaceController extends Controller
{
    /**
     * Menampilkan daftar lahan milik user yang sedang login.
     */
    public function index()
    {
        // Ambil lahan hanya milik user yang sedang login
        $spaces = Space::where('id_user', Auth::id())->get();
        return view('lahan.index', compact('spaces'));
    }

    /**
     * Menampilkan form pendaftaran lahan baru.
     */
    public function create()
    {
        // Tidak perlu ambil data User::all() lagi karena pemiliknya otomatis yang login
        return view('lahan.create');
    }

    /**
     * Menyimpan data lahan baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_lahan'   => 'required|string|max:255',
            'lokasi_lahan' => 'required|string|max:255',
            'luas_lahan'   => 'required|numeric',
            'suhu_lahan'   => 'required|numeric',
            'cahaya_lahan' => 'required|numeric',
        ]);

        // 2. Simpan Data
        // id_user diambil otomatis dari Auth::id() agar tidak error "Property does not exist"
        $space = Space::create([
            'nama_lahan'   => $request->nama_lahan,
            'lokasi_lahan' => $request->lokasi_lahan,
            'luas_lahan'   => $request->luas_lahan,
            'suhu_lahan'   => $request->suhu_lahan,
            'cahaya_lahan' => $request->cahaya_lahan,
            'id_user'      => Auth::id(), 
        ]);

        // 3. Redirect ke halaman rekomendasi
        return redirect()->route('lahan.rekomendasi', $space->id_lahan)
            ->with('success', 'Lahan berhasil dianalisis!');
    }

    /**
     * Logika AI: Rekomendasi tanaman berdasarkan dataset CSV.
     */
    public function rekomendasi($id) 
    {
        $lahan = Space::where('id_lahan', $id)->firstOrFail();
        
        // Ambil tanaman yang suhunya masuk rentang
        $tanamanCocok = KatalogTanaman::where('suhu_min', '<=', $lahan->suhu_lahan)
            ->where('suhu_max', '>=', $lahan->suhu_lahan)
            ->get()
            ->map(function ($t) use ($lahan) {
                // Logika Persentase: 
                // 1. Cari titik tengah (Ideal)
                $ideal = ($t->suhu_min + $t->suhu_max) / 2;
                
                // 2. Hitung jarak suhu lahan ke titik ideal
                $jarak = abs($lahan->suhu_lahan - $ideal);
                
                // 3. Hitung radius/jangkauan suhu
                $radius = ($t->suhu_max - $t->suhu_min) / 2;
                
                // 4. Hitung skor (semakin dekat ke ideal, semakin mendekati 100)
                // Jika radius 0 (suhu min & max sama), set 100%
                $score = $radius > 0 ? (1 - ($jarak / $radius)) * 100 : 100;

                // Simpan skor ke objek tanaman (dibulatkan)
                $t->skor_kecocokan = round(max(50, $score)); // Minimal 50% biar user gak sedih
                return $t;
            })
            ->sortByDesc('skor_kecocokan'); // Urutkan dari yang paling cocok

        return view('lahan.rekomendasi', compact('lahan', 'tanamanCocok'));
    }

    public function edit($id)
    {
        // Cari lahan milik user yang sedang login, jika tidak ada tampilkan 404
        $space = Space::where('id_lahan', $id)->where('id_user', Auth::id())->firstOrFail();
        return view('lahan.edit', compact('space'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lahan'   => 'required|string|max:255',
            'lokasi_lahan' => 'required|string|max:255',
            'luas_lahan'   => 'required|numeric',
            'suhu_lahan'   => 'required|numeric',
            'cahaya_lahan' => 'required|numeric',
            'password_konfirmasi' => 'required',
        ]);

        $space = Space::where('id_lahan', $id)->where('id_user', \Auth::id())->firstOrFail();

        // Cek password
        if (!\Hash::check($request->password_konfirmasi, \Auth::user()->password)) {
            return back()->with('error', 'Konfirmasi Password Salah!')->withInput();
        }

        // Update hanya kolom lahan saja, id_user JANGAN diupdate
        $space->update([
            'nama_lahan'   => $request->nama_lahan,
            'lokasi_lahan' => $request->lokasi_lahan,
            'luas_lahan'   => $request->luas_lahan,
            'suhu_lahan'   => $request->suhu_lahan,
            'cahaya_lahan' => $request->cahaya_lahan,
        ]);

        return redirect()->route('lahan.index')->with('success', 'Data lahan berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        // 1. Cari lahan (pastikan milik user yang login)
        $space = Space::where('id_lahan', $id)->where('id_user', Auth::id())->firstOrFail();

        // 2. Validasi apakah password yang dimasukkan di prompt cocok dengan password akun
        if (!Hash::check($request->password_konfirmasi, Auth::user()->password)) {
            return back()->with('error', 'Gagal menghapus! Password yang Anda masukkan salah.');
        }

        // 3. Hapus
        $space->delete();

        return redirect()->route('lahan.index')->with('success', 'Lahan berhasil dihapus secara permanen.');
    }
}