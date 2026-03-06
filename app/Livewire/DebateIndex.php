<?php

namespace App\Livewire;

use App\Enums\DebateType;
use App\Models\Debate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class DebateIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $sortBy = 'date';

    #[Url]
    public string $sortDirection = 'desc';

    #[Url]
    public string $type = '';

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

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

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'type', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function render()
    {
        $debates = Debate::query()
            ->with('participants')
            ->search($this->search)
            ->filter([
                'type' => $this->type,
                'date_from' => $this->dateFrom,
                'date_to' => $this->dateTo,
            ])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);

        return view('livewire.debate-index', [
            'debates' => $debates,
            'debateTypes' => DebateType::cases(),
        ])->title(__('Debates'));
    }
}
