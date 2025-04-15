<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class PositionListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                'string',
                'max:255',
            ],
        ];
    }
}
