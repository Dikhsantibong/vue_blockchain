<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import UserSidebar from '@/Components/UserSidebar.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800">
        <UserSidebar :user-votes="[]" :active-elections="[]" />
        
        <div class="pl-64">
            <main class="p-8">
                <div class="mb-8 bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-indigo-700/30">
                    <h2 class="text-3xl font-bold text-white">
                        Profile Settings
                    </h2>
                </div>

                <div class="space-y-6">
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-indigo-700/30">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="max-w-xl"
                        />
                    </div>

                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-indigo-700/30">
                        <UpdatePasswordForm class="max-w-xl" />
                    </div>

                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-indigo-700/30">
                        <DeleteUserForm class="max-w-xl" />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
