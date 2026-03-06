<?php

namespace App\Livewire;

use App\Enums\PersonRole;
use App\Models\Person;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PersonIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $sortBy = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    #[Url]
    public string $role = '';

    #[Url]
    public string $joinDateFrom = '';

    #[Url]
    public string $joinDateTo = '';

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        $this->resetPage();
    }

    public function updatedJoinDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedJoinDateTo(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'role', 'joinDateFrom', 'joinDateTo']);
        $this->resetPage();
    }

    public function render()
    {
        $persons = Person::query()
            ->with('roles')
            ->search($this->search)
            ->filter([
                'role' => $this->role,
                'join_date_from' => $this->joinDateFrom,
                'join_date_to' => $this->joinDateTo,
            ])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);

        return view('livewire.person-index', [
            'persons' => $persons,
            'personRoles' => PersonRole::cases(),
        ])->title(__('Persons'));
    }
}
