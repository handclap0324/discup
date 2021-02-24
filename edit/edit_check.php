<?php
require_once __DIR__.'/../helpers/extra_helper.php';
require_once __DIR__.'/../helpers/layout/header.php';
require_once __DIR__.'/../helpers/layout/footer.php';

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

$_POST = sanitize($_POST);

if(!isset($_SESSION['token']) || !isset($_POST['token']) || ($_SESSION['token'] != $_POST['token']))
{
    print'<p>ページ遷移が正しくありません。</p>';
    print'<a href="../index.php">トップへ戻る</a>';
    exit();
}

$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
$week = $_POST['week'];

$parlor = $_POST['parlor'];

$total = $_POST['total'];
$big = $_POST['big'];
$reg = $_POST['reg'];

$money = $_POST['money'];
$coin = $_POST['coin'];
$income = $_POST['income'];

$id = $_POST['id'];


//--------------------------------
//  バリデーション
//--------------------------------

$errflg = 1;
$err =[];

if(empty($year) || empty($month) || empty($day) || empty($week) || empty($parlor) || empty($total) || empty($money))
{
  $err[] = '入力されていない項目があります';
}

if(check_int($year, 4) === false)
{
  $err[] = '年の入力が正しくありません。';
}
else
{
  $year = check_int($year, 4);
}

if(check_int($month, 2) === false)
{
  $err[] = '月の入力が正しくありません。';
}
else
{
  $month = check_int($month, 2);
}

if(check_int($day, 2) === false)
{
  $err[] = '日の入力が正しくありません。';
}
else
{
  $day = check_int($day, 2);
}

if(check_words($week, 3) === false)
{
  $err[] = '曜日の入力が正しくありません。';
}

if(check_int($total, 5) === false)
{
  $err[] = '回転数の入力が正しくありません。';
}
else
{
  $total = (int)check_int($total, 5);
}

if(empty($big))
{
  $big = 0;
}
elseif(check_int($big, 2) === false)
{
  $err[] = 'big回数の入力が正しくありません。';
}
else
{
  $big = (int)check_int($big, 2);
}

if(empty($reg))
{
  $reg = 0;
}
elseif(check_int($reg, 2) === false)
{
  $err[] = '開始時のreg回数の入力が正しくありません。';
}
else
{
  $reg = (int)check_int($reg, 2);
}

if(check_int($money, 7) === false)
{
  $err[] = '投資金額の入力が正しくありません。';
}
else
{
  $money = (int)check_int($money, 7);
}

if(empty($coin))
{
  $coin = 0;
}
elseif(check_int($coin, 6) === false)
{
  $err[] = '獲得枚数の入力が正しくありません。';
}
else
{
  $coin = (int)check_int($coin, 6);
}

if(empty($income))
{
  $income = 0;
}
elseif(check_int($income, 7) === false)
{
  $err[] = '収支の入力が正しくありません。';
}
else
{
  $income = (int)check_int($income, 7);
}


if(check_words($parlor, 20) === false)
{
  $err[] = '実践店の入力が正しくありません。';
}


//------------------------------------
// ↑ バリデーション
//-------------------------------------



if(empty($err)) //エラーが無ければ以下を実行
{

  $errflg = 0;
  
  //Token を設定
  $bytes = openssl_random_pseudo_bytes(16);
  $token = bin2hex($bytes);
  $_SESSION['token'] = $token;

}


}//if($_SERVER['REQUEST_METHOD'] == 'POST')
else
{
  print 'ページ遷移が正しくありません。<br>';
  print '<a href="../index.php">トップへ戻る</a>';
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
	<title>編集データ入力確認画面</title>
	<meta name="description" content="" />
	<link rel="stylesheet" href="../css/normalize.css" />
	<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php print $header; ?>
    <div class="container">
	    <h2>編集データ入力内容の確認</h2>

<?php if($errflg === 1) { ; ?>
        <?php for($i = 0; $i < count($err); $i++) {  /* $errの数だけループさせて表示*/ ?>
         <p class="warning"><?php print h($err[$i]); ?></p>
        <?php } ?>
        <form>
            <input type="button" onclick="history.back()"  value="戻る">
        </form>
<?php } ?> 

<?php if($errflg === 0) { ; ?>

        <form method="post" action="edit_do.php">
        	<div class="table">
        	    <table>
        	    	<tr>
        	    		<th>実戦日</th>
        	    		<td><?php print $year; ?> 年</td>
        	    		<td><?php print $month; ?> 月</td>
        	    		<td><?php print $day; ?> 日</td>
        	    		<td><?php print $week; ?></td>
        	    	</tr>
        	    </table>
            </div>

                <table>
                	<tr>
                      <th>実戦店</th>
                    	<td><?php print $parlor; ?></td>
                    </tr>
        	    </table>

        	<div class="table">
                <table>
                	<tr>
                		<th>総回転数</th>
                		<th>BIG回数</th>
                		<th>REG回数</th>
                		<th>投資金額</th>
                		<th>差枚数</th>
                		<th>収支</th>
                	</tr>
                	<tr>
                		<td><?php print $total; ?> 回転</td>
                		<td><?php print $big; ?> 回</td>
                		<td><?php print $reg; ?> 回</td>
                		<td><?php print number_format($money); ?> 円</td>
                		<td><?php print number_format($coin); ?> 枚</td>
                		<td><?php print number_format($income); ?> 円</td>
                	</tr>
                </table>
          </div>

          <input type="hidden" name="token" value="<?php print $token; ?>">
          <input type="hidden" name="id" value="<?php print $id ;?>" >
          <input type="hidden" name="year" value="<?php print $year ;?>" >
          <input type="hidden" name="month" value="<?php print $month;?>" >
          <input type="hidden" name="day" value="<?php print $day;?>" >
          <input type="hidden" name="week" value="<?php print $week;?>" >
          <input type="hidden" name="parlor" value="<?php print $parlor;?>" >
          <input type="hidden" name="total" value="<?php print $total;?>" >
          <input type="hidden" name="big" value="<?php print $big;?>" >
          <input type="hidden" name="reg" value="<?php print $reg;?>" >
          <input type="hidden" name="money" value="<?php print $money;?>" >
          <input type="hidden" name="coin" value="<?php print $coin;?>" >
          <input type="hidden" name="income" value="<?php print $income;?>" >
          <input class="typeA" type="submit" value="データ登録"><br>
          <input type="button" onclick="history.back()"  value="戻る">
        </form>
<?php } ?>   

    </div>
<?php print $footer; ?>
</body>
</html>