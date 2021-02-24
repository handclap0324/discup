<?php
require_once __DIR__.'/../helpers/config.php';
require_once __DIR__.'/../helpers/db_helper.php';
require_once __DIR__.'/../helpers/extra_helper.php';
require_once __DIR__.'/../helpers/layout/header.php';
require_once __DIR__.'/../helpers/layout/footer.php';

session_start();

if(!isset($_SESSION['token']) || !isset($_POST['token']) || ($_SESSION['token'] != $_POST['token']))
{
    print'<p>ページ遷移が正しくありません。</p>';
    print'<a href="enter.php">入力画面へ戻る</a><br>';
    print'<a href="../index.php">トップへ戻る</a>';
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{

$_POST = sanitize($_POST);

$id = $_POST['id'];

$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];

$combatdate = $year.'/'.$month.'/'.$day;

$week = $_POST['week'];

$parlor = $_POST['parlor'];

$total = $_POST['total'];
$big = $_POST['big'];
$reg = $_POST['reg'];
$money = $_POST['money'];
$coin = $_POST['coin'];
$income = $_POST['income'];


$dbh = get_db_connect();

$sql = 'UPDATE combat_data SET combatdate=:combatdate, week=:week, parlor=:parlor, total =:total, big=:big, reg=:reg, money=:money, coin=:coin, income=:income WHERE id=:id';

$stmt = $dbh->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':combatdate', $combatdate, PDO::PARAM_STR);
$stmt->bindValue(':week', $week, PDO::PARAM_STR);
$stmt->bindValue(':parlor', $parlor, PDO::PARAM_STR);
$stmt->bindValue(':total', $total, PDO::PARAM_INT);
$stmt->bindValue(':big', $big, PDO::PARAM_INT);
$stmt->bindValue(':reg', $reg, PDO::PARAM_INT);
$stmt->bindValue(':money', $money, PDO::PARAM_INT);
$stmt->bindValue(':coin', $coin, PDO::PARAM_INT);
$stmt->bindValue(':income', $income, PDO::PARAM_INT);
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
	<title>編集内容の登録が完了</title>
	<meta name="description" content="" />
	<link rel="stylesheet" href="../css/normalize.css" />
	<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php print $header; ?>

<div class="container">

    <p>データベースへ、編集内容の登録が完了しました。</p>
    <a href="../index.php">トップへ戻る</a>

</div>

<?php print $footer; ?>
</body>
</html>