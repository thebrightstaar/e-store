<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AboutUs extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      return [
          
     'id'=> $this->id
     'name'=> $this->name
    'facebook'=> $this->facebook
     'phone'=> $this->phone
     'whatsapp'=> $this->whatsapp
     'telegram'=> $this->telegram
      'created_at'=> $this->created_at->format('d/m/Y'),
      'updated_at'=> $this->updated_at->format('d/m/Y'),
      ];
    }
}
