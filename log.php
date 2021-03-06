<?php
include 'init.php'; 

if (isset($_SESSION['USER_ID'])) { exit('1'); }


if (!isset($_SESSION["pass_attempt"])) { $_SESSION["pass_attempt"] = 0; }

$js = ''; $error = false;   

$recaptcha = isset($_POST['g-recaptcha-response']) ? filter_var(trim($_POST['g-recaptcha-response']), FILTER_SANITIZE_STRING) : null;

if (empty($recaptcha))
{
  $error = true;  
    
  $html = '<div class="alert alert-danger text-center" role="alert">Необходимо подтвердить что вы не робот!</div>';  
   
  $html = str_replace(array("\r", "\n"), " ", $html);    
  $html = addslashes($html);    
  
  $js .= 'login_form_message.innerHTML = "'.$html.'";'; 
}
else
{
  $google_url = "https://www.google.com/recaptcha/api/siteverify";      
  $ip = $_SERVER['REMOTE_ADDR'];
  $url = $google_url."?secret=".RECHACHA_SECRET_KEY."&response=".$recaptcha."&remoteip=".$ip;
  $res = getCurlData($url);
  $res= json_decode($res, true);
    
  if ($res['success'] == false)
  {
    $error = true;
    
    $html = '<div class="alert alert-danger text-center" role="alert">Необходимо подтвердить что вы не робот!</div>';  
   
    $html = str_replace(array("\r", "\n"), " ", $html);    
    $html = addslashes($html);    
  
    $js .= 'login_form_message.innerHTML = "'.$html.'";'; 
  }      
  else
  {    
    $js .= 'login_form_message.innerHTML = "";'; 
  }  
}    

$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING) : null;

if (empty($email))
{
  $error = true;  
    
  $js .= 'form_register_email.classList.add("is-invalid");'; 
  $js .= 'form_register_email_message.textContent = "Поле необходимо заполнить";';
} 
else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
{
  $error = true;  
    
  $js .= 'form_register_email.classList.add("is-invalid");'; 
  $js .= 'form_register_email_message.textContent = "Некоректное значение";';       
}

$password = isset($_POST['password']) ? filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING) : null;

if (empty($password))
{
  $error = true;  
    
  $js .= 'form_register_password.classList.add("is-invalid");'; 
  $js .= 'form_register_password_message.textContent = "Поле необходимо заполнить";';

} 

if ($_SESSION["pass_attempt"] == 3) { // password --block / unblock

  if (time() - $_SESSION["pass_attempt_time"] < 600) {

    $error = true; 

    $js .= 'form_login_password.classList.add("is-invalid");';
    $js .= 'form_login_name_message.textContent = "Число попыток входа превышено, доступ ограничен на 10 мин";';

  } else {

    $_SESSION["pass_attempt"] = 0;

    $js .= 'form_login_password.classList.remove("is-invalid");';
    $js .= 'form_login_name_message.textContent = "";';
  }
}

if (!$error)
{
  $password_hash = md5($password);  
    
  $query = mysqli_query($db, "SELECT `id` FROM `users` WHERE `email` = '".$email."' and `password` = '".$password_hash."'");  
    
  if (mysqli_num_rows($query)) 
  {
    $row = mysqli_fetch_array($query);
    
    $_SESSION['USER_ID'] = $row['id'];
  }
  else
  {
    $error = true; 
    
    $html = '<div class="alert alert-danger text-center" role="alert">Пользователь не найден!</div>';  
   
    $html = str_replace(array("\r", "\n"), " ", $html);    
    $html = addslashes($html);    
   
    $js .= 'login_form_message.innerHTML = "'.$html.'";'; 

    if ($_POST['password'] != md5($password) && isset($_SESSION["pass_attempt"]) && $_SESSION["pass_attempt"] < 3) {

      $_SESSION["pass_attempt"] ++;
      $_SESSION["pass_attempt_time"] = time();
    }
  }      
} 

if ($error)
{
  $js .= 'grecaptcha.reset(recaptcha_log);';
} 
else
{
  $js .= 'location.href = "/";';
}    

exit($result = json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));
var_dump($result);
echo $result;