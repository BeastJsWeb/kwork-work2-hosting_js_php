<?php
include 'init.php';

$js = '';

if (!isset($_SESSION['USER_ID']))
{  
  $js .= 'form_register.reset();';  
  $js .= 'grecaptcha.reset(recaptcha_reg);';  
    
  exit(json_encode(['data' => $js]));
}    

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0; 
$theme_id = isset($_POST['theme_id']) ? (int)$_POST['theme_id'] : 0;
$type = isset($_POST['type']) ? (int)$_POST['type'] : 0;

$query = mysqli_query($db, "SELECT `id` FROM `comment` WHERE `theme_id` = '".$theme_id."' and `id` = '".$id."'");

if (mysqli_num_rows($query))
{
  $title = 'Жалоба на комментарий';
  $text = 'Тема: '.$theme_id.' Комментарий: '.$id.' Жалоба: '.$complaint[$type];
  
  sendMail(EMAIL, $title, $text);
}

$js .= 'document.getElementById("body").className ="";';

exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));