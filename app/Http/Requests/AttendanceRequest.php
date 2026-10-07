<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Clean the input before the rules run
    protected function prepareForValidation(): void
    {
        $this->merge([
            'student_number' => trim((string) $this->input('student_number')),
            'name'           => preg_replace('/\s+/', ' ', trim((string) $this->input('name'))),
        ]);
    }

    public function rules(): array
    {
        return [
            // Letters, numbers and dashes only, e.g. 2024-0001
            'student_number' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9\-]+$/'],

            // Letters (including ñ, accents), spaces, dots, apostrophes, hyphens
            'name'           => ['required', 'string', 'min:2', 'max:100', "regex:/^[\pL\s.'\-]+$/u"],
        ];
    }

    public function messages(): array
    {
        return [
            'student_number.required' => 'Enter your student number.',
            'student_number.max'      => 'Student number is too long (max 20 characters).',
            'student_number.regex'    => 'Student number may only contain letters, numbers and dashes.',

            'name.required' => 'Enter your full name.',
            'name.min'      => 'Name is too short.',
            'name.max'      => 'Name is too long (max 100 characters).',
            'name.regex'    => 'Name may only contain letters, spaces, periods, apostrophes and hyphens.',
        ];
    }
}