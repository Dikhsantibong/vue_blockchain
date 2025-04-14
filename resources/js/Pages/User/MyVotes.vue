<script setup>
import { Head } from '@inertiajs/vue3';
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
</script>

<template>
    <Head title="My Votes" />

    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800">
        <UserSidebar :user-votes="votes" :active-elections="[]" />
        
        <div class="pl-64">
            <!-- Main Content -->
            <main class="p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">My Voting History</h1>
                    <p class="text-indigo-200">View your complete voting history and verify your votes on the blockchain.</p>
                </div>

                <!-- Votes List -->
                <div class="space-y-6">
                    <div v-for="vote in votes" :key="vote.id" 
                         class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-indigo-700/30">
                        <div class="flex items-start justify-between">
                            <!-- Vote Info -->
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-white mb-2">{{ vote.election.title }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Election Details -->
                                    <div>
                                        <p class="text-indigo-300 text-sm mb-1">Election Period:</p>
                                        <p class="text-white">
                                            {{ formatDate(vote.election.start_date) }} - {{ formatDate(vote.election.end_date) }}
                                        </p>
                                        <p class="text-indigo-300 text-sm mt-4 mb-1">Vote Cast:</p>
                                        <p class="text-white">{{ formatDate(vote.timestamp) }}</p>
                                    </div>

                                    <!-- Candidate Info -->
                                    <div class="flex items-center space-x-4">
                                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-indigo-800/50">
                                            <img :src="vote.candidate.image" :alt="vote.candidate.name" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="text-indigo-300 text-sm mb-1">Voted For:</p>
                                            <p class="text-white font-medium">{{ vote.candidate.name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Blockchain Info Toggle -->
                            <button 
                                @click="showBlockInfo = showBlockInfo === vote.id ? null : vote.id"
                                class="px-3 py-1 text-sm bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 rounded-lg transition-colors"
                            >
                                {{ showBlockInfo === vote.id ? 'Hide' : 'Show' }} Blockchain Info
                            </button>
                        </div>

                        <!-- Blockchain Info -->
                        <div v-if="showBlockInfo === vote.id" 
                             class="mt-6 pt-6 border-t border-indigo-700/30">
                            <h4 class="text-lg font-medium text-white mb-4">Blockchain Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-indigo-300 mb-1">Block Hash:</p>
                                    <p class="text-white font-mono">{{ truncateHash(vote.block_info.hash) }}</p>
                                </div>
                                <div>
                                    <p class="text-indigo-300 mb-1">Previous Block Hash:</p>
                                    <p class="text-white font-mono">{{ truncateHash(vote.block_info.previous_hash) }}</p>
                                </div>
                                <div>
                                    <p class="text-indigo-300 mb-1">Nonce:</p>
                                    <p class="text-white">{{ vote.block_info.nonce }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="votes.length === 0" 
                         class="text-center py-12 bg-white/5 backdrop-blur-sm rounded-xl border border-indigo-700/30">
                        <svg class="w-16 h-16 mx-auto text-indigo-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="text-xl font-medium text-white mb-2">No Votes Yet</h3>
                        <p class="text-indigo-300">You haven't participated in any elections yet.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template> 