<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insert Data Admin Users
        DB::table('admin_users')->insert([
            [
                'id' => 1, 
                'username' => 'admin', 
                'password_hash' => '$2y$10$YZDjhB0iTaVLlVqEEhzUxu90HzlctJZewWdjSGq1IhLafQh/ovsh2', 
                'created_at' => '2026-02-03 05:35:52', 
                'role' => 'admin'
            ],
            [
                'id' => 5, 
                'username' => 'kemahasiswaan', 
                'password_hash' => '$2y$10$.OeK/JkhxUwPKFmZ9ilcteXs1xPQEQOoSZxAGfjbb8fhgKN6LHDI2', 
                'created_at' => '2026-02-03 05:35:52', 
                'role' => 'kemahasiswaan'
            ],
        ]);

        // 2. Insert Data Rooms
        DB::table('rooms')->insert([
            ['id' => 1, 'floor' => '1', 'name' => 'Gedung Serba Guna', 'capacity' => 0, 'facilities' => null, 'photo' => 'assets/room_20260225_112705_73f330cf.jpg', 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 2, 'floor' => '1', 'name' => 'R.102', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => 'assets/room_20260225_112718_c19c2b9e.jpg', 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 3, 'floor' => '1', 'name' => 'R.103', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => 'assets/room_20260225_112729_c21dd5b7.jpg', 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 4, 'floor' => '1', 'name' => 'R.104', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => 'assets/room_20260225_112748_3078b97c.jpg', 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 5, 'floor' => '1', 'name' => 'R.105', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => 'assets/room_20260225_112758_dfab4252.jpg', 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 6, 'floor' => '1', 'name' => 'R.106', 'capacity' => 35, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => 'assets/room_20260225_112815_a70f3849.jpg', 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 7, 'floor' => '1', 'name' => 'R.109', 'capacity' => 35, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => null, 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 8, 'floor' => '1', 'name' => 'R.110', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => null, 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 9, 'floor' => '1', 'name' => 'R.111', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => null, 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 10, 'floor' => '1', 'name' => 'R.112', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => null, 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 11, 'floor' => '1', 'name' => 'R.113', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => null, 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 12, 'floor' => '2', 'name' => 'R.201', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => null, 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 13, 'floor' => '2', 'name' => 'R.202', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => null, 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 14, 'floor' => '2', 'name' => 'R.203', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => null, 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 15, 'floor' => '2', 'name' => 'R.204', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => null, 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 16, 'floor' => '2', 'name' => 'R.205', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => null, 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 17, 'floor' => '2', 'name' => 'R.206', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => null, 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 18, 'floor' => '2', 'name' => 'R.209', 'capacity' => 70, 'facilities' => 'Proyektor, Ac, PC Dosen, Kursi', 'photo' => 'assets/room_20260204_114235_c4e167cc.jpg', 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 19, 'floor' => '3', 'name' => 'R.301', 'capacity' => 30, 'facilities' => 'Lab. Multimedia 1 : Proyektor, Ac, PC 30', 'photo' => 'assets/room_20260330_135146_1353b88c.jpg', 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 20, 'floor' => '3', 'name' => 'R.302', 'capacity' => 30, 'facilities' => 'Lab. Pemrograman : Proyektor, Ac, PC 30', 'photo' => 'assets/room_20260330_135134_65ebac30.jpg', 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 21, 'floor' => '3', 'name' => 'R.303', 'capacity' => 30, 'facilities' => 'Lab. Basis Data : Proyektor, Ac, PC 30', 'photo' => 'assets/room_20260225_112639_69f93646.jpg', 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 22, 'floor' => '3', 'name' => 'R.304', 'capacity' => 30, 'facilities' => 'Lab. Aplikasi : Proyektor, Ac, PC 30', 'photo' => 'assets/room_20260225_112615_56e8146e.jpg', 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 23, 'floor' => '3', 'name' => 'R.305', 'capacity' => 30, 'facilities' => 'Lab. Multimedia 2 : Proyektor, Ac, PC 30', 'photo' => 'assets/room_20260225_112630_5cc2de05.jpg', 'created_at' => '2026-02-03 06:31:51'],
            ['id' => 28, 'floor' => '1', 'name' => 'R.140', 'capacity' => 0, 'facilities' => null, 'photo' => 'assets/room_20260309_143208_a383c0e6.jpg', 'created_at' => '2026-03-09 07:32:08'],
        ]);
    }
}