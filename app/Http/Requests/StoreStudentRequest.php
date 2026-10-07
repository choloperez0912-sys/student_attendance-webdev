<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // the route is already protected by the auth middleware
    }

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
            'student_number' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9\-]+$/', 'unique:students,student_number'],
            'name'           => ['required', 'string', 'min:2', 'max:100', "regex:/^[\pL\s.'\-]+$/u"],
            'photo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // 2 MB
        ];
    }

    public function messages(): array
    {
        return [
            'student_number.required' => 'Enter the student number.',
            'student_number.max'      => 'Student number is too long (max 20 characters).',
            'student_number.regex'    => 'Student number may only contain letters, numbers and dashes.',
            'student_number.unique'   => 'This student number is already registered.',

            'name.required' => 'Enter the student\'s full name.',
            'name.min'      => 'Name is too short.',
            'name.max'      => 'Name is too long (max 100 characters).',
            'name.regex'    => 'Name may only contain letters, spaces, periods, apostrophes and hyphens.',

            'photo.image' => 'The file must be an image.',
            'photo.mimes' => 'Photo must be a JPG or PNG file.',
            'photo.max'   => 'Photo must be 2 MB or smaller.',
            'photo.uploaded' => 'The photo could not be uploaded. Try a file under 2 MB.',
        ];
    }
}