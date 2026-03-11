<link rel="stylesheet" href="/api/style.css">


<?php

// $url = "https://scrapbox.io/api/pages/ryko-ryko/?skip=0&limit=5";
// // 新しい cURL セッションを初期化します
// // コネクションを開く  
// $ch = curl_init(); // はじめ

// //オプション
// curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $html = curl_exec($ch);
// // var_dump($html);

// // タイトルを表示
// $decodedResults = json_decode($html);
// $pages = $decodedResults->pages;
// ?>

<html>

<head>
</head>

<body>
  <header>
    <h1>
      header
    </h1>
  </header>
  <main>

    <div id="blog-head">
      <ul>
        <li>
          メインのサイトへのリンク
        </li>
        <li>
          ブログの紹介
        </li>
      </ul>
      <a href="https://scrapbox.io/scrapboxlab/Scrapbox_REST_API%E3%81%AE%E4%B8%80%E8%A6%A7" target="_blank">Scrapbox
        REST
        APIの一覧</a>
    </div>

    <div id="tag-list">
      <h2>検索用のタグリスト</h2>
      <ul>
        <li>
          Scrapboxにて、ピン止めされたページ<b>tags</b>を作る
        </li>
        <li>
          <b>tags</b>ページの内容を表示する。
        </li>
        <li>
          Scrapboxから読み取った<code>[AAA]</code>とか<code>[BBB]</code>とかの文字列を、タグ用のボタンに変換する。
        </li>
        <li><code>AAA</code>ボタンが押された → <code>[AAA]</code>を含む記事を検索する(<a
            href="https://scrapbox.io/api/pages/ryko-ryko/search/query?q=2026" target="_blank">例: 「2026」を含む記事を検索</a>) →
          ブログ一覧に表示する
        </li>
      </ul>

      <p>
        検索かけない場合には、最新の記事5個を、created順に拾ってくる<br>
        <a href="https://scrapbox.io/api/pages/ryko-ryko/?skip=0&limit=5&sort=created" target="_blank">link</a>

        <?php
        $url = "https://scrapbox.io/api/pages/ryko-ryko/?skip=0&limit=5&sort=created";
        // 新しい cURL セッションを初期化します
        // コネクションを開く
        $ch = curl_init(); // はじめ
        
        //オプション
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($ch);
        // var_dump($html);
        
        // タイトルを表示
        $decodedResults = json_decode($html);
        $pages = $decodedResults->pages;
        ?>
      </p>
    </div>

    <div id="blog-list">
      <h2>ブログ一覧</h2>
      最新(OR 検索結果)のブログ一覧(最初の5件)
      <p>
        <?php


        for ($i = 0; $i < count($pages); $i++) {
          $title = $pages[$i]->title;
          echo ("<div class=\"blog-article\">\n");
          echo ($title);
          echo ("</div>\n");
        }
        ?>

      </p>
    </div>

    <div id="page-nation">
      <h2>ページネーション</h2>
      指定した検索条件のもとで、次の5件 (OR 前の5件)をAPI呼び出しする。と同時に、<b>ブログ一覧</b>の表示を上書きする
    </div>

  </main>
  <footer>
    <h1>
      footer
    </h1>
  </footer>
</body>

</html>