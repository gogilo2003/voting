<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoteRequest extends FormRequest
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
            "voter" => "required|integer|exists:users,id",
            "election" => "required|integer|exists:elections,id",
            "votes" => "required|array|min:1",
            "votes.*.position" => "required|integer|exists:positions,id",
            "votes.*.candidate" => "required|integer|exists:candidates,id",
        ];
    }
}
