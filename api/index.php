<?php

$now_page = 0;
$pages_all;
$tag = "XXX";

function show_article($page_array, $i)
{
  $title = $page_array[$i]->title;
  echo ("<div class=\"blog-article\">\n");
  echo ("<h2>");
  echo ($title);
  echo ("</h2>");

  echo("<div class=\"article-area\">");

  $create_date = date('Y/m/d H:i:s', $page_array[$i]->created);
  echo ("<div class=\"date-box\">");
  echo ("created: " . $create_date);
  echo ("</div>");

  $updated_date = date('Y/m/d H:i:s', $page_array[$i]->updated);
  echo ("<div class=\"date-box\">");
  echo ("updated: " . $updated_date);
  echo ("</div>");

  echo ("<div class=\"article-descriptions\">");
  $index = 0;
  while ($index <= (count($page_array[$i]->descriptions)) - 1) {
    $description_text = $page_array[$i]->descriptions[$index];
    // echo ($description_text);
    // echo ("<br>");
    show_text($description_text);
    $index++;
  }

  echo ("</div>");

  echo ("<div class=\"ReadMore-area\">");
  echo ("<form action=\"single_page.php\"  method=\"get\">");
  echo ("<button class=\"ReadMore-button\"  type=\"submit\" name=\"ReadMore\" value=\"" . $title . "\" >もっと読む</button>");
  echo ("</form>");
  echo ("</div>");

  echo ("</div>");
  echo ("</div>");
}

function show_text($input_text)
{
  if (!strcmp("...", $input_text)) {
    //  "..." 以降の行はタグ情報なので、表示しない
  }

  if (preg_match('/\[https:\/\/gyazo.com\/\S*\]/', $input_text)) {
    //  Gyazoの画像リンクを含む場合

    //  例: 
    //  [https://gyazo.com/5598422019d8c545c0dfe26b620dcf28]

    echo ("<p class=\"Gyazo\">");

    //  [https://gyazo.com/ が現れる位置
    $pos_gz_b = mb_strpos($input_text, '[https://gyazo.com/');

    //  ] Gyazoの閉じかっこが現れる位置
    $pos_gz_e = mb_strpos($input_text, ']');

    //  Gyazoのurl
    $gyazo_url = mb_substr($input_text, $pos_gz_b + 1, $pos_gz_e - $pos_gz_b - 1);

    echo (mb_substr($input_text, 0, $pos_gz_b));
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
    echo (mb_substr($input_text, $pos_gz_e + 1, null));
    echo ("</p>");

  } else if (preg_match('/\[.*\shttp\S*\]/', $input_text)) {
    //  名前付きのリンク
    //  [リンク名 http...]

    //  例: 
    //  あいうえお[link name https://www.php.net/manual/ja/function.strpos.php]これがリンク
    echo ("<p class=\"link-with-name\">");

    //  [が現れる位置
    $pos_bl = mb_strpos($input_text, '[');

    //  http が現れる位置
    $pos_http = mb_strpos($input_text, 'http');

    //  ] が現れる位置
    $pos_el = mb_strpos($input_text, ']');

    echo (mb_substr($input_text, 0, $pos_bl));
    echo (" <a href=\"");
    echo (mb_substr($input_text, $pos_http, $pos_el - $pos_http));
    echo ("\" target=\"_blank\">");
    echo (mb_substr($input_text, $pos_bl + 1, $pos_http - $pos_bl - 2));
    echo ("</a> ");
    echo (mb_substr($input_text, $pos_el + 1, null));

  } else if (preg_match('/http\S*\s/', $input_text)) {
    //  名前のついていない、ただのurl

    //  例
    //  あいうえお https://www.webdesignleaves.com/pr/php/php_basic_03.php これがリンク

    echo ("<p class=\"normal-url\">");

    //  http が現れる位置
    $pos_http = mb_strpos($input_text, 'http');

    //  ' ' (リンクの終わり)が現れる位置
    $pos_el = mb_strpos($input_text, ' ', $pos_http);

    echo (mb_substr($input_text, 0, $pos_http));
    echo (" <a href=\"");
    echo (mb_substr($input_text, $pos_http, $pos_el - $pos_http));
    echo ("\" target=\"_blank\">link</a> ");
    echo (mb_substr($input_text, $pos_el + 1, null));

  } else if (preg_match('/http\S*/', $input_text)) {
    //  名前のついていない、ただのurl(空白無しで終わるやつ)

    //  例
    //  あいうえお https://www.webdesignleaves.com/pr/php/php_basic_03.php

    echo ("<p class=\"normal-url2\">");

    //  http が現れる位置
    $pos_http = mb_strpos($input_text, 'http');

    echo (mb_substr($input_text, 0, $pos_http));
    echo (" <a href=\"");
    echo (mb_substr($input_text, $pos_http, null));
    echo ("\" target=\"_blank\">link</a> ");


  } else if (preg_match('/\[.*\]/', $input_text)) {
    //    [音楽]とかのタグを見つける
    //  タグをクリックしたら、      

    $line_text = $input_text;

    do {
      //  [ が現れる位置
      $pos_tag_b = mb_strpos($line_text, '[');

      //  ] 閉じかっこが現れる位置
      $pos_tag_e = mb_strpos($line_text, ']');

      //  タグの名前
      $tag_name = mb_substr($line_text, $pos_tag_b + 1, $pos_tag_e - $pos_tag_b - 1);

      //  タグの名前にhtml付けたやつ
      $tag_code = ("<form action=\"index.php\"  method=\"post\"><input type=\"hidden\" name=\"test\" value=\"" . $tag_name . "\" /><input type=\"hidden\" name=\"LoadMore\" value=\"0\" /><input type=\"submit\" value=\"" . $tag_name . "\"></form>");

      //  タグの前にあるテキスト
      $tag_before = mb_substr($line_text, 0, $pos_tag_b);

      //  タグの後ろにあるテキスト
      $tag_after = mb_substr($line_text, $pos_tag_e + 1, null);

      $line_text = $tag_before . $tag_code . $tag_after;

    } while (preg_match('/\[.*\]/', $line_text));

    echo ($line_text);

  } else {

    echo ("<p>");
    echo ($input_text);
    echo ("</p>");
  }
}
function show_prev_page($now_page, $tag)
{
  echo ("<span>");
  echo ("<form action=\"\"  method=\"post\">");
  echo ("<input type=\"hidden\" name=\"test\" value=\"" . $tag . "\" />");
  echo ("<input type=\"hidden\" name=\"LoadMore\" value=\"" . ($now_page - 1) . "\" />");
  echo ("<label id=\"prev-label\"  for=\"prev_arrow\">← " . $now_page . "</lavel>");
  echo ("<input id=\"prev_arrow\"  type=\"submit\" value=\"" . ($now_page - 1) . "\">");
  echo ("</form>");
  echo ("</span>");
}

function show_next_page($now_page, $tag)
{
  echo ("<span>");
  echo ("<form action=\"\"  method=\"post\">");
  echo ("<input type=\"hidden\" name=\"test\" value=\"" . $tag . "\" />");
  echo ("<input type=\"hidden\" name=\"LoadMore\" value=\"" . ($now_page + 1) . "\" />");
  echo ("<label id=\"next-label\"  for=\"next_arrow\">→ " . $now_page + 2 . "</lavel>");
  echo ("<input id=\"next_arrow\" type=\"submit\" value=\"" . ($now_page + 1) . "\">");
  echo ("</form>");
  echo ("</span>");
}

?>

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
          <h1><a href="top.html">Ryko: Ryko</a></h1>
        </div>
      </div>
      </div>
    </nav>
  </header>

  <main>

    <article>
      <h2>blog-head</h2>
      <div>
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
    </article>

    <article>
      <div id="tag-list">

        <p>
          <?php

          $url_all = "https://scrapbox.io/api/pages/ryko-ryko/?sort=created";
          // 新しい cURL セッションを初期化
          // コネクションを開く
          $ch_all = curl_init();
          curl_setopt($ch_all, CURLOPT_URL, $url_all);
          curl_setopt($ch_all, CURLOPT_RETURNTRANSFER, true);
          $html_all = curl_exec($ch_all);

          $decodedResults_all = json_decode($html_all);
          $pages_all = $decodedResults_all->pages;

          //  ピンがついていない要素(一覧に乗るやつ)を抽出する
          $pages_unpin = array();
          for ($i = 0; $i < count($pages_all); $i++) {
            //  ピン止めされていないpageのみ、一覧に表示する
            if (!$pages_all[$i]->pin) {
              array_push($pages_unpin, $pages_all[$i]);
            }
          }

          $pages = $pages_unpin;

          ?>
        </p>

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

        echo ("<span>");
        echo ("<form action=\"\"  method=\"post\">");
        echo ("<input type=\"hidden\" name=\"test\" value=\"xxx\" />");
        echo ("<input type=\"hidden\" name=\"LoadMore\" value=\"" . $now_page . "\" />");
        echo ("<input type=\"submit\" value=\"created\">");

        echo ("</form>");
        echo ("</span>");


        for ($i = 1; $i < count($lines); $i++) {
          $tag_name = preg_replace('/\[|\]/', "", $lines[$i]->text);

          echo ("<span>");
          echo ("<form action=\"\"  method=\"post\">");
          echo ("<input type=\"hidden\" name=\"test\" value=\"" . $tag_name . "\" />");
          echo ("<input type=\"hidden\" name=\"LoadMore\" value=\"" . $now_page . "\" />");
          echo ("<input type=\"submit\" value=\"" . $tag_name . "\">");
          echo ("</form>");
          echo ("</span>");
        }

        ?>

      </div>
    </article>

    <article>
      <!-- <h2>ブログ一覧</h2> -->

      <div id="blog-list">
        <p>
          <?php

          if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (array_key_exists('test', $_POST)) {

              $tag = htmlspecialchars($_POST['test'], ENT_QUOTES);
              $now_page = htmlspecialchars($_POST['LoadMore'], ENT_QUOTES);

              if (!($tag === "xxx")) {

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

                $decodedResults_tag = json_decode($html_tag);
                $pages = $decodedResults_tag->relatedPages->links1hop;

                $j = 0;
                for ($i = $now_page * 6; ($i < count($pages) && ($j < 5)); $i++) {

                  $title = $pages[$i]->title;

                  //  $pages(個ページ情報に載っている relatedPages 下のオブジェクト) には、pinの有無については載っていない
                  //  pinの有無の判定のために、 $pages_all にアクセスする
                  $search_result = array_search($title, array_column($pages_all, "title"));

                  //  ピン止めされていないpageのみ、一覧に表示する
                  if (!$pages_all[$search_result]->pin) {

                    $j++;
                    show_article($pages_all, $search_result);
                  }
                }

                if (1 <= $now_page) {
                  show_prev_page($now_page, $tag);
                }

                if ($now_page * 6 + 6 < count($pages)) {
                  show_next_page($now_page, $tag);
                }

              } else {
                //  全件検索のとき
                //  $pages = $pages_unpin;
          
                //  "next"ボタンを押すたびに [API呼び出し & ピンの有無の判定] をやるのはダルいので
          
                $j = 0;
                for ($i = $now_page * 5; ($i < count($pages) && ($j <= 4)); $i++) {

                  $j++;
                  show_article($pages, $i);

                }

                if (1 <= $now_page) {
                  show_prev_page($now_page, $tag);
                }

                if ($now_page * 5 + 5 < count($pages)) {
                  show_next_page($now_page, $tag);
                }

              }


            }
          }
          echo ("</div>\n");
          ?>

        </p>
      </div>
    </article>

    <h2>ページネーション</h2>
    <div id="page-nation">
      指定した検索条件のもとで、次の5件 (OR 前の5件)をAPI呼び出しする。と同時に、<b>ブログ一覧</b>の表示を上書きする
    </div>

  </main>
  <!-- フッター -->

  <footer>
    <button id="back-to-top"></button>
    <nav>
      <ul>
        <li><a href="/index.html" class="btn btn-flat"><span>home</span></a></li>
      </ul>
    </nav>
  </footer>
</body>


</html>