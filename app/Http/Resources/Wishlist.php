<?php

namespace app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use app\Http\Resources\Product as ProductResource;

class Wishlist extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'product'    => new ProductResource($this->product),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}