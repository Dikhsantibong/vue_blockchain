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
            <main class="p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">Manage Candidates</h1>
                    <p class="text-indigo-200">{{ election.title }}</p>
                </div>

                <!-- Actions -->
                <div class="mb-6 flex justify-end">
                    <button 
                        @click="showModal = true"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center space-x-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span>Add New Candidate</span>
                    </button>
                </div>

                <!-- Candidates Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="candidate in candidates" :key="candidate.id" class="bg-white/5 backdrop-blur-sm rounded-xl border border-indigo-700/30 overflow-hidden">
                        <div class="aspect-w-16 aspect-h-9 bg-indigo-900/40">
                            <img 
                                :src="candidate.image_url" 
                                :alt="candidate.name"
                                class="object-cover w-full h-full"
                            >
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-white mb-2">{{ candidate.name }}</h3>
                            <div class="space-y-2 mb-4">
                                <div>
                                    <h4 class="text-sm font-medium text-indigo-300">Vision</h4>
                                    <p class="text-sm text-indigo-100">{{ candidate.vision }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-indigo-300">Mission</h4>
                                    <p class="text-sm text-indigo-100">{{ candidate.mission }}</p>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button 
                                    @click="openDeleteModal(candidate)"
                                    class="p-2 hover:text-red-400 transition-colors" 
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
            </main>
        </div>

        <!-- Add Candidate Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-gradient-to-br from-gray-900 to-indigo-900 rounded-xl shadow-xl max-w-2xl w-full p-6 border border-indigo-700/30">
                    <!-- Modal Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white">Add New Candidate</h2>
                        <p class="text-indigo-200 mt-1">Fill in the candidate details below.</p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submitForm" class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1">Name</label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                required
                            >
                            <div v-if="form.errors.name" class="text-red-400 text-sm mt-1">{{ form.errors.name }}</div>
                        </div>

                        <!-- Vision -->
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1">Vision</label>
                            <textarea
                                v-model="form.vision"
                                rows="3"
                                class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                required
                            ></textarea>
                            <div v-if="form.errors.vision" class="text-red-400 text-sm mt-1">{{ form.errors.vision }}</div>
                        </div>

                        <!-- Mission -->
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1">Mission</label>
                            <textarea
                                v-model="form.mission"
                                rows="3"
                                class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                required
                            ></textarea>
                            <div v-if="form.errors.mission" class="text-red-400 text-sm mt-1">{{ form.errors.mission }}</div>
                        </div>

                        <!-- Image -->
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1">Photo</label>
                            <input
                                type="file"
                                @change="handleImageUpload"
                                accept="image/*"
                                class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white focus:outline-none focus:border-indigo-500"
                                required
                            >
                            <div v-if="form.errors.image" class="text-red-400 text-sm mt-1">{{ form.errors.image }}</div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex justify-end space-x-3 mt-6">
                            <button
                                type="button"
                                @click="showModal = false"
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center space-x-2"
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
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-gradient-to-br from-gray-900 to-indigo-900 rounded-xl shadow-xl max-w-md w-full p-6 border border-indigo-700/30">
                    <!-- Modal Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white">Delete Candidate</h2>
                        <p class="text-indigo-200 mt-1">Are you sure you want to delete this candidate? This action cannot be undone.</p>
                    </div>

                    <!-- Candidate Info -->
                    <div class="mb-6 p-4 bg-white/5 rounded-lg border border-indigo-700/30">
                        <p class="text-white font-medium">{{ selectedCandidate?.name }}</p>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            @click="showDeleteModal = false"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            @click="confirmDelete"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                        >
                            Delete Candidate
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template> 