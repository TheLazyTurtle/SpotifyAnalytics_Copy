<?php

namespace App\Http\Resources;

use App\Models\Artist;
use App\Models\Song;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->album_id,
            'name' => $this->name,
            'releaseDate' => $this->release_date,
            'url' => $this->url,
            'imgUrl' => $this->img_url,
            'type' => $this->type,
            'albumArtist' => new ArtistResource(Artist::where('artist_id', $this->primary_artist_id)->first()),
            'songs' => SongResource::collection(Song::where('album_id', $this->album_id)->get())
        ];
        // return parent::toArray($request);
    }
}
