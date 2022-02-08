<?php

namespace App\Http\Resources;

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
            'cities' => [
                'province_id' => $this->city->province->id,
                'province_name' => $this->city->province->name,
                'city_id' => $this->city->id,
                'city_name' => $this->city->name,
                'city_type' => $this->city->type,
                'city_postalcode' => $this->city->postal_code,
            ],
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'roles' => [
                'role_id' => $this->roles[0]->id,
                'role_name' => $this->roles[0]->name,
            ],
        ];
    }
}
