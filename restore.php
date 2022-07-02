<?php
include 'init.php'; 

if (isset($_SESSION['USER_ID'])) { exit('1'); }

$js = '';

$html = '<div class="alert alert-danger text-center" role="alert">Пользователь не найден!</div>';  
   
$html = str_replace(array("\r", "\n"), " ", $html);    
$html = addslashes($html);


$js .= 'restore_form_message.classList.remove("d-none");'; 
$js .= 'restore_form_message.innerHTML = "'.$html.'";'; 
//$js .= 'location.href = "/";';  

exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));