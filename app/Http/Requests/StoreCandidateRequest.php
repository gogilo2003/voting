<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'candidates' => 'required|array|min:1',
            'candidates.*' => 'required|integer|exists:users,id',
            'election' => 'required|integer|exists:elections,id',
            'position' => 'required|integer|exists:positions,id',
        ];
    }
}
