<?php

namespace App\Http\Resources\Promo;

use App\Http\Resources\Customer\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
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
            'customer_id' => $this->customer->id ?? null,
            'customer_name' => $this->customer->name ?? null,
            'promo_id' => $this->promo->id ?? null,
            'promo_name' => $this->promo->name ?? null,
            'status' => $this->status,
            // 'competency_matrix' => $this->competency_matrix,
            // 'late_under_3' => $this->late_under_3,
            // 'full_absensi' => $this->full_absensi,

            // 'id' => $this->id,
            // 'customer_id' => $this->customer->id ?? null,
            // 'customer_name' => $this->customer->name ?? null,
            // 'promo_id' => $this->promo->id ?? null,
            // 'promo_name' => $this->promo->name ?? null,
            // 'is_available' => $this->is_available
        ];
    }
}
