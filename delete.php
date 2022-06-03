<?php
include 'init.php';

if (!isset($_SESSION['USER_ID'])) { exit('0'); }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;  
$js = '';

$query = mysqli_query($db, "SELECT `id` FROM `comment` WHERE `id` = '".$id."' and `user_id` = '".$_SESSION['USER_ID']."'");

if (mysqli_num_rows($query))
{
  $query = mysqli_query($db, "SELECT `id` FROM `comment` WHERE `parent_id` = '".$id."'");  
   
  if (!mysqli_num_rows($query))
  {
    mysqli_query($db, "DELETE FROM `comment` WHERE `id` = '".$id."' and `user_id` = '".$_SESSION['USER_ID']."'");
  
    $js .= 'a(true, false);';
    
    $js .= 'document.querySelector("#comment_id_'.$id.'").remove();';
  }  
}

exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));