<?php
require_once __DIR__.'/../helpers/config.php';
require_once __DIR__.'/../helpers/db_helper.php';
require_once __DIR__.'/../helpers/extra_helper.php';
require_once __DIR__.'/../helpers/layout/header.php';
require_once __DIR__.'/../helpers/layout/footer.php';

session_start();
if(!isset($_SESSION['token']) || !isset($_POST['token']) || ($_SESSION['token'] != $_POST['token']))
{
    print'<p>ページ遷移が正しくありません(tokenエラー)。</p>';
    print'<a href="../index.php">トップへ戻る</a>';
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{

    $_POST = sanitize($_POST);
    $id = $_POST['id'];

    $dbh = get_db_connect();

    $sql = 'DELETE FROM combat_data WHERE id= :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $dbh = null;

    session_destroy();
}
else
{
  print 'ページ遷移が正しくありません。<br>';
  print '<a href="../index.php">トップ画面へ</a>';
  exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
	<title>データの削除確認</title>
	<meta name="description" content="" />
	<link rel="stylesheet" href="../css/normalize.css" />
	<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php print $header; ?>
<div class="container">
	<h3>データ削除</h3>


    <P style="margin-top: 10px;">●データの削除が完了しました。</P>

    <a class="buttonNormal" href="../index.php">トップへ戻る</a>

</div>
<?php print $footer; ?>
</body>
</html>