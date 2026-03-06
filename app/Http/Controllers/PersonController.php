<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\Person\UpdatePersonRequest;
use App\Models\Person;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PersonController extends Controller
{
    public function create(): View
    {
        return view('persons.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(StorePersonRequest $request): RedirectResponse
    {
        $person = DB::transaction(function () use ($request) {
            $person = Person::create($request->safe()->except('roles'));

            if ($request->has('roles')) {
                $person->roles()->sync($request->input('roles'));
            }

            return $person;
        });

        return redirect()->route('persons.index')
            ->with('success', "Person \"{$person->full_name}\" created successfully.");
    }

    public function show(Person $person): View
    {
        $person->load(['roles', 'trainingSessions', 'debates']);

        return view('persons.show', compact('person'));
    }

    public function edit(Person $person): View
    {
        $person->load('roles');

        return view('persons.edit', [
            'person' => $person,
            'roles' => Role::all(),
        ]);
    }

    public function update(UpdatePersonRequest $request, Person $person): RedirectResponse
    {
        DB::transaction(function () use ($request, $person) {
            $person->update($request->safe()->except('roles'));

            if ($request->has('roles')) {
                $person->roles()->sync($request->input('roles'));
            }
        });

        return redirect()->route('persons.index')
            ->with('success', "Person \"{$person->full_name}\" updated successfully.");
    }

    public function destroy(Person $person): RedirectResponse
    {
        $name = $person->full_name;

        DB::transaction(function () use ($person) {
            $person->delete();
        });

        return redirect()->route('persons.index')
            ->with('success', "Person \"{$name}\" deleted successfully.");
    }
}
