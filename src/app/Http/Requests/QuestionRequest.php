<?php

namespace App\Http\Requests;


use App\Enum\QuestionLabel;
use App\Enum\QuestionType;
use Illuminate\Validation\Rule;

class QuestionRequest extends BaseFormRequest
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

        $rules = [];

        if ($this->isStore()) {
            $rules = [
                'text' => 'required|min:5|max:500|string',
                'chapter_id' => 'required|exists:chapters,id',
                'expectation_time' => 'required|integer|min:1|max:30',
                'label' => [
                    'required',
                    Rule::in(array_column(QuestionLabel::cases(), 'value'))
                ],
                'type' => [
                    'required',
                    Rule::in(array_column(QuestionType::cases(), 'value'))
                ],
                'weight' => 'sometimes|required|integer|min:1|max:25',
                'hint' => 'sometimes|required|string|min:2|max:20',
            ];
        }

        if ($this->isUpdate()) {
            $rules = [
                'text' => 'sometimes|required|min:5|max:500|string',
                'chapter_id' => 'sometimes|required|exists:chapters,id',
                'expectation_time' => 'sometimes|required|integer|min:1|max:30',
                'label' => [
                    'sometimes',
                    'required',
                    Rule::in(array_column(QuestionLabel::cases(), 'value'))
                ],
                'type' => [
                    'sometimes',
                    'required',
                    Rule::in(array_column(QuestionType::cases(), 'value'))
                ],
                'weight' => 'sometimes|required|integer|min:1|max:25',
                'hint' => 'sometimes|required|string|min:2|max:20',

            ];
        }

        if ($this->isStore() || $this->isUpdate()) {
            $rules = array_merge($rules, $this->getAnswersRules());
        }


        return $rules;
    }

    private function getAnswersRules()
    {
        return [
            'answer_options' => 'required|array',
            'answer_options.*' => 'required|array',
            'answer_options.*.id' => 'sometimes|required|exists:answer_options,id',
            'answer_options.*.text' => 'required|string',
            'answer_options.*.is_correct' => [
                'required_if:type,' . QuestionType::MULTIPLE_CHOICE->value,
                'required_if:type,' . QuestionType::CONSTRUCTION->value,
            ],
//            'answer_options.*.question_id' => 'required|exists:questions,id',
            'answer_options.*.seq' => 'sometimes|required_if:type,' . QuestionType::SORTING->value,
            'answer_options.*.answer_id' => 'sometimes|required_if:type,' . QuestionType::MATCHING->value,
            'answer_options.*.blank' => 'sometimes|required_if:type,' . QuestionType::FILL_BLANK->value,
        ];
    }
}
