<?php

namespace App\Http\Request;

use App\Rules\MaxImageSizeInMb;
use App\Rules\MinImageSize;
use App\Rules\ValidImageMime;
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
                new ValidImageMime(),
                new MaxImageSizeInMb(),
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
            'name.min' => 'The name must be at least 2 characters.',
            'position_id.integer' => 'The position id must be an integer.',
            'email.email' => 'The email must be a valid email address.',
        ];
    }
}
