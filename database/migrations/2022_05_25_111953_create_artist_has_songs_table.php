<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtistHasSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artist_has_song', function (Blueprint $table) {
            $table->increments('id');
            $table->string('song_id', 23)->unique();
            $table->string('artist_id', 23)->unique();
            $table->timestamps();

            $table->index('song_id');
            $table->index('artist_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('artist_has_songs');
    }
}
