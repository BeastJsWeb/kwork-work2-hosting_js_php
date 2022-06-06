<?php
include 'init.php';

if (isset($_SESSION['USER_ID'])) { exit('1'); }

$js = ''; $error = false;

$recaptcha = isset($_POST['g-recaptcha-response']) ? filter_var(trim($_POST['g-recaptcha-response']), FILTER_SANITIZE_STRING) : null;

if (empty($recaptcha))
{
  $error = true;  
    
  $html = '<div class="alert alert-danger text-center" role="alert">Необходимо подтвердить что вы не робот!</div>';  
   
  $html = str_replace(array("\r", "\n"), " ", $html);    
  $html = addslashes($html);    
  
  $js .= 'register_form_message.classList.remove("d-none");'; 
  $js .= 'register_form_message.innerHTML = "'.$html.'";'; 
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
  
    $js .= 'register_form_message.classList.remove("d-none");'; 
    $js .= 'register_form_message.innerHTML = "'.$html.'";'; 
  }      
  else
  {    
    $js .= 'register_form_message.classList.add("d-none");';
    $js .= 'register_form_message.innerHTML = "";'; 
  }  
}    

$name = isset($_POST['name']) ? filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING) : null;

if (empty($name))
{
  $error = true;  
    
  $js .= 'form_register_name.classList.add("is-invalid");'; 
  $js .= 'form_register_name_message.textContent = "Поле необходимо заполнить";';
}    
else if (!preg_match("~^[a-zа-яё\-]+$~ui", $name)) 
{   
  $error = true;  
    
  $js .= 'form_register_name.classList.add("is-invalid");'; 
  $js .= 'form_register_name_message.textContent = "Содержит недопустимые символы";';
}

$surname = isset($_POST['surname']) ? filter_var(trim($_POST['surname']), FILTER_SANITIZE_STRING) : null;

if (empty($surname))
{
  $error = true;  
    
  $js .= 'form_register_surname.classList.add("is-invalid");'; 
  $js .= 'form_register_surname_message.textContent = "Поле необходимо заполнить";';
} 
else if (!preg_match("~^[a-zа-яё\-]+$~ui", $surname)) 
{   
  $error = true;  
    
  $js .= 'form_register_surname.classList.add("is-invalid");'; 
  $js .= 'form_register_surname_message.textContent = "Содержит недопустимые символы";';
}

$middlename = isset($_POST['middlename']) ? filter_var(trim($_POST['middlename']), FILTER_SANITIZE_STRING) : null;

if (!empty($middlename) && !preg_match("~^[a-zа-яё\-]+$~ui", $middlename)) 
{   
  $error = true;  
    
  $js .= 'form_register_middlename.classList.add("is-invalid");'; 
  $js .= 'form_register_middlename_message.textContent = "Содержит недопустимые символы";';
}

$gender = isset($_POST['gender']) ? (int)$_POST['gender'] : 0;

if (empty($gender))
{
  $error = true;  
    
  $js .= 'form_register_gender.classList.add("is-invalid");'; 
  $js .= 'form_register_gender_message.textContent = "Поле необходимо заполнить";';
}

if (empty($location))
{
  $error = true;  
    
  $js .= 'form_register_location.classList.add("is-invalid");'; 
  $js .= 'form_register_location_message.textContent = "Поле необходимо заполнить";';
}

if (empty($activity))
{
  $error = true;  
    
  $js .= 'form_register_activity.classList.add("is-invalid");'; 
  $js .= 'form_register_activity_message.textContent = "Поле необходимо заполнить";';
}

if (empty($jobTitle))
{
  $error = true;  
    
  $js .= 'form_register_jobTitle.classList.add("is-invalid");'; 
  $js .= 'form_register_jobTitle_message.textContent = "Поле необходимо заполнить";';
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
else
{
   $query = mysqli_query($db, "SELECT `id` FROM users WHERE `email` ='".$email."'"); 
   
   if (mysqli_num_rows($query))
   {
     $error = true;  
    
     $js .= 'form_register_email.classList.add("is-invalid");'; 
     $js .= 'form_register_email_message.textContent = "Уже существует";';  
   }    
}   

$country = isset($_POST['country']) ? filter_var(trim($_POST['country']), FILTER_SANITIZE_STRING) : null;

if (empty($country))
{
  $error = true;  
    
  $js .= 'form_register_country.classList.add("is-invalid");'; 
  $js .= 'form_register_country_message.textContent = "Поле необходимо заполнить";';
} 
else if (!preg_match("~^[a-zа-яё\-\d\;\:\"\.\/\\\!\? ]+$~mui", $country)) 
{   
  $error = true;  
    
  $js .= 'form_register_country.classList.add("is-invalid");'; 
  $js .= 'form_register_country_message.textContent = "Содержит недопустимые символы";';
}


$sity = isset($_POST['sity']) ? filter_var(trim($_POST['sity']), FILTER_SANITIZE_STRING) : null;

if (empty($sity))
{
  $error = true;  
    
  $js .= 'form_register_sity.classList.add("is-invalid");'; 
  $js .= 'form_register_sity_message.textContent = "Поле необходимо заполнить";';
} 
else if (!preg_match("~^[a-zа-яё\-\d\;\:\"\.\/\\\!\? ]+$~mui", $sity)) 
{   
  $error = true;  
    
  $js .= 'form_register_sity.classList.add("is-invalid");'; 
  $js .= 'form_register_sity_message.textContent = "Содержит недопустимые символы";';
}

$work = isset($_POST['work']) ? trim($_POST['work']) : null;

if (empty($work))
{
  $error = true;  
    
  $js .= 'form_register_work.classList.add("is-invalid");'; 
  $js .= 'form_register_work_message.textContent = "Поле необходимо заполнить";';
} 
else if (!preg_match("~^[a-zа-яё\-\d\;\:\"\.\/\\\!\?\&\,\'\`»«\' ]+$~mui", $work)) 
{   
  $error = true;  
    
  $js .= 'form_register_work.classList.add("is-invalid");'; 
  $js .= 'form_register_work_message.textContent = "Содержит недопустимые символы";';
}

$work = filter_var($work, FILTER_SANITIZE_STRING);

$post = isset($_POST['post']) ? trim($_POST['post']) : null;

if (empty($post))
{
  $error = true;  
    
  $js .= 'form_register_post.classList.add("is-invalid");'; 
  $js .= 'form_register_post_message.textContent = "Поле необходимо заполнить";';
} 
else if (!preg_match("~^[a-zа-яё\-\d\;\:\"\.\/\\\!\?\&\,\'\`»«\' ]+$~mui", $post)) 
{   
  $error = true;  
    
  $js .= 'form_register_post.classList.add("is-invalid");'; 
  $js .= 'form_register_post_message.textContent = "Содержит недопустимые символы";';
}

$post = filter_var($post, FILTER_SANITIZE_STRING);

$company = isset($_POST['company']) ? trim($_POST['company']) : null;

if (!empty($company) && !preg_match("~^[a-zа-яё\-\d\;\:\"\.\/\\\!\?\&\,\'\`»«\' ]+$~mui", $company)) 
{   
  $error = true;  
    
  $js .= 'form_register_company.classList.add("is-invalid");'; 
  $js .= 'form_register_company_message.textContent = "Содержит недопустимые символы";';
}

$company = filter_var($company, FILTER_SANITIZE_STRING);

$about = isset($_POST['about']) ? trim($_POST['about']) : null;

if (!empty($about) && !preg_match("~^[a-zа-яё\-\d\;\:\"\.\/\\\!\?\&\,\'\`»«\' ]+$~mui", $about)) 
{   
  $error = true;  
    
  $js .= 'form_register_about.classList.add("is-invalid");'; 
  $js .= 'form_register_about_message.textContent = "Содержит недопустимые символы";'; 
}

$about = filter_var($about, FILTER_SANITIZE_STRING);

$rules = isset($_POST['rules']) ? true : false;

if (!$rules)
{
  $error = true;  
    
  $js .= 'form_register_rules.classList.add("is-invalid");'; 
}

if (!$error)
{
  $password = genString(8);  
    
  $password_hash = md5($password);
    
  $title = 'Ваш пароль для доступа';
  $text = 'Пароль: '.$password;
  
  $res = sendMail($email, $title, $text);
  
  if ($res === true)
  {
    mysqli_query($db, "INSERT INTO `users` (`name`, `surname`, `middlename`, `gender`, `email`, `password`, `country`, `sity`, `work`, `post`, `company`, `about`) VALUES ('".$name."', '".$surname."', '".$middlename."', '".$gender."', '".$email."', '".$password_hash."', '".$country."', '".$sity."', '".$work."', '".$post."', '".$company."', '".$about."')") or die('Ошибка добавления нового пользователя');  
  }
  else
  {
    $html = '<div class="alert alert-danger text-center" role="alert">Что то пошло не так свяжитесь в администратором abc@site.ru</div>';  
   
    $html = str_replace(array("\r", "\n"), " ", $html);    
    $html = addslashes($html);    
  
    $js .= 'register_form_message.classList.remove("d-none");'; 
    $js .= 'register_form_message.innerHTML = "'.$html.'";'; 
    $js .= 'grecaptcha.reset(recaptcha_reg);';
  }     
} 
else
{
  $js .= 'grecaptcha.reset(recaptcha_reg);';
}    

exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));