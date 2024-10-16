<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->route('id'))],
            'phone_number' => ['required', 'string', Rule::unique('users', 'phone_number')->ignore($this->route('id'))],
            'address' => 'nullable|string',
            'region' => 'nullable|string',
            'gender' => 'nullable|integer|in:1,2',
            'role_id' => 'required || exists:roles,id',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        Response::validationError($this, $validator);
    }
}
