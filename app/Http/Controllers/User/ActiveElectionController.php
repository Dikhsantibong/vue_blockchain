<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActiveElectionController extends Controller
{
    public function index()
    {
        $elections = Election::with('candidates')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        $userVotes = Vote::where('voter_id', Auth::id())->get();

        return inertia('User/ActiveElections', [
            'elections' => $elections,
            'userVotes' => $userVotes
        ]);
    }

    public function vote(Request $request)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,id',
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        $election = Election::findOrFail($request->election_id);
        
        // Check if election is active
        if (!$election->isActive()) {
            return back()->with('error', 'This election is not active.');
        }

        // Check if user has already voted
        if (Vote::where('voter_id', Auth::id())
            ->where('election_id', $request->election_id)
            ->exists()) {
            return back()->with('error', 'You have already voted in this election.');
        }

        // Create blockchain data
        $blockData = [
            'election_id' => $request->election_id,
            'candidate_id' => $request->candidate_id,
            'voter_id' => Auth::id(),
            'timestamp' => now()->timestamp,
            'previous_hash' => Vote::latest()->first()?->block_hash ?? '0',
        ];

        // Generate block hash using SHA256
        $blockHash = hash('sha256', json_encode($blockData));

        // Create vote record
        $vote = Vote::create([
            'election_id' => $request->election_id,
            'candidate_id' => $request->candidate_id,
            'voter_id' => Auth::id(),
            'block_hash' => $blockHash,
            'block_data' => $blockData,
        ]);

        return back()->with('success', 'Your vote has been recorded successfully.');
    }
} 