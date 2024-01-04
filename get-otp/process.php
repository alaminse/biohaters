<?php include('../assets/includes/otp-header.php'); ?>

<?php if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $otp_1 = $_POST['otp_1'];
    $otp_2 = $_POST['otp_2'];
    $otp_3 = $_POST['otp_3'];
    $otp_4 = $_POST['otp_4'];

    $otp = $otp_1 . $otp_2 . $otp_3 . $otp_4;
    
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

    // match otp to login
    $match_otp_row = "SELECT * FROM hc_login_otp WHERE ip_address = '$ipAddress' AND device_type = '$deviceType' AND device_name = '$deviceName' AND email = '$email' AND otp = '$otp'";
    $sql_otp_row = mysqli_query($db, $match_otp_row);
    $num_otp_row = mysqli_num_rows($sql_otp_row);
    if ($num_otp_row > 0) {
        $row_otp_row = mysqli_fetch_array($sql_otp_row);
        
        // set login cookie
        setcookie("student_id", $row_otp_row['student_id'], time() + (14*24*60*60), "/");
        
        // update token
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
        
        // update token
		$update_otp = "UPDATE hc_login_otp SET token = '$token' WHERE email = '$email'";
		mysqli_query($db, $update_otp);
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>dashboard/';
        </script>
        <?php 
    } else {
        $_SESSION['msg'] = "<div class='danger w_100 alert'>সঠিক ওটিপি প্রদান করুন.....</div>";
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>login/';
        </script>
        <?php 
    }
} else {
	?>
	<script type="text/javascript">
		window.location.href = '<?= $base_url ?>login/';
	</script>
	<?php 
}?>
</body>
</html>