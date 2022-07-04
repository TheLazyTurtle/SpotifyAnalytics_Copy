<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderItemDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (isset($this->img_url)) {
            return [
                'y' => $this->y,
                'imgUrl' => $this->img_url
            ];
        }

        if ($this->y == 'null' || $this->y == NULL) {
            return [
                'y' => 0
            ];
        }

        return [
            'y' => $this->y,
        ];
        // return parent::toArray($request);
    }
}
