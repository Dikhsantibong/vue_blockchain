<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="min-h-screen flex flex-col items-center justify-center px-4">
            <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Welcome Back</h2>

                <div v-if="status" class="mb-4 text-sm font-medium text-green-600 text-center">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <TextInput
                            id="email"
                            type="email"
                            class="block w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-blue-500"
                            v-model="form.email"
                            placeholder="Email Address"
                            required
                            autofocus
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
                            autocomplete="current-password"
                        />
                        <InputError class="mt-1" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-blue-600 hover:text-blue-800"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <PrimaryButton
                        class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Log in
                    </PrimaryButton>

                    <div class="text-center mt-4">
                        <Link
                            :href="route('register')"
                            class="text-sm text-gray-600 hover:text-gray-900"
                        >
                            Don't have an account? Register
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
