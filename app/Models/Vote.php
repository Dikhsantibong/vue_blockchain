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
} 