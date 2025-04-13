<script setup>
defineProps({
    userVotes: {
        type: Array,
        required: true
    }
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <div class="space-y-6">
        <div v-for="vote in userVotes" :key="vote.id" class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-indigo-700/30">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-white">{{ vote.election.title }}</h3>
                    <p class="text-sm text-indigo-200 mt-1">Voted for: {{ vote.candidate.name }}</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm">
                        Verified
                    </span>
                    <p class="text-sm text-indigo-200 mt-2">{{ formatDate(vote.created_at) }}</p>
                </div>
            </div>

            <div class="bg-indigo-900/40 rounded-lg p-4 border border-indigo-700/30">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <img :src="vote.candidate.image_url" :alt="vote.candidate.name" class="w-20 h-20 rounded-lg object-cover">
                    </div>
                    <div class="flex-1">
                        <h4 class="text-white font-medium mb-2">Candidate Details</h4>
                        <div class="space-y-2">
                            <p class="text-sm text-indigo-200">
                                <span class="font-medium">Vision:</span> {{ vote.candidate.vision }}
                            </p>
                            <p class="text-sm text-indigo-200">
                                <span class="font-medium">Mission:</span> {{ vote.candidate.mission }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blockchain Info -->
            <div class="mt-4 p-4 bg-indigo-900/40 rounded-lg border border-indigo-700/30">
                <h4 class="text-sm font-medium text-white mb-2">Blockchain Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-indigo-300">Transaction Hash</p>
                        <p class="text-sm text-indigo-100 font-mono truncate">{{ vote.hash }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-indigo-300">Previous Hash</p>
                        <p class="text-sm text-indigo-100 font-mono truncate">{{ vote.previous_hash || 'Genesis Vote' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="userVotes.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-white">No votes yet</h3>
            <p class="mt-2 text-indigo-200">You haven't participated in any elections yet.</p>
        </div>
    </div>
</template> 