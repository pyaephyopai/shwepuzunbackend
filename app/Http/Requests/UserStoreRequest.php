<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'gender' => 'nullable|integer|in:1,2',
            'address' => 'nullable|string',
            'region' => 'nullable|string',
            'phone_number' => 'required|string|unique:users,phone_number',
            'role_id' => 'required || exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        Response::validationError($this, $validator);
    }
}
