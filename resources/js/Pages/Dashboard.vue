<script setup>
import { Head } from '@inertiajs/vue3';
import UserSidebar from '@/Components/UserSidebar.vue';
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    auth: Object,
    elections: Array,
    candidates: Array,
    userVotes: Array,
    tab: {
        type: String,
        default: 'active-elections'
    }
});

const currentTab = ref(props.tab);

const showVoteModal = ref(false);
const selectedElection = ref(null);
const selectedCandidate = ref(null);

const voteForm = useForm({
    election_id: '',
    candidate_id: '',
    voter_id: ''
});

const activeElections = computed(() => {
    return props.elections.filter(election => isElectionActive(election));
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
    voteForm.voter_id = props.auth.user.id;

    voteForm.post(route('votes.store'), {
        onSuccess: () => {
            showVoteModal.value = false;
            selectedElection.value = null;
            selectedCandidate.value = null;
        }
    });
};

const hasVoted = (electionId) => {
    return props.userVotes.some(vote => vote.election_id === electionId);
};

const isElectionActive = (election) => {
    const now = new Date();
    const start = new Date(election.start_date);
    const end = new Date(election.end_date);
    return now >= start && now <= end;
};

const tabs = {
    'active-elections': {
        title: 'Active Elections',
        component: 'ElectionsList'
    },
    'my-votes': {
        title: 'My Votes History',
        component: 'VotesHistory'
    }
};
</script>

<template>
    <Head title="User Dashboard" />

    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800">
        <UserSidebar :user-votes="userVotes" :active-elections="activeElections" />
        
        <div class="pl-64">
            <!-- Main Content -->
            <main class="p-8">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">Welcome back, {{ auth.user.name }}!</h1>
                    <p class="text-indigo-200">Here's what's happening with your voting activities.</p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Voting Status -->
                    <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-indigo-700/30">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Voting Status</h3>
                            <div class="bg-emerald-500/10 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-indigo-200">Your account is verified and ready for voting</p>
                    </div>

                    <!-- Active Elections -->
                    <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-indigo-700/30">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Active Elections</h3>
                            <div class="bg-blue-500/10 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-indigo-200">{{ activeElections.length }} elections currently active</p>
                    </div>

                    <!-- My Votes -->
                    <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-indigo-700/30">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">My Votes</h3>
                            <div class="bg-indigo-500/10 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-indigo-200">You have participated in {{ userVotes.length }} elections</p>
                    </div>
                </div>

                <!-- Active Elections Section -->
                <div class="space-y-8">
                    <template v-for="election in elections" :key="election.id">
                        <div v-if="isElectionActive(election)" class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-indigo-700/30">
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
                                <div v-for="candidate in candidates.filter(c => c.election_id === election.id)" 
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
    </div>
</template>
