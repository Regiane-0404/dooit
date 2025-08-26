<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    /**
     * Obtém e devolve os dados de meteorologia para uma cidade específica.
     */
    public function show(): JsonResponse
    {
        $cityCode = 1110600; // Código para Lisboa, que definimos

        // Definimos uma chave de cache única para esta cidade
        $cacheKey = "weather_{$cityCode}";

        // Usamos a cache do Laravel para evitar fazer pedidos repetidos à API do IPMA.
        // Guardamos os dados por 1 hora (3600 segundos).
        // Se os dados estiverem na cache, ele devolve-os imediatamente.
        // Se não, ele executa a função para os ir buscar.
        $weatherData = Cache::remember($cacheKey, 3600, function () use ($cityCode) {
            $apiUrl = "https://api.ipma.pt/open-data/forecast/meteorology/cities/daily/{$cityCode}.json";

            try {
                // Usamos o cliente HTTP do Laravel para fazer o pedido à API do IPMA.
                $response = Http::get($apiUrl);

                // Se o pedido falhar, devolvemos null.
                if ($response->failed()) {
                    return null;
                }

                // Se o pedido for bem-sucedido, devolvemos os dados da previsão para hoje (índice 0).
                return $response->json('data')[0] ?? null;
            } catch (\Exception $e) {
                // Em caso de erro de conexão, também devolvemos null.
                return null;
            }
        });

        // Se, por alguma razão, não conseguimos obter os dados, devolvemos um erro.
        if (!$weatherData) {
            return response()->json(['error' => 'Não foi possível obter os dados de meteorologia.'], 500);
        }

        // Devolvemos os dados em formato JSON para o nosso frontend.
        return response()->json($weatherData);
    }
    public function getNews(): JsonResponse
    {
        $cacheKey = "gnews_headlines_pt";

        $newsData = Cache::remember($cacheKey, 3600, function () {
            // Lemos a nossa nova chave de API da GNews
            $apiKey = config('services.gnews.key');

            if (!$apiKey) {
                return ['error' => 'A chave da API da GNews não está configurada.'];
            }

            // O URL da GNews API, pedindo as notícias de topo em português (lang=pt)
            $apiUrl = "https://gnews.io/api/v4/top-headlines?lang=pt&token={$apiKey}";

            try {
                $response = Http::withoutVerifying()->get($apiUrl);

                if ($response->failed()) {
                    return ['error' => 'Não foi possível obter as notícias.'];
                }

                // A GNews não tem um campo 'status' como a NewsAPI,
                // por isso verificamos apenas se o pedido HTTP foi bem-sucedido.
                // Os artigos estão diretamente dentro da chave 'articles'.
                return $response->json();
            } catch (\Exception $e) {
                return ['error' => 'Erro de conexão com a API de notícias.'];
            }
        });

        return response()->json($newsData);
    }
}
