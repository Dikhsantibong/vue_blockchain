<script setup>
import { Head, Link } from '@inertiajs/vue3';
import UserSidebar from '@/Components/UserSidebar.vue';
import { ref } from 'vue';

const props = defineProps({
    votes: Array
});

const showBlockInfo = ref(null);

const truncateHash = (hash) => {
    if (!hash) return '';
    return hash.substring(0, 8) + '...' + hash.substring(hash.length - 8);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const downloadJSON = (vote) => {
    const data = {
        election: {
            id: vote.election.id,
            title: vote.election.title,
            start_date: vote.election.start_date,
            end_date: vote.election.end_date
        },
        candidate: {
            id: vote.candidate.id,
            name: vote.candidate.name
        },
        vote: {
            id: vote.id,
            timestamp: vote.timestamp,
            block_info: vote.block_info
        }
    };

    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `vote-${vote.id}-proof.json`;
    a.click();
    window.URL.revokeObjectURL(url);
};

const downloadPDF = (vote) => {
    window.location.href = route('user.my-votes.download-pdf', vote.id);
};
</script>

<template>
    <Head title="My Votes" />

    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800">
        <UserSidebar :user-votes="votes" :active-elections="[]" />
        
        <div class="pl-64">
            <!-- Main Content -->
            <main class="p-8 max-w-7xl mx-auto">
                <!-- Header Section with Stats -->
                <div class="mb-8 bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-indigo-700/30">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">My Voting History</h1>
                            <p class="text-indigo-200">View your complete voting history and verify your votes on the blockchain.</p>
                        </div>
                        <div class="mt-4 md:mt-0 bg-indigo-600/20 px-6 py-4 rounded-xl">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-white">{{ votes.length }}</p>
                                <p class="text-indigo-300 text-sm">Total Votes Cast</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Votes List -->
                <div class="space-y-6">
                    <div v-for="vote in votes" :key="vote.id" 
                         class="bg-white/5 backdrop-blur-sm rounded-2xl border border-indigo-700/30 overflow-hidden">
                        <!-- Vote Header -->
                        <div class="bg-indigo-900/30 px-6 py-4 border-b border-indigo-700/30">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-white">{{ vote.election.title }}</h3>
                                <button 
                                    @click="showBlockInfo = showBlockInfo === vote.id ? null : vote.id"
                                    class="px-4 py-2 text-sm bg-indigo-600/30 hover:bg-indigo-600/50 text-indigo-200 rounded-lg transition-colors flex items-center space-x-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path v-if="showBlockInfo === vote.id" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span>{{ showBlockInfo === vote.id ? 'Hide' : 'Show' }} Blockchain Data</span>
                                </button>
                            </div>
                        </div>

                        <!-- Vote Content -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Left Column: Election Details -->
                                <div class="space-y-6">
                                    <div class="bg-indigo-900/20 rounded-xl p-4">
                                        <h4 class="text-indigo-300 text-sm font-medium mb-2">Election Period</h4>
                                        <div class="flex items-center space-x-2 text-white">
                                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>{{ formatDate(vote.election.start_date) }} - {{ formatDate(vote.election.end_date) }}</span>
                                        </div>
                                    </div>

                                    <div class="bg-indigo-900/20 rounded-xl p-4">
                                        <h4 class="text-indigo-300 text-sm font-medium mb-2">Vote Cast</h4>
                                        <div class="flex items-center space-x-2 text-white">
                                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ formatDate(vote.timestamp) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Candidate Info -->
                                <div class="bg-indigo-900/20 rounded-xl p-4">
                                    <h4 class="text-indigo-300 text-sm font-medium mb-4">Selected Candidate</h4>
                                    <div class="flex items-center space-x-4">
                                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-indigo-800/50 flex-shrink-0">
                                            <img :src="vote.candidate.image" :alt="vote.candidate.name" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h5 class="text-white font-medium text-lg">{{ vote.candidate.name }}</h5>
                                            <p class="text-indigo-300 text-sm">Candidate ID: #{{ vote.candidate.id }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Blockchain Info -->
                            <div v-if="showBlockInfo === vote.id" 
                                 class="mt-6 pt-6 border-t border-indigo-700/30">
                                <div class="bg-gradient-to-r from-indigo-900/30 to-blue-900/30 rounded-xl p-6">
                                    <h4 class="text-lg font-medium text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Blockchain Information
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="bg-indigo-900/30 rounded-lg p-4">
                                            <p class="text-indigo-300 text-sm mb-1">Block Hash</p>
                                            <p class="text-white font-mono text-sm break-all">{{ vote.block_info.hash }}</p>
                                        </div>
                                        <div class="bg-indigo-900/30 rounded-lg p-4">
                                            <p class="text-indigo-300 text-sm mb-1">Previous Block Hash</p>
                                            <p class="text-white font-mono text-sm break-all">{{ vote.block_info.previous_hash }}</p>
                                        </div>
                                        <div class="bg-indigo-900/30 rounded-lg p-4 md:col-span-2">
                                            <p class="text-indigo-300 text-sm mb-1">Nonce</p>
                                            <p class="text-white font-mono">{{ vote.block_info.nonce }}</p>
                                        </div>
                                    </div>

                                    <!-- Download Buttons -->
                                    <div class="mt-6 flex flex-col sm:flex-row gap-4">
                                        <button 
                                            @click="downloadPDF(vote)"
                                            class="flex items-center justify-center px-4 py-2 bg-red-600/30 hover:bg-red-600/50 text-red-200 rounded-lg transition-colors space-x-2"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Download PDF</span>
                                        </button>
                                        <button 
                                            @click="downloadJSON(vote)"
                                            class="flex items-center justify-center px-4 py-2 bg-yellow-600/30 hover:bg-yellow-600/50 text-yellow-200 rounded-lg transition-colors space-x-2"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                            </svg>
                                            <span>Download JSON</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="votes.length === 0" 
                         class="text-center py-16 bg-white/5 backdrop-blur-sm rounded-2xl border border-indigo-700/30">
                        <div class="max-w-md mx-auto">
                            <div class="bg-indigo-600/20 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-medium text-white mb-2">No Votes Yet</h3>
                            <p class="text-indigo-300 mb-6">You haven't participated in any elections yet.</p>
                            <Link 
                                :href="route('user.active-elections.index')"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-colors"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                                </svg>
                                Browse Active Elections
                            </Link>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template> 