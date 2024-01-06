<?php include('../db/db.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-size: 20px;
            display: grid;
            row-gap: 6.75rem;
        }
        
        .card-footer {
            font-size: 16px;
            display: flex;
            justify-content: space-between;
        }
        
        img {
            width: 150px;
        }
    </style>
</head>
<body>
    <?php
        if (isset($_POST['selected_purchase_ids'])) {
            $selectedPurchaseIds = $_POST['selected_purchase_ids'];
            $purchaseIdsArray = explode(',', $selectedPurchaseIds);
            
            $purchaseIdList = [];
            foreach ($purchaseIdsArray as $purchaseId) {
                $purchaseIdList[] = $purchaseId;
            }

            $purchaseIdString = implode(',', $purchaseIdList);


            $select = "SELECT * FROM hc_courier WHERE purchase_id IN ($purchaseIdString) ORDER BY is_delivered ASC";

            $sql = mysqli_query($db, $select);
            $num = mysqli_num_rows($sql);
            if ($num == 0) {
                echo "<tr><td colspan='9' class='text_center'>There are no List</td></tr>";
            } else {
                $si = 0;
                while ($row = mysqli_fetch_assoc($sql)) {
                    $gift_id                = $row['id'];
                    $gift_student_id        = $row['student_id'];
                    $gift_purchase_id       = $row['purchase_id'];
                    $gift_courier_address   = $row['courier_address'];
                    $gift_update_date       = $row['update_date'];
                    $gift_is_delivered      = $row['is_delivered'];
                    $si++;

                    $gift_update_date = date('d M Y', strtotime($gift_update_date));
                    
                    // fetch student data
                    $select_student_data = "SELECT * FROM hc_student WHERE id = '$gift_student_id'";
                    $sql_student_data = mysqli_query($db, $select_student_data);
                    $num_student_data = mysqli_num_rows($sql_student_data);
                    if ($num_student_data > 0) {
                        while ($row_student_data = mysqli_fetch_assoc($sql_student_data)) {
                            $student_data_name  = $row_student_data['name'];
                            $student_data_phone = $row_student_data['phone'];
                            $student_data_roll  = $row_student_data['roll'];
                        }
                    }
                    
                    // fetch purchase data
                    $select_purchase_data = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$gift_purchase_id'";
                    $sql_purchase_data = mysqli_query($db, $select_purchase_data);
                    $num_purchase_data = mysqli_num_rows($sql_purchase_data);
                    if ($num_purchase_data > 0) {
                        while ($row_purchase_data = mysqli_fetch_assoc($sql_purchase_data)) {
                            $purchase_item_id  = $row_purchase_data['item_id'];
                            
                            // fetch course name
                            $select_course_name = "SELECT * FROM hc_course WHERE id = '$purchase_item_id'";
                            $sql_course_name = mysqli_query($db, $select_course_name);
                            $num_course_name = mysqli_num_rows($sql_course_name);
                            if ($num_course_name > 0) {
                                while ($row_course_name = mysqli_fetch_assoc($sql_course_name)) {
                                    $course_name  = $row_course_name['name'];
                                }
                            }
                        }
                    }

                    // fetch check partner
                    // $select_check_partner = "SELECT * FROM hc_purchase WHERE discount_reason = '$student_data_roll' AND is_expired = 0";
                    // $sql_check_partner = mysqli_query($db, $select_check_partner);
                    // $num_check_partner = mysqli_num_rows($sql_check_partner);
                    // if ($num_check_partner == 1) {
                    //     $gift_set = '2 Set';
                    // } else {
                    //     $gift_set = '1 Set';
                    // }
                    
                    if ($gift_is_delivered == 0) {
                        $msg = "Your Course Gift has sent to Courier. Please keep alert to recieve the gift\r-Biology Haters";
                    
                        // send OTP by sms
                        $to = "$student_data_phone";
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
                        $smsresult = curl_exec($ch);
                        
                        // update courier
                        $update = "UPDATE hc_courier SET is_delivered = 1 WHERE purchase_id = '$gift_purchase_id'";
                        $sql_update = mysqli_query($db, $update);
                    }?>
                    <div class="">
                        <div class="row px-5">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Shipping Label # <?= $gift_purchase_id ?></h5>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col-3">
                                            <address>
                                                <strong>Sender:</strong><br>
                                                Elpandora<br>
                                                Farmgate, Dhaka<br>
                                                01716598030
                                            </address>
                                        </div>
                                        <div class="col-3">
                                            <img src="../assets/img/logo.png">
                                        </div>
                                        <div class="col-6">
                                            <address>
                                                <strong>Recipient:</strong><br>
                                                <b><?= $student_data_name ?></b>
                                                <p style="height: 56px;"><?= $gift_courier_address ?></p>
                                                <?= $student_data_phone ?>
                                            </address>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class=""><?= $course_name ?></div>
                                        <div class="">QTY - 1 Set</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                }
            }
        } else {
            echo "No purchase IDs received.";
        }
    ?>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script> window.print(); </script>
</body>
</html>