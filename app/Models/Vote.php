<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'candidate_id',
        'voter_id',
        'hash',
        'previous_hash',
        'timestamp',
        'nonce'
    ];

    protected $casts = [
        'timestamp' => 'datetime'
    ];

    protected $appends = ['block_info'];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }

    public function calculateHash()
    {
        $data = $this->election_id . 
                $this->candidate_id . 
                $this->voter_id . 
                $this->timestamp . 
                $this->previous_hash . 
                $this->nonce;
                
        return hash('sha256', $data);
    }

    public function mine($difficulty = 4)
    {
        $prefix = str_repeat("0", $difficulty);
        $this->nonce = 0;

        while (substr($this->calculateHash(), 0, $difficulty) !== $prefix) {
            $this->nonce++;
        }

        $this->hash = $this->calculateHash();
        return $this->hash;
    }

    public function getBlockInfoAttribute()
    {
        return [
            'block_number' => $this->id,
            'timestamp' => $this->timestamp->format('Y-m-d H:i:s'),
            'hash' => $this->hash,
            'previous_hash' => $this->previous_hash,
            'nonce' => $this->nonce,
            'data' => [
                'election_id' => $this->election_id,
                'candidate_id' => $this->candidate_id,
                'voter_id' => $this->voter_id
            ]
        ];
    }

    public static function getBlockchain()
    {
        return static::with(['election', 'candidate', 'voter'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($vote) {
                return [
                    'block_info' => $vote->block_info,
                    'election_name' => $vote->election->title,
                    'candidate_name' => $vote->candidate->name,
                    'voter_name' => $vote->voter->name,
                    'is_valid' => $vote->validateBlock()
                ];
            });
    }

    public function validateBlock()
    {
        // Verify hash
        $calculatedHash = $this->calculateHash();
        if ($this->hash !== $calculatedHash) {
            return false;
        }

        // Verify previous hash
        if ($this->id > 1) {
            $previousVote = static::find($this->id - 1);
            if ($previousVote && $this->previous_hash !== $previousVote->hash) {
                return false;
            }
        }

        return true;
    }
} 