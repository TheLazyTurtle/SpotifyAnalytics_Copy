<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayedResource extends JsonResource
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
            'songId' => $this->song_id,
            'datePlayed' => $this->date_played,
            'playedBy' => $this->played_by,
            'songName' => $this->song_name
        ];
        // return parent::toArray($request);
    }
}
