<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="robots" content="noindex,nofollow">
  <meta name="googlebot" content="noindex,nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="og:title" content="Ryko: Ryko" />
  <meta property="og:description" content="オタクのサイト。" />
  <meta property="og:image" content="https://ryko-ryko.vercel.app/images/OGP.png" />
  <meta name="theme-color" content="#000000">
  <link rel="shortcut icon" href="/images/favicon.svg" type="image/svg+xml">
  <title>Ryko: Ryko</title>
  <link rel="stylesheet" href="/css/style.css">
</head>

<body>
  <header>
    <nav>
      <div class="header-logo-menu">
        <div class="logo-area">
          <h1><a href="javascript:history.back()">Ryko: Ryko</a></h1>
        </div>
      </div>
      </div>
    </nav>
  </header>


  <main>

    <div class="blog-article">
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
        $decodedResults_single = json_decode($html_single);


        //  タイトルを表示
        echo ("<h2>");
        echo ($decodedResults_single->title);
        echo ("</h2>");

        echo ("<div class=\"article-area\">");

        date_default_timezone_set('Asia/Tokyo');

        $create_date = date('Y/m/d H:i:s', $decodedResults_single->created);
        echo ("<div class=\"date-box\">");
        echo ("created: " . $create_date);
        echo ("</div>");

        $updated_date = date('Y/m/d H:i:s', $decodedResults_single->updated);
        echo ("<div class=\"date-box\">");
        echo ("updated: " . $updated_date);
        echo ("</div>");


        //  記事を表示
        $lines = $decodedResults_single->lines;

        $j = 1;
        while ($j < count($lines)) {

          // if (!strcmp("...", $lines[$j]->text)) {
          //   //  "..." 以降の行はタグ情報なので、表示しない
          //   break;
          // }
      
          $line = $lines[$j]->text;

          if (preg_match('/^(\s|　)(\s|　)(\s|　)(\s|　)\S/', $line)) {

            $line = ("<div class=\"text\"><ul class=\"li-4\"><li>" . mb_substr($line, 4, null) . " </li></ul></div>");
          } else if (preg_match('/^(\s|　)(\s|　)(\s|　)\S/', $line)) {
            $line = ("<div class=\"text\"><ul class=\"li-3\"><li>" . mb_substr($line, 3, null) . " </li></ul></div>");
          } else if (preg_match('/^(\s|　)(\s|　)\S/', $line)) {
            $line = ("<div class=\"text\"><ul class=\"li-2\"><li>" . mb_substr($line, 2, null) . " </li></ul></div>");
          } else if (preg_match('/^(\s|　)\S/', $line)) {
            $line = ("<div class=\"text\"><ul class=\"li-1\"><li>" . mb_substr($line, 1, null) . " </li></ul></div>");
          } else {
            $line = ("<div class=\"text\">" . $line . " </div>");
          }

          if (preg_match('/\[https:\/\/gyazo.com\/\S*\]/', $line)) {
            //  Gyazoの画像リンクを含む場合
      
            //  例: 
            //  [https://gyazo.com/5598422019d8c545c0dfe26b620dcf28]
      
            //  [https://gyazo.com/ が現れる位置
            $pos_gz_b = mb_strpos($line, '[https://gyazo.com/');

            //  ] Gyazoの閉じかっこが現れる位置
            $pos_gz_e = mb_strpos($line, ']');

            //  Gyazoのurl
            $gyazo_url = mb_substr($line, $pos_gz_b + 1, $pos_gz_e - $pos_gz_b - 1);

            echo (mb_substr($line, 0, $pos_gz_b));
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
            echo (mb_substr($line, $pos_gz_e + 1, null));

          } else if (preg_match('/\[.*\shttp\S*\]/', $line)) {
            //  名前付きのリンク
            //  [リンク名 http...]
      
            //  例: 
            //  あいうえお[link name https://www.php.net/manual/ja/function.strpos.php]これがリンク
      
            //  [が現れる位置
            $pos_bl = mb_strpos($line, '[');

            //  http が現れる位置
            $pos_http = mb_strpos($line, 'http');

            //  ] が現れる位置
            $pos_el = mb_strpos($line, ']');

            echo (mb_substr($line, 0, $pos_bl));
            echo (" <a href=\"");
            echo (mb_substr($line, $pos_http, $pos_el - $pos_http));
            echo ("\" target=\"_blank\">");
            echo (mb_substr($line, $pos_bl + 1, $pos_http - $pos_bl - 2));
            echo ("</a> ");
            echo (mb_substr($line, $pos_el + 1, null));

          } else if (preg_match('/http\S*\s/', $line)) {
            //  名前のついていない、ただのurl
      
            //  例
            //  あいうえお https://www.webdesignleaves.com/pr/php/php_basic_03.php これがリンク
      

            //  http が現れる位置
            $pos_http = mb_strpos($line, 'http');

            //  ' ' (リンクの終わり)が現れる位置
            $pos_el = mb_strpos($line, ' ', $pos_http);

            echo (mb_substr($line, 0, $pos_http));
            echo (" <a href=\"");
            echo (mb_substr($line, $pos_http, $pos_el - $pos_http));
            echo ("\" target=\"_blank\">link</a> ");
            echo (mb_substr($line, $pos_el + 1, null));

          } else if (preg_match('/\[.*\]/', $line)) {
            //    [音楽]とかのタグを見つける
            //  タグをクリックしたら、      
      
            $line_text = $line;

            do {
              //  [ が現れる位置
              $pos_tag_b = mb_strpos($line_text, '[');

              //  ] 閉じかっこが現れる位置
              $pos_tag_e = mb_strpos($line_text, ']');

              //  タグの名前
              $tag_name = mb_substr($line_text, $pos_tag_b + 1, $pos_tag_e - $pos_tag_b - 1);

              //  タグの名前にhtml付けたやつ
              $tag_code = ("<span><form action=\"index.php\"  method=\"post\"><input type=\"hidden\" name=\"test\" value=\"" . $tag_name . "\" /><input type=\"hidden\" name=\"LoadMore\" value=\"0\" /><label for=\"" . $tag_name . "\" class=\"tag-label\">" . $tag_name . "</label><input class=\"hidden-button\" id=\"" . $tag_name . "\"  type=\"submit\" value=\"" . $tag_name . "\"></form></span>");

              //  タグの前にあるテキスト
              $tag_before = mb_substr($line_text, 0, $pos_tag_b);

              //  タグの後ろにあるテキスト
              $tag_after = mb_substr($line_text, $pos_tag_e + 1, null);

              $line_text = $tag_before . $tag_code . $tag_after;

            } while (preg_match('/\[.*\]/', $line_text));

            echo ($line_text);

          } else {
            echo ($line);
          }

          $j++;

        }
        echo ("</div>");
      }
      ?>
    </div>

    <div class="return-area">
      <div class="return-button">
        <a href="javascript:history.back()">Return</a>
      </div>
    </div>

    <div id="edit-link">
      <?php
      $url_sbx = ("https://scrapbox.io/ryko-ryko/" . urlencode($load_title));

      echo ("<a href=\"" . $url_sbx . "\" target=\"_blank\">edit</a>");
      ?>
    </div>

  </main>
  <footer>
    <button id="back-to-top"></button>
  </footer>
</body>

<script>

  const backToTop = document.getElementById('back-to-top');

  // トップに戻る
  backToTop.onclick = function () {
    window.scrollTo(0, 0);
  };
</script>

</html>