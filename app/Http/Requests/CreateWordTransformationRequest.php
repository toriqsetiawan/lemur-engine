<?php

namespace App\Http\Requests;

use App\Models\SetValue;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\WordTransformation;
use Illuminate\Validation\Rule;

class CreateWordTransformationRequest extends HiddenIdRequest
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
        $rules = WordTransformation::$rules;

        $rules['first_person_form'] = [
            'required',
            Rule::unique('word_transformations')
                ->where('language_id', $this->language_id)
                ->whereNull('deleted_at')
        ];
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'value.first_person_form' =>
                'Duplicate record - a transformation for this word in this language already exists.',
        ];
    }
}
