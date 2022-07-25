<?php
	include 'config.php';

	if (isset($_GET['id'])) $ident = (int)$_GET['id'];      // ������������������ ��� ������
    else exit();
	
	//$ident = 2546; 	// ������� ��� ������

	$golos_list = "";

	// ������� ��� ������ ��� ������� �����������
	$query = mysqli_query($db, "SELECT `golos`, `user_id`  FROM `rating` WHERE `ident` = $ident");
    while ($row = mysqli_fetch_assoc($query))
    {
    	$golos   = $row['golos'];
    	$user_id = $row['user_id'];

		// ������ ������� ����������������
		$query2 = mysqli_query($db, "SELECT `name`, `middlename`, `surname` FROM `users` WHERE `id` = $user_id");
		$row2 = mysqli_fetch_assoc($query2);
		$name = $row2['name'];
		$middlename = $row2['middlename'];
		$surname = $row2['surname'];

		if ($golos == 1) $style = '<p style="color: DarkGreen;">';
		else $style = '<p style="color: FireBrick;">';

		// �������� ������ ��������������� �� ������ �����������
		$golos_list .= $style . "$name $middlename $surname </p>";

    }

	echo $golos_list;



?>







