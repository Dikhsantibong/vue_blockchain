<script setup>
// import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-900 to-indigo-800">
        <div class="flex min-h-screen flex-col items-center pt-6 sm:justify-center sm:pt-0">
            <div>
                <Link href="/" class="text-white text-3xl font-bold">
                    E-Voting
                </Link>
            </div>

            <div class="w-full overflow-hidden px-6 py-4 sm:max-w-md">
                <slot />
            </div>
        </div>
    </div>
</template>
