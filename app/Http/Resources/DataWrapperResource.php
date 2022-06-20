<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DataWrapperResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (isset($this->object)) {
            return [
                'x' => $this->x,
                'y' => $this->y,
                'object' => $this->object,
            ];
        };
        return [
            'x' => $this->x,
            'y' => $this->y,
        ];
        // return parent::toArray($request);
    }
}
