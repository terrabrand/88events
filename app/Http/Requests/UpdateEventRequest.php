<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->event);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'location_type' => ['required', Rule::in(['physical', 'virtual', 'hybrid'])],
            'venue_address' => ['nullable', 'required_if:location_type,physical,hybrid', 'string'],
            'venue_name' => ['nullable', 'string'],
            'venue_city' => ['nullable', 'string'],
            'venue_state' => ['nullable', 'string'],
            'venue_country' => ['nullable', 'string'],
            'venue_lat' => ['nullable', 'numeric'],
            'venue_lng' => ['nullable', 'numeric'],
            'venue_google_place_id' => ['nullable', 'string'],
            'streaming_url' => ['nullable', 'required_if:location_type,virtual,hybrid', 'url'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', Rule::in(['draft', 'published', 'cancelled'])],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tax_type' => ['required', Rule::in(['none', 'inclusive', 'exclusive'])],
            'tax_rate' => ['required_if:tax_type,inclusive,exclusive', 'numeric', 'min:0', 'max:100'],
            'allow_promoters' => ['nullable', 'boolean'],
            'commission_type' => ['required_if:allow_promoters,true', Rule::in(['flat', 'percentage'])],
            'commission_rate' => ['required_if:allow_promoters,true', 'numeric', 'min:0'],
            'venue_id' => ['nullable', 'exists:venues,id'],
            'has_seat_mapping' => ['nullable', 'boolean'],
        ];
    }
}
