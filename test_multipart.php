<?php
$boundary = "---boundary" . uniqid();
$content = "--$boundary\r\n";
$content .= "Content-Disposition: form-data; name=\"field_1\"\r\n\r\n";
$content .= "testdata\r\n";
$content .= "--$boundary\r\n";
$content .= "Content-Disposition: form-data; name=\"field_2\"; filename=\"test.txt\"\r\n";
$content .= "Content-Type: text/plain\r\n\r\n";
$content .= "hello world\r\n";
$content .= "--$boundary--\r\n";

$options = [
    'http' => [
        'header'  => "Content-type: multipart/form-data; boundary=$boundary\r\n",
        'method'  => 'POST',
        'content' => $content,
        'ignore_errors' => true
    ]
];
$context  = stream_context_create($options);
$result = file_get_contents('http://localhost:8000/register-as-tutor/submit', false, $context);
print_r($http_response_header);
echo $result;
