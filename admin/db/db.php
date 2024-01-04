<?php 

	// database connection
	// $db = mysqli_connect('localhost', 'biologyhaters_new', 'biologyhaters_new', 'biologyhaters_new');

	$db = mysqli_connect('localhost', 'root', '', 'biologyhaters');
	
	mysqli_set_charset($db, "utf8mb4");
	
	if (!$db) {
		header("location: ../404/");
	}

?>