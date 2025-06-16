<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    // {
    //     $this->command->info('Fetching Provinces');
    //     $provinces = Http::get('https://wilayah.id/api/provinces.json')->json('data');

    //     foreach($provinces as $province) {
    //         Region::create([
    //             'code' => data_get($province, 'code'),
    //             'name' => data_get($province, 'name'),
    //             'type' => 'province',
    //             'parent_code' => null
    //         ]);

    //         $this->command->info("Fetching Regencies for {$province['name']} ...");
    //         $regencies = Http::get("https://wilayah.id/api/regencies/{$province['code']}.json")->json('data');
            
    //         foreach($regencies as $regency) {
    //             Region::create([
    //                 'code' => data_get($regency, 'code'),
    //                 'name' => data_get($regency, 'name'),
    //                 'type' => 'regency',
    //                 'parent_code' => data_get($province, 'code')
    //             ]);

    //         $this->command->info("Fetching Districts for {$regency['name']} ...");
    //         $districts = Http::get("https://wilayah.id/api/districts/{$regency['code']}.json")->json('data');
    //         foreach($districts as $district) {
    //             Region::create([
    //                 'code' => data_get($district, 'code'),
    //                 'name' => data_get($district, 'name'),
    //                 'type' => 'district',
    //                 'parent_code' => data_get($regency, 'code')
    //             ]);
                
    //                 $this->command->info("Fetching Villages for {$district['name']} ...");
    //                 $villages = Http::get("https://wilayah.id/api/villages/{$district['code']}.json")->json('data');
    //                 foreach($villages as $village) {
    //                     Region::create([
    //                         'code' => data_get($village, 'code'),
    //                         'name' => data_get($village, 'name'),
    //                         'type' => 'village',
    //                         'postal_code' => data_get($village, 'postal_code'),
    //                         'parent_code' => data_get($district, 'code')
    //                     ]);
    //                 }
    //             }
    //         }
    //     }
    // }
    {
        // Coba lagi 3 kali jika gagal, tunggu 100ms antar percobaan, dan timeout setelah 30 detik.
        $http = Http::retry(3, 100)->timeout(30);
        $this->command->info('Fetching Provinces...');
        $provinces = $http->get('https://wilayah.id/api/provinces.json')->json('data');

        // Membuat progress bar agar proses terlihat lebih interaktif
        $provinceBar = $this->command->getOutput()->createProgressBar(count($provinces));
        $provinceBar->start();

        foreach ($provinces as $province) {
            Region::create([
                'code' => data_get($province, 'code'),
                'name' => data_get($province, 'name'),
                'type' => 'province',
                'parent_code' => null,
            ]);

            // --- Fetch & Insert Regencies ---
            $regencies = $http->get("https://wilayah.id/api/regencies/{$province['code']}.json")->json('data');
            // Menyiapkan data untuk bulk insert
            $regencyData = collect($regencies)->map(fn ($regency) => [
                'code' => data_get($regency, 'code'),
                'name' => data_get($regency, 'name'),
                'type' => 'regency',
                'parent_code' => data_get($province, 'code'),
            ])->all();
            // Lakukan bulk insert untuk semua kabupaten/kota dalam satu provinsi
            Region::insert($regencyData);

            foreach ($regencies as $regency) {
                // --- Fetch & Insert Districts ---
                $districts = $http->get("https://wilayah.id/api/districts/{$regency['code']}.json")->json('data');
                $districtData = collect($districts)->map(fn ($district) => [
                    'code' => data_get($district, 'code'),
                    'name' => data_get($district, 'name'),
                    'type' => 'district',
                    'parent_code' => data_get($regency, 'code'),
                ])->all();
                Region::insert($districtData);

                foreach ($districts as $district) {
                    // --- Fetch & Insert Villages ---
                    $villages = $http->get("https://wilayah.id/api/villages/{$district['code']}.json")->json('data');
                    if (empty($villages)) continue; // Lompati jika tidak ada desa

                    $villageData = collect($villages)->map(fn ($village) => [
                        'code' => data_get($village, 'code'),
                        'name' => data_get($village, 'name'),
                        'type' => 'village',
                        'parent_code' => data_get($district, 'code'),
                        // Model Region Anda mungkin tidak punya 'postal_code', sesuaikan jika ada
                        'postal_code' => data_get($village, 'postal_code'),
                    ])->all();
                    Region::insert($villageData);
                }
            }
            // Majukan progress bar setelah satu provinsi selesai
            $provinceBar->advance();
            // Beri jeda 1 detik agar tidak membebani API server
            sleep(1); 
        }

        $provinceBar->finish();
        $this->command->info("\nRegion seeding completed successfully.");
    }
}
