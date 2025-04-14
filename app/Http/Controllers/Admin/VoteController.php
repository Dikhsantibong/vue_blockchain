<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function index()
    {
        $voteStatistics = Election::with(['candidates' => function ($query) {
            $query->withCount('votes');
        }])
        ->withCount('votes')
        ->get()
        ->map(function ($election) {
            $totalVotes = $election->votes_count;
            $candidates = $election->candidates->map(function ($candidate) use ($totalVotes) {
                return [
                    'id' => $candidate->id,
                    'name' => $candidate->name,
                    'votes_count' => $candidate->votes_count,
                    'percentage' => $totalVotes > 0 
                        ? round(($candidate->votes_count / $totalVotes) * 100, 2) 
                        : 0
                ];
            });

            return [
                'id' => $election->id,
                'title' => $election->title,
                'start_date' => $election->start_date,
                'end_date' => $election->end_date,
                'status' => $election->status,
                'total_votes' => $totalVotes,
                'candidates' => $candidates
            ];
        });

        return Inertia::render('Admin/Votes/Index', [
            'statistics' => $voteStatistics
        ]);
    }
} 