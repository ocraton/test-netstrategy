<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({ event: Object });

const position = ref('Calcolo...');
const message = ref('Stiamo controllando la disponibilità...');
let pollInterval = null;

const checkStatus = async () => {
    try {
        const response = await axios.get(route('events.status', props.event.id));
        const data = response.data;

        if (data.status === 'promoted' || data.status === 'active') {
            clearInterval(pollInterval);
            const dest = data.redirect || route('events.checkout', props.event.id);
            router.visit(dest);
        }

        else if (data.status === 'expired') {
            alert("La sessione è scaduta o l'evento è terminato.");
            router.visit(route('dashboard'));
        }

        else if (data.status === 'waiting') {
            position.value = data.position;

            const peopleAhead = parseInt(data.position) - 1;

            if (peopleAhead <= 0) {
                // Posizione 1: Sei il prossimo
                message.value = "Sei il prossimo! Tieniti pronto, stai per entrare.";
            } else if (peopleAhead === 1) {
                // Posizione 2: Singolare
                message.value = "C'è solo 1 persona davanti a te.";
            } else {
                // Posizione 3+: Plurale
                message.value = `Ci sono ${peopleAhead} persone davanti a te in coda.`;
            }
        }
    } catch (error) {
        console.error("Errore polling", error);
    }
};

onMounted(() => {
    // Primo controllo immediato
    checkStatus();
    // Esegue il controllo ogni 3 secondi
    pollInterval = setInterval(checkStatus, 3000);
});

onUnmounted(() => {
    clearInterval(pollInterval);
});
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg text-center">

            <h2 class="text-2xl font-bold text-gray-900 mb-4">Sala d'Attesa Virtuale</h2>

            <div class="animate-pulse mb-6">
                <div class="h-4 bg-indigo-200 rounded w-3/4 mx-auto mb-2"></div>
                <div class="h-4 bg-indigo-200 rounded w-1/2 mx-auto"></div>
            </div>

            <p class="text-lg mb-2">Sei in coda per:</p>
            <h3 class="font-bold text-xl text-indigo-600 mb-6">{{ event.title }}</h3>

            <div class="p-6 bg-indigo-50 rounded-lg border border-indigo-100">
                <span class="block text-gray-500 text-sm uppercase font-bold tracking-wider">La tua posizione</span>
                <span class="block text-5xl font-extrabold text-indigo-700 my-2">{{ position }}</span>
                <p class="text-sm text-gray-600 font-medium">{{ message }}</p>
            </div>

            <p class="mt-6 text-xs text-gray-400">Non chiudere questa pagina. Ti reindirizzeremo automaticamente.</p>
        </div>
    </div>
</template>
