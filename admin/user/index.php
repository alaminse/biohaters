<?php include('../assets/includes/header.php'); ?>

<!-- DELETE COURSE -->
<?php if (isset($_POST['add'])) {
    $name       = mysqli_escape_string($db, $_POST['name']);
    $username   = mysqli_escape_string($db, $_POST['username']);
    $phone      = mysqli_escape_string($db, $_POST['phone']);
    $pwd        = mysqli_escape_string($db, $_POST['pwd']);
    $con_pwd    = mysqli_escape_string($db, $_POST['con_pwd']);
    $role       = $_POST['role'];
    $status     = $_POST['status'];

    if (empty($name) || empty($username) || empty($phone) || empty($pwd) || empty($con_pwd) || empty($role)) {
        $alert = "<p class='warning mb_75'>Required Fields.....</p>";
    } else {
        $phone = str_replace('+880', '0', $phone);
        $phone = str_replace(' ', '', $phone);

        $phone_verify = substr($phone, 0, 3);
        
        if (!preg_match("/^([0-9]{11})$/", $phone)) {
            $alert = "<p class='danger mb_75'>Phone number invalid.....</p>" . $phone;
        } else {
            if (($phone_verify == '013') || ($phone_verify == '014') || ($phone_verify == '015') || ($phone_verify == '016') || ($phone_verify == '017') || ($phone_verify == '018') || ($phone_verify == '019')) {
                // check username
                $select_username = "SELECT * FROM admin WHERE username = '$username' AND status = 1 AND is_delete = 0";
                $sql_username = mysqli_query($db, $select_username);
                $num_username = mysqli_num_rows($sql_username);
                if ($num_username == 0) {
                    if ($pwd == $con_pwd) {
                        $pwd_hash = password_hash($pwd, PASSWORD_DEFAULT);
                        
                        $created_date = date('Y-m-d H:i:s', time());
                        
                        $otp = rand(0000, 9999);
                        
                        $add = "INSERT INTO admin (name, username, phone, password, role, status, otp, join_date) VALUES ('$name', '$username', '$phone', '$pwd_hash', '$role', '$status', '$otp', '$created_date')";
                        $sql_add = mysqli_query($db, $add);
                        if ($sql_add) {
                            ?>
                            <script type="text/javascript">
                                window.location.href = '../user/';
                            </script>
                            <?php 
                        }
                    } else {
                        $alert = "<p class='danger mb_75'>Password does not match.....</p>";
                    }
                } else {
                    $alert = "<p class='danger mb_75'>Username has taken.....</p>";
                }
            } else {
                $alert = "<p class='danger mb_75'>Phone number invalid.....</p>" . $phone;
            }
        }
    }
}?>


<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Users</h4>
        </div>
    </div>
    
    <?php if (isset($_GET['add'])) {
        if (($admin_role != 0) && ($admin_role != 1) && ($admin_role != 2)) {
            ?>
            <script type="text/javascript">
                window.location.href = '../user/';
            </script>
            <?php 
        }?>
        <!-- welcome message -->
        <div class="ep_section">
            <!--========== MANAGE COURSE ==========-->
            <div class="ep_container mng_category">
                <div class="add_category">
                    <h5 class="box_title">Add User</h5>
    
                    <?php echo $alert; ?>
    
                    <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                        <div>
                            <label for="">Name*</label>
                            <input type="text" id="" name="name" placeholder="Name">
                        </div>
                        
                        <div>
                            <label for="">Username*</label>
                            <input type="text" id="" name="username" placeholder="Username">
                        </div>
                        
                        <div>
                            <label for="">Phone*</label>
                            <input type="text" id="" name="phone" placeholder="Phone Number">
                        </div>
                        
                        <div>
                            <label for="">Password*</label>
                            <input type="password" id="" name="pwd" placeholder="Password">
                        </div>
                        
                        <div>
                            <label for="">Confirm Password*</label>
                            <input type="password" id="" name="con_pwd" placeholder="Confirm Password">
                        </div>
    
                        <div>
                            <label for="">Role*</label>
                            <select id="" name="role">
                                <option value="">Choose Role</option>
                                <?php if (($admin_role == 0) || ($admin_role == 1)) {
                                    ?>
                                    <option value="1">Administrator</option>
                                    <?php 
                                }
                                
                                $select_role = "SELECT * FROM hc_roles WHERE id != 1 ORDER BY id ASC";
                                $sql_role = mysqli_query($db, $select_role);
                                while($row_role = mysqli_fetch_assoc($sql_role)) {
                                    $role_id    = $row_role['id'];
                                    $role       = $row_role['name'];
                                    ?>
                                    <option value="<?= $role_id ?>"><?= $role ?></option>
                                    <?php 
                                }?>
                            </select>
                        </div>
    
                        <div>
                            <label for="">Status</label>
                            <select id="" name="status">
                                <option value="0">Choose Status</option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>
                            </select>
                        </div>
    
                        <button type="submit" name="add" class="grid_col_3">Add User</button>
                    </form>
                </div>
            </div>
        </div>
        <?php 
    } else {
        ?>
        <!-- welcome message -->
        <div class="ep_section">
            <!--========== MANAGE COURSE ==========-->
            <div class="ep_container mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Manage Users</h5>
                    <?php if (($admin_role == 0) || ($admin_role == 1) || ($admin_role == 2)) {
                        ?>
                        <div class="btn_grp">
                            <a href="../user/?add" class="button btn_sm">Add User</a>
                        </div>
                        <?php 
                    }?>
                </div>
            </div>
            
            <div class="ep_container user_widgets">
                <?php $select = "SELECT * FROM admin WHERE id != 1 AND is_delete = 0 ORDER BY role ASC";
                $sql = mysqli_query($db, $select);
                $num = mysqli_num_rows($sql);
                if ($num > 0) {
                    $si = 0;
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $user_id        = $row['id'];
                        $user_name      = $row['name'];
                        $user_username  = $row['username'];
                        $user_phone     = $row['phone'];
                        $user_role      = $row['role'];
                        $user_status    = $row['status'];
                        $user_profile   = $row['profile'];
                        $user_join_date = $row['join_date'];
                        $si++;
                        
                        $user_join_date = date('d M Y h:i:s a', strtotime($user_join_date));
                        ?>
                        <!-- user -->
                        <div class="ep_grid user_card ep_card">
                            <div class="user_card_content">
                                <?php if ($user_profile == '') {
                                    ?>
                                    <img src="../assets/img/admin.png" alt="" class="user_card_img">
                                    <?php 
                                } else {
                                    ?>
                                    <img src="<?php echo $user_profile; ?>" alt="" class="user_card_img">
                                    <?php 
                                }?>
                            </div>
                            <div class="user_card_data">
                                <h5 class="user_card_title"><?php echo $user_name; ?></h5>
                                
                                <div class="btn_grp ep_center mb_75">
                                    <?php // user role
                                    if ($user_role == 1) {
                                        echo '<div class="user_badge">Administrator</div>';
                                    } elseif ($user_role == 2) {
                                        echo '<div class="user_badge user_badge_moderator">Moderator</div>';
                                    } elseif ($user_role == 3) {
                                        echo '<div class="user_badge user_badge_author">Author</div>';
                                    } elseif ($user_role == 4) {
                                        echo '<div class="user_badge user_badge_accountant">Accountant</div>';
                                    } elseif ($user_role == 5) {
                                        echo '<div class="user_badge user_badge_assistant">Assistant</div>';
                                    } elseif ($user_role == 6) {
                                        echo '<div class="user_badge user_badge_graphic">Graphic & Video</div>';
                                    } elseif ($user_role == 7) {
                                        echo '<div class="user_badge user_badge_coo">COO</div>';
                                    } elseif ($user_role == 8) {
                                        echo '<div class="user_badge user_badge_ambassador">Ambassador</div>';
                                    }
                                    
                                    // account activity
                                    if ($user_status == 1) {
                                        echo '<div class="user_badge user_badge_active">Active</div>';
                                    } else {
                                        echo '<div class="user_badge user_badge_deactive">Deactive</div>';
                                    }?>
                                </div>
                                
                                <div class="btn_grp ep_center">
                                    <!-- DELETE MODAL BUTTON -->
                                    <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#view<?php echo $user_id; ?>"><i class='bx bx-detail' ></i></button>
                                    
                                    <?php if (($admin_role == 0) || ($admin_role == 1) || ($admin_role == 2)) {
                                        if (($user_id != 1) && ($user_id != 2) && ($user_id != $admin_id)) {
                                            ?>
                                            <!-- EDIT BUTTON -->
                                            <a href="../user-edit/?user=<?php echo $user_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>
                                            
                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $user_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                            <?php 
                                        }
                                    }?>
                                </div>
                                
                                <!-- view MODAL -->
                                <div class="modal fade" id="view<?php echo $user_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">View User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="ep_flex mb_75">Name: <strong><?php echo $user_name; ?></strong></div>
                                                <div class="ep_flex mb_75">Username: <strong><?php echo $user_username; ?></strong></div>
                                                <div class="ep_flex mb_75">Phone: <strong><?php echo $user_phone; ?></strong></div>
                                                <div class="ep_flex mb_75">
                                                    Role: 
                                                    <strong><?php // user role 
                                                    if ($user_role == 0) {
                                                        echo '<div class="user_badge user_badge_developer">Developer</div>';
                                                    } elseif ($user_role == 1) {
                                                        echo '<div class="user_badge">Administrator</div>';
                                                    } elseif ($user_role == 2) {
                                                        echo '<div class="user_badge user_badge_moderator">Moderator</div>';
                                                    } elseif ($user_role == 3) {
                                                        echo '<div class="user_badge user_badge_author">Author</div>';
                                                    } elseif ($user_role == 4) {
                                                        echo '<div class="user_badge user_badge_accountant">Accountant</div>';
                                                    } elseif ($user_role == 5) {
                                                        echo '<div class="user_badge user_badge_assistant">Assistant</div>';
                                                    } elseif ($user_role == 6) {
                                                        echo '<div class="user_badge user_badge_graphic">Graphic & Video</div>';
                                                    } elseif ($user_role == 7) {
                                                        echo '<div class="user_badge user_badge_coo">COO</div>';
                                                    } elseif ($user_role == 8) {
                                                        echo '<div class="user_badge user_badge_ambassador">Ambassador</div>';
                                                    }?></strong>
                                                </div>
                                                <div class="ep_flex mb_75">
                                                    Status: 
                                                    <strong><?php // account activity
                                                    if ($user_status == 1) {
                                                        echo '<div class="user_badge user_badge_active">Active</div>';
                                                    } else {
                                                        echo '<div class="user_badge user_badge_deactive">Deactive</div>';
                                                    }?></strong>
                                                </div>
                                                <div class="ep_flex mb_75">Join Date: <strong><?php echo $user_join_date; ?></strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- DELETE MODAL -->
                                <div class="modal fade" id="delete<?php echo $user_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $user_name; ?></span>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                <form action="" method="post">
                                                    <input type="hidden" name="delete_id" id="" value="<?php echo $user_id; ?>">
                                                    <button type="submit" name="delete" class="button bg_danger text_danger text_semi">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                    }
                }?>
            </div>
        </div>
        <?php 
    }?>
</main>

<?php include('../assets/includes/footer.php'); ?>