<?php
require_once __DIR__.'/../helpers/config.php';
require_once __DIR__.'/../helpers/db_helper.php';
require_once __DIR__.'/../helpers/extra_helper.php';
require_once __DIR__.'/../helpers/layout/header.php';
require_once __DIR__.'/../helpers/layout/footer.php';

session_start();
if(!isset($_SESSION['token']) || !isset($_GET['token']) || ($_SESSION['token'] != $_GET['token']))
{
    print'<p>ページ遷移が正しくありません(tokenエラー)。</p>';
    print'<a href="../index.php">トップへ戻る</a>';
    exit();
}

if(isset($_GET['id']))
{
    //Token を設定
    $bytes = openssl_random_pseudo_bytes(16);
    $token = bin2hex($bytes);
    $_SESSION['token'] = $token;

    //$_POST = sanitize($_POST);
    $_GET = sanitize($_GET);
    $id = $_GET['id'];

    $dbh = get_db_connect();

    $sql = 'SELECT * FROM combat_data WHERE id= :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $dbh = null;

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($rec);
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
	<h3>データ削除の確認</h3>
    <div class="table">
        <table>
            <tr>
        		<th>実戦日</th>
        		<th>曜日</th>
        		<th>実戦店</th>
        		<th>回転数</th>
        		<th>BIG回数</th>
        		<th>REG回数</th>
        		<th>投資金額</th>
        		<th>差枚数</th>
        		<th>収支</th>
        	</tr>
            <tr>
        		<td><?php print h($rec['combatdate']);?></td>
        		<td><?php print h($rec['week']);?></td>
        		<td><?php print h($rec['parlor']);?></td>
        		<td><?php print h($rec['total']);?></td>
        		<td><?php print h($rec['big']);?></td>
        		<td><?php print h($rec['reg']);?></td>
        		<td><?php print h($rec['money']);?></td>
        		<td><?php print h($rec['coin']);?></td>
        		<td><?php print h($rec['income']);?></td>
        	</tr>
        </table>
    </div>

    <P style="margin-top: 10px;">●上記データを削除してよろしいですか？</P>

    <form method="post" action="delete_do.php">
        <input type="hidden" name="token" value="<?php print $token; ?>" >
        <input type="hidden" name="id" value="<?php print h($rec['id']); ?>" >
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="削除">
    </form>

</div>
<?php print $footer; ?>
</body>
</html>