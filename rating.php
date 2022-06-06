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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$value = isset($_GET['value']) ? (int)$_GET['value'] : 1;

$user_id = $_SESSION['USER_ID'];

$query = mysqli_query($db, "SELECT `id` FROM `comment` WHERE `id` = '".$id."'");

if (mysqli_num_rows($query))
{
  $query = mysqli_query($db, "SELECT `value` FROM `rating` WHERE `ident` = '".$id."' and `user_id` = '".$user_id."'");
  
  if (mysqli_num_rows($query))
  {
    $row = mysqli_fetch_assoc($query);
    
    if ($row['value'] != $value)
    {
      mysqli_query($db, "UPDATE `rating` SET `value` = '".$value."' WHERE `ident` = '".$id."' and `user_id` = '".$user_id."'");
      
      if ($row['value'] == 0)
      {
        mysqli_query($db, "UPDATE `comment` SET `minus` = `minus` - 1  WHERE `id` = '".$id."'");
      }
      else
      {
        mysqli_query($db, "UPDATE `comment` SET `plus` = `plus` - 1  WHERE `id` = '".$id."'");   
      }    
    } 
    else
    {
      exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));   
    }    
  }
  else
  {
    mysqli_query($db, "INSERT INTO `rating` (`ident`, `user_id`, `value`) VALUES ('".$id."', '".$user_id."', '".$value."')");
  } 
  
  if ($value == 0)
  {
    mysqli_query($db, "UPDATE `comment` SET `minus` = `minus` + 1  WHERE `id` = '".$id."'");
  }
  else
  {
    mysqli_query($db, "UPDATE `comment` SET `plus` = `plus` + 1  WHERE `id` = '".$id."'");   
  } 
  
  $query = mysqli_query($db, "SELECT `plus`, `minus` FROM `comment` WHERE `id` = '".$id."'");
  
  $row = mysqli_fetch_assoc($query);
  
  $vote = $row['plus'] - $row['minus'];
  
  if ($vote > 0)
  {
    $js .= 'comment_vote_'.$id.'.classList.add("vote--sum-positive");';
    $js .= 'comment_vote_'.$id.'.classList.remove("vote--sum-negative");';
  }
  else if ($vote < 0)
  {
    $js .= 'comment_vote_'.$id.'.classList.remove("vote--sum-positive");';
    $js .= 'comment_vote_'.$id.'.classList.add("vote--sum-negative");';  
  }
  else
  {
    $js .= 'comment_vote_'.$id.'.classList.remove("vote--sum-positive");';
    $js .= 'comment_vote_'.$id.'.classList.remove("vote--sum-negative");';
  } 
  
  $js .= 'comment_rating_'.$id.'.textContent = "'.$vote.'";';
}

exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));
