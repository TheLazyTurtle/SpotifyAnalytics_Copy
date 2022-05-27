<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Played extends Model
{
    use HasFactory;
    protected $table = 'played';
    protected $fillable = [
        'song_id',
        'date_played',
        'played_by',
        'song_name'
    ];

    // Played has one song
    public function song()
    {
        return $this->belongsTo(Song::class, 'song_id', 'song_id');
    }
}
