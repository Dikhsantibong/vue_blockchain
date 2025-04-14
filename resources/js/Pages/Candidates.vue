<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    election: {
        type: Object,
        required: true
    },
    candidates: {
        type: Array,
        required: true
    }
});

const showModal = ref(false);
const showDeleteModal = ref(false);
const selectedCandidate = ref(null);

const form = useForm({
    name: '',
    vision: '',
    mission: '',
    image: null,
});

const submitForm = () => {
    form.post(route('admin.elections.candidates.store', props.election.id), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        },
    });
};

const openDeleteModal = (candidate) => {
    selectedCandidate.value = candidate;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (selectedCandidate.value) {
        useForm().delete(route('admin.elections.candidates.destroy', [props.election.id, selectedCandidate.value.id]), {
            onSuccess: () => {
                showDeleteModal.value = false;
                selectedCandidate.value = null;
            },
        });
    }
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    form.image = file;
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800">
        <Head :title="`Manage Candidates - ${election.title}`" />
        <Sidebar :is-admin="true" />

        <div class="pl-64">
            <main class="p-8 max-w-7xl mx-auto">
                <!-- Header Section with Stats -->
                <div class="mb-8 bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-indigo-700/30">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">Manage Candidates</h1>
                            <p class="text-indigo-200">{{ election.title }}</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <button 
                                @click="showModal = true"
                                class="w-full md:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg shadow-indigo-900/50"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span>Add New Candidate</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Candidates Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="candidate in candidates" :key="candidate.id" 
                         class="group bg-white/5 hover:bg-white/10 backdrop-blur-sm rounded-2xl border border-indigo-700/30 overflow-hidden transition-all duration-200">
                        <div class="relative h-48 w-full overflow-hidden bg-indigo-900/40">
                            <img 
                                :src="candidate.image_url" 
                                :alt="candidate.name"
                                class="absolute w-full h-full object-cover object-center"
                            >
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-white mb-4">{{ candidate.name }}</h3>
                            <div class="space-y-4 mb-4">
                                <div class="bg-indigo-900/30 rounded-xl p-4">
                                    <h4 class="text-sm font-medium text-indigo-300 mb-2">Vision</h4>
                                    <p class="text-sm text-indigo-100">{{ candidate.vision }}</p>
                                </div>
                                <div class="bg-indigo-900/30 rounded-xl p-4">
                                    <h4 class="text-sm font-medium text-indigo-300 mb-2">Mission</h4>
                                    <p class="text-sm text-indigo-100">{{ candidate.mission }}</p>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button 
                                    @click="openDeleteModal(candidate)"
                                    class="p-2 text-indigo-300 hover:text-red-400 transition-colors duration-200" 
                                    title="Delete Candidate"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Candidate Modal -->
                <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
                    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>

                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-indigo-900 to-blue-900 rounded-2xl shadow-2xl max-w-2xl w-full p-8 border border-indigo-700/30">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-white mb-2">Add New Candidate</h2>
                                <p class="text-indigo-200">Fill in the candidate details below.</p>
                            </div>

                            <form @submit.prevent="submitForm" class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-indigo-200 mb-2">Name</label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="w-full px-4 py-3 bg-indigo-900/50 border border-indigo-500/30 rounded-xl text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-400 transition-colors duration-200"
                                        required
                                    >
                                    <div v-if="form.errors.name" class="mt-2 text-red-400 text-sm">{{ form.errors.name }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-indigo-200 mb-2">Vision</label>
                                    <textarea
                                        v-model="form.vision"
                                        rows="3"
                                        class="w-full px-4 py-3 bg-indigo-900/50 border border-indigo-500/30 rounded-xl text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-400 transition-colors duration-200"
                                        required
                                    ></textarea>
                                    <div v-if="form.errors.vision" class="mt-2 text-red-400 text-sm">{{ form.errors.vision }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-indigo-200 mb-2">Mission</label>
                                    <textarea
                                        v-model="form.mission"
                                        rows="3"
                                        class="w-full px-4 py-3 bg-indigo-900/50 border border-indigo-500/30 rounded-xl text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-400 transition-colors duration-200"
                                        required
                                    ></textarea>
                                    <div v-if="form.errors.mission" class="mt-2 text-red-400 text-sm">{{ form.errors.mission }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-indigo-200 mb-2">Photo</label>
                                    <input
                                        type="file"
                                        @change="handleImageUpload"
                                        accept="image/*"
                                        class="w-full px-4 py-3 bg-indigo-900/50 border border-indigo-500/30 rounded-xl text-white focus:outline-none focus:border-indigo-400 transition-colors duration-200"
                                        required
                                    >
                                    <div v-if="form.errors.image" class="mt-2 text-red-400 text-sm">{{ form.errors.image }}</div>
                                </div>

                                <div class="flex justify-end space-x-4">
                                    <button
                                        type="button"
                                        @click="showModal = false"
                                        class="px-6 py-3 bg-indigo-900/50 hover:bg-indigo-800 text-indigo-200 rounded-xl transition-all duration-200"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl transition-all duration-200 flex items-center space-x-2 shadow-lg shadow-indigo-900/50"
                                    >
                                        <span>Add Candidate</span>
                                        <svg v-if="form.processing" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
                    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>

                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-indigo-900 to-blue-900 rounded-2xl shadow-2xl max-w-md w-full p-8 border border-indigo-700/30">
                            <div class="mb-6">
                                <h2 class="text-2xl font-bold text-white mb-2">Delete Candidate</h2>
                                <p class="text-indigo-200">Are you sure you want to delete this candidate? This action cannot be undone.</p>
                            </div>

                            <div class="mb-8 p-4 bg-indigo-900/50 rounded-xl border border-indigo-500/30">
                                <p class="text-white font-medium">{{ selectedCandidate?.name }}</p>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <button
                                    type="button"
                                    @click="showDeleteModal = false"
                                    class="px-6 py-3 bg-indigo-900/50 hover:bg-indigo-800 text-indigo-200 rounded-xl transition-all duration-200"
                                >
                                    Cancel
                                </button>
                                <button
                                    @click="confirmDelete"
                                    class="px-6 py-3 bg-red-600 hover:bg-red-500 text-white rounded-xl transition-all duration-200 shadow-lg shadow-red-900/50"
                                >
                                    Delete Candidate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>