<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class RecipeStoreRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                Rule::unique('recipes')->ignore($this->recipe->id ?? null),
                'max:255'
            ],
            'rating' => 'numeric',
            'portion' => 'required|int',
            'steps' => 'array',
            'tags' => 'array',
            'ingredients.unitId' => 'int',
            'ingredients.goodId' => 'int',
            'ingredients.amount' => 'int',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.unique' => __('recipes.The title has already been taken')
        ];
    }
}
