<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

// --- Interfaces para os nossos dados ---
interface WeatherData {
    precipitaProb: string;
    tMin: string;
    tMax: string;
    idWeatherType: number;
}
interface NewsArticle {
    title: string;
    url: string;
    image: string;
    source: {
        name: string;
    };
}

// --- Estados (sem alterações) ---
const weather = ref<WeatherData | null>(null);
const loadingWeather = ref(true);
const errorWeather = ref<string | null>(null);
const news = ref<NewsArticle[]>([]);
const loadingNews = ref(true);
const errorNews = ref<string | null>(null);

// --- Dicionário para o tempo (sem alterações) ---
const weatherTypes: { [key: number]: { desc: string } } = {
    1: { desc: 'Céu limpo' }, 2: { desc: 'Céu pouco nublado' }, 3: { desc: 'Céu parcialmente nublado' }, 4: { desc: 'Céu muito nublado ou encoberto' }, 5: { desc: 'Aguaceiros' }, 6: { desc: 'Aguaceiros/chuva fraca' }, 9: { desc: 'Chuva' }, 10: { desc: 'Chuva forte' }, 25: { desc: 'Nevoeiro' },
};

onMounted(() => {
    // Pedidos Axios (sem alterações)
    axios.get<WeatherData>(route('api.weather')).then(response => { weather.value = response.data; }).catch(() => { errorWeather.value = 'Não foi possível carregar os dados de meteorologia.'; }).finally(() => { loadingWeather.value = false; });
    axios.get<NewsArticle[]>(route('api.news')).then(response => { if (Array.isArray(response.data)) { news.value = response.data.slice(0, 5); } else { errorNews.value = 'Não foi possível obter as notícias.'; } }).catch(() => { errorNews.value = 'Não foi possível obter as notícias.'; }).finally(() => { loadingNews.value = false; });
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="p-4 sm:p-6 lg:p-8">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Dashboard</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- **** A CORREÇÃO ESTÁ AQUI **** -->
                <!-- Widget de Meteorologia (CÓDIGO RESTAURADO) -->
                <div class="lg:col-span-1 bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-700 mb-4">Meteorologia em Lisboa</h2>
                    <div v-if="loadingWeather">A carregar...</div>
                    <div v-else-if="errorWeather">{{ errorWeather }}</div>
                    <div v-else-if="weather" class="space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-4xl font-bold">{{ weather.tMax }}°C</p>
                                <p class="text-gray-500">Máxima</p>
                            </div>
                            <div>
                                <p class="text-2xl text-gray-600">{{ weather.tMin }}°C</p>
                                <p class="text-gray-500 text-right">Mínima</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-xl text-blue-500">{{ weatherTypes[weather.idWeatherType]?.desc || 'Condição desconhecida' }}</p>
                            <p class="text-sm text-gray-500">Prob. Precipitação: {{ weather.precipitaProb }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Widget de Notícias (sem alterações) -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-700 mb-4">Últimas Notícias</h2>
                    <div v-if="loadingNews">A carregar...</div>
                    <div v-else-if="errorNews">{{ errorNews }}</div>
                    <div v-else-if="news.length > 0" class="space-y-4">
                        <a v-for="(article, index) in news" :key="index" :href="article.url" target="_blank"
                           class="flex items-center gap-4 group hover:bg-gray-50 p-2 rounded-lg transition">
                            <img :src="article.image" alt="Imagem da notícia"
                                 class="w-24 h-16 object-cover rounded-md flex-shrink-0">
                            <div>
                                <h3 class="font-semibold text-gray-800 group-hover:underline">
                                    {{ article.title }}
                                </h3>
                                <p class="text-xs text-gray-500">{{ article.source.name }}</p>
                            </div>
                        </a>
                    </div>
                    <div v-else>Não foram encontradas notícias.</div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>