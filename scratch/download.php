<?php
$html = file_get_contents('https://edforthtutors.com/');
file_put_contents('d:/Prince/Edforth/edforth.html', $html);
echo "Downloaded";
