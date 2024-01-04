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
include('../db/db.php');
session_start(); 
if (empty($_SESSION['otp_phone'])) {
    ?>
    <script type="text/javascript">
        window.location.href = '../';
    </script>
    <?php 
}?>
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
    $otp_code   = mysqli_escape_string($db, $_POST['otp_code']);

    if (empty($otp_code)) {
        $alert = "<div class='alert alert-warning'>Fill Otp code.....</div>";
    } else {
        // fetch admin id
        $select_data = "SELECT * FROM admin WHERE phone = '$otp_phone' AND otp = '$otp_code' AND is_delete = 0";
        $sql = mysqli_query($db, $select_data);
        $num = mysqli_num_rows($sql);

        if ($num > 0) {
            if ($row = mysqli_fetch_array($sql)) {
                unset($_SESSION['otp_phone']);
                setcookie("admin_id", $row['id'], time() + (30*24*60*60), "/");
                ?>
                <script type="text/javascript">
                    window.location.href = '../dashboard/';
                </script>
                <?php 
            } else {
                $alert = "<div class='alert alert-warning'>Login Failed.....</div>";
            }  
        } else {
            $alert = "<div class='alert alert-warning'>Give correct otp.....</div>";
        }
    }
}?>

<form action="" method="post" class="login_form">
    <div class="login_container">
        <img src="../assets/img/logo.png" alt="" class="m_auto">

        <?php echo $alert; ?>

        <div>
            <input type="hidden" id="user" name="otp_phone" value="<?php echo $_SESSION['otp_phone'] ?>">
        </div>

        <div>
            <label for="user">Give OTP Code</label>
            <input type="text" id="user" name="otp_code" placeholder="Give OTP Code">
        </div>

        <div class="ep_flex">
            <button type="submit" name="otp_login">Verify</button>
        </div>
    </div>
</form>

</body>
</html>