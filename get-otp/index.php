<?php include('../assets/includes/otp-header.php'); ?>

<?php use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/mailer/Exception.php';
require '../assets/mailer/PHPMailer.php';
require '../assets/mailer/SMTP.php';?>

<?php $alert = "";
if (isset($_POST['get_otp'])) {
	$email = mysqli_escape_string($db, $_POST['email']);
	if (empty($email)) {
		$_SESSION['msg'] = "<div class='danger w_100 alert'>আপনার ইমেইলটি প্রদান করুন.....</div>";
		?>
		<script type="text/javascript">
			window.location.href = '<?= $base_url ?>login/';
		</script>
		<?php 
	} else {
		// check student
        $check_student = "SELECT * FROM hc_student WHERE email = '$email'";
        $sql_check_student = mysqli_query($db, $check_student);
        $num_check_student = mysqli_num_rows($sql_check_student);
        if ($num_check_student == 0) {
			$_SESSION['msg'] = "<div class='danger w_100 alert'>আপনার প্রদত্ত ইমেইলটি খুঁজে পাওয়া যায়নি.....</div>";
			?>
			<script type="text/javascript">
				window.location.href = '<?= $base_url ?>login/';
			</script>
			<?php 
		} else {
			$row_check_student = mysqli_fetch_assoc($sql_check_student);

            // get student id if fetched
            $student_id = $row_check_student['id'];
            $phone 		= $row_check_student['phone'];

			// Get IP address
			$ipAddress = $_SERVER['REMOTE_ADDR'];

			// Get device information
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
			$deviceType = "Unknown";
			$deviceName = "Unknown";

			// Define an array of device types and their corresponding keywords
			$deviceTypes = array(
				'Mobile' => array('Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone'),
				'Tablet' => array('Tablet', 'iPad', 'Android'),
				'Desktop' => array('Windows', 'Macintosh', 'Linux', 'Ubuntu')
			);

			// Loop through the device types and check if the user agent contains any of the keywords
			foreach ($deviceTypes as $type => $keywords) {
				foreach ($keywords as $keyword) {
					if (strpos($userAgent, $keyword) !== false) {
						$deviceType = $type;
						break 2; // Break out of both loops once a match is found
					}
				}
			}

			// Get device name (if available)
			if (preg_match('/\((.*?)\)/', $userAgent, $matches)) {
				$deviceName = $matches[1];
			}
			
			// Create a URL for the ipinfo.io API
            $apiUrl = "https://ipinfo.io/{$ipAddress}/json";
            
            // Initialize cURL session
            $ch = curl_init($apiUrl);
            
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            // Execute cURL session and get the JSON response
            $response = curl_exec($ch);
            
            // Check for cURL errors
            if (curl_errno($ch)) {
                echo 'Error: ' . curl_error($ch);
            } else {
                // Parse the JSON response
                $locationData = json_decode($response);
            
                // Extract location information
                $city = isset($locationData->city) ? $locationData->city : 'Unknown';
                $region = isset($locationData->region) ? $locationData->region : 'Unknown';
                $country = isset($locationData->country) ? $locationData->country : 'Unknown';
                
                $tracking_location = "City: " . $city . ", Region: " . $region . ", Country: " . $country;
            }
            
            // Close cURL session
            curl_close($ch);

			$otp_date = date('Y-m-d H:i:s', time());

			// generate otp
			$otp = rand(1000, 9999);
			
			do {
                // generate token
                $tokenLength = 60; // Length of the token in bytes
    
                $randomBytes = random_bytes($tokenLength);
                $token = base64_encode($randomBytes);
    
                // Replace '/' character with '-'
                $token = str_replace('/', '_', $token);
                $token = str_replace('+', '$', $token);
                $token = str_replace('%', '_', $token);
                $token = str_replace('^', '$', $token);
                $token = str_replace('@', '_', $token);
                $token = str_replace('!', '$', $token);
                $token = str_replace('&', '_', $token);
                $token = str_replace('(', '$', $token);
                $token = str_replace(')', '_', $token);
                $token = str_replace('=', '$', $token);
                $token = str_replace(' ', '_', $token);
            
                // check token
                $check_token = "SELECT * FROM hc_login_otp WHERE token = '$token'";
                $sql_check_token = mysqli_query($db, $check_token);
                $num_check_token = mysqli_num_rows($sql_check_token);
            } while ($num_check_token != 0);

			// check otp row
			$check_otp_row = "SELECT * FROM hc_login_otp WHERE email = '$email'";
			$sql_otp_row = mysqli_query($db, $check_otp_row);
			$num_otp_row = mysqli_num_rows($sql_otp_row);
			if ($num_otp_row == 0) {
				// add otp
				$add_otp = "INSERT INTO hc_login_otp (student_id, ip_address, device_type, device_name, email, phone, otp, otp_count, otp_date, token) VALUES ('$student_id', '$ipAddress', '$deviceType', '$deviceName', '$email', '$phone', '$otp', '1', '$otp_date', '$token')";
				mysqli_query($db, $add_otp);
				
				// add otp track
				$add_track = "INSERT INTO hc_otp_track (student_id, ip_address, device_type, device_name, location_details, email, phone, otp_date) VALUES ('$student_id', '$ipAddress', '$deviceType', '$deviceName', '$tracking_location', '$email', '$phone', '$otp_date')";
				mysqli_query($db, $add_track);
				
				$msg = "Your Login OTP Code - " . $otp . "\r-Biology Haters";
				
				$email_msg = "Your Login OTP Code - " . $otp . "<br>Your otp page link - https://biohaters.com/get-otp/?tokenized=" . $token . " <br>-Biology Haters";
				
				// send OTP by sms
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
                
                // send OTP by email
                $subject = 'Login OTP';
                $message = $email_msg;
                
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'mail.biohaters.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'info@biohaters.com';
                $mail->Password = '0ZzTEHbl23infogm';
                $mail->Port = 465;
                $mail->SMTPSecure = 'ssl';
                $mail->isHTML(true);
                $mail->setFrom('info@biohaters.com', 'Biology Haters');
                $mail->addAddress($email);
                $mail->Subject = ("$email ($subject)");
                $mail->Body = $message;
                $mail->send();
                
                $alert = "<div class='success w_100 alert'>আপনার মোবাইল নম্বরে এবং ইমেইলে ওটিপি পাঠানো হয়েছে.....</div>";
			} else {
				// fetch otp row
				$row_otp_row = mysqli_fetch_assoc($sql_otp_row);

				// get student id if fetched
				$otp_count = $row_otp_row['otp_count'];

				// new otp date
				$new_otp_date = date('Y-m-d', time());
				
				// match limit otp & date
				$check_otp_limit = "SELECT * FROM hc_login_otp WHERE email = '$email' AND otp_count = '2' AND DATE(otp_date) = '$new_otp_date'";
				$sql_otp_limit = mysqli_query($db, $check_otp_limit);
				$num_otp_limit = mysqli_num_rows($sql_otp_limit);
				if ($num_otp_limit == 0) {
					// set otp count
					if ($otp_count == 1) {
						$otp_count = 2;
					} elseif ($otp_count == 2) {
						$otp_count = 1;
					}

					// check otp date old or new
					$check_otp_date = "SELECT * FROM hc_login_otp WHERE email = '$email' AND DATE(otp_date) = '$new_otp_date'";
					$sql_otp_date = mysqli_query($db, $check_otp_date);
					$num_otp_date = mysqli_num_rows($sql_otp_date);
					if ($num_otp_date == 0) {
						$otp_count = 1;
					}
					
					// add otp track
    				$add_track = "INSERT INTO hc_otp_track (student_id, ip_address, device_type, device_name, location_details, email, phone, otp_date) VALUES ('$student_id', '$ipAddress', '$deviceType', '$deviceName', '$tracking_location', '$email', '$phone', '$otp_date')";
    				mysqli_query($db, $add_track);

					// update otp
					$update_otp = "UPDATE hc_login_otp SET ip_address = '$ipAddress', device_type = '$deviceType', device_name = '$deviceName', otp = '$otp', otp_count = '$otp_count', otp_date = '$otp_date', token = '$token' WHERE email = '$email'";
					mysqli_query($db, $update_otp);
					
					$msg = "Your Login OTP Code - " .$otp . "\r-Biology Haters";
					
					$email_msg = "Your Login OTP Code - " . $otp . "<br>Your otp page link - https://biohaters.com/get-otp/?tokenized=" . $token . " <br>-Biology Haters";
					
					// send OTP by sms
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
                    
                    // send OTP by email
                    $subject = 'Login OTP';
                    $message = $email_msg;
                    
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'mail.biohaters.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'info@biohaters.com';
                    $mail->Password = '0ZzTEHbl23infogm';
                    $mail->Port = 465;
                    $mail->SMTPSecure = 'ssl';
                    $mail->isHTML(true);
                    $mail->setFrom('info@biohaters.com', 'Biology Haters');
                    $mail->addAddress($email);
                    $mail->Subject = ("$email ($subject)");
                    $mail->Body = $message;
                    $mail->send();
                    
                    $alert = "<div class='success w_100 alert'>আপনার মোবাইল নম্বরে এবং ইমেইলে ওটিপি পাঠানো হয়েছে.....</div>";
				} else {
					$_SESSION['msg'] = "<div class='danger w_100 alert'>আপনার ওটিপি লিমিট শেষ করেছেন। আগামীকাল পর্যন্ত অপেক্ষা করুন.....</div>";
					?>
					<script type="text/javascript">
						window.location.href = '<?= $base_url ?>login/';
					</script>
					<?php 
				}
			}
		}
	}
}

if (!isset($_POST['get_otp']) && !isset($_GET['tokenized'])) {
	?>
	<script type="text/javascript">
		window.location.href = '<?= $base_url ?>login/';
	</script>
	<?php 
}?>

<!--===== OTP SECTION =====-->
<section>
	<!--===== OTP CONTAINER =====-->
	<div class="otp_container hc_container">
		<!--===== OTP FORM =====-->
		<form action="<?= $base_url ?>get-otp/process.php" method="post" class="ep_grid">
			<div class="otp_header">
				<i class='bx bxs-check-shield otp_icon'></i>
				<h2>Verify Your OTP Code</h2>
			</div>
			
			<?php if (isset($_GET['tokenized'])) {
			    $tokenized_token = $_GET['tokenized'];
			    
			    // check tokenized email
    			$check_tokenized_email = "SELECT * FROM hc_login_otp WHERE token = '$tokenized_token'";
    			$sql_tokenized_email = mysqli_query($db, $check_tokenized_email);
    			$num_tokenized_email = mysqli_num_rows($sql_tokenized_email);
    			if ($num_tokenized_email > 0) {
    			    $row_tokenized_email = mysqli_fetch_assoc($sql_tokenized_email);
    			    $tokenized_email = $row_tokenized_email['email'];
    			    ?>
    			    <div class='danger w_100 alert'>আপনার ইমেইল অ্যাড্রেস - <?= $tokenized_email ?></div>
    			    
    			    <div class="otp_field">
        				<input type="number" name="otp_1" minlength="1" maxlength="1">
        				<input type="number" name="otp_2" minlength="1" maxlength="1" disabled>
        				<input type="number" name="otp_3" minlength="1" maxlength="1" disabled>
        				<input type="number" name="otp_4" minlength="1" maxlength="1" disabled>
        			</div>
        
        			<input type="hidden" name="email" value="<?= $tokenized_email ?>">
    			    <?php  
    			} else {
    			    ?>
                	<script type="text/javascript">
                		window.location.href = '<?= $base_url ?>login/';
                	</script>
                	<?php 
    			}
			} else {
			    ?>
			    <?= $alert ?>
			
    			<div class="otp_field">
    				<input type="number" name="otp_1" minlength="1" maxlength="1">
    				<input type="number" name="otp_2" minlength="1" maxlength="1" disabled>
    				<input type="number" name="otp_3" minlength="1" maxlength="1" disabled>
    				<input type="number" name="otp_4" minlength="1" maxlength="1" disabled>
    			</div>
    
    			<input type="hidden" name="email" value="<?= $email ?>">
			    <?php 
			}?>

			<button class="otp_btn button w_100 mt_1_5 disabled" name="login">Verify OTP<i class='bx bx-key'></i></button>
		</form>
	</div>
</section>

<!--=========== CUSTOM JS ===========-->
<script>
/*========= OTP CODE VERIFY =========*/
const otpInputs = document.querySelectorAll('.otp_field input')
const otpBtn = document.querySelector('.otp_container form button')

// iterate over all inputs
otpInputs.forEach((input, index1) => {
    input.addEventListener("keyup", (e) => {
        const currentInput = input
        const nextInput = input.nextElementSibling
        const prevInput = input.previousElementSibling

        if(currentInput.value.length > 1) {
            currentInput.value = '';
            return;
        }

        if(nextInput && nextInput.hasAttribute('disabled') && currentInput.value != '') {
            nextInput.removeAttribute('disabled')
            nextInput.focus()
        }

        if(e.key === "Backspace") {
            otpInputs.forEach((input, index2) => {
                if(index1 <= index2 && prevInput) {
                    input.setAttribute('disabled', true)
                    currentInput.value = ''
                    prevInput.focus()
                }
            })
        }

        if(!otpInputs[3].disabled && otpInputs[3].value !== '') {
            otpBtn.classList.remove('disabled')
            return
        }
        otpBtn.classList.add('disabled')
    })
})

// focus the first input field
window.addEventListener('load', () => otpInputs[0].focus())
</script>
</body>
</html>