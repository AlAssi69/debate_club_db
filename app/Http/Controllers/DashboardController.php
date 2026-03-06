<?php

namespace App\Http\Controllers;

use App\Models\Debate;
use App\Models\Person;
use App\Models\TrainingSession;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'totalPersons' => Person::count(),
            'totalSessions' => TrainingSession::count(),
            'totalDebates' => Debate::count(),
            'upcomingSessions' => TrainingSession::where('scheduled_date', '>=', now()->toDateString())
                ->orderBy('scheduled_date')
                ->limit(5)
                ->get(),
            'recentDebates' => Debate::orderByDesc('date')
                ->limit(5)
                ->get(),
        ]);
    }
}
