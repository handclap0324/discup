<?php 
/*----------------------------------
   db_helper.php
------------------------------------*/
date_default_timezone_set('Asia/Tokyo');
//print 'ファイルの読み込みに成功しました！';

//データベース接続
function get_db_connect(){

	try{
		$dbn = DSN;
		$user = DB_USER;
		$password = DB_PASSWORD;

		$dbh = new PDO($dbn, $user, $password);
		
	}catch(PDOException $e){
        print ($e->getMessage());
        print '<p>'.'データベース接続に失敗しました。'.'</p>';
        exit();

	}
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}
//メールアドレスの重複を調べる関数
function email_exists($dbh, $email){

	$sql = 'SELECT COUNT(id) FROM members where email = :email';
	      //COUNT関数はSELECT文により選択されたレコードの件数を返します． 
	$stmt = $dbh->prepare($sql);
	$stmt->bindValue(':email', $email, PDO::PARAM_STR);
	$stmt->execute();

	$count = $stmt->fetch(PDO::FETCH_ASSOC);

	if($count['COUNT(id)'] > 0){
		return TRUE;
	}else{
		return FALSE;
	}
}




//入力データを挿入する関数
function insert_member_data($dbh, $name, $email, $password){

	$password = password_hash($password, PASSWORD_DEFAULT);
	$date = date('Y-m-d H:i:s');
	$sql = "INSERT INTO members (name, email, password, created) 
	VALUES (:name, :email, :password, '{$date}')";
	$stmt = $dbh->prepare($sql);
	$stmt->bindValue(':name', $name, PDO::PARAM_STR);
	$stmt->bindValue(':email', $email, PDO::PARAM_STR);
	$stmt->bindValue(':password', $password, PDO::PARAM_STR);
    
	if($stmt->execute()){
		return TRUE;
	}else{
		return FALSE;
	}
}
//メールアドレスとパスワードが一致するか調べる関数
function select_member($dbh, $email, $password){

	$sql = 'SELECT * FROM members WHERE email = :email LIMIT 1';
	$stmt = $dbh->prepare($sql);
	$stmt->bindValue(':email', $email, PDO::PARAM_STR);
	$stmt->execute();

	if($stmt->rowCount() > 0){
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		if(password_verify($password, $data['password'])){
			return $data;
		}else{
			return FALSE;
		}
		return FALSE;
	}
}
//全会員データを取得する関数
function select_members($dbh){
	$sql = 'SELECT name FROM members';//全会員のデータを取得
	$stmt = $dbh->prepare($sql);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$data[] = $row; //二次元配列として格納
	}
	return $data;
}