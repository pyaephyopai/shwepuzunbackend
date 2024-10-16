<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;


class orderDetailsRequest extends FormRequest
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
            'user_id'  => 'required',
            'product_id' => 'required',
            'order_id' => 'required',
            'qty' => 'required | integer',
            'price' => 'required | integer',
            'total' => 'required | integer',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        Response::validationError($this, $validator);
    }
}
