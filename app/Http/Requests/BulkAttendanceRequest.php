<?php

namespace App\Http\Requests;

use App\Enums\AttendanceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attendance' => ['required', 'array'],
            'attendance.*.person_id' => ['required', 'exists:persons,id'],
            'attendance.*.status' => ['required', Rule::enum(AttendanceStatus::class)],
        ];
    }
}
