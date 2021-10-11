<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CalendarStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [
                'required',
                Rule::unique('calendars')->ignore($this->calendar->id ?? null),
                'max:255'
            ],
            'color' => [
                'required',
                Rule::unique('calendars')->ignore($this->calendar->id ?? null),
                'max:7'
            ],
            'token' => 'required',
            'refresh_token' => 'required',
        ];
    }
}
