<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => 'nullable|email|max:255|unique:users',
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|max:20',
        ];
    }
}
