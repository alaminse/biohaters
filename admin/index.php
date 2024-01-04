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
include('db/db.php');
if (isset($_COOKIE['admin_id'])) {
    ?>
    <script type="text/javascript">
        window.location.href = 'dashboard/';
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
    <link rel="shortcut icon" type="image/png" href="assets/img/logo.png">

    <!--=========== STYLE CSS ===========-->
    <link rel="stylesheet" href="assets/css/style.css">

    <!--=========== LOGIN CSS ===========-->
    <link rel="stylesheet" href="assets/css/login.css">

    <title>Biohaters - Login</title>
</head>
<body>

<?php $alert = '';
if (isset($_POST['admin_login'])) {
    $user_name  = mysqli_escape_string($db, $_POST['admin_user']);
    $user_pwd   = mysqli_escape_string($db, $_POST['admin_pwd']);

    if (empty($user_name) || empty($user_pwd)) {
        $alert = "<div class='alert alert-warning'>Please Fill The All Field.....</div>";
    } else {
        // fetch admin id
        $select_data = "SELECT * FROM admin WHERE (username = '$user_name' OR phone = '$user_name') AND status = 1 AND is_delete = 0";
        $sql = mysqli_query($db, $select_data);
        $num = mysqli_num_rows($sql);

        if ($num > 0) {
            if ($row = mysqli_fetch_array($sql)) {
                $hashed_pwd = password_verify($user_pwd , $row['password']);

                if ($hashed_pwd == false) {
                    $alert = "<div class='alert alert-warning'>The Password is Incorrect.....</div>";
                } elseif ($hashed_pwd == true) {
                    setcookie("admin_id", $row['id'], time() + (30*24*60*60), "/");
                    ?>
                    <script type="text/javascript">
                        window.location.href = 'dashboard/';
                    </script>
                    <?php 
                }
            } else {
                $alert = "<div class='alert alert-warning'>Data Error.....</div>";
            }
        } else {
            $alert = "<div class='alert alert-warning'>Login Failed.....</div>";
        }
    }
}?>

<form action="" method="post" class="login_form">
    <div class="login_container">
        <img src="assets/img/logo.png" alt="" class="m_auto">
        <?php echo $alert; ?>
        <div>
            <label for="user">Username or Phone no.</label>
            <input type="text" id="user" name="admin_user" placeholder="Username or Phone no.">
        </div>

        <div>
            <label for="pass">Password</label>
            <input type="password" id="pass" name="admin_pwd" placeholder="Password">
        </div>

        <div class="ep_flex">
            <button type="submit" name="admin_login">Login</button>
            <a href="forgot-password/">Forgot Password?</a>
        </div>
    </div>
</form>

</body>
</html>