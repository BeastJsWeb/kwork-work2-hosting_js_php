<?php
include 'init.php';

if (!isset($_SESSION['USER_ID'])) { exit('0'); }

$id = isset($_POST['reply_id']) ? (int)$_POST['reply_id'] : 0; 

$message = isset($_POST['message']) ? trim($_POST['message']) : null;

$message = preg_replace('#<div class="fst-italic text-muted small">(.+?)</div>#is', '[quote]\\1[/quote]', $message);

$message = preg_replace('#<(/?)(br)>#', '[$1$2]', $message);
$message = filter_var($message, FILTER_SANITIZE_STRING);

$image = isset($_POST['media']) ? filter_var(trim($_POST['media']), FILTER_SANITIZE_STRING) : null;

$js = ''; $html = '';

if (empty($message) && empty($image))
{
  exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));  
}  
  
$query = mysqli_query($db, "SELECT `id` FROM `comment` WHERE `id` = '".$id."' and `user_id` = '".$_SESSION['USER_ID']."'") or die('ERROR 1');

  if (mysqli_num_rows($query)) 
  {           
    mysqli_query($db, "UPDATE `comment` SET `message` = '".$message."', `image` = '".$image."' WHERE `id` = '".$id."'") or die('ERROR 2');
    
    $message = replaceBBCode($message);
    
    $js .= 'comment_text_'.$id.'.innerHTML = "'.$message.'";'; 
    
    if (!empty($image))
    {
      $html .= '<div id="comment_image_'.$id.'" class="comment__attaches comment__media">';       
      $html .= '<div class="andropov_image andropov_image--zoomable" style="max-height: 300px;max-width: 410px;" >';
      $html .= '<div class="andropov_image__inner" style="padding-bottom: 0px; background: transparent none repeat scroll 0% 0%;"><img onclick="view_image(this);" src="/upload/'.$image.'"></div>';
      $html .= '</div>';
      $html .= '</div>'; 
      
      $html = str_replace(["\r","\n"]," ",$html);    
      $html = addslashes($html); 
      
      $js .= 'if (document.querySelector("#comment_image_'.$id.'")) { comment_image_'.$id.'.remove(); }';
      
      $js .= 'comment_text_'.$id.'.insertAdjacentHTML("afterend", "'.$html.'");';
      
      $js .= 'comment_id_'.$id.'.setAttribute("data-image", "'.$image.'");';                
    }
    else
    {
      $js .= 'if (document.querySelector("#comment_image_'.$id.'")) { comment_image_'.$id.'.remove(); }'; 
      
      $js .= 'comment_id_'.$id.'.setAttribute("data-image", "");';
    }    
  }
  
$js .= 'a(true, false);';

exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));