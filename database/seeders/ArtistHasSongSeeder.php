<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtistHasSongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('artist_has_song')->insert(
            [
                [
                    'artist_id' => '5Wpn7BDRJ8oq7CcF1EufWI',
                    'song_id' => '1zAljZbhp0j8EBSDEcsRLI'
                ],
                [
                    'artist_id' => '6pWcSL9wSJZQ9ne0TnhdWr',
                    'song_id' => '1jLsirPDkUS2g4gnkYua58'
                ]
            ]
        );
    }
}
