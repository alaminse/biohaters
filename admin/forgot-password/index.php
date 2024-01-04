<?php // php extension file redirecting to folder
function current_url()
{
    $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $valid_url = str_replace("&", "&amp;", $url);

    return $valid_url;
}

$current_url = current_url();

$array_url = explode('/', $current_url);
$extension_url = end($array_url);

if ($extension_url == 'index.php') {
    $redirect_url = substr($current_url, 0, -9); ?>
    <script type="text/javascript">
        window.location.href = '<?php echo $redirect_url; ?>';
    </script>
    <?php 
}

// include database
include('../db/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=========== GOOGLE FONT ===========-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Montserrat+Alternates:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!--=========== BOOTSTRAP CSS ===========-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!--=========== JQUERY ===========-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!--=========== BOOTSTRAP JS ===========-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <!--=========== FAV ICON ===========-->
    <link rel="shortcut icon" type="image/png" href="../assets/img/logo.png">

    <!--=========== STYLE CSS ===========-->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!--=========== LOGIN CSS ===========-->
    <link rel="stylesheet" href="../assets/css/login.css">

    <title>BH - Forgot Password</title>
</head>
<body>

<?php $alert = '';
if (isset($_POST['otp_login'])) {
    $otp_phone  = mysqli_escape_string($db, $_POST['otp_phone']);

    if (empty($otp_phone)) {
        $alert = "<div class='alert alert-warning'>Please Fill The All Field.....</div>";
    } else {
        // fetch admin id
        $select_data = "SELECT * FROM admin WHERE phone = '$otp_phone' AND is_delete = 0";
        $sql = mysqli_query($db, $select_data);
        $num = mysqli_num_rows($sql);

        if ($num > 0) {
            $fetch = mysqli_fetch_assoc($sql);

            session_start();
            $_SESSION['otp_phone']  = $fetch['phone'];
            $otp                    = rand(1000, 9999);

            $update_otp = "UPDATE admin SET otp = '$otp' WHERE phone = '$otp_phone'";
            $sql = mysqli_query($db, $update_otp);
            
            $phone = $otp_phone;
            $msg = "Your Login OTP Code - " .$otp . "\r-Biology Haters";
            
            if ($sql) {
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
                ?>
                <script type="text/javascript">
                    window.location.href = '../get-otp/';
                </script>
                <?php 
            }    
        } else {
            $alert = "<div class='alert alert-warning'>This Number has no account.....</div>";
        }
    }
}?>

<form action="" method="post" class="login_form">
    <div class="login_container">
        <img src="../assets/img/logo.png" alt="" class="m_auto">
        <?php echo $alert; ?>
        <div>
            <label for="user">Phone no</label>
            <input type="text" id="user" name="otp_phone" placeholder="Phone no.">
        </div>

        <div class="ep_flex">
            <button type="submit" name="otp_login">Send OTP</button>
        </div>
    </div>
</form>

</body>
</html>