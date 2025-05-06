<?php
// Configuration for AI Chat Module
return [
    'zabbix_api_url' => 'http://localhost/zabbix/api_jsonrpc.php', // URL del servidor Zabbix
    'zabbix_username' => 'admin', // Usuario de Zabbix
    'zabbix_password' => 'zabbix', // Contraseña de Zabbix
    'gemini_api_key' => 'your-gemini-api-key', // Clave API de Google Gemini
    'gemini_endpoint' => 'https://gemini.googleapis.com/v1beta1/models/text-bison:predict', // Endpoint de Google Gemini
];
