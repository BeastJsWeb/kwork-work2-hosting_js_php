<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

define('SITE_NAME', 's5s.ru');   //название сайта в письме
define('FROM_NAME', 'notification@s5s.ru');   //от кого в письме

define('EMAIL', 'notification@s5s.ru'); //мыло на которое приходят опповещения о жалобах
define('SMTP_USERNAME', 'notification@s5s.ru'); //логин почты
define('SMTP_PASSWORD', 'j4%1Ks8z');     //пароль почты
define('SMTP_HOST', 'smtp.beget.ru');     //хост почты
define('SMTP_CHARSET', 'utf-8');               //кодировка сайта для почты
define('SMTP_PORT', '2525');                    // порт почты

define('BD_HOST', 'localhost');                   // хост базы данных
define('BD_LOG', 'bkru42_comment_2');                   // логин базы данных
define('BD_PASS', 'xZCcar5%');           // пароль базы данных
define('BD_NAME', 'bkru42_comment_2');                  // название базы данных

define('RECHACHA_PUBLIC_KEY', '6LcoBQQfAAAAAKnRfhH1orNPy1UVWUNTyWF7wa5j'); //рекаптча гугла публичный ключ
define('RECHACHA_SECRET_KEY', '6LcoBQQfAAAAAEMtau6XipBCBP-h_56t-1aruF_I'); //рекаптча гугла секретны ключ

$complaint = array('Спам', 'Оскорбление', 'Нарушение закона', 'Порнографический контент', 'Насилие', 'Авторские права', 'Введение в заблуждение', 'Другое');

session_start();

$db = mysqli_connect(BD_HOST, BD_LOG, BD_PASS, BD_NAME) or die('Ошибка подключения к БД');

function relDateTime($t, $r = true)
{
  $t = $r ? strtotime($t) : $t;

  $reldays = ($t - strtotime(date('d.m.Y'))) / 86400;

  if ($reldays >= 0 && $reldays < 1)
  {
    return 'Сегодня, '.date('H:i', $t);
  }
  else if ($reldays >= -1 && $reldays < 0)
  {
    return 'Вчера, '.date('H:i', $t);
  }
  else
  {
    return date('d.m.Y', $t).' '.date('H:i', $t);
  }
}

function getCurlData($url)
{
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_TIMEOUT, 10);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
  $curlData = curl_exec($curl);
  curl_close($curl);
  return $curlData;
}

function genString($num)
{
  $arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','P','R','S','T','U','V','X','Y','Z','1','2','3','4','5','6','7','8','9');

  $str = "";

  $count_arr = count($arr) - 1;

  for($n = 0; $n < $num; $n++)
  {
    $index = rand(0, $count_arr);
    $str .= $arr[$index];
  }

  return $str;
}

function delete_value_from_array($needle, &$array, $all = true)
{
  foreach(array_keys($array,$needle) as $key)
  {
    unset($array[$key]);
    if(!$all) { return; }
  }
}

function genUserAvatar($name, $surname)
{
  $string = mb_substr($name, 0, 1);

  $string .= mb_substr($surname, 0, 1);

  $hash = md5($name.$surname);

  $bg = '#'.substr($hash, 0, 6);
  $color = '#'.substr($hash, 5, 6);
  $border = '#'.substr($hash, 11, 6);

  return array('name' => $string, 'bg' => $bg, 'color' => $color, 'border' => $border);
}

function replaceBBCode($text_post)
{
  $str_search = array("#\[br\]#is", "#\[quote\](.+?)\[\/quote\]#is");

  $str_replace = array("<br>", "<div class='fst-italic text-muted small'>\\1</div>" );

  return preg_replace($str_search, $str_replace, $text_post);
}

function sendMail($mail_to, $subject, $message)
{

	// Атрибуты соединения с сервером
	$smtp_server 	= SMTP_HOST;	//  smtp.server.ru реальные данные
	$login		= SMTP_USERNAME;  	//  login соответствующего
	$password 	= SMTP_PASSWORD;    	//  password реального ящика
	$domen		= SITE_NAME;
	$port		= SMTP_PORT;				//  порт для отправки писем

		$from_mail = $login;
                $from_name = FROM_NAME;

	    $encoding = SMTP_CHARSET;

	    $socket = @fsockopen($smtp_server, $port, $errno, $errstr, 30);
		if(substr(@fgets($socket,256),0,3) != 220) die("Сервер $smtp_server не найден");
		usleep(1000);

		fputs($socket, "EHLO $smtp_server\r\n");
		if(substr(@fgets($socket,256),0,3) != 250) die("Сервер не принял команду идентификации EHLO");
        usleep(1000);

		fputs($socket, "AUTH LOGIN\r\n");
		if(substr(@fgets($socket,256),0,3) != 250) die("Сервер отклонил аутентификацию AUTH LOGIN");
		// echo "<br />68 $socket " . substr(fgets($socket,256),0,3);
        fgets($socket,256);
        usleep(1000);

		fputs($socket, base64_encode($login)."\r\n");
		if(substr(@fgets($socket,256),0,3) != 250) die("Не принят логин");
		// echo "<br />68 $socket " . substr(fgets($socket,256),0,3);
        fgets($socket,256);
        usleep(1000);

		fputs($socket, base64_encode($password)."\r\n");
		if(substr(@fgets($socket,256),0,3) != 250) die("Пароль неверен");
		// echo "<br />68 $socket " . substr(fgets($socket,256),0,3);
        fgets($socket,256);
        usleep(1000);

	    $SEND  = "Date: ".date("r")."\r\n";
	    $SEND .= "From: =?$encoding?B?".base64_encode($from_name)."?= <$from_mail>\r\n";
	    $SEND .= "X-Priority: 3 (Normal)\r\n";
	    $SEND .= "Message-ID: <" . mt_rand(123456789, 987654321) . "575496646." . date("YmjHis") . "@$domen>\r\n";

		$SEND .= "List-Subscribe: <https://s5s.ru/subscribe>\r\n";
		// List-Unsubscribe: <http://www.host.com/list.cgi?cmd=unsub&lst=list>
		// List-Unsubscribe: <http://srclickpro.ru/l_ru/delete.html?q=0exg1p0bqy1yvYSuf1aa091e1000zhx>
		// List-Unsubscribe: <http://12347.ru/test/unsubscribe.php?adr=svn@adrainbow.ru>
		// http://12347.ru/test/unsubscribe.php?adr=vtortorg@mail.ru
	    $SEND .= "List-Unsubscribe: <https://s5s.ru/unsubscribe/?adr=$mail_to>\r\n";


	    $SEND .= "To: $mail_to\r\n";
	    $SEND .= "Subject: =?$encoding?B?".base64_encode($subject)."?=\r\n";
	    $SEND .= "MIME-Version: 1.0\r\n";
	    $SEND .= "Content-Type: text/html; charset=$encoding\r\n";
	    // $SEND .= "Content-Transfer-Encoding: 8bit\r\n";
	    // $SEND .= $message."\r\n";

	    $SEND .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
	    $SEND .= quoted_printable_encode($message) ."\r\n";

		unset($flag);

	    fputs($socket, "MAIL FROM: <$from_mail>\r\n");
	    if(substr(fgets($socket,256),0,3) != 250) $flag = "Ошибка адреса отправителя MAIL FROM";
		// echo "<br />68 $socket " . substr(fgets($socket,256),0,3);
        fgets($socket,256);
        usleep(1000);

	    fputs($socket, "RCPT TO: <$mail_to>\r\n");
	    if(substr(fgets($socket,256),0,3) != 250) $flag = "Ошибка адреса получаетеля mail_to";
		// echo "<br />68 $socket " . substr(fgets($socket,256),0,3);
        fgets($socket,256);
        usleep(1000);

	    fputs($socket, "DATA\r\n");
	    if(substr(fgets($socket,256),0,3) != 250) $flag = "Ошибка инициации почтовых данных DATA";
		// echo "<br />68 $socket " . substr(fgets($socket,256),0,3);
        fgets($socket,256);
        usleep(1000);

	    fputs($socket, $SEND."\r\n.\r\n");
	    if(substr(fgets($socket,256),0,3) != 334) $flag = "Ошибка передачи письма";
		// echo "<br />68 $socket " . substr(fgets($socket,256),0,3);
        fgets($socket,256);
        usleep(1000);

	    fputs($socket, "QUIT\r\n");
	    if(substr(fgets($socket,256),0,3) != 334) $flag = "Ошибка звершения сеанса";
		// echo "<br />68 $socket " . substr(fgets($socket,256),0,3);
        fgets($socket,256);
        usleep(1000);

	    fclose($socket);

            return true;
}


	//$email = "mem@12347.ru";
	//sendMail($email, "Тема smtpmail.php", "Текст письма");
?>