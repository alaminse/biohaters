<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 3 || $admin_role == 6 || $admin_role == 7 || $admin_role == 8) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php // ADD PURCHASE
if (isset($_POST['add'])) {
    $name               = mysqli_escape_string($db, $_POST['name']);
    $email              = mysqli_escape_string($db, $_POST['email']);
    $phone              = mysqli_escape_string($db, $_POST['phone']);
    $payment_number     = mysqli_escape_string($db, $_POST['payment_number']);
    $trx_id             = mysqli_escape_string($db, $_POST['trx_id']);
    $paid_amount        = mysqli_escape_string($db, $_POST['paid_amount']);
    $method             = $_POST['method'];
    $course_id          = $_POST['course'];

    if (isset($_POST['is_discount'])) {
        $is_discount = 1;
        
        $discount_by        = mysqli_escape_string($db, $_POST['discount_by']);
        $discount_reason    = mysqli_escape_string($db, $_POST['discount_reason']);
        $discount_amount    = mysqli_escape_string($db, $_POST['discount_amount']);
    } else {
        $is_discount = 0;
        
        $discount_by        = '';
        $discount_reason    = '';
        $discount_amount    = 0;
    }

    function purchase_process($db, $admin_name, $name, $email, $phone, $payment_number, $trx_id, $discount_by, $discount_reason, $discount_amount, $paid_amount, $method, $course_id) 
    {
        $purchase_date = date('Y-m-d H:i:s', time());

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

        // admin purchase valid
        $purchase_status = 1;

        // course = 1, chapter = 2
        $purchase_item = 1;

        // as success in admin
        $payment_status = 1;

        // get course details
        $select_course  = "SELECT * FROM hc_course WHERE id = '$course_id' AND is_delete = 0";
        $sql_course     = mysqli_query($db, $select_course);
        $row_course     = mysqli_fetch_assoc($sql_course);
        $course_name    = $row_course['name'];
        $course_price   = $row_course['price'];
        $course_sale    = $row_course['sale_price'];
        $course_expired_date    = $row_course['expired_date'];
        
        // exact price
        if ($course_sale > 0) {
            $exact_price = $course_sale;
        } else {
            $exact_price = $course_price;
        }
        
        $price = $exact_price - $discount_amount;

        // expired date
        $total_time = time() + $course_expired_date;
        $expired_date = date('Y-m-d H:i:s', $total_time);

        // expired date
        $charge = 0;

        // grand total
        $grand_total = $paid_amount + $charge;

        // add purchase
        $add_purchase = "INSERT INTO hc_purchase (student_id, purchase_item, status, payment_status, method, trx_id, subtotal, charge, total_amount, discount_by, discount_reason, discount_amount, insert_by, purchase_date, expired_date) VALUES ('$student_id', '$purchase_item', '$purchase_status', '$payment_status', '$method', '$trx_id', '$paid_amount', '$charge', '$grand_total', '$discount_by', '$discount_reason', '$discount_amount', '$admin_name', '$purchase_date', '$expired_date')";
        $sql_purchase = mysqli_query($db, $add_purchase);
        
        // get student id by add purchase
        $purchase_id = mysqli_insert_id($db);

        // insert transaction
        $insert_transaction = "INSERT INTO hc_transaction (reference, method, payment_number, trx_id, amount, transaction_type, status, issued_by, issued_date) VALUES ('$purchase_id', '$method', '$payment_number', '$trx_id', '$grand_total', '1', '$payment_status', '$admin_name', '$purchase_date')";
        mysqli_query($db, $insert_transaction);

        // insert purchased items
        $insert_purchase_items = "INSERT INTO hc_purchase_details (purchase_id, purchase_item, item_id, price, paid_amount, student_id, payment_time) VALUES ('$purchase_id', '$purchase_item', '$course_id', '$price', '$paid_amount', '$student_id', '$purchase_date')";
        mysqli_query($db, $insert_purchase_items);

        // include to attendance
        if (isset($_POST['batch']) && isset($_POST['gender']) && isset($_POST['guardian_phone']) && isset($_POST['due_date'])) {
            $batch          = $_POST['batch'];
            $gender         = $_POST['gender'];
            $guardian_phone = $_POST['guardian_phone'];
            $due_date       = $_POST['due_date'];

            // insert to attendance
            $insert_attendance = "INSERT INTO hc_batch_student (purchase_id, roll, name, batch, phone, gen) VALUES ('$purchase_id', '$roll', '$name', '$batch', '$guardian_phone', '$gender')";
            mysqli_query($db, $insert_attendance);
            
            // insert to next installment
            $insert_installment = "INSERT INTO hc_due (purchase_id, due_date) VALUES ('$purchase_id', '$due_date')";
            mysqli_query($db, $insert_installment);
        }
        
        $phone = $phone;
        $msg = "You have successfully purchased " . $course_name . "\rYour Roll " . $roll . "\rYou can login our website: https://biohaters.com/login/ \r-Biology Haters\rNote: The payment is non-refundable at any circumstance";
        
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
        $smsresult = curl_exec($ch); ?>
        <script type="text/javascript">
            window.location.href = '../dashboard/';
        </script>
        <?php 
    }

    if (empty($name) || empty($email) || empty($phone) || empty($method) || empty($course_id) || $paid_amount == '') {
        ?>
        <script type="text/javascript">
            window.location.href = '../new-enroll/?alert=Required Fields';
        </script>
        <?php 
    } else {
        if ($method == 'Cash' || $method == 'Free') {
            if ($is_discount == 1) {
                if (empty($discount_by) || empty($discount_reason) || empty($discount_amount)) {
                    ?>
                    <script type="text/javascript">
                        window.location.href = '../new-enroll/?alert=Required Discount Details';
                    </script>
                    <?php 
                } else {
                    echo purchase_process($db, $admin_name, $name, $email, $phone, $payment_number, $trx_id, $discount_by, $discount_reason, $discount_amount, $paid_amount, $method, $course_id);
                }
            } else {
                echo purchase_process($db, $admin_name, $name, $email, $phone, $payment_number, $trx_id, $discount_by, $discount_reason, $discount_amount, $paid_amount, $method, $course_id);
            }
        } elseif (($method == 'Bkash') || ($method == 'Nagad') || ($method == 'Rocket') || ($method == 'Merchant')) {
            if (empty($payment_number) || empty($trx_id)) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../new-enroll/?alert=Required Payment Number and TRX ID';
                </script>
                <?php 
            } else {
                // check transaction validity
                $check_trxid        = "SELECT * FROM hc_purchase WHERE trx_id = '$trx_id'";
                $sql_check_trxid    = mysqli_query($db, $check_trxid);
                $num_check_trxid    = mysqli_num_rows($sql_check_trxid);
                if ($num_check_trxid > 0) {
                    ?>
                    <script type="text/javascript">
                        window.location.href = '../new-enroll/?alert=Repeat TRX ID Fact';
                    </script>
                    <?php 
                } else {
                    if ($is_discount == 1) {
                        if (empty($discount_by) || empty($discount_reason) || empty($discount_amount)) {
                            ?>
                            <script type="text/javascript">
                                window.location.href = '../new-enroll/?alert=Required Discount Details';
                            </script>
                            <?php 
                        } else {
                            echo purchase_process($db, $admin_name, $name, $email, $phone, $payment_number, $trx_id, $discount_by, $discount_reason, $discount_amount, $paid_amount, $method, $course_id);
                        }
                    } else {
                        echo purchase_process($db, $admin_name, $name, $email, $phone, $payment_number, $trx_id, $discount_by, $discount_reason, $discount_amount, $paid_amount, $method, $course_id);
                    }
                }
            }
        }
    }
}?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">New Enrollment</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== ADD COURSE CATEGORY ==========-->
            <div class="add_category">
                <?php if (isset($_GET['alert'])) {
                    echo "<p class='danger mb_75'>" . $_GET['alert'] . ".....</p>";
                }?>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div class="grid_col_3 mt_75">
                        <h5>Student Details</h5>
                    </div>

                    <div>
                        <label for="">Name*</label>
                        <input type="text" id="" name="name" placeholder="Name">
                    </div>

                    <div>
                        <label for="">Email Address*</label>
                        <input type="text" id="" name="email" placeholder="Email Address">
                    </div>

                    <div>
                        <label for="">Phone Number*</label>
                        <input type="text" id="" name="phone" placeholder="Phone Number">
                    </div>
                    
                    <div>
                        <label for="">Course*</label>
                        <select id="course-select" name="course">
                            <option value="">Choose Course</option>
                            <?php $select_course  = "SELECT * FROM hc_course WHERE status = 1 AND is_delete = 0 ORDER BY id DESC";
                            $sql_course     = mysqli_query($db, $select_course);
                            $num_course     = mysqli_num_rows($sql_course);
                            if ($num_course > 0) {
                                while ($row_course     = mysqli_fetch_assoc($sql_course)) {
                                    $course_id    = $row_course['id'];
                                    $course_name    = $row_course['name'];
                                    echo '<option value="' . $course_id . '">' . $course_name . '</option>';
                                }
                            }?>
                        </select>
                    </div>

                    <div class="grid_col_3 double_col_form" id="batchContainer"></div>

                    <div class="grid_col_3 mt_2">
                        <h5>Transaction Details</h5>
                    </div>

                    <div>
                        <label for="">Payment Method*</label>
                        <select id="" name="method">
                            <option value="">Choose Category</option>
                            <option value="Free">Free</option>
                            <option value="Cash">Cash</option>
                            <option value="Bkash">Bkash</option>
                            <option value="Nagad">Nagad</option>
                            <option value="Rocket">Rocket</option>
                            <option value="Merchant">Merchant</option>
                        </select>
                    </div>

                    <div>
                        <label for="">Payment Number</label>
                        <input type="text" id="" name="payment_number" placeholder="Payment Number">
                    </div>

                    <div>
                        <label for="">Transaction ID</label>
                        <input type="text" id="" name="trx_id" placeholder="Transaction ID">
                    </div>

                    <div class="grid_col_3 mt_2">
                        <h5>Payment Details</h5>
                    </div>

                    <div class="grid_col_3">
                        <label for="discount">Discount?</label>
                        <label for="discount" class="checkbox_label">
                            No 
                            <input type="checkbox" class="checkbox" name="is_discount" id="discount">
                            <span class="checked"></span>
                            Yes
                        </label>
                    </div>

                    <div class="grid_col_3 double_col_form" id="resultContainer"></div>

                    <div>
                        <label for="">Paid Amount*</label>
                        <input type="text" id="" name="paid_amount" placeholder="Paid Amount">
                    </div>

                    <button type="submit" name="add" class="grid_col_3">Add Student</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
// select batch
$(document).ready(function() {
    // When the select input value changes
    $('#course-select').on('change', function() {
        const selectedOption = $(this).val();
        if (selectedOption !== "") {
            // Call the PHP script via AJAX
            $.ajax({
                url: 'offline-batch.php', // Path to your PHP script
                method: 'POST',
                data: { selectedOption: selectedOption }, // Sending selected option to the PHP script
                success: function(response) {
                    // Display the response from PHP in the resultContainer div
                    $('#batchContainer').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error: ', error);
                }
            });
        } else {
            // If no option is selected, clear the resultContainer div
            $('#batchContainer').html('');
        }
    });
});

// discount switch
$(document).ready(function() {
    // When the checkbox state changes
    $('#discount').on('change', function() {
        if (this.checked) {
            // Call the PHP script via AJAX
            $.ajax({
                url: 'process.php', // Path to your PHP script
                method: 'POST',
                data: { checkboxStatus: 1 }, // Sending checkbox status to the PHP script
                success: function(response) {
                    // Display the response from PHP in the resultContainer div
                    $('#resultContainer').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error: ', error);
                }
            });
        } else {
            // If the checkbox is unchecked, clear the resultContainer div
            $('#resultContainer').html('');
        }
    });
});
</script>

<?php include('../assets/includes/footer.php'); ?>