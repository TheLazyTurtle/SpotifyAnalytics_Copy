<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // TODO: Add following / followers / has_following_request here
        return [
            'username' => $this->username,
            'imgUrl' => $this->img_url,
            'private' => $this->private
        ];
        // return parent::toArray($request);
    }
}
