<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MealUpdateRequest extends FormRequest
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
            'meal_config_id' => [
                'int',
                'exists:meal_configs,id'
            ],
            'title' => [
                'required',
                Rule::unique('meals')->ignore($this->meal->id ?? null),
                'max:20'
            ],
        ];
    }
}
