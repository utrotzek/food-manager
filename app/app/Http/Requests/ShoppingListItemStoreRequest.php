<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShoppingListItemStoreRequest extends FormRequest
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
            'good_id' => 'required_without:description|int|exists:App\Models\Good,id',
            'day_plan_id' => 'nullable|int|exists:App\Models\DayPlan,id',
            'unit_id' => 'nullable|int|exists:App\Models\Unit,id',
            'unit_amount' => 'numeric',
            'shopping_list_id' => 'required|exists:App\Models\ShoppingList,id',
            'descriptionAmount' => 'nullable|string',
            'description' => 'nullable|string'
        ];
    }
}
