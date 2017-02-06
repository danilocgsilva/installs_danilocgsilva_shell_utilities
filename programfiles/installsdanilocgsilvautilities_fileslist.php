<?php
echo 'oi';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/users/danilocgsilva/repos');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$content = curl_exec($ch);
// header("Content-Type: text/plain");
echo $content;
$obj_returned = json_decode($content);
echo 'oi2';