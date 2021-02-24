<?php
require_once __DIR__.'/helpers/config.php';
require_once __DIR__.'/helpers/db_helper.php';
require_once __DIR__.'/helpers/extra_helper.php';
require_once __DIR__.'/helpers/layout/header.php';
require_once __DIR__.'/helpers/layout/footer.php';

session_start();
//Token を設定
$bytes = openssl_random_pseudo_bytes(16);
$token = bin2hex($bytes);
$_SESSION['token'] = $token;

$dbh = get_db_connect();

//これまでの全戦績の集計値取得

$sql ='SELECT COUNT(id), SUM(total), SUM(big), SUM(reg), SUM(coin), SUM(income) FROM combat_data WHERE 1';
$stmtall = $dbh->prepare($sql);
$stmtall->execute();

$sum = $stmtall->fetch(PDO::FETCH_ASSOC);

$sum_total = $sum['SUM(total)'];
$sum_big = $sum['SUM(big)'];
$sum_reg = $sum['SUM(reg)'];
$sum_coin = $sum['SUM(coin)'];
$sum_income = $sum['SUM(income)'];

$sum_battle = $sum['COUNT(id)'];

//これまでの全戦績の勝利数取得
$sql ='SELECT COUNT(coin) FROM combat_data WHERE coin > 0';
$stmtwin = $dbh->prepare($sql);
$stmtwin->execute();

$winrec = $stmtwin->fetch(PDO::FETCH_ASSOC);
$win = $winrec['COUNT(coin)'];
$lose = $sum_battle - $win;


//直近のデータ取得
$record_counts = 3;

if(isset($_POST['input_days']))
{
	$_POST = sanitize($_POST);
	//$_POST['input_days']のバリデーションは省略
	$record_counts = $_POST['input_days'];
}

$sql = 'SELECT * FROM combat_data ORDER BY combatdate DESC LIMIT :record_counts';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':record_counts', $record_counts, PDO::PARAM_INT);
$stmt->execute();

$dbh = null;
$data = [];

//データ集計
$amount_total = 0;
$amount_big = 0;
$amount_reg = 0;
$amount_coin = 0;
$amount_income = 0;

while($rec = $stmt->fetch(PDO::FETCH_ASSOC))
{
  $data [] = $rec;
  $reverse_data = array_reverse($data); //取得した配列を逆にして表示させる為の変数

  $amount_total += $rec['total'];
  $amount_big += $rec['big'];
  $amount_reg += $rec['reg'];
  $amount_coin += $rec['coin'];
  $amount_income += $rec['income'];

}

/*
print '<pre>';
print_r($reverse_data);
print '</pre>';
*/

//確率の演算
if($amount_big == 0)
{
	$amount_big_rate = '―';
}else
{
	$amount_big_rate = $amount_total/$amount_big;
	$amount_big_rate = round($amount_big_rate, 0);
}

if($amount_reg == 0)
{
	$amount_reg_rate = '―';
}else
{
	$amount_reg_rate = $amount_total/$amount_reg;
	$amount_reg_rate = round($amount_reg_rate, 0);
}

if($sum_big == 0)
{
	$sum_big_rate = '―';
}else
{
	$sum_big_rate = $sum_total/$sum_big;
	$sum_big_rate = round($sum_big_rate, 0);
}

if($sum_reg == 0)
{
	$sum_reg_rate = '―';
}else
{
	$sum_reg_rate = $sum_total/$sum_reg;
	$sum_reg_rate = round($sum_reg_rate, 0);
}

//payoutの計算
$payout_sum = 100*(($sum_total*3 + $sum_coin)/($sum_total*3));
$payout_sum = round($payout_sum, 1);

$payout = 100*(($amount_total*3 + $amount_coin)/($amount_total*3));
$payout = round($payout, 1);

//--------------------
//設定1との比較計算
//--------------------

//設定1確率分母
$set1_big_denom = 288.7;
$set1_reg_denom = 496.5;

//設定1通りにひけていたら・・・・・・
$set1_big_sum = round(($sum_total/$set1_big_denom), 1);
$set1_reg_sum = round(($sum_total/$set1_reg_denom), 1);
$set1_coin_sum = round(($sum_total*3)*0.03, 0);

$set1_big = round(($amount_total/$set1_big_denom), 1);
$set1_reg = round(($amount_total/$set1_reg_denom), 1);
$set1_coin = round(($amount_total*3)*0.03, 0);


//select部分の関数
function selected_days($days)
{
    global $record_counts;

	if(isset($record_counts))
	{
		if($days == $record_counts)
		{
			print 'selected';
		}
		print '';
	}
	else
	{
		print '';
	}
}

// + -　を表示するための関数

function plusnumber($int)
{
	if($int > 0)
	{
		print '+'.number_format($int);
	}
	else
	{
		print number_format($int);
	}
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
	<title>ディスクアップ実戦データの墓場</title>
	<meta name="description" content="" />
	<link rel="stylesheet" href="css/normalize.css" />
	<link rel="stylesheet" href="css/style.css?ver='<?php print date('YmdHis');?>'" />
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
</head>
<body>
<?php print $header_home; ?>

<div class="container">
    <h2>ディスクアップ実戦データの墓場</h2>
    <h3>これまでの戦績：全<?php print $sum_battle; ?>戦：<?php print $win; ?>勝　<?php print $lose; ?>敗</h3>

    <div class="table senseki">
        <table>
        	<tr><th></th><th>実戦値</th><th>設定1理論値</th></tr>
            <tr><th>総回転数</th><td><?php print $sum_total; ?></td><td><?php print $sum_total; ?></td></tr>
            <tr><th>BIG回数</th><td><?php print $sum_big; ?> 回<br><small>(1/<?php print $sum_big_rate; ?>)</small></td>
            	<td><?php print $set1_big_sum; ?> 回</td>
            </tr>
        	<tr><th>REG回数</th><td><?php print $sum_reg; ?> 回<br><small>(1/<?php print $sum_reg_rate; ?>)</small></td>
        		<td><?php print $set1_reg_sum; ?> 回</td>
        	</tr>
        	<tr><th>差枚数</th><td><?php plusnumber($sum_coin); ?> 枚</td><td>+ <?php print $set1_coin_sum; ?> 枚</td></tr>
        	<tr><th>Payout</th><td><?php print $payout_sum; ?> ％</td><td>103%</td></tr>
        	<tr><th>収支</th><td><?php plusnumber($sum_income); ?> 円</td><td>ー</td></tr>
        </table>
    </div>

    <a class="buttonNormal" href="enter/enter.php">データ入力画面へ</a>
    <a class="buttonNormal" href="data/graph.php">グラフ画面へ</a>

    <form class="smooth_scroll" method="post" aciton="index.php" >
    	<select class="select" name="input_days">
    		<option value="1" <?php selected_days(1); ?> >1日</option>
    		<option value="2" <?php selected_days(2); ?> >2日</option>
    		<option value="3" <?php selected_days(3); ?> >3日</option>
    		<option value="5" <?php selected_days(5); ?> >5日</option>
    		<option value="10" <?php selected_days(10); ?> >10日</option>
    		<option value="20" <?php selected_days(20); ?> >20日</option>
    		<option value="30" <?php selected_days(30); ?> >30日</option>
    		<option value="50" <?php selected_days(50); ?> >50日</option>
    		<option value="100" <?php selected_days(100); ?> >100日</option>
    		<option value="200" <?php selected_days(200); ?> >200日</option>
    		<option value="300" <?php selected_days(300); ?> >300日</option>
    	</select>
    	分のデータを <input type="submit" value="表示する">
    </form>

    <div class="table">
        <table>
        	<caption>●直近、<?php print $record_counts; ?>日分の実戦データ</caption>
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
        		<th>編集／削除</th>
        	</tr>
<?php foreach($reverse_data as $combat): ?>
            <tr>
        		<td><?php print h($combat['combatdate']);?></td>
        		<td><?php print h($combat['week']);?></td>
        		<td><?php print h($combat['parlor']);?></td>
        		<td><?php print h($combat['total']);?></td>
        		<td><?php print h($combat['big']);?></td>
        		<td><?php print h($combat['reg']);?></td>
        		<td><?php print h($combat['money']);?></td>
        		<td><?php print h($combat['coin']);?></td>
        		<td><?php print h($combat['income']);?></td>
        		<td>
        			<form method="post" action="edit/edit_branch.php">
            			<input type="hidden" name="token" value="<?php print $token; ?>" >
            			<input type="hidden" name="id" value="<?php print h($combat['id']); ?>">
            			<input type="submit" name="edit" value="編集">
            			<input type="submit" name="delete" value="削除">
        			</form>
        		</td>
        	</tr>
<?php endforeach; ?>
        </table>
    </div>

    <div class="table">
    	<table>
    		<caption>●直近、<?php print $record_counts; ?>日分の累計データ</caption>
    		<tr>
    		    <th>回転数</th>
    		    <th>BIG回数</th>
    		    <th>REG回数</th>
    		    <th>差枚数</th>
    		    <th>Payout</th>
    		    <th>収支</th>
    	    </tr>
    	    <tr>
    	    	<td><?php print $amount_total;?></td>
    	    	<td><?php print $amount_big; ?>（1/<?php print $amount_big_rate; ?>）</td>
    	    	<td><?php print $amount_reg; ?>（1/<?php print $amount_reg_rate; ?>）</td>
    	    	<td><?php print $amount_coin; ?> 枚</td>
    	    	<td><?php print $payout; ?> ％</td>
    	    	<td><?php print number_format($amount_income); ?> 円</td>
    	    </tr>
    	</table>
    </div>

    <div class="table">
    	<table>
    		<caption>●設定1の理論値</caption>
    		<tr>
    		    <th>回転数</th>
    		    <th>BIG回数</th>
    		    <th>REG回数</th>
    		    <th>差枚数</th>
    		    <th>Payout</th>
    	    </tr>
    	    <tr>
    	    	<td><?php print $amount_total;?></td>
    	    	<td><?php print $set1_big; ?></td>
    	    	<td><?php print $set1_reg; ?></td>
    	    	<td><?php print $set1_coin; ?> 枚</td>
    	    	<td>103 ％</td>
    	    </tr>
    	</table>
    </div>



</div>

<?php print $footer_home; ?>
</body>
</html>