<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_data' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'address' => $this->address,
                'phone' => $this->phone_number,
                "region" => $this->region
            ],
            'token' => $this->token,
            'role' => $this->role,
            'permission' => $this->permission,
        ];
    }
}
