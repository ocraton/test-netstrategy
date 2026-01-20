<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// Riceviamo gli eventi dal Controller
defineProps({
    events: Object
});
</script>

<template>

    <Head title="Eventi Disponibili" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Eventi Disponibili
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <div v-for="event in events.data" :key="event.id"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <span
                                    class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full bg-indigo-100 text-indigo-800">
                                    {{ event.type }}
                                </span>
                                <span v-if="event.max_concurrent_users"
                                    class="text-xs text-orange-600 font-semibold flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Fila probabile
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ event.title }}</h3>

                            <div class="text-sm text-gray-600 mb-6 space-y-1">

                                <div v-if="event.type === 'concert'">
                                    <p>🎤 <strong>Artista:</strong> {{ event.details.artist }}</p>
                                    <p>📍 <strong>Location:</strong> {{ event.details.location || event.details.tour_leg
                                        }}</p>
                                </div>

                                <div v-else-if="event.type === 'theater'">
                                    <p>🎭 <strong>Opera:</strong> {{ event.details.opera }}</p>
                                    <p>🎬 <strong>Regia:</strong> {{ event.details.director || event.details.playwright
                                        }}</p>
                                </div>

                                <div v-else-if="event.type === 'sport'">
                                    <p>🏆 <strong>Competizione:</strong> {{ event.details.competition ||
                                        event.details.league }}
                                    </p>
                                    <p>⚔️ <strong>Match:</strong>
                                        <span v-if="event.details.teams">{{ event.details.teams.join(' vs ') }}</span>
                                    </p>
                                </div>

                                <div v-else>
                                    <p>ℹ️ Dettagli non specificati</p>
                                </div>
                            </div>

                            <Link :href="route('dashboard')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Acquista Biglietto
                            </Link>
                        </div>
                    </div>

                </div>

                <div class="mt-6 flex justify-center">
                    <div class="flex gap-2">
                        <Link v-for="link in events.links" :key="link.label" :href="link.url ?? '#'"
                            class="px-4 py-2 border rounded-md text-sm"
                            :class="{ 'bg-gray-800 text-white': link.active, 'text-gray-500 cursor-not-allowed': !link.url, 'hover:bg-gray-100': link.url && !link.active }"
                             />
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
