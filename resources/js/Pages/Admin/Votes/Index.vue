<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';

defineProps({
    statistics: Array
});
</script>

<template>
    <Head title="Manage Votes" />

    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800">
        <Sidebar :is-admin="true" />
        
        <div class="pl-64">
            <main class="p-8 max-w-7xl mx-auto">
                <!-- Header -->
                <div class="mb-8 bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-indigo-700/30">
                    <h1 class="text-3xl font-bold text-white mb-2">Vote Statistics</h1>
                    <p class="text-indigo-200">View and analyze voting results for all elections.</p>
                </div>

                <!-- Elections List -->
                <div class="space-y-8">
                    <template v-for="election in statistics" :key="election.id">
                        <div class="bg-white/5 backdrop-blur-sm rounded-2xl border border-indigo-700/30 overflow-hidden">
                            <!-- Election Header -->
                            <div class="bg-indigo-900/30 px-8 py-6 border-b border-indigo-700/30">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <h2 class="text-2xl font-semibold text-white mb-2">{{ election.title }}</h2>
                                        <div class="flex items-center space-x-6 text-sm text-indigo-300">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ new Date(election.start_date).toLocaleDateString() }} - {{ new Date(election.end_date).toLocaleDateString() }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                {{ election.total_votes }} Total Votes
                                            </span>
                                        </div>
                                    </div>
                                    <div class="px-4 py-2 bg-indigo-500/20 rounded-xl text-indigo-300 text-sm">
                                        {{ election.status }}
                                    </div>
                                </div>
                            </div>

                            <!-- Candidates Results -->
                            <div class="p-8">
                                <div class="grid gap-6">
                                    <div v-for="candidate in election.candidates" 
                                         :key="candidate.id" 
                                         class="bg-indigo-900/40 rounded-xl p-6 border border-indigo-700/30">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                                            <div>
                                                <h3 class="text-xl font-medium text-white">{{ candidate.name }}</h3>
                                                <p class="text-indigo-300 mt-1">{{ candidate.votes_count }} votes</p>
                                            </div>
                                            <div class="text-2xl font-bold text-indigo-300">
                                                {{ candidate.percentage }}%
                                            </div>
                                        </div>
                                        
                                        <!-- Progress Bar -->
                                        <div class="h-4 bg-indigo-900/50 rounded-full overflow-hidden">
                                            <div class="h-full bg-indigo-500 transition-all duration-500"
                                                 :style="{ width: candidate.percentage + '%' }">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <div v-if="statistics.length === 0" 
                         class="text-center py-16 bg-white/5 backdrop-blur-sm rounded-2xl border border-indigo-700/30">
                        <div class="max-w-md mx-auto">
                            <div class="bg-indigo-600/20 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-medium text-white mb-2">No Voting Data</h3>
                            <p class="text-indigo-300">There are currently no elections with voting data to display.</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template> 