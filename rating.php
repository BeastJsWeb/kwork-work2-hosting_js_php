<?php
	include 'config.php';

	$js = '';


	if (!isset($_SESSION['USER_ID']))
	{
	  $js .= 'form_register.reset();';
	  $js .= 'grecaptcha.reset(recaptcha_reg);';

	  exit(json_encode(['data' => $js]));
	}

	$user_id = $_SESSION['USER_ID'];


	if (isset($_GET['id'])) $ident = (int)$_GET['id'];
    else exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));


	if (isset($_GET['value'])) $golos = (int)$_GET['value'];
    else exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));

	if ($golos != 1) $golos = -1;

	// Автор поста не имеет права голосовать
	// Узнаем автора поста
	unset($row);
	unset($user_id_autor);
	$query = mysqli_query($db, "SELECT `user_id` FROM `comment` WHERE `id` = $ident");
	$row = mysqli_fetch_assoc($query);
	$user_id_autor = $row['user_id'];

	if ($user_id == $user_id_autor)exit();


	// Защита от повторного клика
    // Голосовал ли этот посетитель по этому комменнтарию?

	unset($row);
	unset($golos_db);
	$query = mysqli_query($db, "SELECT `golos` FROM `rating` WHERE `ident` = $ident AND `user_id` = $user_id");
    $row = mysqli_fetch_assoc($query);
    $golos_db = $row['golos'];


	// Первый клик - если $golos_db не существует
	if (isset($golos_db)) exit();
	else
	{
		// Фиксируем факт голoсование ютого юзера по этой теме
		mysqli_query($db, "INSERT INTO `rating` (`ident`, `user_id`, `golos`) VALUES ($ident, $user_id, $golos)");

		// Запрашиваем голоса по этой теме
	    unset($row);
	    unset($golos_comment);
		$query = mysqli_query($db, "SELECT `plus` FROM `comment` WHERE `id` = $ident");
	    $row = mysqli_fetch_assoc($query);
	    $golos_comment = $row['plus'];

	    $golos_summa = $golos_comment + $golos;

		mysqli_query($db, "UPDATE `comment` SET `plus` = $golos_summa WHERE `id` = $ident");

		if ($golos_summa > 0)
		{
		    $js .= 'comment_vote_'.$ident.'.classList.add("vote--sum-positive");';
		    $js .= 'comment_vote_'.$ident.'.classList.remove("vote--sum-negative");';
		}
		else if ($golos_summa < 0)
		{
		    $js .= 'comment_vote_'.$ident.'.classList.remove("vote--sum-positive");';
		    $js .= 'comment_vote_'.$ident.'.classList.add("vote--sum-negative");';
		}
		else
		{
		    $js .= 'comment_vote_'.$ident.'.classList.remove("vote--sum-positive");';
		    $js .= 'comment_vote_'.$ident.'.classList.remove("vote--sum-negative");';
		}

		$js .= 'comment_rating_'.$ident.'.textContent = "'.$golos_summa.'";';

		exit(json_encode(['data' => $js], JSON_UNESCAPED_UNICODE));

	}

?>







