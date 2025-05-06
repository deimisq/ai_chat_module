<?php
require_once 'config.php';

$config = include('config.php');

// Obtener datos de la solicitud
$request = json_decode(file_get_contents('php://input'), true);
$question = $request['question'] ?? '';

// Validar entrada
if (!$question) {
    http_response_code(400);
    echo json_encode(['error' => 'Pregunta no proporcionada.']);
    exit;
}

// Función para autenticar y conectarse a la API de Zabbix
function callZabbixAPI($method, $params = []) {
    global $config;
    $url = $config['zabbix_api_url'];

    $payload = json_encode([
        'jsonrpc' => '2.0',
        'method' => $method,
        'params' => $params,
        'auth' => null,
        'id' => 1
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Conectar con Google Gemini
function callGemini($prompt) {
    global $config;

    $ch = curl_init($config['gemini_endpoint']);
    $payload = [
        'instances' => [
            ['content' => $prompt]
        ]
    ];

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $config['gemini_api_key']
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Lógica para manejar preguntas (ejemplo simple)
if (strpos($question, 'hosts') !== false) {
    $zabbixResponse = callZabbixAPI('host.get', ['output' => 'extend']);
    $hostCount = count($zabbixResponse['result']);
    $geminiResponse = callGemini("Hay $hostCount hosts siendo monitoreados en Zabbix.");
} else {
    $geminiResponse = callGemini($question);
}

echo json_encode(['answer' => $geminiResponse['predictions'][0]['content']]);
exit;
