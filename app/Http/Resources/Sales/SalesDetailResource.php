<?php

namespace App\Http\Resources\Sales;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesDetailResource extends JsonResource
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
            't_sales_id' => $this->t_sales_id,
            'm_product_id' => $this->m_product_id,
            'product' => new ProductResource($this->product),
            'm_product_detail_id' => $this->m_product_detail_id,
            'total_item' => $this->total_item,
            'price' => $this->price,
            'discount_nominal' => $this->discount_nominal
        ];
    }
}
