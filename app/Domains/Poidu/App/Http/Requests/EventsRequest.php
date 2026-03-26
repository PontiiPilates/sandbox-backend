<?php

namespace App\Domains\Poidu\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'column' => 'in:category',
            'value' => 'integer',
            'sort' => 'in:price_min,price_max,date_start',
            'direction' => 'in:asc,desc',
            'search' => 'min:3|max:100',
        ];
    }
}
