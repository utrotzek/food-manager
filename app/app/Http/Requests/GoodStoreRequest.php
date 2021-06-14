<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class GoodStoreRequest extends BaseFormRequest
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
                Rule::unique('goods')->ignore($this->good->id ?? null),
                'max:255'
            ],
            'carbs' => 'int',
            'fat' => 'int',
            'protein' => 'int',
            'kcal' => 'int',
            'piece_in_gramm' => 'int',
            'good_group_id' => 'required|int|exists:good_groups,id',
        ];
    }
}
