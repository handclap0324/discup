<?php
require_once __DIR__.'/../helpers/extra_helper.php';
require_once __DIR__.'/../helpers/layout/header.php';
require_once __DIR__.'/../helpers/layout/footer.php';

session_start();
if(!isset($_SESSION['token']) || !isset($_POST['token']) || ($_SESSION['token'] != $_POST['token']))
{
    print'<p>ページ遷移が正しくありません(tokenエラー)。</p>';
    print'<a href="../index.php">トップへ戻る</a>';
    exit();
}

if(isset($_POST['edit']) === true)
{
    $id = $_POST['id'];
    $token = $_POST['token'];

    header('Location:edit.php?id='.$id.'&token='.$token);
    exit();
}

if(isset($_POST['delete']) === true)
{
	$id = $_POST['id'];
	$token = $_POST['token'];
    header('Location:delete.php?id='.$id.'&token='.$token);
    exit();
}