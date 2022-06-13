<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('played')->insert([
            [
                'song_id' => '1zAljZbhp0j8EBSDEcsRLI',
                'date_played' => '2020-01-01 12:00:00',
                'played_by' => '11182819693',
                'song_name' => 'Slow Down'
            ],
            [
                'song_id' => '1zAljZbhp0j8EBSDEcsRLI',
                'date_played' => '2020-01-01 12:10:00',
                'played_by' => '11182819693',
                'song_name' => 'Slow Down'
            ],
            [
                'song_id' => '1jLsirPDkUS2g4gnkYua58',
                'date_played' => '2020-01-01 12:01:00',
                'played_by' => '11182819693',
                'song_name' => 'Ignite'
            ],
        ]);
    }
}
