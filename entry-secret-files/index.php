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

    $secret_code  = mysqli_escape_string($db, $_POST['secret_code']);

    $purchase_date = date('Y-m-d H:i:s', time());
    
    if (empty($name) || empty($email) || empty($phone) || empty($secret_code)) {
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
                        // check token validity
                        $check_token = "SELECT * FROM hc_token WHERE token = '$secret_code' AND is_delete = 0";
                        $sql_check_token = mysqli_query($db, $check_token);
                        $num_check_token = mysqli_num_rows($sql_check_token);
                        if ($num_check_token > 0) {
                            // check entry has taken
                            $check_entry = "SELECT * FROM hc_secret_file_entry WHERE token = '$secret_code' AND status = 1";
                            $sql_check_entry = mysqli_query($db, $check_entry);
                            $num_check_entry = mysqli_num_rows($sql_check_entry);
                            if ($num_check_entry == 0) {
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
                                    $roll       = $row_check_student['roll'];
                                }

                                // expired date
                                $total_time = time() + 63072000;
                                $expired_date = date('Y-m-d H:i:s', $total_time);

                                // add secret file entry
                                $add_entry = "INSERT INTO hc_secret_file_entry (student_id, roll, token, status, insert_by, purchase_date, expired_date) VALUES ('$student_id', '$roll', '$secret_code', '1', 'Self', '$purchase_date', '$expired_date')";
                                $sql_add_entry = mysqli_query($db, $add_entry);
                                if ($sql_add_entry) {
                                    $add_secret = "INSERT INTO secret_file (student_id, attempt) VALUES ('$student_id', '1');INSERT INTO secret_file (student_id, attempt) VALUES ('$student_id', '2');INSERT INTO secret_file (student_id, attempt) VALUES ('$student_id', '3')";

                                    $single_insert = explode(';', $add_secret);
                                    foreach ($single_insert as $add_secret) {
                                        $sql_add_secret = mysqli_query($db, $add_secret);
                                    }
                                    if ($sql_add_secret) {
                                        ?>
                                        <div class="modal_container payment_modal show-modal" id="payment-success">
                                            <div class="modal_content payment_content">
                                                <div class="modal_body">
                                                    <div class="payment_icon_success text_center">
                                                        <i class='bx bx-check' ></i>
                                                    </div>
                            
                                                    <p class="payment_success_subtitle text_center">Successful Entry in Secret File!</p>
                                                </div>
                            
                                                <div class="">
                                                    <a href="<?= $base_url ?>login/" class="button no_hover btn_sm m_auto">Login</a>
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
                    
                                            <p class="payment_success_subtitle text_center">This Token Has Been Taken</p>
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
                
                                        <p class="payment_success_subtitle text_center">Invalid Token</p>
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