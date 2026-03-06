<?php

namespace App\Http\Controllers;

use App\Enums\SessionRole;
use App\Http\Requests\TrainingSession\StoreTrainingSessionRequest;
use App\Http\Requests\TrainingSession\UpdateTrainingSessionRequest;
use App\Models\Person;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TrainingSessionController extends Controller
{
    public function index(): View
    {
        return view('training-sessions.index', [
            'sessions' => TrainingSession::with(['trainers', 'trainees'])
                ->latest('scheduled_date')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('training-sessions.create', [
            'persons' => Person::orderBy('first_name')->get(),
        ]);
    }

    public function store(StoreTrainingSessionRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $session = TrainingSession::create($request->safe()->except(['trainer_ids', 'trainee_ids']));

            $this->syncParticipants($session, $request->input('trainer_ids', []), $request->input('trainee_ids', []));
        });

        return redirect()->route('training-sessions.index')
            ->with('success', 'Training session created successfully.');
    }

    public function show(TrainingSession $trainingSession): View
    {
        $trainingSession->load(['trainers', 'trainees']);

        return view('training-sessions.show', [
            'session' => $trainingSession,
        ]);
    }

    public function edit(TrainingSession $trainingSession): View
    {
        $trainingSession->load(['trainers', 'trainees']);

        return view('training-sessions.edit', [
            'session' => $trainingSession,
            'persons' => Person::orderBy('first_name')->get(),
        ]);
    }

    public function update(UpdateTrainingSessionRequest $request, TrainingSession $trainingSession): RedirectResponse
    {
        DB::transaction(function () use ($request, $trainingSession) {
            $trainingSession->update($request->safe()->except(['trainer_ids', 'trainee_ids']));

            $this->syncParticipants($trainingSession, $request->input('trainer_ids', []), $request->input('trainee_ids', []));
        });

        return redirect()->route('training-sessions.index')
            ->with('success', 'Training session updated successfully.');
    }

    public function destroy(TrainingSession $trainingSession): RedirectResponse
    {
        DB::transaction(function () use ($trainingSession) {
            $trainingSession->delete();
        });

        return redirect()->route('training-sessions.index')
            ->with('success', 'Training session deleted successfully.');
    }

    protected function syncParticipants(TrainingSession $session, array $trainerIds, array $traineeIds): void
    {
        $pivotData = [];

        foreach ($trainerIds as $id) {
            $pivotData[$id] = ['role' => SessionRole::Trainer->value];
        }

        foreach ($traineeIds as $id) {
            if (! isset($pivotData[$id])) {
                $pivotData[$id] = ['role' => SessionRole::Trainee->value];
            }
        }

        $session->participants()->sync($pivotData);
    }
}
