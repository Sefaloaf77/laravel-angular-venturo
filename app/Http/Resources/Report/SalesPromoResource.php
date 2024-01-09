<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesPromoResource extends JsonResource
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
            'date_transaction' => $this->date ?? null,
            'customer_name' => $this->customer->name ?? null,
            'promo_name' => $this->voucher->promo->name ?? null,
        ];
    }
}
