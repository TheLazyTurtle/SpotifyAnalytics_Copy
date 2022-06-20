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
            'albumId' => $this->album_id,
            'name' => $this->name,
            'releaseDate' => $this->release_date,
            'primaryArtistId' => $this->primary_artist_id,
            'url' => $this->url,
            'imgUrl' => $this->img_url,
            'type' => $this->type
        ];
        // return parent::toArray($request);
    }
}
