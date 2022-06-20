<?php

namespace App\Http\Resources;

use App\Models\Song;
use Illuminate\Http\Resources\Json\JsonResource;

class SongResource extends JsonResource
{
    public $colletcs = Song::class;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->song_id,
            'name' => $this->name,
            'length' => $this->length,
            'imgUrl' => $this->img_url,
            'url' => $this->url,
            'previewUrl' => $this->preview_url,
            'albumId' => $this->album_id,
            'trackNumber' => $this->track_number,
            'explicit' => $this->explicit,
            'artists' => Song::artists($this->song_id),
        ];
        // return parent::toArray($request);
    }
}
