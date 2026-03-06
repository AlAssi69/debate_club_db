<?php

namespace App\Livewire;

use App\Models\TrainingSession;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TrainingSessionIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $sortBy = 'scheduled_date';

    #[Url]
    public string $sortDirection = 'desc';

    #[Url]
    public string $category = '';

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

    public function updatedCategory(): void
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
        $this->reset(['search', 'category', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function render()
    {
        $sessions = TrainingSession::query()
            ->with(['trainers', 'trainees'])
            ->search($this->search)
            ->filter([
                'category' => $this->category,
                'date_from' => $this->dateFrom,
                'date_to' => $this->dateTo,
            ])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);

        return view('livewire.training-session-index', [
            'sessions' => $sessions,
        ])->title(__('Training Sessions'));
    }
}
