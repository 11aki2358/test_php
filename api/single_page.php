<?php
if (array_key_exists('ReadMore', $_GET)) {

  $load_title = htmlspecialchars($_GET['ReadMore'], ENT_QUOTES);

  // 個ページについて
  $single_url = ("https://scrapbox.io/api/pages/ryko-ryko/" . $load_title);
  // 新しい cURL セッションを初期化します
  // コネクションを開く
  $ch_single = curl_init(); // はじめ

  //オプション
  curl_setopt($ch_single, CURLOPT_URL, $single_url);
  curl_setopt($ch_single, CURLOPT_RETURNTRANSFER, true);
  $html_single = curl_exec($ch_single);

  // タイトルを表示
  $decodedResults_single = json_decode($html_single);

  // $descriptions = $decodedResults_single->descriptions;
  $lines = $decodedResults_single->lines;

  $j = 1;


  while ($j < count($lines)) {

    if (!strcmp("...", $lines[$j]->text)) {
      //  "..." 以降の行はタグ情報なので、表示しない
      break;
    }

    if (preg_match('/\[https:\/\/gyazo.com\/\S*\]/', $lines[$j]->text)) {
      //  Gyazoの画像リンクを含む場合

      //  例: 
      //  [https://gyazo.com/5598422019d8c545c0dfe26b620dcf28]

      echo ("<p class=\"Gyazo\">");

      //  [https://gyazo.com/ が現れる位置
      $pos_gz_b = mb_strpos($lines[$j]->text, '[https://gyazo.com/');

      //  ] Gyazoの閉じかっこが現れる位置
      $pos_gz_e = mb_strpos($lines[$j]->text, ']');

      //  Gyazoのurl
      $gyazo_url = mb_substr($lines[$j]->text, $pos_gz_b + 1, $pos_gz_e - $pos_gz_b - 1);

      echo (mb_substr($lines[$j]->text, 0, $pos_gz_b));
      echo ("<br>");

      //  GyazoのAPIを叩いて、画像の埋め込みリンクを取得する
      // コネクションを開く
      $ch_gyazo = curl_init(); // はじめ

      //オプション
      $gyazo_api = ("https://api.gyazo.com/api/oembed?url=" . $gyazo_url);
      curl_setopt($ch_gyazo, CURLOPT_URL, $gyazo_api);
      curl_setopt($ch_gyazo, CURLOPT_RETURNTRANSFER, true);
      $html_gyazo = curl_exec($ch_gyazo);

      //  画像を表示
      $gyazo_json = json_decode($html_gyazo);
      echo ("<img src=\"" . $gyazo_json->url . "\">");
      echo ("<br>");

      echo (mb_substr($lines[$j]->text, $pos_gz_e + 1, null));

      echo ("</p>");



    } else if (preg_match('/\[.*\shttp\S*\]/', $lines[$j]->text)) {
      //  名前付きのリンク
      //  [リンク名 http...]

      //  例: 
      //  あいうえお[link name https://www.php.net/manual/ja/function.strpos.php]これがリンク
      echo ("<p class=\"link-with-name\">");

      //  [が現れる位置
      $pos_bl = mb_strpos($lines[$j]->text, '[');

      //  http が現れる位置
      $pos_http = mb_strpos($lines[$j]->text, 'http');

      //  ] が現れる位置
      $pos_el = mb_strpos($lines[$j]->text, ']');


      echo (mb_substr($lines[$j]->text, 0, $pos_bl));
      echo (" <a href=\"");
      echo (mb_substr($lines[$j]->text, $pos_http, $pos_el - $pos_http));
      echo ("\" target=\"_blank\">");
      echo (mb_substr($lines[$j]->text, $pos_bl + 1, $pos_http - $pos_bl - 2));
      echo ("</a> ");
      echo (mb_substr($lines[$j]->text, $pos_el + 1, null));

    } else if (preg_match('/http\S*\s/', $lines[$j]->text)) {
      //  名前のついていない、ただのurl

      //  例
      //  あいうえお https://www.webdesignleaves.com/pr/php/php_basic_03.php これがリンク

      echo ("<p class=\"normal-url\">");

      //  http が現れる位置
      $pos_http = mb_strpos($lines[$j]->text, 'http');

      //  ' ' (リンクの終わり)が現れる位置
      $pos_el = mb_strpos($lines[$j]->text, ' ', $pos_http);

      echo (mb_substr($lines[$j]->text, 0, $pos_http));
      echo (" <a href=\"");
      echo (mb_substr($lines[$j]->text, $pos_http, $pos_el - $pos_http));
      echo ("\" target=\"_blank\">link</a> ");
      echo (mb_substr($lines[$j]->text, $pos_el + 1, null));

    } else if (preg_match('/http\S*/', $lines[$j]->text)) {
      //  名前のついていない、ただのurl(空白無しで終わるやつ)

      //  例
      //  あいうえお https://www.webdesignleaves.com/pr/php/php_basic_03.php

      echo ("<p class=\"normal-url2\">");

      //  http が現れる位置
      $pos_http = mb_strpos($lines[$j]->text, 'http');

      echo (mb_substr($lines[$j]->text, 0, $pos_http));
      echo (" <a href=\"");
      echo (mb_substr($lines[$j]->text, $pos_http, null));
      echo ("\" target=\"_blank\">link</a> ");

    } else {

      echo ("<p>");
      echo ($lines[$j]->text);
      echo ("</p>");
    }

    $j++;

  }


}
?>