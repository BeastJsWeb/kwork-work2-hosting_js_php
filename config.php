<?php
session_start();

define('BD_HOST', 'localhost');         // хост базы данных
define('BD_LOG', 'bkru42_comment_3');	// логин базы данных
define('BD_PASS', 'jGeDfe5&');          // пароль базы данных
define('BD_NAME', 'bkru42_comment_3');  // название базы данных

$db = mysqli_connect(BD_HOST, BD_LOG, BD_PASS, BD_NAME) or die('Ошибка подключения к БД');

?>