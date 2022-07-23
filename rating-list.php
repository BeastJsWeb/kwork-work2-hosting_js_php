<?php
	include 'config.php';

//	if (isset($_GET['id'])) $ident = (int)$_GET['id'];      // Раскомменнтировать для работы
//    else exit();

	$ident = 2381; 	// Удалить для работы

	$golos_list = "";

	// Соберем все отзывы для данного комментария
	$query = mysqli_query($db, "SELECT `golos`, `user_id`  FROM `rating` WHERE `ident` = $ident");
    while ($row = mysqli_fetch_assoc($query))
    {
    	$golos   = $row['golos'];
    	$user_id = $row['user_id'];

		// Узнаем каждого проголосовавшего
		$query2 = mysqli_query($db, "SELECT `name`, `middlename`, `surname` FROM `users` WHERE `id` = $user_id");
		$row2 = mysqli_fetch_assoc($query2);
		$name = $row2['name'];
		$middlename = $row2['middlename'];
		$surname = $row2['surname'];

		if ($golos == 1) $style = '<p style="color: DarkGreen;">';
		else $style = '<p style="color: FireBrick;">';

		// Составим список проголосовавших по эхтому комментарию
		$golos_list .= $style . "$name $middlename $surname </p>";

    }

	echo $golos_list;



?>







