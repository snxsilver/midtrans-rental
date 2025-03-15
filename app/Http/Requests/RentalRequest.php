<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RentalRequest extends FormRequest
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
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'regex:/^(\08|8)[1-9][0-9]{6,11}$/'],
            'date' => ['required','date'],
            'service_id' => ['required', 'exists:services,id'],
            'total' => ['required', 'numeric','digits_between:0,11'],
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => ':attribute tidak boleh kosong.',
            'string' => ':attribute tidak valid',
            'date' => ':attribute tidak valid',
            'numeric' => ':attribute tidak valid',
            'regex' => ':attribute tidak valid',
            'exists' => ':attribute tidak valid',
            'max' => ':attribute melebihi :max karakter.',
            'digits_between' => ':attribute maksimal 11 digit.'
        ];
    }

    public function attributes()
    {
        $attributes = [
            'first_name' => 'Nama Depan',
            'last_name' => 'Nama Belakang',
            'email' => 'Email',
            'phone' => 'No HP',
            'date' => 'Tanggal',
            'service_id' => 'Layanan',
            'total' => 'Biaya',
        ];

        return $attributes;
    }

    protected function failedValidation(Validator $validator)
    {
        return redirect()->back()->withErrors($validator)->withInput();
        // throw new HttpResponseException($this->redirectWithErrors($validator));
    }

    // protected function redirectWithErrors(Validator $validator)
    // {
    //     return redirect()->back()->withErrors($validator)->withInput();
    // }
}
