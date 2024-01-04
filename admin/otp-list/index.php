<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 3 || $admin_role == 6 || $admin_role == 7 || $admin_role == 8) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<!-- GIVE ONE MORE OTP CHANCE -->
<?php if (isset($_POST['chance'])) {
    $chance_id = mysqli_escape_string($db, $_POST['chance_id']);

    $chance = "UPDATE hc_login_otp SET otp_count = 1 WHERE id = '$chance_id'";
    $sql_chance = mysqli_query($db, $chance);
    if ($sql_chance) {
        ?>
        <script type="text/javascript">
            window.location.href = '../otp-list/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">OTP List</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <div class="ep_flex mb_75">
                <h5 class="box_title">Duplicate OTP List</h5>
                
                <div class="btn_grp">
                    <a href="../track-otp/" class="button btn_sm">Track OTP</a>
                </div>
            </div>
        </div>
    </div>

    <!-- profile photo setting -->
    <div class="ep_section">
        <div class="ep_container">
            <?php // check double otp
            $select = "SELECT *, COUNT(id) as otp_row FROM hc_login_otp GROUP BY student_id ORDER BY student_id ASC";
            $sql = mysqli_query($db, $select);
            $num = mysqli_num_rows($sql);
            if ($num > 0) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    $student_id = $row['student_id'];
                    $otp_row    = $row['otp_row'];
                    if ($otp_row > 1) {
                        ?>
                        <p>Student ID - <?= $student_id ?> || OTP ROW - <?= $otp_row ?></p>
                        <?php $select_otp = "SELECT * FROM hc_login_otp WHERE student_id = '$student_id'";
                        $sql_otp = mysqli_query($db, $select_otp);
                        while ($row_otp = mysqli_fetch_assoc($sql_otp)) {
                            $otp_id = $row_otp['id'];
                            $otp_email = $row_otp['email'];
                            $otp_phone = $row_otp['phone'];
                            $otp_date = $row_otp['otp_date'];
                            ?>
                            <p>OTP ID - <?= $otp_id ?> || OTP Email - <?= $otp_email ?> || OTP Phone - <?= $otp_phone ?> || OTP Date - <?= $otp_date ?></p>
                            <?php 
                        }
                    }
                }
            }?>
        </div>
    </div>
    
    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <div class="ep_flex mb_75">
                <h5 class="box_title">Search OTP List</h5>
            </div>
            
            <div class="mb_75">
                <form action="" method="get" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Search Student OTP Row*</label>
                        <input type="text" id="search-otp" name="search_otp" placeholder="Email Address, Phone Number">
                    </div>
                    
                    <button type="submit" name="search">Search</button>
    			</form>
            </div>
            
            <table class="ep_table">
                <thead>
                    <tr>
                        <th>SI</th>
                        <th>Name</th>
                        <th>IP Address</th>
                        <th>Device Type</th>
                        <th>Device Details</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>OTP</th>
                        <th>OTP Count</th>
                        <th>OTP Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_GET['search'])) {
                        $search_otp = $_GET['search_otp'];
                    
                        $search_data = "SELECT * FROM hc_login_otp WHERE email LIKE '%$search_otp%' OR phone LIKE '%$search_otp%'";
                        $search_sql = mysqli_query($db, $search_data);
                        $search_num = mysqli_num_rows($search_sql);
                    
                        if ($search_num > 0) {
                            $si = 0;
                            while ($search = mysqli_fetch_assoc($search_sql)) {
                                $otp_id             = $search['id'];
                                $otp_student        = $search['student_id'];
                                $otp_ip             = $search['ip_address'];
                                $otp_device         = $search['device_type'];
                                $otp_device_name    = $search['device_name'];
                                $otp_email          = $search['email'];
                                $otp_phone          = $search['phone'];
                                $otp_value          = $search['otp'];
                                $otp_count          = $search['otp_count'];
                                $otp_date           = $search['otp_date'];
                    
                                // fetch student name
                                $fetch_student = "SELECT * FROM hc_student WHERE id = '$otp_student'";
                                $sql_fetch_student = mysqli_query($db, $fetch_student);
                                $num_fetch_student = mysqli_num_rows($sql_fetch_student);
                            
                                if ($num_fetch_student > 0) {
                                    $row_student = mysqli_fetch_assoc($sql_fetch_student);
                                    $name = $row_student['name'];
                                }
                    
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                    
                                    <td><?php echo $name; ?></td>
                    
                                    <td><?php echo $otp_ip; ?></td>
                    
                                    <td><?php echo $otp_device; ?></td>
                    
                                    <td><?php echo $otp_device_name; ?></td>
                    
                                    <td><?php echo $otp_email; ?></td>
                    
                                    <td><?php echo $otp_phone; ?></td>
                    
                                    <td><?php echo $otp_value; ?></td>
                    
                                    <td><?php echo $otp_count; ?></td>
                    
                                    <td><?php echo $otp_date; ?></td>
                    
                                    <td>
                                        <div class="btn_grp">
                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#chance<?php echo $otp_id; ?>"><i class='bx bxs-lock-open' ></i></button>
                                        </div>
                    
                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="chance<?php echo $otp_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Give One Chance More || OTP ID - <?php echo $otp_id; ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to give one more otp to <span class ="ep_p text_semi bg_danger text_danger"><?php echo $name; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <form action="" method="post">
                                                            <input type="hidden" name="chance_id" id="" value="<?php echo $otp_id; ?>">
                                                            <button type="submit" name="chance" class="button bg_success text_success text_semi">Give Chance</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="11">No data in this database.....</td></tr>';
                        }
                    }?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>