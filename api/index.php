<link rel="stylesheet" href="/css/style.css">


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



      <p>
        検索かけない場合には、最新の記事5個を、created順に拾ってくる<br>
        <a href="https://scrapbox.io/api/pages/ryko-ryko/?skip=0&limit=5&sort=created" target="_blank">link</a>

        <?php
        $url = "https://scrapbox.io/api/pages/ryko-ryko/?sort=created";
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

      <?php
      $url_tags = "https://scrapbox.io/api/pages/ryko-ryko/tags";
      // 新しい cURL セッションを初期化します
      // コネクションを開く
      $ch_tags = curl_init(); // はじめ
      
      //オプション
      curl_setopt($ch_tags, CURLOPT_URL, $url_tags);
      curl_setopt($ch_tags, CURLOPT_RETURNTRANSFER, true);
      $html_tags = curl_exec($ch_tags);
      $decodedResults_tags = json_decode($html_tags);
      
      // タイトルを表示
      $lines = $decodedResults_tags->lines;


      for($i = 1; $i < count($lines); $i++){
        $tag_name = preg_replace('/\[|\]/', "", $lines[$i]->text);

        echo("<p>");
        echo($tag_name);
        echo("</p>");
      }


      ?>

    </div>



    <div>
      <h2>めも</h2>

      <p>
        初期や検索では、該当するページのtitleのみを気にする(プロジェクト内ページ全文検索 / プロジェクト内のページ情報)。
        (検索の場合、limit/skipを指定できない点に注意。)
      </p>
      <p>
        各ページ(.blog-article)を表示する際に、記事の冒頭・更新日時・サムネ画像諸々を取得する
      </p>

      ページの情報の取得
      <ul>
        <li>
          個別のページ情報<br>
          作成/更新日時や画像URLも取得できる<br>
          <a href="https://scrapbox.io/api/pages/ryko-ryko/top" target="_blank">画像あり(<b>top</b>)</a><br>
          <a href="https://scrapbox.io/api/pages/ryko-ryko/20260218" target="_blank">画像無し(<b>20260218</b>)</a>
        </li>
        <li>
          指定したページのプレーンなテキスト。ブラケットなどはそのまま維持(今回は、<b>20260301</b>)
          <a href="https://scrapbox.io/api/pages/ryko-ryko/20260301/text" target="_blank">link</a>
        </li>
        <li>
          プロジェクト内のページ一覧と、各ページの[リンク]情報が取得できる(作成日時が古い順)<br>
          <a href="https://scrapbox.io/api/pages/ryko-ryko/search/titles" target="_blank">link</a>
        </li>
      </ul>

      全文検索
      <ul>
        <li>
          プロジェクト内ページ全文検索。複数語句検索は出来る、マイナス検索は怪しい?<br>
          <a href="https://scrapbox.io/api/pages/ryko-ryko/search/query?q=2026" target="_blank">例: 「2026」を含む記事を検索</a>
        </li>
      </ul>

      Projectの情報を取得する
      <ul>
        <li>プロジェクト内のページ情報<br>
          <code>limit</code>: 取得するページ情報の最大数<br>
          <code>skip</code>: 何番目のページから取得するかを指定する<br>
          <code>sort</code>: ソート方法(`updated`,`created`,`accessed`,`linked`,`views`,`title`,`updatedbyMe`)<br>
          <a href="https://scrapbox.io/api/pages/ryko-ryko" target="_blank">link</a>
        </li>
      </ul>

    </div>

    <div id="blog-list">
      <h2>ブログ一覧</h2>
      最新(OR 検索結果)のブログ一覧(最初の5件だけを表示できるようにしたい)
      <p>
        <?php
        for ($i = 0; $i < count($pages); $i++) {
          $title = $pages[$i]->title;

          if (!$pages[$i]->pin) {
            //  ピン止めされている投稿は除外
            //  top, setting, タグの説明など
        

            // 個ページについて
            $single_url = ("https://scrapbox.io/api/pages/ryko-ryko/" . $title);
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

            echo ("<div class=\"blog-article\">\n");
            echo ("<h2>");
            echo ($title);
            echo ("</h2>");

            $j = 1;
            while ($j <= 5) {

              if (!strcmp("...", $lines[$j]->text)) {
                //  "..." 以降の行はタグ情報なので、表示しない
                break;
              }

              if (preg_match('/\[http\S*\]/', $lines[$j]->text)) {
                //  [http...]
                //  URLを含む場合
                //  リンク名は不明
                echo ("<p class=\"red\">");
              } else {
                echo ("<p>");
              }



              echo ($lines[$j]->text);
              echo ("</p>");
              $j++;

            }

            if (count(($lines)) > 6) {
              echo ("<details>");
              echo ("<summary>");
              echo ("show more");
              echo ("</summary>");
              $j = 6;

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
              echo ("</details>");
            }
            echo ("</div>\n");
          }
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