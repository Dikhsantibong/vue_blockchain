<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { ref } from 'vue';

defineProps({
    elections: {
        type: Array,
        required: true
    }
});

const searchQuery = ref('');

const getStatusClass = (status) => {
    return {
        'px-2 py-1 text-xs font-medium rounded-full': true,
        'bg-green-500/20 text-green-300': status === 'active',
        'bg-yellow-500/20 text-yellow-300': status === 'upcoming',
        'bg-gray-500/20 text-gray-300': status === 'completed'
    }
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800">
        <Head title="Manage Elections" />
        <Sidebar :is-admin="true" />

        <div class="pl-64">
            <main class="p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">Manage Elections</h1>
                    <p class="text-indigo-200">Create and manage election events.</p>
                </div>

                <!-- Search and Actions -->
                <div class="mb-6 flex justify-between items-center">
                    <div class="relative w-64">
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Search elections..."
                            class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                        >
                        <svg
                            class="absolute right-3 top-2.5 h-5 w-5 text-indigo-300"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                    </div>
                    <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span>Create New Election</span>
                    </button>
                </div>

                <!-- Elections Table -->
                <div class="bg-white/5 backdrop-blur-sm rounded-xl border border-indigo-700/30 overflow-hidden">
                    <table class="min-w-full divide-y divide-indigo-700/30">
                        <thead>
                            <tr class="bg-indigo-900/40">
                                <th class="px-6 py-3 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border border-indigo-700/30">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border border-indigo-700/30">Judul Pemilu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border border-indigo-700/30">Tanggal Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border border-indigo-700/30">Tanggal Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border border-indigo-700/30">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border border-indigo-700/30">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-700/30">
                            <tr v-for="(election, index) in elections" :key="election.id" class="hover:bg-indigo-900/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-indigo-700/30 text-white">{{ index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-indigo-700/30 text-white">{{ election.title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-indigo-700/30 text-white">{{ election.start_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-indigo-700/30 text-white">{{ election.end_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-indigo-700/30">
                                    <span :class="getStatusClass(election.status)">
                                        {{ election.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border border-indigo-700/30">
                                    <div class="flex space-x-2">
                                        <button class="p-1 hover:text-blue-400 transition-colors" title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <button class="p-1 hover:text-blue-400 transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button class="p-1 hover:text-red-400 transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <button class="p-1 hover:text-green-400 transition-colors" title="Manage Candidates">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</template> 