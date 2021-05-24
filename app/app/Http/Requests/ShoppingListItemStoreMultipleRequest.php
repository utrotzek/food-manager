<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShoppingListItemStoreMultipleRequest extends FormRequest
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
            'items' => 'required',
            'items.*.good_id' => 'required_without:description|int|exists:App\Models\Good,id',
            'items.*.day_plan_id' => 'nullable|int|exists:App\Models\DayPlan,id',
            'items.*.unit_id' => 'nullable|int|exists:App\Models\Unit,id',
            'items.*.unit_amount' => 'numeric',
            'items.*.shopping_list_id' => 'required|exists:App\Models\ShoppingList,id',
            'items.*.descriptionAmount' => 'nullable|string',
            'items.*.description' => 'nullable|string'
        ];
    }
}
