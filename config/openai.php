<?php 

// config/openai.php
return [
    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),
    
    'defaults' => [
        'model' => 'gpt-3.5-turbo', // ou 'gpt-3.5-turbo'
        'temperature' => 0.7,
        'max_tokens' => 2000,
    ],
    
    'cost_per_token' => [
        'gpt-4' => 0.03 / 1000, // $0.03 per 1K tokens
        'gpt-3.5-turbo' => 0.002 / 1000, // $0.002 per 1K tokens
    ],
];