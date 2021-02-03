<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReserve extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'form' => 'required|array',
            'form.date' => 'required|date',
            'form.number' => 'required|integer|digits_between:1,99',
            'form.time' => 'required|date_format:H:i:s',
            'form.email' => 'required|email',
            'form.name' => 'required|string',
            'form.kana' => 'required|string|regex:/^[ぁ-ん]+$/',
            'form.phone_num' => 'required|regex:/^[0-9]{2,4}[0-9]{2,4}[0-9]{3,4}$/',
            'form.purpose' => 'nullable|integer',
            'form.request' => 'nullable|string',
            'shop_id' => 'required|alpha_num',
        ];
    }
}
