<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="min-h-screen flex flex-col items-center justify-center px-4">
            <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Create Account</h2>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <TextInput
                            id="name"
                            type="text"
                            class="block w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-blue-500"
                            v-model="form.name"
                            placeholder="Full Name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>

                    <div>
                        <TextInput
                            id="email"
                            type="email"
                            class="block w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-blue-500"
                            v-model="form.email"
                            placeholder="Email Address"
                            required
                            autocomplete="username"
                        />
                        <InputError class="mt-1" :message="form.errors.email" />
                    </div>

                    <div>
                        <TextInput
                            id="password"
                            type="password"
                            class="block w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-blue-500"
                            v-model="form.password"
                            placeholder="Password"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-1" :message="form.errors.password" />
                    </div>

                    <div>
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="block w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-blue-500"
                            v-model="form.password_confirmation"
                            placeholder="Confirm Password"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-1" :message="form.errors.password_confirmation" />
                    </div>

                    <PrimaryButton
                        class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Create Account
                    </PrimaryButton>

                    <div class="text-center mt-4">
                        <Link
                            :href="route('login')"
                            class="text-sm text-gray-600 hover:text-gray-900"
                        >
                            Already have an account? Login
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
