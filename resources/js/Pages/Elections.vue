<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

defineProps({
    elections: {
        type: Array,
        required: true
    }
});

const searchQuery = ref('');
const showModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const selectedElection = ref(null);

const form = useForm({
    title: '',
    description: '',
    start_date: '',
    end_date: '',
});

const editForm = useForm({
    title: '',
    description: '',
    start_date: '',
    end_date: '',
    status: ''
});

const getStatusClass = (status) => {
    return {
        'px-2 py-1 text-xs font-medium rounded-full': true,
        'bg-green-500/20 text-green-300': status === 'active',
        'bg-yellow-500/20 text-yellow-300': status === 'upcoming',
        'bg-gray-500/20 text-gray-300': status === 'completed'
    }
};

const submitForm = () => {
    form.post(route('admin.elections.store'), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        },
    });
};

const openEditModal = (election) => {
    selectedElection.value = election;
    editForm.title = election.title;
    editForm.description = election.description || '';
    editForm.start_date = formatDateForInput(election.start_date);
    editForm.end_date = formatDateForInput(election.end_date);
    editForm.status = election.status;
    showEditModal.value = true;
};

const formatDateForInput = (dateString) => {
    const date = new Date(dateString);
    return date.toISOString().slice(0, 16);
};

const formatDateTime = (dateString) => {
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

const submitEditForm = () => {
    editForm.put(route('admin.elections.update', selectedElection.value.id), {
        onSuccess: () => {
            showEditModal.value = false;
            editForm.reset();
            selectedElection.value = null;
        },
    });
};

const openDeleteModal = (election) => {
    selectedElection.value = election;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (selectedElection.value) {
        useForm().delete(route('admin.elections.destroy', selectedElection.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                selectedElection.value = null;
            },
        });
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
                    <div class="flex items-center space-x-4 mb-2">
                        <div class="p-2 bg-indigo-600/20 rounded-lg">
                            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Manage Elections</h1>
                            <p class="text-indigo-200 mt-1">Create and manage election events with enhanced control.</p>
                        </div>
                    </div>
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-3 gap-6 mt-6">
                        <div class="bg-gradient-to-br from-blue-600/20 to-indigo-600/20 p-4 rounded-lg border border-indigo-700/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-indigo-200 text-sm">Total Elections</p>
                                    <p class="text-2xl font-bold text-white mt-1">{{ elections.length }}</p>
                                </div>
                                <div class="p-3 bg-blue-500/20 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-green-600/20 to-indigo-600/20 p-4 rounded-lg border border-indigo-700/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-indigo-200 text-sm">Active Elections</p>
                                    <p class="text-2xl font-bold text-white mt-1">{{ elections.filter(e => e.status === 'active').length }}</p>
                                </div>
                                <div class="p-3 bg-green-500/20 rounded-lg">
                                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-600/20 to-indigo-600/20 p-4 rounded-lg border border-indigo-700/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-indigo-200 text-sm">Upcoming Elections</p>
                                    <p class="text-2xl font-bold text-white mt-1">{{ elections.filter(e => e.status === 'upcoming').length }}</p>
                                </div>
                                <div class="p-3 bg-yellow-500/20 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Actions -->
                <div class="mb-6 bg-white/5 p-4 rounded-lg border border-indigo-700/30 flex justify-between items-center">
                    <div class="relative w-96">
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Search elections by title or description..."
                            class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500 pl-10"
                        >
                        <svg
                            class="absolute left-3 top-2.5 h-5 w-5 text-indigo-300"
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
                    <button 
                        @click="showModal = true"
                        class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 flex items-center space-x-2 shadow-lg shadow-indigo-600/20"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span>Create New Election</span>
                    </button>
                </div>

                <!-- Elections Table -->
                <div class="bg-white/5 backdrop-blur-sm rounded-xl border border-indigo-700/30 overflow-hidden shadow-xl shadow-indigo-900/20">
                    <table class="min-w-full divide-y divide-indigo-700/30">
                        <thead>
                            <tr class="bg-gradient-to-r from-indigo-900/40 to-blue-900/40">
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">No</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">Judul Pemilu</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">Tanggal Mulai</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">Tanggal Selesai</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-700/30">
                            <tr v-for="(election, index) in elections" :key="election.id" class="hover:bg-indigo-900/40 transition-all duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-medium text-sm">
                                            {{ election.title.charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-white">{{ election.title }}</p>
                                            <p class="text-xs text-indigo-300">{{ election.description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-200">{{ formatDateTime(election.start_date) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-200">{{ formatDateTime(election.end_date) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'px-3 py-1 text-xs font-medium rounded-full shadow-sm': true,
                                        'bg-green-500/20 text-green-300 border border-green-500/30': election.status === 'active',
                                        'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30': election.status === 'upcoming',
                                        'bg-gray-500/20 text-gray-300 border border-gray-500/30': election.status === 'completed'
                                    }">
                                        {{ election.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    <div class="flex space-x-3">
                                        <button 
                                            @click="openEditModal(election)"
                                            class="p-1.5 bg-blue-500/10 rounded-lg hover:bg-blue-500/20 transition-colors"
                                            title="Edit Election"
                                        >
                                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button 
                                            @click="openDeleteModal(election)"
                                            class="p-1.5 bg-red-500/10 rounded-lg hover:bg-red-500/20 transition-colors"
                                            title="Delete Election"
                                        >
                                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <Link 
                                            :href="route('admin.elections.candidates.index', election.id)"
                                            class="p-1.5 bg-green-500/10 rounded-lg hover:bg-green-500/20 transition-colors"
                                            title="Manage Candidates"
                                        >
                                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>

        <!-- Create Election Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-gradient-to-br from-gray-900 to-indigo-900 rounded-xl shadow-xl max-w-2xl w-full p-6 border border-indigo-700/30">
                    <!-- Modal Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white">Create New Election</h2>
                        <p class="text-indigo-200 mt-1">Fill in the election details below.</p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submitForm" class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1">Title</label>
                            <input
                                v-model="form.title"
                                type="text"
                                class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                required
                            >
                            <div v-if="form.errors.title" class="text-red-400 text-sm mt-1">{{ form.errors.title }}</div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1">Description</label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                            ></textarea>
                            <div v-if="form.errors.description" class="text-red-400 text-sm mt-1">{{ form.errors.description }}</div>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Start Date</label>
                                <input
                                    v-model="form.start_date"
                                    type="datetime-local"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                <div v-if="form.errors.start_date" class="text-red-400 text-sm mt-1">{{ form.errors.start_date }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">End Date</label>
                                <input
                                    v-model="form.end_date"
                                    type="datetime-local"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                <div v-if="form.errors.end_date" class="text-red-400 text-sm mt-1">{{ form.errors.end_date }}</div>
                            </div>
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
                                <span>Create Election</span>
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

        <!-- Edit Election Modal -->
        <div v-if="showEditModal" class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-gradient-to-br from-gray-900 to-indigo-900 rounded-xl shadow-xl max-w-2xl w-full p-6 border border-indigo-700/30">
                    <!-- Modal Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white">Edit Election</h2>
                        <p class="text-indigo-200 mt-1">Update the election details below.</p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submitEditForm" class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1">Title</label>
                            <input
                                v-model="editForm.title"
                                type="text"
                                class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                required
                            >
                            <div v-if="editForm.errors.title" class="text-red-400 text-sm mt-1">{{ editForm.errors.title }}</div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1">Description</label>
                            <textarea
                                v-model="editForm.description"
                                rows="3"
                                class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                            ></textarea>
                            <div v-if="editForm.errors.description" class="text-red-400 text-sm mt-1">{{ editForm.errors.description }}</div>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Start Date</label>
                                <input
                                    v-model="editForm.start_date"
                                    type="datetime-local"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                <div v-if="editForm.errors.start_date" class="text-red-400 text-sm mt-1">{{ editForm.errors.start_date }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">End Date</label>
                                <input
                                    v-model="editForm.end_date"
                                    type="datetime-local"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                <div v-if="editForm.errors.end_date" class="text-red-400 text-sm mt-1">{{ editForm.errors.end_date }}</div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1">Status</label>
                            <select
                                v-model="editForm.status"
                                class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white focus:outline-none focus:border-indigo-500"
                                required
                            >
                                <option value="upcoming">Upcoming</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                            </select>
                            <div v-if="editForm.errors.status" class="text-red-400 text-sm mt-1">{{ editForm.errors.status }}</div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex justify-end space-x-3 mt-6">
                            <button
                                type="button"
                                @click="showEditModal = false"
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="editForm.processing"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center space-x-2"
                            >
                                <span>Update Election</span>
                                <svg v-if="editForm.processing" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
                        <h2 class="text-2xl font-bold text-white">Delete Election</h2>
                        <p class="text-indigo-200 mt-1">Are you sure you want to delete this election? This action cannot be undone.</p>
                    </div>

                    <!-- Election Info -->
                    <div class="mb-6 p-4 bg-white/5 rounded-lg border border-indigo-700/30">
                        <p class="text-white font-medium">{{ selectedElection?.title }}</p>
                        <p class="text-sm text-indigo-200 mt-1">{{ selectedElection?.description }}</p>
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
                            Delete Election
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template> 