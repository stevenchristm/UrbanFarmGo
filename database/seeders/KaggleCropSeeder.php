<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KatalogTanaman;

class KaggleCropSeeder extends Seeder
{
    public function run()
    {
        // Path file sesuai screenshot Anda
        $file = base_path('database/seeders/Crop_recommendation.csv');
        $handle = fopen($file, 'r');
        fgetcsv($handle); // Skip header

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            KatalogTanaman::updateOrCreate(
                ['nama_tanaman' => ucfirst($data[7])],
                [
                    'suhu_min'     => floor($data[3]) - 2,
                    'suhu_max'     => ceil($data[3]) + 2,
                    'cahaya_jam'   => rand(4, 9),
                    'humidity_avg' => $data[4],
                    'rainfall_avg' => $data[6],
                    'deskripsi_edukasi' => "Tanaman optimal di suhu " . round($data[3], 1) . "°C",
                ]
            );
        }
        fclose($handle);
    }
}