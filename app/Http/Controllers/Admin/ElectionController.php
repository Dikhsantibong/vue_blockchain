<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ElectionController extends Controller
{
    public function index()
    {
        return Inertia::render('Elections', [
            'elections' => Election::orderBy('created_at', 'desc')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
        ]);

        Election::create($request->all());

        return redirect()->back()->with('success', 'Election created successfully.');
    }

    public function update(Request $request, Election $election)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:upcoming,active,completed'
        ]);

        $election->update($request->all());

        return redirect()->back()->with('success', 'Election updated successfully.');
    }

    public function destroy(Election $election)
    {
        $election->delete();

        return redirect()->back()->with('success', 'Election deleted successfully.');
    }
} 