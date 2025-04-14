<script setup>
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    users: {
        type: Array,
        required: true
    }
});

const searchQuery = ref('');
const showModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const selectedUser = ref(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
    nik: '',
    phone: '',
    address: ''
});

const editForm = useForm({
    name: '',
    email: '',
    role: '',
    nik: '',
    phone: '',
    address: '',
    password: '',
    password_confirmation: ''
});

const submitForm = () => {
    form.post(route('admin.users.store'), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        }
    });
};

const openEditModal = (user) => {
    selectedUser.value = user;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.role = user.role;
    editForm.nik = user.nik;
    editForm.phone = user.phone;
    editForm.address = user.address;
    editForm.password = '';
    editForm.password_confirmation = '';
    showEditModal.value = true;
};

const submitEditForm = () => {
    editForm.put(route('admin.users.update', selectedUser.value.id), {
        onSuccess: () => {
            showEditModal.value = false;
            editForm.reset();
            selectedUser.value = null;
        }
    });
};

const openDeleteModal = (user) => {
    selectedUser.value = user;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (selectedUser.value) {
        useForm().delete(route('admin.users.destroy', selectedUser.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                selectedUser.value = null;
            }
        });
    }
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800">
        <Head title="Manage Users" />
        <Sidebar :is-admin="true" />

        <div class="pl-64">
            <main class="p-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center space-x-4 mb-2">
                        <div class="p-2 bg-indigo-600/20 rounded-lg">
                            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Manage Users</h1>
                            <p class="text-indigo-200 mt-1">View and manage system users with enhanced control.</p>
                        </div>
                    </div>
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-3 gap-6 mt-6">
                        <div class="bg-gradient-to-br from-blue-600/20 to-indigo-600/20 p-4 rounded-lg border border-indigo-700/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-indigo-200 text-sm">Total Users</p>
                                    <p class="text-2xl font-bold text-white mt-1">{{ users.length }}</p>
                                </div>
                                <div class="p-3 bg-blue-500/20 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-600/20 to-indigo-600/20 p-4 rounded-lg border border-indigo-700/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-indigo-200 text-sm">Admin Users</p>
                                    <p class="text-2xl font-bold text-white mt-1">{{ users.filter(u => u.role === 'admin').length }}</p>
                                </div>
                                <div class="p-3 bg-purple-500/20 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-green-600/20 to-indigo-600/20 p-4 rounded-lg border border-indigo-700/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-indigo-200 text-sm">Regular Users</p>
                                    <p class="text-2xl font-bold text-white mt-1">{{ users.filter(u => u.role === 'user').length }}</p>
                                </div>
                                <div class="p-3 bg-green-500/20 rounded-lg">
                                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
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
                            placeholder="Search by name, email, or NIK..."
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
                        <span>Add New User</span>
                    </button>
                </div>

                <!-- Users Table -->
                <div class="bg-white/5 backdrop-blur-sm rounded-xl border border-indigo-700/30 overflow-hidden shadow-xl shadow-indigo-900/20">
                    <table class="min-w-full divide-y divide-indigo-700/30">
                        <thead>
                            <tr class="bg-gradient-to-r from-indigo-900/40 to-blue-900/40">
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">No</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">NIK</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">Phone</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-indigo-200 uppercase tracking-wider border-b border-indigo-700/30">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-700/30">
                            <tr v-for="(user, index) in users" :key="user.id" class="hover:bg-indigo-900/40 transition-all duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-medium text-sm">
                                            {{ user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-white">{{ user.name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-200">{{ user.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-200">{{ user.nik }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-200">{{ user.phone }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'px-3 py-1 text-xs font-medium rounded-full shadow-sm': true,
                                        'bg-blue-500/20 text-blue-300 border border-blue-500/30': user.role === 'user',
                                        'bg-purple-500/20 text-purple-300 border border-purple-500/30': user.role === 'admin'
                                    }">
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    <div class="flex space-x-3">
                                        <button 
                                            @click="openEditModal(user)"
                                            class="p-1.5 bg-blue-500/10 rounded-lg hover:bg-blue-500/20 transition-colors"
                                            title="Edit User"
                                        >
                                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button 
                                            @click="openDeleteModal(user)"
                                            class="p-1.5 bg-red-500/10 rounded-lg hover:bg-red-500/20 transition-colors"
                                            title="Delete User"
                                        >
                                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <!-- Add User Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-gradient-to-br from-gray-900 to-indigo-900 rounded-xl shadow-xl max-w-2xl w-full p-6 border border-indigo-700/30">
                    <!-- Modal Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white">Add New User</h2>
                        <p class="text-indigo-200 mt-1">Fill in the user details below.</p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submitForm" class="space-y-4">
                        <!-- Name & Email -->
                        <div class="grid grid-cols-2 gap-4">
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
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                <div v-if="form.errors.email" class="text-red-400 text-sm mt-1">{{ form.errors.email }}</div>
                            </div>
                        </div>

                        <!-- Password Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Password</label>
                                <input
                                    v-model="form.password"
                                    type="password"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                <div v-if="form.errors.password" class="text-red-400 text-sm mt-1">{{ form.errors.password }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Confirm Password</label>
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                    required
                                >
                            </div>
                        </div>

                        <!-- NIK & Phone -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">NIK</label>
                                <input
                                    v-model="form.nik"
                                    type="text"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                <div v-if="form.errors.nik" class="text-red-400 text-sm mt-1">{{ form.errors.nik }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Phone</label>
                                <input
                                    v-model="form.phone"
                                    type="text"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                <div v-if="form.errors.phone" class="text-red-400 text-sm mt-1">{{ form.errors.phone }}</div>
                            </div>
                        </div>

                        <!-- Address & Role -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Address</label>
                                <textarea
                                    v-model="form.address"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                    rows="2"
                                    required
                                ></textarea>
                                <div v-if="form.errors.address" class="text-red-400 text-sm mt-1">{{ form.errors.address }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Role</label>
                                <select
                                    v-model="form.role"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <div v-if="form.errors.role" class="text-red-400 text-sm mt-1">{{ form.errors.role }}</div>
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
                                <span>Create User</span>
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

        <!-- Edit User Modal -->
        <div v-if="showEditModal" class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-gradient-to-br from-gray-900 to-indigo-900 rounded-xl shadow-xl max-w-2xl w-full p-6 border border-indigo-700/30">
                    <!-- Modal Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white">Edit User</h2>
                        <p class="text-indigo-200 mt-1">Update user details below.</p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submitEditForm" class="space-y-4">
                        <!-- Name & Email -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Name</label>
                                <input
                                    v-model="editForm.name"
                                    type="text"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                <div v-if="editForm.errors.name" class="text-red-400 text-sm mt-1">{{ editForm.errors.name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Email</label>
                                <input
                                    v-model="editForm.email"
                                    type="email"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                <div v-if="editForm.errors.email" class="text-red-400 text-sm mt-1">{{ editForm.errors.email }}</div>
                            </div>
                        </div>

                        <!-- Password Fields (Optional) -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Password (leave blank to keep current)</label>
                                <input
                                    v-model="editForm.password"
                                    type="password"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                >
                                <div v-if="editForm.errors.password" class="text-red-400 text-sm mt-1">{{ editForm.errors.password }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Confirm Password</label>
                                <input
                                    v-model="editForm.password_confirmation"
                                    type="password"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                >
                            </div>
                        </div>

                        <!-- NIK & Phone -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">NIK</label>
                                <input
                                    v-model="editForm.nik"
                                    type="text"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                <div v-if="editForm.errors.nik" class="text-red-400 text-sm mt-1">{{ editForm.errors.nik }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Phone</label>
                                <input
                                    v-model="editForm.phone"
                                    type="text"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                <div v-if="editForm.errors.phone" class="text-red-400 text-sm mt-1">{{ editForm.errors.phone }}</div>
                            </div>
                        </div>

                        <!-- Address & Role -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Address</label>
                                <textarea
                                    v-model="editForm.address"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:border-indigo-500"
                                    rows="2"
                                    required
                                ></textarea>
                                <div v-if="editForm.errors.address" class="text-red-400 text-sm mt-1">{{ editForm.errors.address }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-indigo-200 mb-1">Role</label>
                                <select
                                    v-model="editForm.role"
                                    class="w-full px-4 py-2 bg-white/10 border border-indigo-700/30 rounded-lg text-white focus:outline-none focus:border-indigo-500"
                                    required
                                >
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <div v-if="editForm.errors.role" class="text-red-400 text-sm mt-1">{{ editForm.errors.role }}</div>
                            </div>
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
                                <span>Update User</span>
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
                        <h2 class="text-2xl font-bold text-white">Delete User</h2>
                        <p class="text-indigo-200 mt-1">Are you sure you want to delete this user? This action cannot be undone.</p>
                    </div>

                    <!-- User Info -->
                    <div class="mb-6 p-4 bg-white/5 rounded-lg border border-indigo-700/30">
                        <p class="text-white font-medium">{{ selectedUser?.name }}</p>
                        <p class="text-sm text-indigo-200 mt-1">{{ selectedUser?.email }}</p>
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
                            Delete User
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template> 