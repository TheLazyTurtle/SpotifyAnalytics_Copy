<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('songs')->insert(
            [
                [
                    'song_id' => '1zAljZbhp0j8EBSDEcsRLI',
                    'name' => 'Slow Down',
                    'length' => 217600,
                    'url' => 'https://open.spotify.com/track/1zAljZbhp0j8EBSDEcsRLI',
                    'img_url' => 'https://i.scdn.co/image/ab67616d0000b2733db8ccf7039af1d4d1ee80a1',
                    'preview_url' => 'https://p.scdn.co/mp3-preview/dc7d67af85b29d8d82e7823edb417f90423ed1b2?cid=f67b9e03ecde4b9786a9d743fe199d07',
                    'album_id' => '0HpjiDTMrVazgCn2wT4evB',
                    'track_number' => 1,
                    'explicit' => false
                ],
                [
                    'song_id' => '1jLsirPDkUS2g4gnkYua58',
                    'name' => 'ignite',
                    'length' => 210288,
                    'url' => 'https://open.spotify.com/track/1jLsirPDkUS2g4gnkYua58',
                    'img_url' => 'https://i.scdn.co/image/ab67616d0000b273eaee7835ad1cd0c435edd7cf',
                    'preview_url' => 'https://p.scdn.co/mp3-preview/7c49e2f4a66f2d49d9fec3eeca2b8a5b62714645?cid=f67b9e03ecde4b9786a9d743fe199d07',
                    'album_id' => '6Rg9tJW4DSAUyNp59VXzu1',
                    'track_number' => 1,
                    'explicit' => false
                ]
            ]
        );
    }
}
