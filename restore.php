<?php
include 'init.php'; 

if (isset($_SESSION['USER_ID'])) { exit('1'); }

if (!isset($_SESSION["email_attempt"])) { $_SESSION["email_attempt"] = 0; }

$js = ''; $error = false;  

$recaptcha = isset($_POST['g-recaptcha-response']) ? filter_var(trim($_POST['g-recaptcha-response']), FILTER_SANITIZE_STRING) : null;

if (empty($recaptcha))
{
  $error = true;  
    
  $html = '<div class="alert alert-danger text-center" role="alert">Необходимо подтвердить что вы не робот!</div>';  
   
  $html = str_replace(array("\r", "\n"), " ", $html);    
  $html = addslashes($html);    
  
  $js .= 'restore_form_message.innerHTML = "'.$html.'";'; 
}

$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING) : null;

if (empty($email))
{
  $error = true;  
    
  $js .= 'form_restore_email.classList.add("is-invalid");'; 
  $js .= 'form_restore_email_message.textContent = "Поле необходимо заполнить";';
} 
else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
{
  $error = true;  
    
  $js .= 'form_restore_email.classList.add("is-invalid");'; 
  $js .= 'form_restore_email_message.textContent = "Некоректное значение";';       
}

if ($_SESSION["email_attempt"] == 1) { // email--restore  block/unblock 10 min

  $now = time() - $_SESSION["email_attempt_time"];

  if ($now < 600) {

    $error = true; 

    //$time_left = ($now - 660) / 60;
    //$time_left = (int) -$time_left;

    //$html = '<div class="alert alert-danger text-center" role="alert">Повторный запрос пароля возможен через '.$time_left.' мин</div>';  
    //$html = '<div id="emailNotFound" class="alert alert-danger text-center" role="alert"></div>';  

    //$html = str_replace(array("\r", "\n"), " ", $html);    
    //$html = addslashes($html);    

    //$js .= 'restore_form_message.innerHTML = "'.$html.'";';

  } else {

    $_SESSION["email_attempt"] = 0;
  }
}

$query = mysqli_query($db, "SELECT `id` FROM `users` WHERE `email` = '".$email."'"); 

if (!$error) {

  if (mysqli_num_rows($query)) 
  {

  } else {

    $error = true;

    $html = '<div id="userNotFound" class="alert">Пользователь не найден!</div>';  
    
    $html = str_replace(array("\r", "\n"), " ", $html);    
    $html = addslashes($html);    

    $js .= 'restore_form_message.innerHTML = "'.$html.'";'; 

    if ($_POST['email'] != md5($email) && isset($_SESSION["email_attempt"]) && $_SESSION["email_attempt"] < 1) { // email --not found

      $_SESSION["email_attempt"] ++;
      $_SESSION["email_attempt_time"] = time();
    }
  } 
}


if ($error)
{
  
} 
else
{
  $js .= 'document.getElementById("body").className ="";';
}   

exit($result = json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));
var_dump($result);
echo $result;