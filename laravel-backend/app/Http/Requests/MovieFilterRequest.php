<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieFilterRequest extends FormRequest
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
            'with_genres'      => 'nullable|integer|exists:genres,id',
            'vote_average_gte' => 'nullable|numeric|min:0|max:10',
            'vote_average_lte' => 'nullable|numeric|min:0|max:10',
            'release_date_gte' => 'nullable|date',
            'release_date_lte' => 'nullable|date',
            'sort_by'          => 'nullable|string',
            'page'             => 'integer|min:1',
        ];
    }
}
