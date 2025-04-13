<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->foreignId('voter_id')->constrained('users')->onDelete('cascade');
            $table->string('hash', 64);
            $table->string('previous_hash', 64)->nullable();
            $table->timestamp('timestamp');
            $table->integer('nonce');
            $table->timestamps();

            // Ensure one vote per voter per election
            $table->unique(['election_id', 'voter_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
}; 