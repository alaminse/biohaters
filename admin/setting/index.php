<?php include('../assets/includes/header.php'); ?>

<!-- edit profile picture -->
<?php $profile_alert = '';
if (isset($_POST['update_profile'])) {
    $profile = $_FILES['profile']['name'];
    $tmp_profile = $_FILES['profile']['tmp_name'];

    if (empty($profile)) {
        $profile_alert = '<p class="danger text_center">Choose a file</p>';
    } else {
        $array_img = explode('.', $profile);
        $extension_img = end($array_img);

        if ($extension_img == 'jpg' || $extension_img == 'png' || $extension_img == 'jpeg' || $extension_img == 'JPG') {
            $random_prev = rand(0, 999999);
            $random = rand(0, 999999);
            $random_next = rand(0, 999999);
            $random_xtra = rand(0, 999999);

            $final_img = "../assets/profile/hc_".$random_prev."_".$random."_".$random_next."_".$random_xtra."_".$profile;

            move_uploaded_file($tmp_profile, $final_img);

            $update = "UPDATE admin SET profile = '$final_img' WHERE id = $admin_id";
            $sql = mysqli_query($db, $update);
            if ($sql) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../setting/';
                </script>
                <?php 
            }
        } else {
            $profile_alert = '<p class="danger text_center">Give PNG or JPG file</p>';
        }
    }
}?>

<!-- edit info -->
<?php $info_alert = '';
if (isset($_POST['update_info'])) {
    $name = mysqli_escape_string($db, $_POST['name']);
    $username = mysqli_escape_string($db, $_POST['username']);

    if (empty($name) || empty($username)) {
        $info_alert = '<p class="danger text_center">Empty field</p>';
    } else {
        $update = "UPDATE admin SET name = '$name', username = '$username' WHERE id = $admin_id";
        $sql = mysqli_query($db, $update);
        if ($sql) {
            ?>
            <script type="text/javascript">
                window.location.href = '../setting/';
            </script>
            <?php 
        }
    }
}?>

<!-- edit password -->
<?php if (isset($_POST['update_password'])) {
    $pwd = mysqli_escape_string($db, $_POST['pwd']);
    $con_pwd = mysqli_escape_string($db, $_POST['con_pwd']);

    if (empty($pwd) || empty($con_pwd)) {
        $info_alert = '<p class="danger text_center">Empty field</p>';
    } else {
        if ($pwd != $con_pwd) {
            $info_alert = '<p class="danger text_center">Password not matched</p>';
        } else {
            $hashed = password_hash($pwd, PASSWORD_DEFAULT);

            $update = "UPDATE admin SET password = '$hashed' WHERE id = $admin_id";
            $sql = mysqli_query($db, $update);
            if ($sql) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../setting/';
                </script>
                <?php 
            }
        }
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Setting</h4>
        </div>
    </div>

    <!-- profile setting tabs -->
    <div class="ep_section">
        <div class="ep_container ep_grid grid_1_2">
            <!-- profile photo setting -->
            <div class="ep_grid h_max ep_card">
                <div class="profile_setting_pic m_auto">
                    <?php if (empty($admin_profile)) {
                        echo '<img src="../assets/img/admin.png" alt="">';
                    } else {
                        echo '<img src="'.$admin_profile.'" alt="">';
                    }?>
                </div>

                <form action="" method="post" class="ep_grid ep_center" enctype="multipart/form-data">
                    <div class="input_grp">
                        <input type="file" name="profile" id="" class="input_sm trasparent">
                    </div>

                    <div class="input_grp">
                        <button type="submit" name="update_profile" class="m_auto btn_sm">Save</button>
                    </div>
                </form>
                <?php echo $profile_alert; ?>
            </div>

            <!-- profile details setting -->
            <div>
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="user-info-tab" data-bs-toggle="pill" data-bs-target="#user-info" type="button" role="tab" aria-controls="user-info" aria-selected="true">User Info</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="user-pass-tab" data-bs-toggle="pill" data-bs-target="#user-pass" type="button" role="tab" aria-controls="user-pass-profile" aria-selected="false">Change Password</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <!-- user info tab -->
                    <div class="tab-pane fade show active" id="user-info" role="tabpanel" aria-labelledby="user-info">
                        <form action="" method="post" class="ep_grid tab_form grid_3 grid_align_end">
                            <div class="input_grp">
                                <label for="">Name</label>
                                <input type="text" name="name" id="" value="<?php echo $admin_name; ?>">
                            </div>
                            
                            <div class="input_grp">
                                <label for="">Username</label>
                                <input type="text" name="username" id="" value="<?php echo $admin_username; ?>">
                            </div>

                            <div class="input_grp">
                                <button type="submit" name="update_info">Save</button>
                            </div>
                        </form>
                        <?php echo $info_alert; ?>
                    </div>

                    <!-- change password tab -->
                    <div class="tab-pane fade" id="user-pass" role="tabpanel" aria-labelledby="user-pass-tab">
                        <form action="" method="post" class="ep_grid tab_form grid_3 grid_align_end">
                            <div class="input_grp">
                                <label for="">New Password</label>
                                <input type="password" name="pwd" id="" placeholder="New Password">
                            </div>
                            
                            <div class="input_grp">
                                <label for="">Confirm Password</label>
                                <input type="password" name="con_pwd" id="" placeholder="Confirm Password">
                            </div>

                            <div class="input_grp">
                                <button type="submit" name="update_password">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>