<script setup>
import { onMounted, onUnmounted, ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({ event: Object });

const secondsLeft = ref(120);

// Definiamo DUE intervalli distinti
let timerInterval = null;       // Per il countdown visivo (ogni secondo)
let statusCheckInterval = null; // Per il controllo col server (ogni 5 secondi)

const formattedTime = computed(() => {
    const totalSeconds = Math.floor(secondsLeft.value);
    const m = Math.floor(totalSeconds / 60);
    const s = totalSeconds % 60;
    return `${m}:${s < 10 ? '0' : ''}${s}`;
});

const checkStatus = async () => {
    try {
        const response = await axios.get(route('events.status', props.event.id));
        const data = response.data;

        // caso scaduto
        if (data.status === 'expired') {
            clearInterval(statusCheckInterval); // Smettiamo di controllare
            alert("Tempo scaduto! Il tuo biglietto è stato liberato.");
            router.visit(route('dashboard'));
        }

        // caso ancora attivo (Sincronizziamo il timer)
        else if (data.seconds_remaining) {
            // Aggiorniamo il timer col valore reale del server per evitare discrepanze
            secondsLeft.value = Math.floor(data.seconds_remaining);
        }

        // caso se per errore finisci in waiting (es. reset manuale DB)
        else if (data.status === 'waiting') {
            router.visit(route('events.waiting-room', props.event.id));
        }

    } catch (e) {
        console.error("Errore polling:", e);
    }
};

onMounted(() => {
    // 1. Controllo immediato e poi ogni 5 secondi
    checkStatus();
    statusCheckInterval = setInterval(checkStatus, 5000);

    // 2. Countdown locale per fluidità UI
    timerInterval = setInterval(() => {
        if (secondsLeft.value > 0) {
            secondsLeft.value--;
        }
    }, 1000);
});

onUnmounted(() => {
    clearInterval(timerInterval);
    clearInterval(statusCheckInterval);
});
</script>

<template>

    <Head title="Checkout" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Checkout</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-red-700">Completa l'acquisto!</p>
                            <p class="text-sm text-red-600">I tuoi biglietti sono riservati per un tempo limitato.</p>
                        </div>
                        <div class="text-3xl font-mono font-bold text-red-700">
                            {{ formattedTime }}
                        </div>
                    </div>

                    <h3 class="text-2xl font-bold mb-4">{{ event.title }}</h3>
                    <p class="text-gray-600 mb-8">Stai acquistando i biglietti. Inserisci i dati di pagamento...</p>

                    <div class="space-y-4">
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                        <button class="bg-green-600 text-white px-6 py-3 rounded font-bold hover:bg-green-700">
                            Paga e Concludi
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
