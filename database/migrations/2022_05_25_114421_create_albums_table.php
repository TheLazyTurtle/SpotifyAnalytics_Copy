<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('album_id', 23)->unique();
            $table->text('name');
            $table->date('release_date');
            $table->string('primary_artist_id', 23);
            $table->text('url');
            $table->text('img_url');
            $table->text('type', 10);
            $table->timestamps();

            $table->index('album_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums');
    }
}
