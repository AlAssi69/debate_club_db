<?php

namespace App\Http\Controllers;

use App\Http\Requests\Debate\StoreDebateRequest;
use App\Http\Requests\Debate\UpdateDebateRequest;
use App\Models\Debate;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DebateController extends Controller
{
    public function create(): View
    {
        return view('debates.create', [
            'persons' => Person::orderBy('first_name')->get(),
        ]);
    }

    public function store(StoreDebateRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $debate = Debate::create($request->safe()->except('participants'));

            $this->syncParticipants($debate, $request->input('participants', []));
        });

        return redirect()->route('debates.index')
            ->with('success', 'Debate created successfully.');
    }

    public function show(Debate $debate): View
    {
        $debate->load('participants');

        return view('debates.show', compact('debate'));
    }

    public function edit(Debate $debate): View
    {
        $debate->load('participants');

        return view('debates.edit', [
            'debate' => $debate,
            'persons' => Person::orderBy('first_name')->get(),
        ]);
    }

    public function update(UpdateDebateRequest $request, Debate $debate): RedirectResponse
    {
        DB::transaction(function () use ($request, $debate) {
            $debate->update($request->safe()->except('participants'));

            $this->syncParticipants($debate, $request->input('participants', []));
        });

        return redirect()->route('debates.index')
            ->with('success', 'Debate updated successfully.');
    }

    public function destroy(Debate $debate): RedirectResponse
    {
        DB::transaction(function () use ($debate) {
            $debate->delete();
        });

        return redirect()->route('debates.index')
            ->with('success', 'Debate deleted successfully.');
    }

    protected function syncParticipants(Debate $debate, array $participants): void
    {
        $pivotData = [];

        foreach ($participants as $participant) {
            $personId = $participant['person_id'];
            $pivotData[$personId] = ['role' => $participant['role']];
        }

        $debate->participants()->sync($pivotData);
    }
}
