<?php include('db.php');
if (isset($_GET['batch']) && isset($_GET['date'])) {
	$batch = $_GET['batch'];
	$date = $_GET['date'];
	date_default_timezone_set('Asia/Dhaka');
	$present_array = '';
	$select_present = "SELECT * FROM attendance WHERE batch = '$batch' AND DATE(entry) = '$date'";
	$sql_present = mysqli_query($conn, $select_present);
	$num_present = mysqli_num_rows($sql_present);
	while ($row_present = mysqli_fetch_assoc($sql_present)) {
		$bh_reg_present = $row_present['roll'];

		$present_array = $bh_reg_present.','.$present_array;
	}
	$present_array = substr($present_array, 0, -1);
	$select_absents = "SELECT * FROM list WHERE batch = '$batch' AND roll NOT IN ($present_array)";
	$sql_absents = mysqli_query($conn, $select_absents);
	$num_absents = mysqli_num_rows($sql_absents);
	if ($num_absents > 0) {
		while ($row_absents = mysqli_fetch_assoc($sql_absents)) {
			$bh_reg_absents = $row_absents['roll'];
			$phone_absents = $row_absents['phone'];
			$gen_absents = $row_absents['gen'];
	
			if ($gen_absents == 1) {
				$child = "son";
			} elseif ($gen_absents == 0) {
				$child = "daugther";
			}
	
			$msg = "Your ".$child." is absent today in Biology Haters";
	
			// send sms to absent
			$to = "$phone_absents";
			$token = "913518264916767232092ebe8e002b72391add1856353f4a8c3b";
			$message = "$msg";
	
			$url = "http://api.greenweb.com.bd/api.php?json";
	
	
			$data= array(
			'to'=>"$to",
			'message'=>"$message",
			'token'=>"$token"
			); 
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$smsresult = curl_exec($ch);
		}
		?>
		<script type="text/javascript">
			window.location.href = 'database.php?batch=<?php echo $batch; ?>&date=<?php echo $date; ?>';
		</script>
		<?php 
	}
}?>