<?php
$url = 'http://localhost:8000/dashboard';
$content = file_get_contents($url);
echo substr($content, 0, 500);
