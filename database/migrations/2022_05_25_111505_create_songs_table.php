<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('song_id', 23)->unique();
            $table->text('name');
            $table->integer('length');
            $table->text('url');
            $table->text('img_url');
            $table->text('preview_url');
            $table->text('album_id');
            $table->integer('track_number');
            $table->boolean('explicit');
            $table->timestamps();

            $table->index('song_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('songs');
    }
}
