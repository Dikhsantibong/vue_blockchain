<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="E-Voting System" />
    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800 text-white">
        <div class="relative">
            <!-- Navigation -->
            <nav class="absolute w-full p-6 flex justify-end">
                <div v-if="canLogin" class="space-x-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-white hover:text-blue-200 transition"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-white hover:text-blue-200 transition"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="ml-4 text-white hover:text-blue-200 transition"
                        >
                            Register
                        </Link>
                    </template>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="min-h-screen flex flex-col items-center justify-center px-4 text-center">
                <div class="max-w-4xl mx-auto">
                    <h1 class="text-5xl font-bold mb-8">Secure Blockchain E-Voting System</h1>
                    <p class="text-xl text-blue-100 mb-12">
                        Experience the future of voting with our secure, transparent, and efficient blockchain-based voting system.
                    </p>
                    
                    <div class="flex justify-center space-x-6">
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-8 py-3 bg-white text-blue-900 rounded-lg font-semibold hover:bg-blue-100 transition"
                        >
                            Get Started
                        </Link>
                        <Link
                            :href="route('login')"
                            class="px-8 py-3 border-2 border-white text-white rounded-lg font-semibold hover:bg-white/10 transition"
                        >
                            Login to Vote
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-white/10 backdrop-blur-sm py-24">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-3xl font-bold text-center mb-16">Why Choose Our E-Voting System?</h2>
                
                <div class="grid md:grid-cols-3 gap-12">
                    <div class="text-center">
                        <div class="bg-white/20 rounded-lg p-6 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Secure Voting</h3>
                        <p class="text-blue-100">Protected by blockchain technology and SHA-256 encryption</p>
                    </div>

                    <div class="text-center">
                        <div class="bg-white/20 rounded-lg p-6 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Transparent Process</h3>
                        <p class="text-blue-100">Verifiable and immutable voting records</p>
                    </div>

                    <div class="text-center">
                        <div class="bg-white/20 rounded-lg p-6 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Instant Results</h3>
                        <p class="text-blue-100">Real-time vote counting and result verification</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
