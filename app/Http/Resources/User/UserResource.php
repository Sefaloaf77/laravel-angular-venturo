<?php

namespace App\Http\Resources\User;

use Illuminate\Support\Facades\Storage;
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            // 'photo_url' => !empty($this->photo) ? Storage::disk('public')->url($this->photo) : Storage::disk('public')->url('../assets/img/no-image.png'),
            'photo_url' => !empty($this->photo) ? Storage::disk('public')->url($this->photo) : null,
            'updated_security' => $this->updated_security
        ];
    }
}
