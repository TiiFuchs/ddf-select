<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Willkommen" />
    <div class="bg-gray-50 text-black dark:bg-black dark:text-white min-h-screen flex flex-col items-center justify-center">
        <div class="max-w-xl text-center">
            <h1 class="text-4xl font-bold mb-6 text-gray-900 dark:text-white">Willkommen zur API</h1>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-8">
                Diese Seite dient als API-Endpunkt, um zufällige Episoden von <strong>"Die drei ???"</strong> abzurufen.
            </p>
            <nav v-if="canLogin">
                <p class="text-md text-gray-700 dark:text-gray-400 mb-8">
                    Um auf das Dashboard zuzugreifen oder sich anzumelden, verwenden Sie den unten stehenden Link.
                </p>

                <Link
                    :href="route('login')"
                    class="rounded-md px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 transition"
                >
                    Anmelden
                </Link>
            </nav>
        </div>
    </div>
</template>
