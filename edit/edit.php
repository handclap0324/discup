<?php
require_once __DIR__.'/../helpers/config.php';
require_once __DIR__.'/../helpers/db_helper.php';
require_once __DIR__.'/../helpers/extra_helper.php';
require_once __DIR__.'/../helpers/layout/header.php';
require_once __DIR__.'/../helpers/layout/footer.php';

session_start();

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


//年月日の分割

//方法1 関数substrで切り出し分割
/*
$date = $rec['combatdate']; //ex. 2019-11-04

$year = substr($date, 0, 4);
$month = substr($date, 5, 2);
$day = substr($date, 8, 2);

*/

//方法2  Date time クラスを使う(こちらの方がスマート)
$date = new DateTime($rec['combatdate']);

$year = $date->format('Y');
$month = $date->format('m');
$day = $date->format('d');

/*
print $year.'<br>';
print $month.'<br>';
print $day.'<br>';
*/

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
	<title></title>
	<meta name="description" content="" />
	<link rel="stylesheet" href="../css/normalize.css" />
	<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php print $header; ?>
    <div class="container">
    	<form method="post" action="edit_check.php">
    		<div class="table">
                <table>
                    <tr>
                		<th>年</th>
                		<th>月</th>
                		<th>日</th>
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
                        <input type="hidden" name="token" value="<?php print $token; ?>">
                		<td><input type="text" name="year" value="<?php print h($year); ?>" style="width: 50px;"></td>
                		<td><input type="text" name="month" value="<?php print h($month); ?>" style="width: 30px;"></td>
                		<td><input type="text" name="day" value="<?php print h($day); ?>" style="width: 30px;"></td>
                		<td><input type="text" name="week" value="<?php print h($rec['week']); ?>" style="width: 70px;"></td>
                		<td><input type="text" name="parlor" value="<?php print h($rec['parlor']); ?>" style="width: 150px;"></td>
                		<td><input type="text" name="total" value="<?php print h($rec['total']); ?>" style="width: 60px;"></td>
                		<td><input type="text" name="big" value="<?php print h($rec['big']); ?>" style="width: 30px;"></td>
                		<td><input type="text" name="reg" value="<?php print h($rec['reg']); ?>" style="width: 30px;"></td>
                		<td><input type="text" name="money" value="<?php print h($rec['money']); ?>" style="width: 70px;"></td>
                		<td><input type="text" name="coin" value="<?php print h($rec['coin']); ?>" style="width: 70px;"></td>
                		<td><input type="text" name="income" value="<?php print h($rec['income']); ?>" style="width: 70px;"></td>
                	</tr>
                </table>
    		</div>

        <P style="margin-top: 10px;">●上記デの内容でよろしいですか？</P>
        <input type="hidden" name="id" value="<?php print h($rec['id']); ?>" >
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="登録確認">
    	</form>
    </div>

<?php print $footer; ?>
</body>
</html>