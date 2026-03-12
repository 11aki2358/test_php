<link rel="stylesheet" href="/css/style.css">

<?php
session_start();
?>

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
        if (!isset($_SESSION["pages_all"])) {

          $url_all = "https://scrapbox.io/api/pages/ryko-ryko/?sort=created";
          // 新しい cURL セッションを初期化します
          // コネクションを開く
          $ch_all = curl_init(); // はじめ
        
          //オプション
          curl_setopt($ch_all, CURLOPT_URL, $url_all);
          curl_setopt($ch_all, CURLOPT_RETURNTRANSFER, true);
          $html_all = curl_exec($ch_all);
          // var_dump($html);
        
          // タイトルを表示
          $decodedResults_all = json_decode($html_all);
          $pages_all = $decodedResults_all->pages;
          $_SESSION['pages_all'] = $pages_all;


          //  ピンがついていない要素(一覧に乗るやつ)を抽出する
          $pages_unpin = array();
          for ($i = 0; $i < count($pages_all); $i++) {
            //  ピン止めされていないpageのみ、一覧に表示する
            if (!$pages_all[$i]->pin) {
              array_push($pages_unpin, $pages_all[$i]);
            }
          }

          // $decodedResults = $decodedResults_all;
          // $pages = $pages_all;
          $pages = $pages_unpin;
        }

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


      for ($i = 1; $i < count($lines); $i++) {
        $tag_name = preg_replace('/\[|\]/', "", $lines[$i]->text);

        echo ("<span>");
        echo ("<form method=\"get\">");
        echo ("<input type=\"submit\" name=\"test\" value=\"" . $tag_name . "\" />");
        echo ("</form>");
        echo ("</span>");
      }


      if (array_key_exists('test', $_GET)) {

        $tag = htmlspecialchars($_GET['test'], ENT_QUOTES);

        // タグの情報をもとに、APIを叩く
        //  日本語をURLに含めるときは、エンコードが必要!
        $url_tag = ("https://scrapbox.io/api/pages/ryko-ryko/" . urlencode($tag));
        // 新しい cURL セッションを初期化します
        // コネクションを開く
      
        $ch_tag = curl_init(); // はじめ
      
        //オプション
        curl_setopt($ch_tag, CURLOPT_URL, $url_tag);
        curl_setopt($ch_tag, CURLOPT_RETURNTRANSFER, true);
        $html_tag = curl_exec($ch_tag);

        // タイトルを表示
        unset($GLOBALS['decodedResults']);
        unset($GLOBALS['pages']);
        $decodedResults_tag = json_decode($html_tag);
        $pages_tag = $decodedResults_tag->relatedPages->links1hop;

        //  ピンがついていない要素(一覧に乗るやつ)を抽出する
        $pages_unpin = array();
        for ($i = 0; $i < count($pages_tag); $i++) {

          $title = $pages_tag[$i]->title;
          $search_result = array_search($title, array_column($pages_all, "title"));

          //  ピン止めされていないpageのみ、一覧に表示する
          if (!$pages_all[$search_result]->pin) {
            array_push($pages_unpin, $pages_tag[$i]);
          }
        }
        $pages = $pages_unpin;

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

        //  いまから、 $now_page*5 ~ $now_page*5+5
        $now_page = 0;

        for ($i = $now_page; ($i < count($pages)); $i++) {


          $title = $pages[$i]->title;
          $search_result = array_search($title, array_column($pages_all, "title"));


          //  ピン止めされている投稿は除外
          //  top, setting, タグの説明など
        
          echo ("<div class=\"blog-article\">\n");
          echo ("<h2>");
          echo ($title);
          echo ("</h2>");

          echo ("<div class=\"article-descriptions\">");

          $index = 0;
          while ($index <= (count($pages_all[$search_result]->descriptions)) - 1) {
            echo ($pages_all[$search_result]->descriptions[$index]);
            echo ("<br>");
            $index++;
          }
          echo ("</div>");

          echo ("<span>");
          echo ("<form action=\"single_page.php\"  method=\"get\">");
          echo ("<button type=\"submit\" name=\"ReadMore\" value=\"" . $title . "\" >もっと読む</button>");
          echo ("</form>");
          echo ("</span>");
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