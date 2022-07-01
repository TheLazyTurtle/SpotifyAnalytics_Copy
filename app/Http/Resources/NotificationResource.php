<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'notificationTypeId' => $this->notification_type_id,
            'receiverUserId' => $this->receiver_user_id,
            'senderuserId' => $this->sender_user_id
        ];
        // return parent::toArray($request);
    }
}
