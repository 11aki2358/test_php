<?php
$name = '';
$name2 = '';
$isDarvish = false;
$isDarvish2 = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $name = $_POST['name'];
  $name2 = $_POST['name2'];
  $isDarvish = false;
  if ($name === 'ダルビッシュ') {
    $isDarvish = true;
  }
  if ($name2 === 'ダルビッシ') {
    $isDarvish2 = true;
  }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>test</title>
</head>

<body>
  <form action="" method="POST">
    <input type="text" name="name" placeholder="ダルビッシュと入力してください"
      value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="text" name="name2" placeholder="ダルビッシと入力してください"
      value="<?php echo htmlspecialchars($name2, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="submit" value="submit">
    <?php
    if ($isDarvish) {
      echo "私はダルビッシュです";
    } else {
      echo "私はダルビッシュではありません";
    }
    if ($isDarvish2) {
      echo "私はダルビッシです";
    } else {
      echo "私はダルビッシではありません";
    }
    ?>
  </form>
</body>

</html>