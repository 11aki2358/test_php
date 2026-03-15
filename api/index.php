<?php

$now_page = 0;
$pages_all;
$tag = "";

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
  <title>Ryko: Ryko: Ryko</title>
  <link rel="stylesheet" href="/css/style.css">
</head>

<body>
  <header>
    <nav>
      <div class="header-logo-menu">
        <div class="logo-area">
          <h1><a>Ryko: Ryko: Ryko</a></h1>
        </div>
      </div>
      </div>
    </nav>
  </header>

  <main>



    <article>

      <div id="index-warn">
        <div>
          <div>
            Ryko: Rykoのブログです。
          </div>
          <div>
            前置きなく、犯罪/暴力/薬物乱用など反社会的な話題に触れることがあります。<br>
            また、同性愛の話題を扱っています。
          </div>
          <div></div>
          <div>
            当サイトのコンテンツの無断使用・無断転載・生成AIによる学習はご遠慮ください。
          </div>
        </div>

        <div>
          <div class="not-enter-button">
            <a href="https://ryko-ryko.vercel.app/index.html">メインのサイトに戻る</a>
          </div>
        </div>

        <div>
          <?php
          echo ("<span>");
          echo ("<form action=\"main.php\"   method=\"post\">");
          echo ("<input type=\"hidden\" name=\"test\" value=\"xxx\" />");
          echo ("<input type=\"hidden\" name=\"LoadMore\" value=\"0\" />");
          echo ("<label for=\"created\" class=\"enter-button\">Enter</label>");
          echo ("<input class=\"hidden-button\" id=\"created\"  type=\"submit\" value=\"created\">");
          echo ("</form>");
          echo ("</span>");
          ?>
          </div>

      </div>
    </article>

  </main>
  <!-- フッター -->

  <footer>
    <a href="https://ryko-ryko.vercel.app/index.html" target="_blank" class="banner-link"><img
        src="/images/banner.png"></a>
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