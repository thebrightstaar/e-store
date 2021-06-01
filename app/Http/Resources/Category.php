<?php

namespace app\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'id'               => $this->id,
            'catrgory_name', 'up_name','parent_id','is_active'             
            'catrgory_name'             => $this->catrgory_name,
            'up_name'             => $this->up_name,
            'parent_id'     => $this->parent_id,
            'is_active'      => $this->is_active,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}