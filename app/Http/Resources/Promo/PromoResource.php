<?php

namespace App\Http\Resources\Promo;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoResource extends JsonResource
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
            'status' => $this->status,
            'expired_in_day' => $this->expired_in_day,
            'nominal_percentage' => $this->nominal_percentage,
            'nominal_rupiah' => $this->nominal_rupiah,
            'term_conditions' => $this->term_conditions,
            'photo_url' => !empty($this->photo) ? Storage::disk('public')->url($this->photo) : Storage::disk('public')->url('../assets/img/no-image.png'),
        ];
    }
}
