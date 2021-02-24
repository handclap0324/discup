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
    print'<a href="enter.php">入力画面へ戻る</a><br>';
    print'<a href="../index.php">トップへ戻る</a>';
    exit();
}

$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
$week = $_POST['week'];

$parlor = $_POST['parlor'];

$start_total = $_POST['start_total'];
$start_big = $_POST['start_big'];
$start_reg = $_POST['start_reg'];

$end_total = $_POST['end_total'];
$end_big = $_POST['end_big'];
$end_reg = $_POST['end_reg'];


$money = $_POST['money'];
$rentcoin = $_POST['rentcoin'];
$getcoin = $_POST['getcoin'];
$getmoney = $_POST['getmoney'];


//--------------------------------
//  バリデーション
//--------------------------------

$errflg = 1;
$err =[];

if(empty($year) || empty($month) || empty($day) || empty($week) || empty($parlor) || empty($end_total) || empty($money) || empty($rentcoin))
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

if(empty($start_total))
{
  $start_total = 0;
}
elseif(check_int($start_total, 5) === false)
{
  $err[] = '開始時の回転数の入力が正しくありません。';
}
else
{
  $start_total = (int)check_int($start_total, 5);
}

if(empty($start_big))
{
  $start_big = 0;
}
elseif(check_int($start_big, 2) === false)
{
  $err[] = '開始時のbig回数の入力が正しくありません。';
}
else
{
  $start_big = (int)check_int($start_big, 2);
}

if(empty($start_reg))
{
  $start_reg = 0;
}
elseif(check_int($start_reg, 2) === false)
{
  $err[] = '開始時のreg回数の入力が正しくありません。';
}
else
{
  $start_reg = (int)check_int($start_reg, 2);
}

if(check_int($end_total, 5) === false)
{
  $err[] = '終了時の回転数の入力が正しくありません。';
}
else
{
  $end_total = (int)check_int($end_total, 5);
}

if(empty($end_big))
{
  $end_big = 0;
}
elseif(check_int($end_big, 2) === false)
{
  $err[] = '終了時のBIGの入力が正しくありません。';
}
else
{
  $end_big = (int)check_int($end_big, 2);
}

if(empty($end_reg))
{
  $end_reg = 0;
}
elseif(check_int($end_reg, 2) === false)
{
  $err[] = '終了時のREGの入力が正しくありません。';
}
else
{
  $end_reg = (int)check_int($end_reg, 2);
}


if(check_int($money, 7) === false)
{
  $err[] = '投資金額の入力が正しくありません。';
}
else
{
  $money = (int)check_int($money, 7);
}

if(check_int($rentcoin, 2) === false)
{
  $err[] = '貸コインの入力が正しくありません。';
}
else
{
  $rentcoin= (int)check_int($rentcoin, 2);
}

if(empty($getcoin))
{
  $getcoin = 0;
}
elseif(check_int($getcoin, 6) === false)
{
  $err[] = '獲得枚数の入力が正しくありません。';
}
else
{
  $getcoin = (int)check_int($getcoin, 6);
}

if(empty($getmoney))
{
  $getmoney = 0;
}
elseif(check_int($getmoney, 7) === false)
{
  $err[] = '換金額の入力が正しくありません。';
}
else
{
  $getmoney = (int)check_int($getmoney, 7);
}


if(check_words($parlor, 20) === false)
{
  $err[] = '実践店の入力が正しくありません。';
}

if(!empty($end_total) && !empty($end_big) && !empty($end_reg))
{
    if(($end_total - $start_total) < 0 || ($end_big - $start_big) < 0 || ($end_reg - $start_reg) < 0 )
    {
        $err[] = '開始時または、終了時の入力が正しくありません。';
    }
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

  //------------------------------
  // 各種演算
  //------------------------------

  //総回転数
  $total = $end_total - $start_total;

  //実戦BIG回数
  $big = $end_big - $start_big;

  //実戦REG回数
  $reg = $end_reg - $start_reg; 

  //差枚数 = 獲得枚数 - 総借枚数
  $coin = $getcoin - $rentcoin*($money/1000);

  //収支 = 換金額 - 投資金額
  $income = $getmoney - $money;

  //big確率
  if($big == 0)
  {
      $bigrate = 'ー';
  }
  else
  {
      $bigrate = $total/$big;
      $bigrate = round($bigrate, 1);
  }

  //reg確率
  if($reg == 0)
  {
      $regrate = 'ー';
  }
  else
  {
      $regrate = $total/$reg;
      $regrate = round($regrate, 1);
  }

  //addingup合算確率
  if(($big + $reg) == 0)
  {
      $addingup = 'ー';
  }
  else
  {
      $addingup = $total/($big + $reg);
      $addingup = round($addingup, 1);
  }

}


}//if($_SERVER['REQUEST_METHOD'] == 'POST')
else
{
  print 'ページ遷移が正しくありません。<br>';
  print '<a href="enter.php">データ入力画面へ</a>';
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
	<title>データ入力確認画面</title>
	<meta name="description" content="" />
	<link rel="stylesheet" href="../css/normalize.css" />
	<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php print $header; ?>
    <div class="container">
	    <h2>データ入力内容の確認</h2>

<?php if($errflg === 1) { ; ?>
        <?php for($i = 0; $i < count($err); $i++) {  /* $errの数だけループさせて表示*/ ?>
         <p class="warning"><?php print h($err[$i]); ?></p>
        <?php } ?>
        <form>
            <input type="button" onclick="history.back()"  value="戻る">
        </form>
<?php } ?> 

<?php if($errflg === 0) { ; ?>

        <form method="post" action="enter_do.php">
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
          <div class="table">
                <table>
                  <tr>
                    <th>総回転数</th>
                    <th>BIG確率</th>
                    <th>REG確率</th>
                    <th>合算確率</th>
                  </tr>
                  <tr>
                    <td><?php print $total; ?> 回転</td>
                    <td><?php print $bigrate; ?> 分の1</td>
                    <td><?php print $regrate; ?> 分の1</td>
                    <td><?php print $addingup; ?> 分の1</td>
                  </tr>
                </table>
          </div>
          <input type="hidden" name="token" value="<?php print $token; ?>">
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