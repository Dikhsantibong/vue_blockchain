<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index(Election $election)
    {
        return Inertia::render('Candidates', [
            'election' => $election,
            'candidates' => $election->candidates()->get()
        ]);
    }

    public function store(Request $request, Election $election)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $image = $request->file('image')->store('candidates', 'public');

        $election->candidates()->create([
            'name' => $request->name,
            'vision' => $request->vision,
            'mission' => $request->mission,
            'image' => $image
        ]);

        return redirect()->back()->with('success', 'Candidate added successfully.');
    }

    public function destroy(Election $election, Candidate $candidate)
    {
        if ($candidate->image) {
            Storage::disk('public')->delete($candidate->image);
        }
        
        $candidate->delete();

        return redirect()->back()->with('success', 'Candidate deleted successfully.');
    }
} 