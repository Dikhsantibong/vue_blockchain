<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { ref } from 'vue';

defineProps({
    users: {
        type: Array,
        required: true
    }
});

const searchQuery = ref('');
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800">
        <Head title="Manage Users" />
        <Sidebar :is-admin="true" />

        <div class="pl-64">
            <main class="p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">Manage Users</h1>
                    <p class="text-indigo-200">View and manage system users.</p>
                </div>

                <!-- Search and Actions -->
                <div class="mb-6 flex justify-between items-center">
                    <div class="relative w-64">
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Search users..."
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
                        <span>Add New User</span>
                    </button>
                </div>

                <!-- Users Table -->
                <div class="bg-white/5 backdrop-blur-sm rounded-xl border border-indigo-700/30 overflow-hidden">
                    <table class="min-w-full divide-y divide-indigo-700/30">
                        <thead>
                            <tr class="bg-indigo-900/40">
                                <th class="px-6 py-3 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider">NIK</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-700/30">
                            <tr v-for="user in users" :key="user.id" class="hover:bg-indigo-900/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ user.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ user.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ user.nik }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ user.phone }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span :class="{
                                        'px-2 py-1 text-xs font-medium rounded-full': true,
                                        'bg-blue-500/20 text-blue-300': user.role === 'user',
                                        'bg-indigo-500/20 text-indigo-300': user.role === 'admin'
                                    }">
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    <div class="flex space-x-2">
                                        <button class="p-1 hover:text-blue-400 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button class="p-1 hover:text-red-400 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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