<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Http\Requests\BulkAttendanceRequest;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function edit(TrainingSession $trainingSession): View
    {
        $trainingSession->load('participants');

        return view('training-sessions.attendance', [
            'session' => $trainingSession,
        ]);
    }

    public function update(BulkAttendanceRequest $request, TrainingSession $trainingSession): RedirectResponse
    {
        DB::transaction(function () use ($request, $trainingSession) {
            foreach ($request->input('attendance') as $entry) {
                $trainingSession->participants()->updateExistingPivot(
                    $entry['person_id'],
                    ['status' => $entry['status']]
                );
            }
        });

        return redirect()->route('training-sessions.show', $trainingSession)
            ->with('success', 'Attendance updated successfully.');
    }
}
