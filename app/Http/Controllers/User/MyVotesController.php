<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MyVotesController extends Controller
{
    public function index()
    {
        $votes = Vote::with(['election', 'candidate'])
            ->where('voter_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($vote) {
                return [
                    'id' => $vote->id,
                    'election' => [
                        'title' => $vote->election->title,
                        'start_date' => $vote->election->start_date->format('Y-m-d H:i:s'),
                        'end_date' => $vote->election->end_date->format('Y-m-d H:i:s'),
                    ],
                    'candidate' => [
                        'name' => $vote->candidate->name,
                        'image' => $vote->candidate->image,
                    ],
                    'timestamp' => $vote->created_at->format('Y-m-d H:i:s'),
                    'block_info' => [
                        'hash' => $vote->hash,
                        'previous_hash' => $vote->previous_hash,
                        'nonce' => $vote->nonce
                    ]
                ];
            });

        return Inertia::render('User/MyVotes', [
            'votes' => $votes
        ]);
    }
} 