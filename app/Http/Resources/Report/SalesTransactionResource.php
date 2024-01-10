<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Sales\SalesDetailResource;

class SalesTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $details = $this->details; // Kumpulan detail penjualan

        $detailsArray = $details->map(function ($detail) {
            return [
                'menu' => $detail->product->name,
                'menu_id' => $detail->product->id,
                'jumlah' => $detail->total_item,
                'harga' => $detail->product->price,
                'total_harga' => $detail->product->price * $detail->total_item,
            ];
        });

        return [
            'no_struk' => $this->id,
            'customer_id' => $this->customer->id ?? null,
            'customer_name' => $this->customer->name ?? null,
            'date_transaction' => $this->date ?? null,
            'promo_percentage' => $this->voucher->promo->nominal_percentage ?? null,
            'promo_rupiah' => $this->voucher->promo->nominal_rupiah ?? null,
            'total_bayar' => $details->sum(function ($detail) {
                return $detail->product->price - ($this->voucher->promo->nominal_percentage ?? 0);
            }),
            'details' => $detailsArray,
            // 'details' => SalesDetailResource::collection($this->details),
        ];
    }
}
