<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    userVotes: {
        type: Array,
        required: true
    },
    activeElections: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <div class="fixed inset-y-0 left-0 w-64 bg-indigo-900/50 backdrop-blur-sm border-r border-indigo-700/30">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 border-b border-indigo-700/30">
            <span class="text-xl font-bold text-white">E-Voting System</span>
        </div>

        <!-- Navigation -->
        <nav class="mt-6 px-4">
            <!-- User Menu -->
            <Link 
                :href="route('dashboard')"
                class="flex items-center px-4 py-2 mb-2 text-indigo-200 hover:bg-indigo-800/50 rounded-lg transition-colors"
                :class="{ 'bg-indigo-800/50': route().current('dashboard') && !route().params.tab }"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </Link>

            <Link 
                :href="route('dashboard', { tab: 'active-elections' })"
                class="flex items-center px-4 py-2 mb-2 text-indigo-200 hover:bg-indigo-800/50 rounded-lg transition-colors"
                :class="{ 'bg-indigo-800/50': route().params.tab === 'active-elections' }"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Active Elections
                <span class="ml-auto bg-blue-500/20 text-blue-300 px-2 py-0.5 rounded-full text-xs">
                    {{ activeElections.length }}
                </span>
            </Link>

            <Link 
                :href="route('dashboard', { tab: 'my-votes' })"
                class="flex items-center px-4 py-2 mb-2 text-indigo-200 hover:bg-indigo-800/50 rounded-lg transition-colors"
                :class="{ 'bg-indigo-800/50': route().params.tab === 'my-votes' }"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                My Votes
                <span class="ml-auto bg-indigo-500/20 text-indigo-300 px-2 py-0.5 rounded-full text-xs">
                    {{ userVotes.length }}
                </span>
            </Link>

            <!-- Profile & Logout -->
            <div class="mt-6 border-t border-indigo-700/30 pt-6">
                <Link 
                    :href="route('profile.edit')"
                    class="flex items-center px-4 py-2 mb-2 text-indigo-200 hover:bg-indigo-800/50 rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </Link>

                <Link 
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="w-full flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-800/50 rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </Link>
            </div>
        </nav>
    </div>
</template> 