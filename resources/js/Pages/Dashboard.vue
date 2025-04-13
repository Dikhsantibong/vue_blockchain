<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';

defineProps({
    auth: Object
});
</script>

<template>
    <Head title="User Dashboard" />

    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800">
        <Sidebar :is-admin="false" />
        
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
                        <p class="text-indigo-200">2 elections currently active</p>
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
                        <p class="text-indigo-200">You have participated in 3 elections</p>
                    </div>
                </div>

                <!-- Active Elections Section -->
                <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-indigo-700/30">
                    <h3 class="text-xl font-semibold text-white mb-6">Current Elections</h3>
                    <div class="space-y-4">
                        <div class="bg-indigo-900/40 rounded-lg p-4 border border-indigo-700/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-white font-medium">Presidential Election 2024</h4>
                                    <p class="text-sm text-indigo-200 mt-1">Ends in 5 days</p>
                                </div>
                                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    Vote Now
                                </button>
                            </div>
                        </div>
                        <div class="bg-indigo-900/40 rounded-lg p-4 border border-indigo-700/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-white font-medium">Local Council Election</h4>
                                    <p class="text-sm text-indigo-200 mt-1">Ends in 2 days</p>
                                </div>
                                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    Vote Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
