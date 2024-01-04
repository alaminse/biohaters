<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_POST['checkout'])) {
    if ($login_validity == 1) {
        $name   = mysqli_escape_string($db, $_POST['student_name']);
        $email  = mysqli_escape_string($db, $_POST['student_email']);
        $phone  = mysqli_escape_string($db, $_POST['student_phone']);
    } else {
        $name   = mysqli_escape_string($db, $_POST['name']);
        $email  = mysqli_escape_string($db, $_POST['email']);
        $phone  = mysqli_escape_string($db, $_POST['phone']);
    }

    $purchase_date = date('Y-m-d H:i:s', time());

    // expired date
    $total_time = time() + 2592000;
    $expired_date = date('Y-m-d H:i:s', $total_time);
    
    if (empty($name) || empty($email) || empty($phone)) {
        ?>
        <div class="modal_container payment_modal show-modal" id="">
            <div class="modal_content payment_content">
                <div class="modal_body">
                    <div class="payment_icon_error text_center">
                        <i class='bx bxs-error'></i>
                    </div>

                    <p class="payment_success_subtitle text_center">Required Fields are Invalid!</p>
                </div>

                <div class="">
                    <a href="<?= $base_url ?>" class="button no_hover btn_sm m_auto">Go Back</a>
                </div>
            </div>
        </div>
        <?php 
    } else {
        $phone = str_replace('+880', '0', $phone);
        $phone = str_replace(' ', '', $phone);

        $phone_verify = substr($phone, 0, 3);
        
        if ((!preg_match("/^([0-9]{11})$/", $phone))) {
            ?>
            <div class="modal_container payment_modal show-modal" id="">
                <div class="modal_content payment_content">
                    <div class="modal_body">
                        <div class="payment_icon_error text_center">
                            <i class='bx bxs-error'></i>
                        </div>
    
                        <p class="payment_success_subtitle text_center">Phone Number is Invalid!</p>
                    </div>
    
                    <div class="">
                        <a href="<?= $base_url ?>" class="button no_hover btn_sm m_auto">Go Back</a>
                    </div>
                </div>
            </div>
            <?php 
        } else {
            if (($phone_verify == '013') || ($phone_verify == '014') || ($phone_verify == '015') || ($phone_verify == '016') || ($phone_verify == '017') || ($phone_verify == '018') || ($phone_verify == '019')) {
                $email = str_replace(' ', '', $email);

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    ?>
                    <div class="modal_container payment_modal show-modal" id="">
                        <div class="modal_content payment_content">
                            <div class="modal_body">
                                <div class="payment_icon_error text_center">
                                    <i class='bx bxs-error'></i>
                                </div>
        
                                <p class="payment_success_subtitle text_center">Email Address is Invalid!</p>
                            </div>
        
                            <div class="">
                                <a href="<?= $base_url ?>" class="button no_hover btn_sm m_auto">Go Back</a>
                            </div>
                        </div>
                    </div>
                    <?php 
                } else {
                    $array_email = explode('@', $email);
                    $extension_email = end($array_email);

                    if ($extension_email == 'gmail.com' || $extension_email == 'yahoo.com' || $extension_email == 'icloud.com' || $extension_email == 'outlook.com') {
                        // check student
                        $check_student = "SELECT * FROM hc_student WHERE email = '$email' OR phone = '$phone'";
                        $sql_check_student = mysqli_query($db, $check_student);
                        $num_check_student = mysqli_num_rows($sql_check_student);
                        if ($num_check_student == 0) {
                            do {
                                // generate roll
                                $roll = rand(1000000, 9999999);
                            
                                // check roll
                                $check_roll = "SELECT * FROM hc_student WHERE roll = '$roll'";
                                $sql_check_roll = mysqli_query($db, $check_roll);
                                $num_check_roll = mysqli_num_rows($sql_check_roll);
                            } while ($num_check_roll != 0);
                    
                            // valid student
                            $status = 0;
                            
                            // add student
                            $add_student = "INSERT INTO hc_student (name, email, phone, roll, join_date, status) VALUES ('$name', '$email', '$phone', '$roll', '$purchase_date', '$status')";
                            $sql_add_student = mysqli_query($db, $add_student);
                            
                            // get student id if added
                            $student_id = mysqli_insert_id($db);
                        } else {
                            $row_check_student = mysqli_fetch_assoc($sql_check_student);
                    
                            // get student id if fetched
                            $student_id = $row_check_student['id'];
                        }
                    
                        // check previous trial
                        $check_trial = "SELECT * FROM hc_free_trial WHERE student_id = '$student_id'";
                        $sql_check_trial = mysqli_query($db, $check_trial);
                        $num_check_trial = mysqli_num_rows($sql_check_trial);
                        if ($num_check_trial == 0) {
                            // add purchase
                            $add_purchase = "INSERT INTO hc_free_trial (student_id, purchase_date, expired_date) VALUES ('$student_id', '$purchase_date', '$expired_date')";
                            $sql_purchase = mysqli_query($db, $add_purchase);
                            if ($sql_purchase) {
                                ?>
                                <div class="modal_container payment_modal show-modal" id="payment-success">
                                    <div class="modal_content payment_content">
                                        <div class="modal_body">
                                            <div class="payment_icon_success text_center">
                                                <i class='bx bx-check' ></i>
                                            </div>
                    
                                            <p class="payment_success_subtitle text_center">Get Trial Success!</p>
                                            <p class="payment_success_title text_center">Free</p>
                                        </div>
                    
                                        <div class="">
                                            <a href="<?= $base_url ?>login/" class="button no_hover btn_sm m_auto">Login</a>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                            }
                        } else {
                            ?>
                            <div class="modal_container payment_modal show-modal" id="">
                                <div class="modal_content payment_content">
                                    <div class="modal_body">
                                        <div class="payment_icon_error text_center">
                                            <i class='bx bxs-error'></i>
                                        </div>
                
                                        <p class="payment_success_subtitle text_center">You have already done 30 Days Trial</p>
                                    </div>
                
                                    <div class="">
                                        <a href="<?= $base_url ?>" class="button no_hover btn_sm m_auto">Go Back</a>
                                    </div>
                                </div>
                            </div>
                            <?php 
                        }
                    } else {
                        ?>
                        <div class="modal_container payment_modal show-modal" id="">
                            <div class="modal_content payment_content">
                                <div class="modal_body">
                                    <div class="payment_icon_error text_center">
                                        <i class='bx bxs-error'></i>
                                    </div>
                
                                    <p class="payment_success_subtitle text_center">Email Address is Invalid!</p>
                                </div>
                
                                <div class="">
                                    <a href="<?= $base_url ?>" class="button no_hover btn_sm m_auto">Go Back</a>
                                </div>
                            </div>
                        </div>
                        <?php 
                    }
                }
            } else {
                ?>
                <div class="modal_container payment_modal show-modal" id="">
                    <div class="modal_content payment_content">
                        <div class="modal_body">
                            <div class="payment_icon_error text_center">
                                <i class='bx bxs-error'></i>
                            </div>
        
                            <p class="payment_success_subtitle text_center">Phone Number is Invalid!</p>
                        </div>
        
                        <div class="">
                            <a href="<?= $base_url ?>" class="button no_hover btn_sm m_auto">Go Back</a>
                        </div>
                    </div>
                </div>
                <?php 
            }
        }
    }
} else {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>';
    </script>
    <?php 
}?>

<?php include('../assets/includes/footer.php'); ?>