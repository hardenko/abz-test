<?php

namespace App\Http\Request\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

final class GetUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => [
                'bail',
                'required',
                'integer',
                'exists:users,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'User ID is required.',
            'id.integer' => 'The user ID must be an integer.',
            'id.exists' => 'The user with the requested id does not exist.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['id' => $this->route('id')]);
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->toArray();

        if (isset($errors['id'])) {
            if (in_array('The user ID must be an integer.', $errors['id'])) {
                throw new HttpResponseException(response()->json([
                    'success' => false,
                    'message' => 'The user with the requested id does not exist.',
                    'fails' => [
                        'userId' => [
                            'The user ID must be an integer.',
                        ],
                    ],
                ], Response::HTTP_BAD_REQUEST));
            }

            if (in_array('The user with the requested id does not exist.', $errors['id'])) {
                throw new HttpResponseException(response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], Response::HTTP_NOT_FOUND));
            }
        }

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'fails' => $errors,
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
