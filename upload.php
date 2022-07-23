<?php  
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$html = ''; $js = '';

if (empty($_FILES['loadfile']['name']))
{
  exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));                
}  
    
if (!in_array($_FILES['loadfile']['type'], array('image/jpeg','image/png','image/gif')))
{	
  $html .= '<div class="andropov_uploader__preview_item__inner text-danger">Недопустимый формат</div>';

  $html = str_replace(["\r","\n"]," ",$html);    
  $html = addslashes($html);   

  $js .= 'comment_uploader.innerHTML = "'.$html.'";';

  exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));   
}
    
if (!is_uploaded_file($_FILES["loadfile"]["tmp_name"]))
{	      
  exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));  
}	
    
if ($_FILES["loadfile"]["size"] > 1024*2000) 	
{  
  $html .= '<div class="andropov_uploader__preview_item__inner text-danger">Файл слишком большой</div>';

  $html = str_replace(["\r","\n"]," ",$html);    
  $html = addslashes($html);   

  $js .= 'comment_uploader.innerHTML = "'.$html.'";';

  exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));                
}
       
$data_img = getimagesize($_FILES['loadfile']['tmp_name']);
	
$type_img = [1 => 'gif', 2 => 'jpg', 3 => 'png'];
    
if (!isset($type_img[$data_img[2]])) 
{       
  exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));                             
}
    
$type = $type_img[$data_img[2]];
    
$upload_dir = $_SERVER['DOCUMENT_ROOT']."/upload/";
    
$file_name = md5(time().$type).'.'.$type;
    
$new_image = $upload_dir.$file_name;	   
    
if (move_uploaded_file($_FILES["loadfile"]["tmp_name"], $new_image))
{
  $html .= '<div class="andropov_uploader__preview_item__inner">';
  $html .= '<div class="andropov_preview--image" style="min-height: 80px; min-width: 80px"><img style="max-width: 80px; max-height: 80px;" src="/upload/'.$file_name.'"></div>';
  $html .= '<div class="andropov_uploader__preview_item__remove" onclick="f();"></div>';
  $html .= '</div>';

  $html = str_replace(["\r","\n"]," ",$html);    
  $html = addslashes($html);      

  $js .= 'comment_uploader.innerHTML = "'.$html.'";';
  $js .= 'comment_form_data_media.value = "'.$file_name.'";';       
  $js .= 'comment_send_button.classList.remove("v-button--disabled");';
}

exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));