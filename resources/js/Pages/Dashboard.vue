<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// Definiamo le props in ingresso da Laravel (in TS)
const props = defineProps<{
    events: {
        data: Array<any>;
        links: Array<any>;
    }
}>();
</script>

<template>

    <Head title="Dashboard - Eventi" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Eventi in Programma
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <div v-for="event in props.events.data" :key="event.id"
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200 border border-transparent hover:border-indigo-500">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <span
                                    class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                    {{ event.type }}
                                </span>
                                <span v-if="event.max_concurrent_users"
                                    class="flex items-center text-xs font-semibold text-orange-600 dark:text-orange-400">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Fila probabile
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 truncate">
                                {{ event.title }}
                            </h3>

                            <div class="text-sm text-gray-600 dark:text-gray-300 mb-6 space-y-1 h-20">
                                <template v-if="event.type === 'concert'">
                                    <p>🎤 <strong>Artista:</strong> {{ event.details.artist }}</p>
                                    <p>📍 <strong>Luogo:</strong> {{ event.details.location ?? 'TBA' }}</p>
                                </template>

                                <template v-else-if="event.type === 'theater'">
                                    <p>🎭 <strong>Opera:</strong> {{ event.details.opera }}</p>
                                    <p>🎬 <strong>Regia:</strong> {{ event.details.director }}</p>
                                </template>

                                <template v-else-if="event.type === 'sport'">
                                    <p>🏆 <strong>Torneo:</strong> {{ event.details.competition }}</p>
                                    <p>⚔️ <strong>Match:</strong> {{ event.details.teams ? event.details.teams.join(' vs ') : '' }}</p>
                                </template>

                                <template v-else>
                                    <p class="italic text-gray-400">Dettagli vari...</p>
                                </template>
                            </div>

                            <div class="mt-auto">
                                <Link :href="route('events.join', event.id)" method="post" as="button"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Acquista Biglietto
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="props.events.links.length > 3" class="mt-8 flex justify-center">
                    <div class="flex flex-wrap gap-2">
                        <Link v-for="(link, key) in props.events.links" :key="key" :href="link.url ?? '#'"
                            class="px-3 py-1 border rounded-md text-sm transition-colors" :class="{
                                'bg-gray-800 text-white dark:bg-gray-200 dark:text-gray-800': link.active,
                                'text-gray-500 cursor-not-allowed': !link.url,
                                'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300': link.url && !link.active
                            }">
                            <span v-html="link.label" />
                        </Link>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
