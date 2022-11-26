<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMd5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("songs", function (Blueprint $table) {
            $table->string("hash");
        });

        // NOTE: This will add the hash to all the songs
        // UPDATE songs 
        // INNER JOIN (
        //     SELECT md5(group_concat(s.name, c.names)) as hash, s.song_id 
        //     FROM songs s
        //     INNER JOIN
        //          (SELECT group_concat(name SEPARATOR ' ') as names, song_id 
        //          FROM artists a 
        //          INNER JOIN artist_has_song ahs ON a.artist_id = ahs.artist_id
        //          GROUP BY song_id) c
        //     ON s.song_id = c.song_id
        //     GROUP BY s.song_id
        // ) h
        // ON songs.song_id = h.song_id
        // SET songs.hash = h.hash;

        // NOTE: This adds the hash to the artist_has_song table
        // UPDATE artist_has_song ahs
        // INNER JOIN songs s ON ahs.song_id = s.song_id
        // SET ahs.hash = s.hash;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
