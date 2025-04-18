<?php

namespace App\Http\Request;

use App\Dto\CreateUserDto;
use App\Rules\MinImageSize;
use App\Rules\ValidPhone;
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
                new ValidPhone(),
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

    public function messages(): array
    {
        return [
            'name.min' => 'The name must be at least 2 characters.',
            'position_id.integer' => 'The position id must be an integer.',
            'email.email' => 'The email must be a valid email address.',
            'photo.max' => 'The photo may not be greater than 5 Mbytes.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function toDto(): CreateUserDto
    {
        return CreateUserDto::fromArray($this->validated());
    }
}
