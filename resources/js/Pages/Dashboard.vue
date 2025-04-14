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
            <main class="p-8 max-w-7xl mx-auto">
                <!-- Welcome Section -->
                <div class="mb-8 bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-indigo-700/30">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">Welcome back, {{ auth.user.name }}!</h1>
                            <p class="text-indigo-200">Here's what's happening with your voting activities.</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <span class="inline-flex items-center px-4 py-2 bg-emerald-500/20 text-emerald-300 rounded-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Account Verified
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Active Elections -->
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-indigo-700/30 hover:bg-white/10 transition-colors">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Active Elections</h3>
                            <div class="bg-blue-500/20 p-3 rounded-xl">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-baseline">
                            <p class="text-3xl font-bold text-white">{{ activeElections.length }}</p>
                            <p class="ml-2 text-indigo-300">elections ongoing</p>
                        </div>
                    </div>

                    <!-- My Votes -->
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-indigo-700/30 hover:bg-white/10 transition-colors">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">My Votes</h3>
                            <div class="bg-indigo-500/20 p-3 rounded-xl">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-baseline">
                            <p class="text-3xl font-bold text-white">{{ userVotes.length }}</p>
                            <p class="ml-2 text-indigo-300">votes cast</p>
                        </div>
                    </div>

                    <!-- Total Candidates -->
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-indigo-700/30 hover:bg-white/10 transition-colors">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Total Candidates</h3>
                            <div class="bg-purple-500/20 p-3 rounded-xl">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-baseline">
                            <p class="text-3xl font-bold text-white">{{ candidates.length }}</p>
                            <p class="ml-2 text-indigo-300">candidates</p>
                        </div>
                    </div>
                </div>

                <!-- Active Elections Section -->
                <div class="space-y-8">
                    <template v-for="election in elections" :key="election.id">
                        <div v-if="isElectionActive(election)" 
                             class="bg-white/5 backdrop-blur-sm rounded-2xl border border-indigo-700/30 overflow-hidden hover:bg-white/10 transition-colors">
                            <!-- Election Header -->
                            <div class="bg-indigo-900/30 px-8 py-6 border-b border-indigo-700/30">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-2xl font-semibold text-white mb-2">{{ election.title }}</h3>
                                        <p class="text-indigo-200 max-w-2xl">{{ election.description }}</p>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="px-4 py-2 bg-blue-500/20 text-blue-300 rounded-xl text-sm flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Ends in {{ getRemainingTime(election.end_date) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center space-x-6 text-sm text-indigo-300">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Start: {{ formatDate(election.start_date) }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        End: {{ formatDate(election.end_date) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Candidates Grid -->
                            <div class="p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div v-for="candidate in candidates.filter(c => c.election_id === election.id)" 
                                         :key="candidate.id"
                                         class="bg-indigo-900/40 rounded-xl overflow-hidden border border-indigo-700/30 transition-all hover:scale-[1.02] hover:shadow-xl">
                                        <!-- Candidate Image -->
                                        <div class="aspect-w-16 aspect-h-9 bg-indigo-800/50">
                                            <img :src="candidate.image" :alt="candidate.name" class="object-cover w-full h-full">
                                        </div>

                                        <!-- Candidate Info -->
                                        <div class="p-6">
                                            <h4 class="text-xl font-medium text-white mb-4">{{ candidate.name }}</h4>
                                            
                                            <!-- Vision & Mission -->
                                            <div class="space-y-4 mb-6">
                                                <div>
                                                    <h5 class="text-sm font-medium text-indigo-300 mb-2 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Vision
                                                    </h5>
                                                    <p class="text-sm text-indigo-200">{{ candidate.vision }}</p>
                                                </div>
                                                
                                                <div>
                                                    <h5 class="text-sm font-medium text-indigo-300 mb-2 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"></path>
                                                        </svg>
                                                        Mission
                                                    </h5>
                                                    <p class="text-sm text-indigo-200">{{ candidate.mission }}</p>
                                                </div>
                                            </div>

                                            <!-- Vote Button -->
                                            <button 
                                                @click="openVoteModal(election, candidate)"
                                                :disabled="hasVoted(election.id)"
                                                :class="{
                                                    'w-full px-6 py-3 rounded-xl transition-all text-white text-center flex items-center justify-center space-x-2': true,
                                                    'bg-blue-600 hover:bg-blue-700 hover:shadow-lg': !hasVoted(election.id),
                                                    'bg-gray-600/50 cursor-not-allowed': hasVoted(election.id)
                                                }"
                                            >
                                                <svg v-if="hasVoted(election.id)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ hasVoted(election.id) ? 'Already Voted' : 'Vote for Candidate' }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <div v-if="activeElections.length === 0" 
                         class="text-center py-16 bg-white/5 backdrop-blur-sm rounded-2xl border border-indigo-700/30">
                        <div class="max-w-md mx-auto">
                            <div class="bg-indigo-600/20 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-medium text-white mb-2">No Active Elections</h3>
                            <p class="text-indigo-300">There are currently no active elections. Please check back later.</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Vote Confirmation Modal -->
        <div v-if="showVoteModal" class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm transition-opacity"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-gradient-to-br from-gray-900 to-indigo-900 rounded-2xl shadow-2xl max-w-md w-full p-8 border border-indigo-700/30">
                    <!-- Modal Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white mb-2">Confirm Your Vote</h2>
                        <p class="text-indigo-200">Please review your selection before confirming.</p>
                    </div>

                    <!-- Vote Details -->
                    <div class="space-y-4 mb-8">
                        <div class="bg-white/5 rounded-xl p-6 border border-indigo-700/30">
                            <h3 class="text-lg font-medium text-white mb-4">{{ selectedElection?.title }}</h3>
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-indigo-800/50 flex-shrink-0">
                                    <img :src="selectedCandidate?.image" :alt="selectedCandidate?.name" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm text-indigo-200">You are voting for:</p>
                                    <p class="text-white font-medium mt-1">{{ selectedCandidate?.name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-500/10 rounded-xl p-6 border border-yellow-500/30">
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p class="text-yellow-300 text-sm">
                                    This action cannot be undone. Your vote will be permanently recorded in the blockchain.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-end space-x-4">
                        <button
                            type="button"
                            @click="showVoteModal = false"
                            class="px-6 py-3 bg-gray-600/50 hover:bg-gray-600 text-white rounded-xl transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            @click="submitVote"
                            :disabled="voteForm.processing"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors flex items-center space-x-2"
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
