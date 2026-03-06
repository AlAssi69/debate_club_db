<?php

namespace App\Http\Requests\Debate;

use App\Enums\DebateType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDebateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(DebateType::class)],
            'date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'outcome' => ['nullable', 'string'],
            'participants' => ['nullable', 'array'],
            'participants.*.person_id' => ['required_with:participants', 'exists:persons,id'],
            'participants.*.role' => ['required_with:participants', 'string'],
        ];
    }
}
