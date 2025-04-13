<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';

defineProps({
    auth: Object
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
        <Sidebar :is-admin="true" />
        
        <div class="pl-64">
            <!-- Main Content -->
            <main class="p-8">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">Admin Dashboard</h1>
                    <p class="text-gray-400">Monitor and manage your e-voting system.</p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Users -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Total Users</h3>
                            <div class="bg-blue-500/20 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-white">1,234</p>
                        <p class="text-gray-400 text-sm mt-1">Registered voters</p>
                    </div>

                    <!-- Active Elections -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Active Elections</h3>
                            <div class="bg-green-500/20 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-white">2</p>
                        <p class="text-gray-400 text-sm mt-1">Ongoing elections</p>
                    </div>

                    <!-- Total Votes -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Total Votes</h3>
                            <div class="bg-purple-500/20 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-white">5,678</p>
                        <p class="text-gray-400 text-sm mt-1">Votes cast</p>
                    </div>

                    <!-- System Status -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">System Status</h3>
                            <div class="bg-emerald-500/20 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-emerald-500">Healthy</p>
                        <p class="text-gray-400 text-sm mt-1">All systems operational</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Create New Election -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="flex items-center justify-center space-x-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-4 py-3 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span>New Election</span>
                            </button>
                            <button class="flex items-center justify-center space-x-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg px-4 py-3 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Add User</span>
                            </button>
                        </div>
                    </div>

                    <!-- System Health -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">System Health</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300">Server Status</span>
                                <span class="text-emerald-500">Online</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300">Database</span>
                                <span class="text-emerald-500">Connected</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300">Last Backup</span>
                                <span class="text-blue-400">2 hours ago</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-white mb-6">Recent Activity</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 bg-white/5 rounded-lg p-4">
                            <div class="bg-blue-500/20 p-2 rounded-full">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white">New user registered</p>
                                <p class="text-sm text-gray-400">2 minutes ago</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 bg-white/5 rounded-lg p-4">
                            <div class="bg-green-500/20 p-2 rounded-full">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white">Election "Presidential 2024" created</p>
                                <p class="text-sm text-gray-400">1 hour ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template> 