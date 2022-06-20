<?php

namespace App\Http\Resources;

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
        // TODO: Add releation
        return [
            'id' => $this->album_id,
            'name' => $this->name,
            'releaseDate' => $this->release_date,
            'albumArtist' => $this->album_artist,
            'url' => $this->url,
            'imgUrl' => $this->img_url,
            'type' => $this->type,
            'songs' => $this->album_songs,
        ];
        // return parent::toArray($request);
    }
}
