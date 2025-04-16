<?php

namespace App\Http\Request;

use App\Rules\MinImageSize;
use Illuminate\Foundation\Http\FormRequest;

final class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:60'
            ],
            'email' => [
                'required',
                'email',
            ],
            'phone' => [
                'required',
                'regex:/^\+380\d{9}$/',
            ],
            'position_id' => [
                'required',
                'integer',
                'exists:positions,id'
            ],
            'photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg',
                'max:5120',
                new MinImageSize(),
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'email.email' => 'The email must be a valid email address.',
            'photo.max' => 'The photo may not be greater than 5 Mbytes.',
        ];
    }
}
