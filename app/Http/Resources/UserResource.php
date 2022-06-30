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
        if (isset($this->first_name)) {
            return [
                'id' => $this->id,
                'firstName' => $this->first_name,
                'lastName' => $this->last_name,
                'username' => $this->username,
                'email' => $this->email,
                'imgUrl' => $this->img_url,
                'following' => $this->following,
                'followersCount' => $this->followers_count,
                'followingCount' => $this->following_count,
                'private' => $this->private,
                'hasFollowingRequest' => $this->has_following_request,
                'isOwnAccount' => $this->is_own_account
            ];
        }
        return [
            'id' => $this->id,
            'username' => $this->username,
            'imgUrl' => $this->img_url,
            'following' => $this->following,
            'followersCount' => $this->followers_count,
            'followingCount' => $this->following_count,
            'private' => $this->private,
            'hasFollowingRequest' => $this->has_following_request,
            'isOwnAccount' => $this->is_own_account
        ];
        // return parent::toArray($request);
    }
}
