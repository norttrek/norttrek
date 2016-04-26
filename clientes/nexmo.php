<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');


$url = 'https://rest.nexmo.com/sms/json?' . http_build_query([
        'api_key' => '16541359',
        'api_secret' => 'b5b1d20d',
        'to' => '8112299159',
        'from' => 'NEXMO_NUMBER',
        'text' => 'Hello from Nexmo'
    ]);

$ch = curl_init($url); 
?>