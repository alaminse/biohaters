<?php 

	$conn = mysqli_connect('localhost', 'biologyhaters_qr_attend', 'biologyhaters_qr_attend', 'biologyhaters_qr_attend');
	
	if (!$conn) {
		header("location: 404.php");
	}

 ?>