<?php 

/*----------------------------------
   config.php
------------------------------------*/

define('DSN', 'mysql:dbname=discup;host=localhost;charset=utf8');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('SITE_URL', 'http://localhost/diskup/');

error_reporting(E_ALL & ~E_NOTICE); //E_NOTICE以外の全てのエラーを出力する　公開時にはerror_reporting(0)とする
session_set_cookie_params(1440, '/');
