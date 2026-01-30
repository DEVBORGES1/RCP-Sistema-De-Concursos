<?php

use Illuminate\Support\Facades\Http;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "<h1>Diagnostico Gemini AI (Round 2)</h1>";

$apiKey = env('GEMINI_API_KEY');
echo "API Key Configurada: " . ($apiKey ? "SIM" : "NAO") . "<br>";

$gemini = new \App\Services\GeminiService();

echo "<h2>Tentando gerar questoes...</h2>";
$start = microtime(true);

$models = [
    'gemini-1.5-flash',
    'gemini-1.5-flash-001',
    'gemini-1.5-flash-latest',
    'gemini-1.5-pro',
    'gemini-pro'
];

echo "<h2>Testando Modelos...</h2>";

foreach ($models as $model) {
    echo "<h3>Testing: $model</h3>";
    $url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=$apiKey";
    
    try {
        $start = microtime(true);
        $response = Http::withoutVerifying()->post($url, [
            'contents' => [['parts' => [['text' => 'Hello']]]]
        ]);
        $end = microtime(true);
        
        if ($response->successful()) {
            echo "<span style='color:green; font-weight:bold'>SUCESSO!</span> (Time: " . round($end - $start, 2) . "s)<br>";
            echo "Body: " . substr($response->body(), 0, 100) . "...<br>";
            // Found a winner, we could stop but let's see options
        } else {
            echo "<span style='color:red'>FALHA ({$response->status()})</span><br>";
            $err = json_decode($response->body(), true);
            echo "Msg: " . ($err['error']['message'] ?? 'Unknown') . "<br>";
        }
    } catch (\Exception $e) {
        echo "<span style='color:red'>ERRO EXCECAO: " . $e->getMessage() . "</span><br>";
    }
    echo "<hr>";
}
