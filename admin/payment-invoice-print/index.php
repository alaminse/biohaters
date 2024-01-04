<?php include('../db/db.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=========== GOOGLE FONT ===========-->
    <link rel="preload" href="../assets/LiSubhaLetterpressUnicode.woff2" as="font" type="font/woff2" crossorigin>

    <!--=========== JQUERY ===========-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    
    <!--=========== FAV ICON ===========-->
    <link rel="shortcut icon" type="image/png" href="../assets/img/logo.png">

    <!--=========== KALPURUSH FONT ===========-->
    <!-- <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet"> -->

    <!--=========== SOLAIMANLIPI FONT ===========-->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    <!--=========== STYLE CSS ===========-->
    <style type="text/css">
        * {margin: 0; padding: 0;}
        
        @font-face {
            font-family: 'Li Subha Letterpress Unicode';
            src: url('../assets/LiSubhaLetterpressUnicode.woff2') format('woff2'),
                url('../assets/LiSubhaLetterpressUnicode.woff') format('woff');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
        
        body {
            max-width: 800px;
            margin: 0 auto;
            overflow-x: hidden;
            position: relative;
            font-family: 'Li Subha Letterpress Unicode' !important;
            font-weight: normal;
            font-style: normal;
        }
        
        img {
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 10;
        }
        
        div {
            position: absolute;
            top: 0;
            z-index: 100;
            font-size: 20px;
            opacity: .7;
        }
        
        /*office copy*/
        .invoice_no {
            top: 168px;
            left: 160px;
        }
        
        .invoice_date {
            top: 165px;
            right: 85px;
        }
        
        .name {
            top: 195px;
            left: 190px;
        }
        
        .item_name {
            top: 225px;
            left: 200px;
        }
        
        .roll {
            top: 225px;
            left: 675px;
        }
        
        .method {
            top: 255px;
            left: 210px;
        }
        
        .phone {
            top: 255px;
            left: 510px;
        }
        
        .due {
            top: 285px;
            left: 600px;
        }
        
        .installment {
            top: 285px;
            left: 260px;
        }
        
        .paid_word {
            top: 315px;
            left: 340px;
        }
        
        .paid {
            top: 320px;
            left: 138px;
        }
        
        /*student copy*/
        /*.invoice_no_student {*/
        /*    top: 745px;*/
        /*    left: 164px;*/
        /*}*/
        
        /*.invoice_date_student {*/
        /*    top: 745px;*/
        /*    right: 85px;*/
        /*}*/
        
        /*.name_student {*/
        /*    top: 775px;*/
        /*    left: 190px;*/
        /*}*/
        
        /*.item_name_student {*/
        /*    top: 805px;*/
        /*    left: 200px;*/
        /*}*/
        
        /*.roll_student {*/
        /*    top: 805px;*/
        /*    left: 520px;*/
        /*}*/
        
        /*.method_student {*/
        /*    top: 835px;*/
        /*    left: 210px;*/
        /*}*/
        
        /*.phone_student {*/
        /*    top: 835px;*/
        /*    left: 510px;*/
        /*}*/
        
        /*.due_student {*/
        /*    top: 865px;*/
        /*    left: 600px;*/
        /*}*/
        
        /*.installment_student {*/
        /*    top: 865px;*/
        /*    left: 260px;*/
        /*}*/
        
        /*.paid_word_student {*/
        /*    top: 895px;*/
        /*    left: 220px;*/
        /*}*/
        
        /*.paid_student {*/
        /*    top: 938px;*/
        /*    left: 120px;*/
        /*}*/
    </style>

    <title>BH - Admin</title>
</head>
<body>
    
<?php if (isset($_GET['payment']) && $_GET['payment'] != '') { 
    $purchase_id = $_GET['payment'];
    
    $select = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$purchase_id' GROUP BY purchase_id";
    $sql = mysqli_query($db, $select);
    $num = mysqli_num_rows($sql);
    if ($num > 0) {
        $si = 0;
        while ($row = mysqli_fetch_assoc($sql)) {
            $student_id = $row['student_id'];
            
            // fetch student data
            $select_student = "SELECT * FROM hc_student WHERE id = '$student_id'";
            $sql_student = mysqli_query($db, $select_student);
            $num_student = mysqli_num_rows($sql_student);
            if ($num_student > 0) {
                while ($row_student = mysqli_fetch_assoc($sql_student)) {
                    $name       = $row_student['name'];
                    $roll       = $row_student['roll'];
                    $phone      = $row_student['phone'];
                    $email      = $row_student['email'];
                    $profile    = $row_student['profile'];
                }
            }
            
            // fetch purchased items
            $select_items = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$purchase_id' GROUP BY item_id";
            $sql_items = mysqli_query($db, $select_items);
            $num_items = mysqli_num_rows($sql_items);
            if ($num_items > 0) {
                while ($row_items = mysqli_fetch_assoc($sql_items)) {
                    $purchase_item  = $row_items['purchase_item'];
                    $item_id        = $row_items['item_id'];
                    
                    // fetch payment date
                    $select_payment_date = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$purchase_id' AND item_id = '$item_id'";
                    $sql_payment_date = mysqli_query($db, $select_payment_date);
                    $row_payment_date = mysqli_fetch_assoc($sql_payment_date);
                    $payment_date   = $row_payment_date['payment_time'];
                    
                    $payment_date = date('d M, Y', strtotime($payment_date));
                    
                    if ($purchase_item == 1) {
                        // fetch item details
                        $select_item_details = "SELECT * FROM hc_course WHERE id = '$item_id'";
                        $sql_item_details = mysqli_query($db, $select_item_details);
                        $num_item_details = mysqli_num_rows($sql_item_details);
                        if ($num_item_details > 0) {
                            while ($row_item_details = mysqli_fetch_assoc($sql_item_details)) {
                                $item_name  = $row_item_details['name'];
                            }
                        }
                    } elseif ($purchase_item == 2) {
                        // fetch item details
                        $select_item_details = "SELECT * FROM hc_chapter WHERE id = '$item_id'";
                        $sql_item_details = mysqli_query($db, $select_item_details);
                        $num_item_details = mysqli_num_rows($sql_item_details);
                        if ($num_item_details > 0) {
                            while ($row_item_details = mysqli_fetch_assoc($sql_item_details)) {
                                $item_name  = $row_item_details['chapter'];
                            }
                        }
                    }
                }
            }
            
            // fetch batch
            $select_batch = "SELECT * FROM hc_batch_student WHERE purchase_id = '$purchase_id'";
            $sql_batch = mysqli_query($db, $select_batch);
            $num_batch = mysqli_num_rows($sql_batch);
            if ($num_batch > 0) {
                while ($row_batch = mysqli_fetch_assoc($sql_batch)) {
                    $batch_id   = $row_batch['batch'];
                    
                    // fetch batch name
                    $select_batch_name = "SELECT * FROM hc_course_batch WHERE id = '$batch_id'";
                    $sql_batch_name = mysqli_query($db, $select_batch_name);
                    $num_batch_name = mysqli_num_rows($sql_batch_name);
                    if ($num_batch_name > 0) {
                        while ($row_batch_name = mysqli_fetch_assoc($sql_batch_name)) {
                            $batch_name   = $row_batch_name['name'];
                            $batch_time   = $row_batch_name['start_time'];
                            
                            $batch_time = date('h:i a', strtotime($batch_time));
                        }
                    }
                }
            }
            
            // initialize paid amount
            $total_paid = 0;
            
            // fetch paid amount
            $select_amount = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$purchase_id'";
            $sql_amount = mysqli_query($db, $select_amount);
            $num_amount = mysqli_num_rows($sql_amount);
            if ($num_amount > 0) {
                while ($row_amount = mysqli_fetch_assoc($sql_amount)) {
                    $paid   = $row_amount['paid_amount'];
                    
                    $total_paid += $paid;
                }
            }
            
            // fetch total amount & last payment date & last payment amount
            $select_last_payment = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$purchase_id' ORDER BY id DESC LIMIT 1";
            $sql_last_payment = mysqli_query($db, $select_last_payment);
            $num_last_payment = mysqli_num_rows($sql_last_payment);
            if ($num_last_payment > 0) {
                while ($row_last_payment = mysqli_fetch_assoc($sql_last_payment)) {
                    $total_amount   = $row_last_payment['price'];
                    $paid_amount    = $row_last_payment['paid_amount'];
                    $last_payment   = $row_last_payment['payment_time'];
                    
                    // last payment date convert to text
                    $last_payment_txt = date('d M, Y', strtotime($last_payment));
                }
            }
            
            // function numberToWords($number) {
            //     $ones = array(
            //         1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            //         6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine'
            //     );
                
            //     $tens = array(
            //         10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen',
            //         15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen',
            //         20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            //         70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
            //     );
                
            //     $thousands = array(
            //         1 => 'Thousand', 2 => 'Million', 3 => 'Billion', 4 => 'Trillion'
            //     );
            
            //     if ($number == 0) {
            //         return 'Zero';
            //     }
            
            //     $words = '';
            
            //     foreach ($thousands as $idx => $unit) {
            //         $current = floor($number / pow(1000, $idx));
            //         if ($current > 0) {
            //             if ($words !== '') {
            //                 $words = ' ' . $words; // Add space between words
            //             }
            //             $words = convertThreeDigitNumber($current, $ones, $tens) . ' ' . $unit . $words;
            //         }
            //     }
            
            //     return $words;
            // }
            
            // function convertThreeDigitNumber($number, $ones, $tens) {
            //     $words = '';
            
            //     $hundreds = floor($number / 100);
            //     $remainder = $number % 100;
            
            //     if ($hundreds > 0) {
            //         $words .= $ones[$hundreds] . ' Hundred';
            //         if ($remainder > 0) {
            //             $words .= ' and ';
            //         }
            //     }
            
            //     if ($remainder > 0) {
            //         if ($remainder < 10) {
            //             $words .= $ones[$remainder];
            //         } elseif ($remainder < 20) {
            //             $words .= $tens[$remainder];
            //         } else {
            //             $words .= $tens[floor($remainder / 10) * 10];
            //             if ($remainder % 10 > 0) {
            //                 $words .= ' ' . $ones[$remainder % 10];
            //             }
            //         }
            //     }
            
            //     return $words;
            // }
            
            function numberToWords($number) {
                $ones = array(
                    1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
                    6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine'
                );
            
                $tens = array(
                    10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen',
                    15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen',
                    20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
                    70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
                );
            
                if ($number == 0) {
                    return 'Zero';
                }
            
                if (isset($ones[$number])) {
                    return $ones[$number];
                }
            
                if (isset($tens[$number])) {
                    return $tens[$number];
                }
            
                $words = '';
            
                $thousands = floor($number / 1000);
                $remainder = $number % 1000;
            
                if ($thousands > 0) {
                    $words .= convertThreeDigitNumber($thousands, $ones, $tens) . ' Thousand';
                    if ($remainder > 0) {
                        $words .= ' ';
                    }
                }
            
                if ($remainder > 0) {
                    $words .= convertThreeDigitNumber($remainder, $ones, $tens);
                }
            
                return $words;
            }
            
            function convertThreeDigitNumber($number, $ones, $tens) {
                $words = '';
            
                $hundreds = floor($number / 100);
                $remainder = $number % 100;
            
                if ($hundreds > 0) {
                    $words .= $ones[$hundreds] . ' Hundred';
                    if ($remainder > 0) {
                        $words .= ' and ';
                    }
                }
            
                if ($remainder > 0) {
                    if ($remainder < 10) {
                        $words .= $ones[$remainder];
                    } else {
                        $tensDigit = floor($remainder / 10) * 10;
                        $onesDigit = $remainder % 10;
                        $words .= $tens[$tensDigit];
                        if ($onesDigit > 0) {
                            $words .= ' ' . $ones[$onesDigit];
                        }
                    }
                }
            
                return $words;
            }
            
            $amountInWords = numberToWords($paid_amount);
            
            // fetch admitted date
            $select_admitted = "SELECT * FROM hc_purchase WHERE id = '$purchase_id'";
            $sql_admitted = mysqli_query($db, $select_admitted);
            $num_admitted = mysqli_num_rows($sql_admitted);
            if ($num_admitted > 0) {
                while ($row_admitted = mysqli_fetch_assoc($sql_admitted)) {
                    $admit_date = $row_admitted['purchase_date'];
                    
                    // admit date convert to text
                    $admit_date_txt = date('d M, Y', strtotime($admit_date));
                }
            }
            
            // calculate due
            $due = $total_amount - $total_paid;

            // fetch payment method
            $select_method = "SELECT * FROM hc_transaction WHERE reference = '$purchase_id' ORDER BY id DESC LIMIT 1";
            $sql_method = mysqli_query($db, $select_method);
            $num_method = mysqli_num_rows($sql_method);
            if ($num_method > 0) {
                while ($row_method = mysqli_fetch_assoc($sql_method)) {
                    $method = $row_method['method'];
                }
            }
            
            // fetch next installment date
            $select_installment = "SELECT * FROM hc_due WHERE purchase_id = '$purchase_id' ORDER BY id DESC LIMIT 1";
            $sql_installment = mysqli_query($db, $select_installment);
            $num_installment = mysqli_num_rows($sql_installment);
            if ($num_installment > 0) {
                while ($row_installment = mysqli_fetch_assoc($sql_installment)) {
                    $installment = $row_installment['due_date'];
                    
                    // installment date convert to text
                    $installment_date_txt = date('d M, Y', strtotime($installment));
                }
            }
        }
    }?>
    <img src="../assets/img/money.jpg" alt="">
    
    <!--office copy-->
    <div class="invoice_no">#<?= $purchase_id ?></div>
    <div class="invoice_date"><?= $payment_date ?></div>
    <div class="name"><?= $name ?></div>
    <div class="item_name"><?= $item_name ?><?php if (isset($batch_time)) { echo ' (Batch: ' . $batch_time . ')'; }?></div>
    <div class="roll"><?= $roll ?></div>
    <div class="method"><?= $method ?></div>
    <div class="phone"><?= $phone ?></div>
    <div class="installment"><?= $installment_date_txt ?></div>
    <div class="due"><?= $due ?>/- BDT</div>
    <div class="paid_word"><?= $amountInWords ?> Taka Only</div>
    <div class="paid"><?= $paid_amount ?>/- BDT</div>
    
    <!--student copy-->
    <!--<div class="invoice_no_student">#<?= $purchase_id ?></div>-->
    <!--<div class="invoice_date_student"><?= $payment_date ?></div>-->
    <!--<div class="name_student"><?= $name ?></div>-->
    <!--<div class="item_name_student"><?= $item_name ?></div>-->
    <!--<div class="roll_student"><?= $roll ?></div>-->
    <!--<div class="method_student"><?= $method ?></div>-->
    <!--<div class="phone_student"><?= $phone ?></div>-->
    <!--<div class="installment_student"><?= $installment_date_txt ?></div>-->
    <!--<div class="due_student"><?= $due ?>/- BDT</div>-->
    <!--<div class="paid_word_student"><?= $amountInWords ?> Taka Only</div>-->
    <!--<div class="paid_student"><?= $paid_amount ?>/- BDT</div>-->
    <?php 
} else {
    ?>
    <script type="text/javascript">window.location.href = '../payment-list/';</script>
    <?php 
}?>

<script>
    window.print();
</script>
</body>
</html>