<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link} from '@inertiajs/vue3';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                API-Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">API-Endpunkte</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        Die API bietet folgende Endpunkte für zufällige Episoden und Alben von <strong>"Die drei
                        Fragezeichen"</strong>:
                    </p>

                    <ul class="list-disc pl-5 text-gray-700 dark:text-gray-300 mb-6">
                        <li>
                            <strong>GET /api/episodes/random</strong><br/>
                            Gibt eine zufällige Episode zurück. Folgende Abfrageparameter stehen zur Verfügung:
                            <ul class="list-disc pl-5">
                                <li><strong>include</strong>: Mögliche Werte sind "tracks" und "album", um die
                                    Trackliste als Apple Music IDs oder Informationen zum Album hinzuzufügen.
                                </li>
                                <li><strong>filter[duration]</strong>: Mögliche Werte sind "short" (<30 Minuten),
                                    "normal" (>=30 und <90 Minuten), "long" (>=90 Minuten).
                                </li>
                            </ul>
                        </li>
                        <li class="mt-4">
                            <strong>GET /api/albums/random</strong><br/>
                            Gibt ein zufälliges Album zurück. Folgende Abfrageparameter stehen zur Verfügung:
                            <ul class="list-disc pl-5">
                                <li><strong>include</strong>: Mögliche Werte sind "episodes", um die Episoden dieses
                                    Albums hinzuzufügen.
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        Um die API zu verwenden, muss der generierte API-Schlüssel als Bearer-Token im
                        Authorization-Header übermittelt werden.
                    </p>

                    <div class="flex space-x-4">
                        <Link
                            :href="route('api-tokens.index')"
                            class="rounded-md px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 transition"
                        >
                            API-Token verwalten
                        </Link>

                        <a
                            :href="route('swagger.index')"
                            class="rounded-md px-4 py-2 bg-green-600 text-white hover:bg-green-700 transition"
                        >
                            API Explorer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
