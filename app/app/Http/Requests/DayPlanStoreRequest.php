<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DayPlanStoreRequest extends FormRequest
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
            'recipe_id' => 'required|int|exists:recipes,id',
            'meal_id' => 'required|int|exists:meals,id',
            'day_id'  => 'required|int|exists:days,id',
            'description' => 'string|max:100'
        ];
    }
}
