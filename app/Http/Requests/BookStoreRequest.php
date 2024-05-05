<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;//izin veriyoruz
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|max:50',
            'price'=>'required',

        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'İsim Alanı Zorunludur',
            'price.required' => 'Fiyat Alanı Zorunludur',
            'name.max'=>'Kitabın ismi 50 karakternden fazla olamaz',
        ];
    }
}
