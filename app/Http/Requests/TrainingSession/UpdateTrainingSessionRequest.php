<?php

namespace App\Http\Requests\TrainingSession;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'scheduled_date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'trainer_ids' => ['nullable', 'array'],
            'trainer_ids.*' => ['exists:persons,id'],
            'trainee_ids' => ['nullable', 'array'],
            'trainee_ids.*' => ['exists:persons,id'],
        ];
    }
}
