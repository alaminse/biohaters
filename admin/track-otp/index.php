<?php include('../assets/includes/header.php'); ?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">OTP Tracking</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE BLOG ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Manage Tracking</h5>
                </div>
            </div>
            
            <div class="mb_75">
                <form action="" method="get" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">OTP COUNT*</label>
                        <input type="text" id="" name="otp_count" placeholder="OTP Count more than 10">
                    </div>
                    
                    <button type="submit" name="get_report">Get Report</button>
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
                        <th>OTP Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_GET['get_report'])) {
                        $otp_count = $_GET['otp_count'];
                    
                        $search_data = "SELECT * FROM hc_otp_track WHERE email LIKE '%$otp_count%' OR phone LIKE '%$otp_count%' ORDER BY otp_date DESC LIMIT 20";
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
                                $otp_location       = $search['location_details'];
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
                    
                                    <td><?php echo $otp_date; ?></td>
                                </tr>
                                
                                <tr>
                                    <td colspan="8"><?php echo $otp_location; ?></td>
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