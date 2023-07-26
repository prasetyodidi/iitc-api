<?php

namespace App\Http\Requests;

use App\Helpers\Gender;
use App\Helpers\Grade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationRuleParser;

class UpdateParticipantRequest extends FormRequest
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
     * @return array<string, ValidationRuleParser|array|string>
     */
    public function rules(): array
    {
        $stringValidation = 'required|string|max:255';
        return [
            'fullName' => $stringValidation,
            'grade' => [Rule::in([Grade::STUDENT, Grade::COLLEGE_STUDENT]), 'required'],
            'institution' => $stringValidation,
            'studentId' => $stringValidation,
            'gender' => [Rule::in([Gender::MALE, Gender::FEMALE]), 'required'],
            'phone' => 'required|numeric',
            'avatar' => 'required|file|mimes:png,jpg|max:5120',
            'photoIdentity' => 'required|file|mimes:png,jpg|max:10240',
            'twibbon' => 'required|file|mimes:png,jpg|max:5120',
        ];
    }
}
