<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class UserListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => [
                'nullable',
                'integer',
                'min:1'
            ],
            'count' => [
                'nullable',
                'integer',
                'between:1,100'
            ],
        ];
    }
}
