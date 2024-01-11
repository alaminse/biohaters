<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['bh_tokenized'])) {
    $bh_tokenized = $_GET['bh_tokenized'];

    // fetch tokenized list
    $tokenized_list = "SELECT * FROM hc_purchase_token WHERE payment_token = '$bh_tokenized'";
    $sql_tokenized_list = mysqli_query($db, $tokenized_list);
    $num_tokenized_list = mysqli_num_rows($sql_tokenized_list);
    if ($num_tokenized_list > 0) {
        $i = 0;
        while ($row_tokenized_list = mysqli_fetch_assoc($sql_tokenized_list)) {
            $tokenized_id               = $row_tokenized_list['id'];
            $tokenized_payment_token    = $row_tokenized_list['payment_token'];
            $tokenized_payment_id       = $row_tokenized_list['payment_id'];
            $tokenized_trx_id           = $row_tokenized_list['trx_id'];
            $tokenized_price            = $row_tokenized_list['price'];
            $tokenized_subtotal         = $row_tokenized_list['subtotal'];
            $tokenized_charge           = $row_tokenized_list['charge'];
            $tokenized_total_amount     = $row_tokenized_list['total_amount'];
            $tokenized_name             = $row_tokenized_list['name'];
            $tokenized_email            = $row_tokenized_list['email'];
            $tokenized_phone            = $row_tokenized_list['phone'];
            $tokenized_purchase_item    = $row_tokenized_list['purchase_item'];
            $tokenized_item_id          = $row_tokenized_list['item_id'];

            $i++;

            $purchase_item['tokenized'][$i] = array(
                'id'        => $tokenized_id,
                'item_id'   => $tokenized_item_id,
                'price'     => $tokenized_price,
            );
        }

        // check dublicate tokenized payment
        $check_token = "SELECT * FROM hc_purchase WHERE payment_token = '$bh_tokenized'";
        $sql_check_token = mysqli_query($db, $check_token);
        $num_check_token = mysqli_num_rows($sql_check_token);
        if ($num_check_token == 0) {
            // purchase date
            $purchase_date = date('Y-m-d H:i:s', time());

            // check student
            $check_student = "SELECT * FROM hc_student WHERE email = '$tokenized_email' OR phone = '$tokenized_phone'";
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
                $add_student = "INSERT INTO hc_student (name, email, phone, roll, join_date, status) VALUES ('$tokenized_name', '$tokenized_email', '$tokenized_phone', '$roll', '$purchase_date', '$status')";
                $sql_add_student = mysqli_query($db, $add_student);
                
                // get student id if added
                $student_id = mysqli_insert_id($db);
            } else {
                $row_check_student = mysqli_fetch_assoc($sql_check_student);

                // get student id if fetched
                $student_id = $row_check_student['id'];
            }

            // gateway purchase valid
            $purchase_status = 1;

            // as success in gateway
            $payment_status = 1;

            // as bkash gateway
            $method = 'Merchant';

            if ($tokenized_purchase_item == 1) {
                $select_course  = "SELECT * FROM hc_course WHERE id = '$tokenized_item_id'";
                $sql_course     = mysqli_query($db, $select_course);
                $num_course     = mysqli_num_rows($sql_course);
                if ($num_course > 0) {
                    while ($row_course = mysqli_fetch_assoc($sql_course)) {
                        $course_id              = $row_course['id'];
                        $course_name            = $row_course['name'];
                        $course_expired_date    = $row_course['expired_date'];
                    }
                }

                // expired date
                $total_time = time() + $course_expired_date;
                $expired_date = date('Y-m-d H:i:s', $total_time);
            } elseif ($tokenized_purchase_item == 2) {
                // expired date
                $total_time = time() + 63072000;
                $expired_date = date('Y-m-d H:i:s', $total_time);
            }

            // as bkash gateway
            $insert_by = 'Self';

            $issued_by = 'BH Web';

            // add purchase
            $add_purchase = "INSERT INTO hc_purchase (student_id, purchase_item, status, payment_status, method, payment_token, payment_id, trx_id, subtotal, charge, total_amount, insert_by, purchase_date, expired_date) VALUES ('$student_id', '$tokenized_purchase_item', '$purchase_status', '$payment_status', '$method', '$tokenized_payment_token', '$tokenized_payment_id', '$tokenized_trx_id', '$tokenized_subtotal', '$tokenized_charge', '$tokenized_total_amount', '$insert_by', '$purchase_date', '$expired_date')";
            $sql_purchase = mysqli_query($db, $add_purchase);
            
            // get student id by add purchase
            $purchase_id = mysqli_insert_id($db);

            // insert transaction
            $insert_transaction = "INSERT INTO hc_transaction (reference, method, payment_id, trx_id, amount, transaction_type, status, issued_by, issued_date) VALUES ('$purchase_id', '$method', '$tokenized_payment_id', '$tokenized_trx_id', '$tokenized_total_amount', '1', '$payment_status', '$issued_by', '$purchase_date')";
            mysqli_query($db, $insert_transaction);

            // execute insert items
            foreach ($purchase_item['tokenized'] as $key => $purchase_item_details) {
                $item_id = $purchase_item_details['item_id'];
                $price = $purchase_item_details['price'];

                // insert purchased items
                $insert_purchase_items = "INSERT INTO hc_purchase_details (purchase_id, purchase_item, item_id, price, paid_amount, student_id, payment_time) VALUES ('$purchase_id', '$tokenized_purchase_item', '$item_id', '$price', '$price', '$student_id', '$purchase_date')";
                mysqli_query($db, $insert_purchase_items);
            }
            
            $phone = $tokenized_phone;
            $msg = "You have successfully purchased " . $course_name . "\rYour TRX ID " . $tokenized_trx_id . "\r-Biology Haters";
            
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
            <div class="modal_container payment_modal show-modal" id="payment-success">
                <div class="modal_content payment_content">
                    <div class="modal_body">
                        <div class="payment_icon_success text_center">
                            <i class='bx bx-check' ></i>
                        </div>
    
                        <p class="payment_success_subtitle text_center">Payment Success!</p>
                        <p class="payment_success_title text_center">BDT <?= $tokenized_total_amount ?>.00</p>
    
                        <div class="payment_success_data">
                            <div class="ep_flex">
                                <div class="payment_success_properties">TRX ID</div>
                                <div class="payment_success_value"><?= $tokenized_trx_id ?></div>
                            </div>
    
                            <div class="ep_flex">
                                <div class="payment_success_properties">Payment Time</div>
                                <div class="payment_success_value"><?= $purchase_date ?></div>
                            </div>
    
                            <div class="ep_flex">
                                <div class="payment_success_properties">Payment Method</div>
                                <div class="payment_success_value">Bkash</div>
                            </div>
    
                            <div class="ep_flex">
                                <div class="payment_success_properties">Sender Name</div>
                                <div class="payment_success_value"><?= $tokenized_name ?></div>
                            </div>
                        </div>
    
                        <div class="payment_success_summery">
                            <div class="ep_flex">
                                <div class="payment_success_properties">Amount</div>
                                <div class="payment_success_value">BDT <?= $tokenized_subtotal ?>.00</div>
                            </div>
    
                            <div class="ep_flex">
                                <div class="payment_success_properties">Charge</div>
                                <div class="payment_success_value">BDT <?= $tokenized_charge ?>.00</div>
                            </div>
                        </div>
                    </div>
    
                    <div class="">
                        <a href="http://localhost/biohaters/login/" class="button no_hover btn_sm m_auto">Login</a>
                    </div>
                </div>
            </div>
            <?php 
        } else {
            ?>
            <div class="modal_container payment_modal show-modal" id="">
                <div class="modal_content payment_content">
                    <div class="modal_body">
                        <div class="payment_icon_error text_center">
                            <i class='bx bxs-error'></i>
                        </div>
        
                        <p class="payment_success_subtitle text_center">Payment Failed!</p>
                    </div>
        
                    <div class="">
                        <a href="http://localhost/biohaters/" class="button no_hover btn_sm m_auto">Go Back</a>
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
    
                    <p class="payment_success_subtitle text_center">Payment Failed!</p>
                </div>
    
                <div class="">
                    <a href="http://localhost/biohaters/" class="button no_hover btn_sm m_auto">Go Back</a>
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

                <p class="payment_success_subtitle text_center">Payment Failed!</p>
            </div>

            <div class="">
                <a href="http://localhost/biohaters/" class="button no_hover btn_sm m_auto">Go Back</a>
            </div>
        </div>
    </div>
    <?php 
}?>

<?php include('../assets/includes/footer.php'); ?>