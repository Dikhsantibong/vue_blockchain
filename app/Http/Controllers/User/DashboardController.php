<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $elections = Election::with('candidates')->get();
        $candidates = Candidate::all();
        $userVotes = Vote::with(['election', 'candidate'])
            ->where('voter_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Dashboard', [
            'elections' => $elections,
            'candidates' => $candidates,
            'userVotes' => $userVotes,
            'tab' => $request->get('tab', 'active-elections')
        ]);
    }
} 