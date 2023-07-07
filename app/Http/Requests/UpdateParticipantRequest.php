<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Enums\Grade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $stringValidation = 'required|string|max:255';
        return [
            'fullName' => $stringValidation,
            'grade' => [new Enum(Grade::class), 'required'],
            'institution' => $stringValidation,
            'studentId' => $stringValidation,
            'gender' => [new Enum(Gender::class), 'required'],
            'phone' => 'required|numeric',
            'avatar' => 'file|mimes:png,jpg|max:5120',
            'photoIdentity' => 'required|file|mimes:png,jpg|max:10240',
        ];
    }
}
