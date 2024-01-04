<?php 
include('db.php');
session_start();

if (!$conn) {
	die("Connection Failed");
}

if (isset($_POST['roll'])) {
	$roll = $_POST['roll'];
	$batch = $_POST['batch'];
	date_default_timezone_set('Asia/Dhaka');
	$date = date('Y-m-d', time());
	$select_student = "SELECT * FROM list WHERE roll = '$roll' AND batch = '$batch'";
	$sql_student = mysqli_query($conn, $select_student);
	$num_student = mysqli_num_rows($sql_student);
    date_default_timezone_set('Asia/Dhaka');
	if ($num_student == 0) {
		$_SESSION['error'] = 'You are not a student of this batch';
	} else {
		$select_attend = "SELECT * FROM attendance WHERE roll = '$roll' AND DATE(entry) = '$date'";
		$sql_attend = mysqli_query($conn, $select_attend);
		$num_attend = mysqli_num_rows($sql_attend);
        date_default_timezone_set('Asia/Dhaka');
        $student_entry = date('Y-m-d H:i:s', time());
		if ($num_attend == 0) {
			$select_info = "SELECT * FROM list WHERE roll = '$roll' AND batch = '$batch'";
			$sql_info = mysqli_query($conn, $select_info);
			$row_info = mysqli_fetch_assoc($sql_info);
			$name = $row_info['name'];
			$phone = $row_info['phone'];
			$gen = $row_info['gen'];

			$insert = "INSERT INTO attendance (roll, name, batch, phone, entry, gen) VALUES ('$roll', '$name', '$batch', '$phone', '$student_entry', '$gen')";
			if (mysqli_query($conn, $insert)) {
				$_SESSION['success'] = 'Attendance added successfully';

				// send sms
				date_default_timezone_set('Asia/Dhaka');
				$student_entry = date('h:i:s a', time());
				if ($gen == 1) {
					$child = "son";
				} else {
					$child = "daugther";
				}

				$msg = "Your ".$child." has arrived in Biology Haters at ".$student_entry;
				
				if ($roll == '01616720009' || $roll == '01518448104' || $roll == '01680920753' || $roll == '01796086202' || $roll == '01952756497' || $roll == '01616752861') {
				    $msg = $name." has arrived in Biology Haters at ".$student_entry;
				}

				$to = "$phone";
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
			} else {
				$_SESSION['error'] = 'There is an error';
			}
		} else {
			$_SESSION['error'] = 'You have scanned once today';
		}
	}
	// $voice = CreateObject("SAPI.SpVoice");
	// $message = "Hi ".$roll." Your attendance has been successfully added! Thank you";

	// $insert = "INSERT INTO attendance (roll, entry) VALUES ('$roll', now())";

	// if (mysqli_query($conn, $insert)) {
	// 	$_SESSION['success'] = 'Attendance added successfully';
	// } else {
	// 	$_SESSION['error'] = 'There is an error';
	// }
	?>
	<script type="text/javascript">
	    window.location.href = 'attendance.php?batch=<?php echo $batch; ?>';
	</script>
	<?php 
}

mysqli_close($conn);

 ?>