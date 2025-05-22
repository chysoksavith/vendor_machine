<?php

namespace App\Http\Requests;


class UpdateCategoryRequest extends StoreCategoryRequest
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
        $rules = parent::rules();
        $rules['parent_id'] = [
            'nullable',
            'exists:categories,id',
            function ($attribute, $value, $fail) {
                if ($value == $this->category->id) {
                    $fail('A category cannot be its own parent.');
                }
            }
        ];
        return $rules;
    }
}
