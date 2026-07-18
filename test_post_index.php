<?php
$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query(['test' => 'data'])
    ]
];
$context  = stream_context_create($options);
$result = @file_get_contents('http://localhost:8000/register-as-tutor', false, $context);
print_r($http_response_header);
