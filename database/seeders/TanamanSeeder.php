<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KatalogTanaman;
use Illuminate\Support\Facades\DB;

class TanamanSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        KatalogTanaman::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $file = fopen(database_path('seeders/Crop_recommendation.csv'), 'r');
        $header = fgetcsv($file);
        $insertedCrops = [];

        while (($row = fgetcsv($file)) !== FALSE) {
            if (count($header) !== count($row)) continue;

            $data = array_combine($header, $row);
            $label = strtolower($data['label']);

            if (!in_array($label, $insertedCrops)) {
                // LOGIKA OTOMATIS CARI FILE DI FOLDER
                $imagePath = public_path("assets/img/bibit/");
                // Mencari file dengan nama $label dan ekstensi apapun (*)
                $files = glob($imagePath . $label . ".*");
                
                // Jika ketemu, ambil nama filenya saja (misal: rice.png atau banana.avif)
                $namaFoto = !empty($files) ? basename($files[0]) : null;

                KatalogTanaman::create([
                    'nama_tanaman'      => ucfirst($label),
                    'suhu_min'          => round($data['temperature'] - 2, 2),
                    'suhu_max'          => round($data['temperature'] + 2, 2),
                    'cahaya_jam'        => 12,
                    'humidity_avg'      => round($data['humidity'], 2),
                    'rainfall_avg'      => round($data['rainfall'], 2),
                    'cahaya_min'        => '12.5',
                    'rentang_suhu'      => round($data['temperature']) . "°C",
                    'jarak_tanam_ideal' => "20-30 cm",
                    'deskripsi_edukasi' => "Tanaman " . ucfirst($label) . " cocok di pH " . round($data['ph'], 1),
                    'foto_tanaman'      => $namaFoto, // Menyimpan hanya nama file + ekstensi
                    'video_id'          => "D5S_m87vY6w",
                ]);
                $insertedCrops[] = $label;
            }
        }
        fclose($file);
    }
}