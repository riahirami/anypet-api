<?php

namespace App\Http\Requests\Ad;

use Illuminate\Foundation\Http\FormRequest;

class AdRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:30',
            'description' => 'required|string|min:12|max:255',
            'country' => 'required|string|min:5|max:30',
            'state' => 'required|string|min:3|max:30',
            'city' => 'required|string|min:3|max:30',
            'street' => 'required|string|min:3|max:30',
            'postal_code' => 'required|string|min:3|max:12',
        ];
    }
}
