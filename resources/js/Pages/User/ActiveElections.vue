<script setup>
import { Head } from '@inertiajs/vue3';
import UserSidebar from '@/Components/UserSidebar.vue';
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    elections: Array,
    userVotes: Array,
    blockchain: Array
});

const showVoteModal = ref(false);
const selectedElection = ref(null);
const selectedCandidate = ref(null);
const showBlockchainModal = ref(false);

const voteForm = useForm({
    election_id: '',
    candidate_id: '',
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const getRemainingTime = (endDate) => {
    const now = new Date();
    const end = new Date(endDate);
    const diff = end - now;
    
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    
    return `${days} hari ${hours} jam`;
};

const openVoteModal = (election, candidate) => {
    selectedElection.value = election;
    selectedCandidate.value = candidate;
    showVoteModal.value = true;
};

const submitVote = () => {
    voteForm.election_id = selectedElection.value.id;
    voteForm.candidate_id = selectedCandidate.value.id;

    voteForm.post(route('user.active-elections.vote'), {
        onSuccess: () => {
            showVoteModal.value = false;
            selectedElection.value = null;
            selectedCandidate.value = null;
            showBlockchainModal.value = true;
        }
    });
};

const hasVoted = (electionId) => {
    return props.userVotes.some(vote => vote.election_id === electionId);
};

const truncateHash = (hash) => {
    if (!hash) return '';
    return hash.substring(0, 8) + '...' + hash.substring(hash.length - 8);
};
</script>

<template>
    <Head title="Active Elections" />

    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800">
        <UserSidebar :user-votes="userVotes" :active-elections="elections" />
        
        <div class="pl-64">
            <!-- Main Content -->
            <main class="p-8">
                <!-- Header -->
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Active Elections</h1>
                        <p class="text-indigo-200">Participate in ongoing elections and cast your vote securely.</p>
                    </div>
                    <button 
                        @click="showBlockchainModal = true"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        View Blockchain
                    </button>
                </div>

                <!-- Elections Grid -->
                <div class="space-y-8">
                    <template v-for="election in elections" :key="election.id">
                        <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-indigo-700/30">
                            <!-- Election Header -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-xl font-semibold text-white">{{ election.title }}</h3>
                                    <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-sm">
                                        Ends in {{ getRemainingTime(election.end_date) }}
                                    </span>
                                </div>
                                <p class="text-indigo-200">{{ election.description }}</p>
                                <div class="mt-2 flex items-center space-x-4 text-sm text-indigo-300">
                                    <span>Start: {{ formatDate(election.start_date) }}</span>
                                    <span>End: {{ formatDate(election.end_date) }}</span>
                                </div>
                            </div>

                            <!-- Candidates Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div v-for="candidate in election.candidates" 
                                     :key="candidate.id"
                                     class="bg-indigo-900/40 rounded-lg overflow-hidden border border-indigo-700/30 transition-transform hover:scale-105">
                                    <!-- Candidate Image -->
                                    <div class="aspect-w-16 aspect-h-9 bg-indigo-800/50">
                                        <img :src="candidate.image" :alt="candidate.name" class="object-cover w-full h-full">
                                    </div>

                                    <!-- Candidate Info -->
                                    <div class="p-4">
                                        <h4 class="text-lg font-medium text-white mb-2">{{ candidate.name }}</h4>
                                        
                                        <!-- Vision & Mission -->
                                        <div class="mb-4">
                                            <h5 class="text-sm font-medium text-indigo-300 mb-1">Vision:</h5>
                                            <p class="text-sm text-indigo-200">{{ candidate.vision }}</p>
                                            
                                            <h5 class="text-sm font-medium text-indigo-300 mt-2 mb-1">Mission:</h5>
                                            <p class="text-sm text-indigo-200">{{ candidate.mission }}</p>
                                        </div>

                                        <!-- Vote Button -->
                                        <button 
                                            @click="openVoteModal(election, candidate)"
                                            :disabled="hasVoted(election.id)"
                                            :class="{
                                                'w-full px-4 py-2 rounded-lg transition-colors text-white text-center': true,
                                                'bg-blue-600 hover:bg-blue-700': !hasVoted(election.id),
                                                'bg-gray-600 cursor-not-allowed': hasVoted(election.id)
                                            }"
                                        >
                                            {{ hasVoted(election.id) ? 'Already Voted' : 'Vote for Candidate' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </main>
        </div>

        <!-- Vote Confirmation Modal -->
        <div v-if="showVoteModal" class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-gradient-to-br from-gray-900 to-indigo-900 rounded-xl shadow-xl max-w-md w-full p-6 border border-indigo-700/30">
                    <!-- Modal Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white">Confirm Your Vote</h2>
                        <p class="text-indigo-200 mt-1">Please review your selection before confirming.</p>
                    </div>

                    <!-- Vote Details -->
                    <div class="mb-6 space-y-4">
                        <div class="bg-white/5 rounded-lg p-4 border border-indigo-700/30">
                            <h3 class="text-lg font-medium text-white mb-2">{{ selectedElection?.title }}</h3>
                            <p class="text-sm text-indigo-200">You are voting for:</p>
                            <p class="text-white font-medium mt-1">{{ selectedCandidate?.name }}</p>
                        </div>

                        <div class="bg-yellow-500/10 rounded-lg p-4 border border-yellow-500/30">
                            <p class="text-yellow-300 text-sm">
                                Warning: This action cannot be undone. Your vote will be permanently recorded in the blockchain.
                            </p>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            @click="showVoteModal = false"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            @click="submitVote"
                            :disabled="voteForm.processing"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center space-x-2"
                        >
                            <span>Confirm Vote</span>
                            <svg v-if="voteForm.processing" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blockchain Modal -->
        <div v-if="showBlockchainModal" class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-gradient-to-br from-gray-900 to-indigo-900 rounded-xl shadow-xl max-w-4xl w-full p-6 border border-indigo-700/30">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-white">Blockchain Explorer</h2>
                            <p class="text-indigo-200 mt-1">View the complete voting blockchain history</p>
                        </div>
                        <button 
                            @click="showBlockchainModal = false"
                            class="text-gray-400 hover:text-white transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Blockchain Content -->
                    <div class="space-y-4 max-h-[600px] overflow-y-auto">
                        <div v-for="block in blockchain" :key="block.block_info.block_number" 
                             class="bg-white/5 rounded-lg p-4 border border-indigo-700/30">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white font-medium">Block #{{ block.block_info.block_number }}</span>
                                <span :class="block.is_valid ? 'text-green-400' : 'text-red-400'">
                                    {{ block.is_valid ? 'Valid ✓' : 'Invalid ✗' }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-indigo-300">Hash:</p>
                                    <p class="text-white font-mono">{{ truncateHash(block.block_info.hash) }}</p>
                                </div>
                                <div>
                                    <p class="text-indigo-300">Previous Hash:</p>
                                    <p class="text-white font-mono">{{ truncateHash(block.block_info.previous_hash) }}</p>
                                </div>
                                <div>
                                    <p class="text-indigo-300">Timestamp:</p>
                                    <p class="text-white">{{ block.block_info.timestamp }}</p>
                                </div>
                                <div>
                                    <p class="text-indigo-300">Nonce:</p>
                                    <p class="text-white">{{ block.block_info.nonce }}</p>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-indigo-700/30">
                                <h4 class="text-indigo-300 mb-2">Vote Details:</h4>
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <p class="text-indigo-300">Election:</p>
                                        <p class="text-white">{{ block.election_name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-indigo-300">Candidate:</p>
                                        <p class="text-white">{{ block.candidate_name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-indigo-300">Voter:</p>
                                        <p class="text-white">{{ block.voter_name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="mt-6 flex justify-end">
                        <button
                            @click="showBlockchainModal = false"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template> 