<?php 

/*----------------------------------
   extra_helper.php
------------------------------------*/

//print'正常に読み込まれています。';

function h($word){
	return htmlspecialchars($word, ENT_QUOTES, 'UTF-8');
}

function sanitize($before){
	foreach($before as $key => $value){
        $value = trim($value);
		$after[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	}
	return $after;
}

//前後の空白を除去
function get_post($key){
	if(isset($_POST[$key])){
		$var = trim($_POST[$key]);
		return $var;
	}
}

//文字の長さをチェックする
function check_words($word, $length){

	if(mb_strlen($word) === 0){
		return FALSE;
	}elseif(mb_strlen($word) > $length){
		return FALSE;
	}else{
		return TRUE;
	}
}

//数字入力のチェックを行い、半角にする
function check_int($int, $max) {

    $int = mb_convert_kana($int, 'n');

    if(preg_match('/\A[-+]?[0-9]+\z/', $int) === 0)
    {
        return false;
    }
    elseif(mb_strlen($int) > $max)
    {
        return false;
    }
    else
    {
        return $int;
    }

}

//パスワードの長さをチェックする
function check_pass($word, $min, $max){

    if(mb_strlen($word) > $max || mb_strlen($word) < $min){
        return false;
    }else{
        return true;
    }
}

//年月日のプルダウンメニュー

function pulldown_year(){

	print'<select name="year">'."\n";
	print'<option value="2017">2017</option>'."\n";
	print'<option value="2018">2018</option>'."\n";
	print'<option value="2019">2019</option>'."\n";
	print'<option value="2020">2020</option>'."\n";
	print'</select>'."\n";

}

function pulldown_month(){

    print'<select name="month">'."\n";
    print'<option value="01">01</option>'."\n";
    print'<option value="02">02</option>'."\n";
    print'<option value="03">03</option>'."\n";
    print'<option value="04">04</option>'."\n";
    print'<option value="05">05</option>'."\n";
    print'<option value="06">06</option>'."\n";
    print'<option value="07">07</option>'."\n";
    print'<option value="08">08</option>'."\n";
    print'<option value="09">09</option>'."\n";
    print'<option value="10">10</option>'."\n";
    print'<option value="11">11</option>'."\n";
    print'<option value="12">12</option>'."\n";
    print'</select>'."\n";

}

function pulldown_day(){

    print'<select name="day">'."\n";
    print'<option value="01">01</option>'."\n";
    print'<option value="02">02</option>'."\n";
    print'<option value="03">03</option>'."\n";
    print'<option value="04">04</option>'."\n";
    print'<option value="05">05</option>'."\n";
    print'<option value="06">06</option>'."\n";
    print'<option value="07">07</option>'."\n";
    print'<option value="08">08</option>'."\n";
    print'<option value="09">09</option>'."\n";
    print'<option value="10">10</option>'."\n";
    print'<option value="11">11</option>'."\n";
    print'<option value="12">12</option>'."\n";
    print'<option value="13">13</option>'."\n";
    print'<option value="14">14</option>'."\n";
    print'<option value="15">15</option>'."\n";
    print'<option value="16">16</option>'."\n";
    print'<option value="17">17</option>'."\n";
    print'<option value="18">18</option>'."\n";
    print'<option value="19">19</option>'."\n";
    print'<option value="20">20</option>'."\n";
    print'<option value="21">21</option>'."\n";
    print'<option value="22">22</option>'."\n";
    print'<option value="23">23</option>'."\n";
    print'<option value="24">24</option>'."\n";
    print'<option value="25">25</option>'."\n";
    print'<option value="26">26</option>'."\n";
    print'<option value="27">27</option>'."\n";
    print'<option value="28">28</option>'."\n";
    print'<option value="29">29</option>'."\n";
    print'<option value="30">30</option>'."\n";
    print'<option value="31">31</option>'."\n";
    print'</select>'."\n";

}

