<?php
require_once __DIR__.'/../helpers/extra_helper.php';
require_once __DIR__.'/../helpers/layout/header.php';
require_once __DIR__.'/../helpers/layout/footer.php';

session_start();
//Token を設定
$bytes = openssl_random_pseudo_bytes(16);
$token = bin2hex($bytes);
$_SESSION['token'] = $token;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
	<title>データ入力画面</title>
	<meta name="description" content="" />
	<link rel="stylesheet" href="../css/normalize.css" />
	<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php print $header; ?>

    <div class="container">
	    <h2>ディスクアップ実践データ入力</h2>

        <form method="post" action="enter_check.php">
        	<div class="table">
        	    <table>
        	    	<tr>
        	    		<th>実戦日</th>
        	    		<td><select name="year" id="id_year"></select> 年</td>
        	    		<td><select name="month" id="id_month"></select> 月</td>
        	    		<td><select name="day" id="id_day"></select> 日</td>
        	    		<td><select name="week" id="id_week"></select></td>
        	    	</tr>
        	    </table>
            </div>

                <table>
                	<tr>
                        <th>実戦店</th>
                    	<td>
                            <select name="parlor" style="width: 170px;">
                                <option value="マルハン大久保店" selected >マルハン大久保店</option>
                                <option value="マルハン玉津店">マルハン玉津店</option>
                                <option value="マルハン神戸店">マルハン神戸店</option>
                                <option value="その他マルハン系列店">その他マルハン系列店</option>
                                <option value="ラッキー1BAN明石店">ラッキー1BAN明石店</option>
                                <option value="その他の店舗">その他の店舗</option>
                            </select>
                    	</td>
                    </tr>
        	    </table>

        	    <table>
        	    	<tr>
        	    		<th>貸コインレート(千円)</th>
                    	<td>
                            <select name="rentcoin" style="width: 50px;">
                                <option value="50" selected >50</option>
                                <option value="49" >49</option>
                                <option value="48">48</option>
                                <option value="47">47</option>
                                <option value="46">46</option>
                                <option value="45">45</option>
                            </select> 枚貸
                    	</td>
        	    	</tr>
        	    </table>

        	<div class="table">
                <table>
                	<caption>●実戦開始時データ</caption>
                	<tr>
                		<th>回転数</th>
                		<th>BIG回数</th>
                		<th>REG回数</th>
                	</tr>
                	<tr>
                		<td><input type="text" name="start_total" inputmode="numeric" value="0" style="width: 75px;"> 回転</td>
                		<td><input type="text" name="start_big" inputmode="numeric" value="0" style="width: 75px;"> 回</td>
                		<td><input type="text" name="start_reg" inputmode="numeric" value="0" style="width: 75px;"> 回</td>
                	</tr>
                </table>
            </div>

        	<div class="table">
                <table>
                	<caption>●実戦終了時時データ</caption>
                	<tr>
                		<th>回転数</th>
                		<th>BIG回数</th>
                		<th>REG回数</th>
                		<th>投資金額</th>
                		<th>獲得枚数</th>
                		<th>換金額</th>
                	</tr>
                	<tr>
                		<td><input type="text" inputmode="numeric" name="end_total" style="width: 75px;"> 回転</td>
                		<td><input type="text" inputmode="numeric" name="end_big" style="width: 75px;"> 回</td>
                		<td><input type="text" inputmode="numeric" name="end_reg" style="width: 75px;"> 回</td>
                		<td><input type="text" inputmode="numeric" name="money" style="width: 75px;"> 円</td>
                		<td><input type="text" inputmode="numeric" name="getcoin" style="width: 75px;"> 枚</td>
                		<td><input type="text" inputmode="numeric" name="getmoney" style="width: 75px;"> 円</td>
                	</tr>
                </table>
            </div>
            <input type="hidden" name="token" value="<?php print $token; ?>" >
            <input class="typeA" type="submit" value="入力確認画面へ"><br>
        </form>

    <a class="buttonNormal" href="../index.php">トップへ戻る</a>
    </div>

<?php print $footer; ?>
<script>
(function() {
  'use strict';

  /*
    今日の日付データを変数todayに格納
   */
  var optionLoop, optionLoopWeek, this_day, this_month, this_year, this_week, today;
  today = new Date();
  this_year = today.getFullYear();
  this_month = today.getMonth() + 1;
  this_day = today.getDate();
  this_week = today.getDay(); //曜日の取得

  /*
    ループ処理（スタート数字、終了数字、表示id名、デフォルト数字）
   */
  optionLoop = function(start, end, id, this_day) {
    var i, opt;

    opt = null;
    for (i = start; i <= end ; i++) {
      if (i === this_day) {
        opt += "<option value='" + i + "' selected>" + i + "</option>";
      } else {
        opt += "<option value='" + i + "'>" + i + "</option>";
      }
    }
    return document.getElementById(id).innerHTML = opt;
  };

  optionLoopWeek = function(start, end, id, this_week){
  	var i, opt;
  	var youbi = ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'];

    opt = null;
    for (i = start; i <= end ; i++) {
      if (i === this_week) {
        opt += "<option value='" + youbi[i] + "' selected>" + youbi[i] + "</option>";
      } else {
        opt += "<option value='" + youbi[i] + "'>" + youbi[i] + "</option>";
      }
    }
    return document.getElementById(id).innerHTML = opt;

  }


  /*
    関数設定（スタート数字[必須]、終了数字[必須]、表示id名[省略可能]、デフォルト数字[省略可能]）
   */
  optionLoop(1950, this_year, 'id_year', this_year);
  optionLoop(1, 12, 'id_month', this_month);
  optionLoop(1, 31, 'id_day', this_day);
  optionLoopWeek(0, 6, 'id_week', this_week);
})();

</script>
</body>
</html>