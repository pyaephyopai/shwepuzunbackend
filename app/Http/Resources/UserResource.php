<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $user = User::where('id', $this->id)->first();

        $accountStatus = $user->email_verified_at ? true : false;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'gender' => $this->gender,
            'address' => $this->address,
            'region' => $this->region,
            'role_id' => $this->role_id,
            'image' =>  $this->image ?  asset('storage/userProfileImages/' . $this->image) : null,
            'image_name' => $this->image,
            'account_status' => $accountStatus,
        ];
    }
}
