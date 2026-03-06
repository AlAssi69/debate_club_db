<?php

namespace App\Livewire;

use App\Models\Debate;
use App\Models\Person;
use App\Models\TrainingSession;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $search = '';

    public function updatedSearch(): void
    {
        // Reactivity handled by Livewire; kept as hook point.
    }

    public function render()
    {
        $results = [];

        if (strlen($this->search) >= 2) {
            $results['persons'] = Person::search($this->search)->limit(5)->get();
            $results['training_sessions'] = TrainingSession::search($this->search)->limit(5)->get();
            $results['debates'] = Debate::search($this->search)->limit(5)->get();
        }

        return view('livewire.global-search', [
            'results' => $results,
            'hasResults' => collect($results)->flatten()->isNotEmpty(),
        ]);
    }
}
