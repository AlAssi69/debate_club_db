<?php

namespace App\Http\Controllers;

use App\Jobs\SyncPullJob;
use App\Jobs\SyncPushJob;
use App\Models\Debate;
use App\Models\Person;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SyncController extends Controller
{
    public function index(): View
    {
        return view('sync.index', [
            'lastPersonSync' => Person::whereNotNull('synced_at')->max('synced_at'),
            'lastSessionSync' => TrainingSession::whereNotNull('synced_at')->max('synced_at'),
            'lastDebateSync' => Debate::whereNotNull('synced_at')->max('synced_at'),
            'isConfigured' => ! empty(config('services.google.sheet_id')),
        ]);
    }

    public function pull(): RedirectResponse
    {
        if (! config('services.google.sheet_id')) {
            return redirect()->route('sync.index')
                ->with('error', 'Google Sheets is not configured. Set GOOGLE_SHEET_ID in your .env file.');
        }

        SyncPullJob::dispatch();

        return redirect()->route('sync.index')
            ->with('success', 'Pull sync job dispatched. Data will be updated shortly.');
    }

    public function push(): RedirectResponse
    {
        if (! config('services.google.sheet_id')) {
            return redirect()->route('sync.index')
                ->with('error', 'Google Sheets is not configured. Set GOOGLE_SHEET_ID in your .env file.');
        }

        $models = [
            ...Person::all(),
            ...TrainingSession::all(),
            ...Debate::all(),
        ];

        foreach ($models as $model) {
            SyncPushJob::dispatch($model, 'updated');
        }

        return redirect()->route('sync.index')
            ->with('success', count($models) . ' push sync jobs dispatched.');
    }
}
