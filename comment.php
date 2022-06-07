<?php
include 'init.php';

$js = '';
$html = '';

if (!isset($_SESSION['USER_ID']))
{  
  $js .= 'form_register.reset();';  
  $js .= 'grecaptcha.reset(recaptcha_reg);';  
    
  exit(json_encode(['data' => $js]));
}    

$user_id = $_SESSION['USER_ID'];

$query = mysqli_query($db, "SELECT * FROM `users` WHERE `id` = '".$user_id."'"); 

$user_data = mysqli_fetch_array($query);

$theme_id = isset($_POST['theme_id']) ? (int)$_POST['theme_id'] : 0;
$reply_id = isset($_POST['reply_id']) ? (int)$_POST['reply_id'] : 0;
$level = isset($_POST['level']) ? (int)$_POST['level'] : 0;
$up = isset($_POST['up']) ? (int)$_POST['up'] : 0;

$message = isset($_POST['message']) ? trim($_POST['message']) : null;

$message = preg_replace('#<(/?)(br)>#', '[$1$2]', $message);

$message = filter_var($message, FILTER_SANITIZE_STRING);

$image = isset($_POST['media']) ? filter_var(trim($_POST['media']), FILTER_SANITIZE_STRING) : null;
$subscription = isset($_POST['subscription']) ? (int)$_POST['subscription'] : 0;

if ($reply_id > 0)
{
  $query = mysqli_query($db, "SELECT * FROM `comment` WHERE `id` = '".$reply_id."'");

  if (!mysqli_num_rows($query))
  {
    exit('0');
  }
} 

if (empty($message) && empty($image))
{
  $js .= 'c();';  
  exit(json_encode(['data' => $js]));
}    

if (!empty($image))
{
  if (!file_exists($_SERVER['DOCUMENT_ROOT']."/upload/".$image))
  {
    $js .= 'c();';  
    exit(json_encode(['data' => $js]));
  }    
}    

$level_up = $level + 1;

mysqli_query($db, "INSERT INTO `comment` (`theme_id`, `parent_id`, `user_id`, `message`, `image`) VALUES ('".$theme_id."', '".$reply_id."', '".$user_id."', '".$message."', '".$image."')") or die('Ошибка добавления комментария');

$id = mysqli_insert_id($db);

$html .= '<div id="comment_id_'.$id.'" class="comment '.($level_up > 1 ? 'comment--reply' : '').'" data-image="'.(!empty($image) ? $image : '').'" data-id="'.$id.'" data-level="'.($level+1).'" style="--level: '.($level+1).';">';
$html .= '<div class="comment__branches">';

$arr_next = isset($_POST['next']) ? $_POST['next'] : '';

if ($level > 0)
{  
  $arr = explode('#', $arr_next);  
  
  foreach ($arr as $key => $val)
  {
    switch ($val)
    {
      case '0':
        
      $html .= '<div class="comment__branch comment__branch--hidden"></div>';    
          
      break;    
        
      case '1':
       
      $html .= '<div class="comment__branch"></div>';    
          
      break;  

      case '2':
      
      $html .= '<div class="comment__branch comment__branch--hidden"></div>';    
          
      break;  
    }  
  }
      
  $html .= '<div class="comment__branch comment__branch--no-border"></div>';
}

$message = replaceBBCode($message);

$ava = genUserAvatar($user_data['name'], $user_data['surname']);

$html .= '</div>';
$html .= '<div class="comment__content">';
$html .= '<span class="comment__avatar" style="background-image: url(\'https://leonardo.osnova.io/2e384afa-663a-ce88-81cd-82136b61e56c/-/scale_crop/64x64/\');">';
$html .= '<div class="d-flex justify-content-center align-items-center rounded-circle" style="width: 32px; height:32px;  color: '.$ava['color'].'; background-color: '.$ava['bg'].';"><span class="text-uppercase text-center font-weight-bold">'.$ava['name'].'</span></div>';        
$html .= '</span>';
$html .= '<a class="comment__author" href="#">'.$user_data['name'].' '.$user_data['middlename'].'  '.$user_data['surname'].'</a>';
$html .= '<div class="comment__icon" onclick="form_complaint_id.value = '.$id.'; form_complaint.reset(); return false;" title="Пожаловаться">';
$html .= '<svg height="20" width="20" class="icon icon--v_flag"><use xlink:href="#v_flag"></use></svg>';            
$html .= '</div>';
$html .= '<div class="comment__break"></div>';
$html .= '<span class="comment__detail">';
$html .= '<time class="time" title="'.date("Y-m-d H:i:s").'">'.relDateTime(time(), false).'</time>';            
$html .= '</span>';
$html .= '<div class="comment__text">';
$html .= '<div id="comment_text_'.$id.'">'.$message.'</div>';                
$html .= '</div>';

if (!empty($image))
{
  $html .= '<div id="comment_image_'.$id.'" class="comment__attaches comment__media">';       
  $html .= '<div class="andropov_image andropov_image--zoomable" style="max-height: 300px;max-width: 410px;" >';
  $html .= '<div class="andropov_image__inner" style="padding-bottom: 0px; background: transparent none repeat scroll 0% 0%;"><img onclick="view_image(this);" src="/upload/'.$image.'"></div>';
  $html .= '</div>';
  $html .= '</div>';   
}    


$html .= '<div class="comment__action">Ответить</div>';
$html .= '<details id="dropdownMenuButton1" class="ddMenu" data-bs-toggle="dropdown" >';
$html .= '<summary><svg class="icon icon--v_etc" width="16" height="16" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#v_etc"></use></svg></summary>';                
$html .= ' <ul class="dropdown-menu">';
$html .= '<li><button class="dropdown-item comment-edit" ><svg height="20" width="20" class="icon icon--v_pen"><use xlink:href="#v_pen"></use></svg> Редактировать</button></li>';
$html .= '<li><button class="dropdown-item" onclick="ajax(\'delete.php?id='.$id.'\');"><svg height="20" width="20" class="icon icon--v_pen"><use xlink:href="#v_delete"></use></svg> Удалить</button></li>';
$html .= '</ul>';
$html .= '</details>';
$html .= '<div id="comment_vote_'.$id.'" class="vote vote--comment">';
$html .= '<div class="vote__button vote__button--minus" onclick="ajax(\'rating.php?value=0&id='.$id.'\'); return false;">';
$html .= '<svg class="icon icon--v_arrow_down" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#thumbs-down"></use></svg>';    
$html .= '</div>';
$html .= '<div id="comment_rating_'.$id.'" class="vote__value">0</div>';
$html .= '<div class="vote__button vote__button--plus" onclick="ajax(\'rating.php?value=1&id='.$id.'\'); return false;">';
$html .= '<svg class="icon icon--v_arrow_up" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#thumbs-up"></use></svg>';    
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="comment__break"></div>';
$html .= '<div class="comment__expand-branch comment__inline-action">Развернуть ветку</div>';                                
$html .= '</div>';
$html .= '</div>';

$html = str_replace(["\r","\n"]," ",$html);    
$html = addslashes($html);      

if ($reply_id > 0)
{
  $js .= 'document.querySelector("#comment_id_'.$reply_id.'").insertAdjacentHTML("afterend", "'.$html.'");';  
}
else
{  
  if ($up == 0)  
  {  
    $js .= 'comment_box.insertAdjacentHTML("beforeend", "'.$html.'");';
  }
  else 
  {
    $js .= 'comment_box.insertAdjacentHTML("afterbegin", "'.$html.'");';  
  } 
}  

if ($subscription)
{
  $query = mysqli_query($db, "SELECT `id` FROM `subscription` WHERE `theme_id` = '".$theme_id."' and `user_id` = '".$_SESSION['USER_ID']."'");

  if (!mysqli_num_rows($query)) 
  {
    mysqli_query($db, "INSERT INTO `subscription` (`theme_id`, `user_id`) VALUES ('".$theme_id."', '".$user_id."')");
 
    $js .= 'comment_subscription_box.style.display = "none";';
  }
}    

$query = mysqli_query($db, "SELECT t1.user_id, t2.email FROM `subscription` AS t1 LEFT JOIN `users` AS t2 ON t2.id = t1.user_id WHERE t1.theme_id = '".$theme_id."' and t1.user_id != '".$_SESSION['USER_ID']."'");

if (mysqli_num_rows($query)) 
{
  while ($row = mysqli_fetch_assoc($query))          
  {  
    $email = $row['email']; 
    
    $title = 'Новый комментарий в теме';
   
    $text = 'Новый комментарий в теме '.$theme_id.' <a href="'.$_SERVER['HTTP_HOST'].'/?theme='.$theme_id.'" target="_blank">перейти</a>';
    
    sendMail($email, $title, $text); 
  }
}

$js .= 'a(true, false);';

exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));
