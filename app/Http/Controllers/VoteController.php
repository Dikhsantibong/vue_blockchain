<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'election_id' => 'required|exists:elections,id',
            'candidate_id' => 'required|exists:candidates,id',
            'voter_id' => 'required|exists:users,id'
        ]);

        // Verify election is active
        $election = Election::findOrFail($validated['election_id']);
        $now = now();
        
        if ($now < $election->start_date || $now > $election->end_date) {
            throw ValidationException::withMessages([
                'election_id' => ['This election is not currently active.']
            ]);
        }

        // Check if user has already voted
        if (Vote::where('election_id', $validated['election_id'])
                ->where('voter_id', $validated['voter_id'])
                ->exists()) {
            throw ValidationException::withMessages([
                'voter_id' => ['You have already voted in this election.']
            ]);
        }

        try {
            return DB::transaction(function () use ($validated) {
                // Get the last vote to get previous hash
                $lastVote = Vote::latest()->first();
                
                $vote = new Vote([
                    'election_id' => $validated['election_id'],
                    'candidate_id' => $validated['candidate_id'],
                    'voter_id' => $validated['voter_id'],
                    'timestamp' => now(),
                    'previous_hash' => $lastVote ? $lastVote->hash : null,
                    'nonce' => 0
                ]);

                // Mine the vote (find valid hash)
                $vote->mine();
                
                // Save the vote
                $vote->save();

                return redirect()->back()->with('success', 'Your vote has been recorded successfully.');
            });
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'error' => ['Failed to record your vote. Please try again.']
            ]);
        }
    }

    

    public function verify($electionId)
    {
        $votes = Vote::where('election_id', $electionId)
                    ->orderBy('id')
                    ->get();

        $isValid = true;
        $previousHash = null;

        foreach ($votes as $vote) {
            // Verify the previous hash matches
            if ($vote->previous_hash !== $previousHash) {
                $isValid = false;
                break;
            }

            // Verify the hash is valid
            if ($vote->hash !== $vote->calculateHash()) {
                $isValid = false;
                break;
            }

            $previousHash = $vote->hash;
        }

        return response()->json([
            'is_valid' => $isValid,
            'total_votes' => $votes->count()
        ]);
    }
} 