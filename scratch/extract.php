<?php
$html = file_get_contents('edforth.html');
preg_match('/<!-- Main Content -->(.*?)<!-- Footer -->/s', $html, $matches);
file_put_contents('scratch/main_content.html', $matches[1] ?? 'NOT FOUND');

preg_match('/(.*?)<!-- Main Content -->/s', $html, $matches_head);
file_put_contents('scratch/header.html', $matches_head[1] ?? 'NOT FOUND');

preg_match('/<!-- Footer -->(.*)/s', $html, $matches_foot);
file_put_contents('scratch/footer.html', $matches_foot[1] ?? 'NOT FOUND');

echo "Extracted";
