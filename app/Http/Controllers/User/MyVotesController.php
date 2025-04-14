<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
                        'id' => $vote->election->id,
                        'title' => $vote->election->title,
                        'start_date' => $vote->election->start_date,
                        'end_date' => $vote->election->end_date,
                    ],
                    'candidate' => [
                        'id' => $vote->candidate->id,
                        'name' => $vote->candidate->name,
                        'image' => $vote->candidate->image_url,
                    ],
                    'timestamp' => $vote->timestamp,
                    'block_info' => [
                        'hash' => $vote->hash,
                        'previous_hash' => $vote->previous_hash,
                        'nonce' => $vote->nonce,
                    ],
                ];
            });

        return Inertia::render('User/MyVotes', [
            'votes' => $votes
        ]);
    }

    public function downloadPdf($voteId)
    {
        $vote = Vote::with(['election', 'candidate', 'voter'])
            ->where('voter_id', Auth::id())
            ->findOrFail($voteId);

        // Generate QR Code
        $verifyUrl = route('votes.verify', $vote->election_id);
        
        // Ensure temp directory exists
        $tempPath = storage_path('app/public/temp');
        if (!File::exists($tempPath)) {
            File::makeDirectory($tempPath, 0755, true);
        }
        
        // Generate and save QR code
        $qrFilename = 'qr-' . $vote->id . '.svg';
        $qrPath = $tempPath . '/' . $qrFilename;
        
        QrCode::size(200)
            ->format('svg')
            ->generate($verifyUrl, $qrPath);

        $data = [
            'vote' => $vote,
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'qr_path' => $qrPath
        ];

        $pdf = Pdf::loadView('pdf.vote-proof', $data);
        
        // Delete temporary QR code file after generating PDF
        if (File::exists($qrPath)) {
            File::delete($qrPath);
        }
        
        return $pdf->download('vote-' . $vote->id . '-proof.pdf');
    }
} 