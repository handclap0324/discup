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

$dbh = get_db_connect();

//これまでの回転数と収支の値取得（グラフ化目的）

$sql ='SELECT total, income FROM combat_data WHERE 1';
$graphxy = $dbh->prepare($sql);
$graphxy->execute();

//回転数取得の変数と配列の初期化
$ruiseki_total = 0;
$ruiseki_total_array = [];  //グラフのx軸

//収支の変数と配列の初期化
$ruiseki_income = 0;
$ruiseki_income_array = [];  //グラフのy軸

while($xy = $graphxy->fetch(PDO::FETCH_ASSOC))
{
  $data[] = $xy;

  $ruiseki_total += $xy['total'];
  $ruiseki_total_array [] = $ruiseki_total;

  $ruiseki_income += $xy['income'];
  $ruiseki_income_array [] = $ruiseki_income;

};

//回転数
$rta = implode(',', $ruiseki_total_array);
//収支
$ria = implode(',', $ruiseki_income_array);


/*
print '<pre>';
print_r ($ruiseki_income_array);
print '</pre>';
*/


?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
	<title>グラフ表示|ディスクアップ実戦データの墓場</title>
	<meta name="description" content="" />
	<link rel="stylesheet" href="../css/normalize.css" />
	<link rel="stylesheet" href="../css/style.css?ver='<?php print date('YmdHis');?>'" />
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://unpkg.com/apexcharts/dist/apexcharts.min.js"></script>

</head>
<body>
<?php print $header; ?>

<div class="container">
    <h2>ディスクアップ実戦データグラフ</h2>


    <div id="chart"></div>
    <a class="buttonNormal" href="../index.php">トップへ戻る</a>

   

</div>

<?php print $footer; ?>


    <script>
        var options = {
          title: {
              text: 'ディスクアップ収支グラフ',
              align: 'center',
              margin: 10,
              offsetX: 0,
              offsetY: 0,
              floating: false,
              style: {
                fontSize:  '16px',
                color:  '#263238'
              },
          },
          chart: {
            type: 'line'
          },
          series: [{
            name: 'sales',
            data: [<?php print h($ria); ?>]
          }],
          xaxis: {  //x軸のカスタマイズ
            categories: [<?php print h($rta); ?>],
            title: {
                text: '総回転数'+'<?php print h(number_format($ruiseki_total)); ?>',
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: '#333333',
                    fontSize: '14px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    cssClass: 'apexcharts-xaxis-title',
                },
            },
            labels: {
              show: false, //x軸のラベルは表示しない（数が多くなると密になるため）
            }
          },
          yaxis: {  //y軸のカスタマイズ

           title: {
               text: '収支（円）',
               rotate: 270,
               offsetX: 0,
               offsetY: 0,
               style: {
                   color: '#333333',
                   fontSize: '14px',
                   fontFamily: 'Helvetica, Arial, sans-serif',
                   cssClass: 'apexcharts-yaxis-title',
                }
            },
            labels: {
              formatter: function(val){
                return val.toLocaleString();
              },
            }
          } //yaxis
        } //var options=

        var chart = new ApexCharts(document.querySelector("#chart"), options);        

        chart.render();
    </script>
</body>
</html>