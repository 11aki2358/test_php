<?php

$url = "https://scrapbox.io/api/pages/testblog11aki2358/";
// 新しい cURL セッションを初期化します
// コネクションを開く  
$ch = curl_init(); // はじめ

//オプション
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html =  curl_exec($ch);
var_dump($html);

// phpinfo();