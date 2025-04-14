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
    nik: '',
    phone: '',
    address: '',
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

        <div class="min-h-screen flex flex-col items-center justify-center">
            <div class="max-w-4xl w-full bg-white/10 backdrop-blur-sm rounded-lg shadow-xl p-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-white mb-2">Create Account</h2>
                    <p class="text-blue-200">Register to participate in secure voting</p>
                </div>
                
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div>
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="block w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-blue-200 focus:ring-2 focus:ring-blue-500"
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
                                    class="block w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-blue-200 focus:ring-2 focus:ring-blue-500"
                                    v-model="form.email"
                                    placeholder="Email Address"
                                    required
                                    autocomplete="username"
                                />
                                <InputError class="mt-1" :message="form.errors.email" />
                            </div>

                            <div>
                                <TextInput
                                    id="nik"
                                    type="text"
                                    class="block w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-blue-200 focus:ring-2 focus:ring-blue-500"
                                    v-model="form.nik"
                                    placeholder="NIK (Nomor Induk Kependudukan)"
                                    required
                                    autocomplete="off"
                                />
                                <InputError class="mt-1" :message="form.errors.nik" />
                            </div>

                            <div>
                                <TextInput
                                    id="phone"
                                    type="tel"
                                    class="block w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-blue-200 focus:ring-2 focus:ring-blue-500"
                                    v-model="form.phone"
                                    placeholder="Nomor HP"
                                    required
                                    autocomplete="tel"
                                />
                                <InputError class="mt-1" :message="form.errors.phone" />
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <TextInput
                                    id="address"
                                    type="text"
                                    class="block w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-blue-200 focus:ring-2 focus:ring-blue-500"
                                    v-model="form.address"
                                    placeholder="Alamat Lengkap"
                                    required
                                    autocomplete="street-address"
                                />
                                <InputError class="mt-1" :message="form.errors.address" />
                            </div>

                            <div>
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="block w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-blue-200 focus:ring-2 focus:ring-blue-500"
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
                                    class="block w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-blue-200 focus:ring-2 focus:ring-blue-500"
                                    v-model="form.password_confirmation"
                                    placeholder="Confirm Password"
                                    required
                                    autocomplete="new-password"
                                />
                                <InputError class="mt-1" :message="form.errors.password_confirmation" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <PrimaryButton
                            class="w-full py-3   text-blue-900 font-semibold rounded-lg transition duration-200"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Create Account
                        </PrimaryButton>

                        <div class="text-center mt-6">
                            <Link
                                :href="route('login')"
                                class="text-sm text-blue-300 hover:text-blue-200 transition"
                            >
                                Already have an account? Login
                            </Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
