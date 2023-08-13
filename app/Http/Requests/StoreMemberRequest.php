<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Propaganistas\LaravelPhone\PhoneNumber;

class StoreMemberRequest extends FormRequest
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
        try {
            $phone = new PhoneNumber($this->phone, 'KE');
            $this->merge(['phone' => $phone->formatE164()]);
        } catch (\Throwable $th) {
            throw $th;
        }

        return [
            'name' => 'required',
            'phone' => 'required|unique:users',
            'email' => 'nullable|unique:users|email',
        ];
    }
}
