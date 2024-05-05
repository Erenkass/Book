<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class customerFormRequest extends FormRequest
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
            'name'=>'required|max:50',
            'address'=>'required',
            'surname'=>'required',
            'message' => 'required|min:10'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'İsim Alanı Zorunludur',
            'surname.required'=>'Soyad Alanı Zorunludur',
            'address.required' => 'Adres Alanı Zorunludur',
            'message.required' => 'Mesaj Alanı Zorunludur',
        ];
    }
}
