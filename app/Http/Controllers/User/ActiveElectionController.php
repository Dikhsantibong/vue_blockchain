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
        $blockchain = Vote::getBlockchain();

        return inertia('User/ActiveElections', [
            'elections' => $elections,
            'userVotes' => $userVotes,
            'blockchain' => $blockchain
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

        // Create new vote instance
        $vote = new Vote([
            'election_id' => $request->election_id,
            'candidate_id' => $request->candidate_id,
            'voter_id' => Auth::id(),
            'timestamp' => now(),
            'nonce' => 0
        ]);

        // Get previous hash
        $previousVote = Vote::latest()->first();
        $vote->previous_hash = $previousVote ? $previousVote->hash : '0';

        // Mine the block (generate hash with proof of work)
        $vote->mine(4); // Using difficulty level 4 (adjust as needed)

        // Save the vote
        $vote->save();

        // Get updated blockchain for response
        $blockchain = Vote::getBlockchain();

        return back()->with([
            'success' => 'Your vote has been recorded successfully and added to the blockchain.',
            'blockchainUpdate' => [
                'newBlock' => $vote->block_info,
                'blockchain' => $blockchain
            ]
        ]);
    }
} 