<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('albums')->insert(
            [
                [
                    'album_id' => '0HpjiDTMrVazgCn2wT4evB',
                    'name' => 'Slow Down',
                    'release_date' => '2020-08-14 00:00:00',
                    'primary_artist_id' => '5Wpn7BDRJ8oq7CcF1EufWI',
                    'url' => 'https://open.spotify.com/album/0HpjiDTMrVazgCn2wT4evB',
                    'img_url' => 'https://i.scdn.co/image/ab67616d0000b2733db8ccf7039af1d4d1ee80a1',
                    'type' => 'single'
                ],
                [
                    'album_id' => '6Rg9tJW4DSAUyNp59VXzu1',
                    'name' => 'Ignite',
                    'release_date' => '2018-05-11 00:00:00',
                    'primary_artist_id' => '6pWcSL9wSJZQ9ne0TnhdWr',
                    'url' => 'https://open.spotify.com/album/6Rg9tJW4DSAUyNp59VXzu1',
                    'img_url' => 'https://i.scdn.co/image/ab67616d0000b273eaee7835ad1cd0c435edd7cf',
                    'type' => 'single'
                ]

            ]
        );
    }
}
