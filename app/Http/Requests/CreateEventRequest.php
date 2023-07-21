<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'created_by' => 'required|exists:users,id',
            'thumbnail' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'price' => 'required|numeric',
            'attendees' => 'required|numeric',
        ];
    }
}
