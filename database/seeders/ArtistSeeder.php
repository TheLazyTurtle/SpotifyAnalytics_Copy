<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('artists')->insert(
            [
                [
                    'artist_id' => '5Wpn7BDRJ8oq7CcF1EufWI',
                    'name' => 'Chris Linton',
                    'url' => 'https://open.spotify.com/artist/5Wpn7BDRJ8oq7CcF1EufWI',
                    'img_url' => 'https://i.scdn.co/image/c0c8d359acfbd83d01349c318fd0221c61a8c71e'
                ],
                [
                    'artist_id' => '6pWcSL9wSJZQ9ne0TnhdWr',
                    'name' => 'K-391',
                    'url' => 'https://open.spotify.com/artist/6pWcSL9wSJZQ9ne0TnhdWr',
                    'img_url' => 'https://i.scdn.co/image/ab6761610000e5eb98c0be76b2a5f1b170039f16'
                ]
            ]
        );
    }
}
