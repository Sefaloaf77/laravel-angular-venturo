<?php

namespace App\Http\Resources\Sales;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Sales\SalesDetailResource;

class SalesResource extends JsonResource
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
            'customer_id' => $this->m_customer_id,
            'name' => $this->customer->name,
            'voucher_id'=>$this->m_voucher_id,
            // 'total voucher' =>$this->voucher->total_voucher,
            'voucher_nominal'=>$this->voucher_nominal,
            'discount_id'=>$this->m_discount_id,
            'date'=>$this->date,
            'details' => SalesDetailResource::collection($this->details)
        ];
    }
}
