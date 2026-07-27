<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9._-]+$/',
                Rule::unique('users', 'username')->ignore($this->user()->id),
            ],
            'name' => ['required', 'string', 'max:255', Rule::unique('users', 'name')->ignore($this->user()->id)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => Str::lower(trim((string) $this->input('username'))),
        ]);
    }
}
