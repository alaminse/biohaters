<?php include('../assets/includes/header.php'); ?>

<?php 
  include 'execute.php';

  if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        $result_data = execute($_GET['paymentID']);
        $response = json_decode($result_data, true);

        if (isset($response['statusCode']) && $response['statusCode'] != '0000') {
            // if (isset($_GET['bh_tokenized'])) {
            //     $bh_tokenized = $_GET['bh_tokenized'];
                
            //     // create url
            //     $url = "bh_tokenized=".$bh_tokenized;
            // }
            // Error case
            // header("Location: fail.php?statusMessage=".$response['statusMessage']."&".$url);
            header("Location: fail.php?statusMessage=".$response['statusMessage']);
            exit;
        } else {
            if (isset($_GET['bh_tokenized'])) {
                $bh_tokenized = $_GET['bh_tokenized'];
            
                // create url
                $url = "bh_tokenized=".$bh_tokenized;
            
                $payment_id = $_GET['paymentID'];
                $trx_id     = $response['trxID'];
                
                if ($payment_id != '' && $trx_id != '') {
                    // fetch tokenized list
                    $tokenized_list = "SELECT * FROM hc_purchase_token WHERE payment_token = '$bh_tokenized'";
                    $sql_tokenized_list = mysqli_query($db, $tokenized_list);
                    $num_tokenized_list = mysqli_num_rows($sql_tokenized_list);
                    if ($num_tokenized_list > 0) {
                        while ($row_tokenized_list = mysqli_fetch_assoc($sql_tokenized_list)) {
                            $tokenized_id = $row_tokenized_list['id'];
                
                            // update tokenized list
                            $update_tokenized_list = "UPDATE hc_purchase_token SET payment_id = '$payment_id', trx_id = '$trx_id' WHERE id = '$tokenized_id'";
                            mysqli_query($db, $update_tokenized_list);
                        }
                        ?>
                        <script type="text/javascript">
                            window.location.href = 'https://biohaters.com/purchase/success.php?<?= $url ?>';
                        </script>
                        <?php 
                        exit;
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
                                    <a href="https://biohaters.com/" class="button no_hover btn_sm m_auto">Go Back</a>
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
                                <a href="https://biohaters.com/" class="button no_hover btn_sm m_auto">Go Back</a>
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
                            <a href="https://biohaters.com/" class="button no_hover btn_sm m_auto">Go Back</a>
                        </div>
                    </div>
                </div>
                <?php 
            }
        }
    } else {
        // if (isset($_GET['bh_tokenized'])) {
        //     $bh_tokenized = $_GET['bh_tokenized'];
            
        //     // create url
        //     $url = "bh_tokenized=".$bh_tokenized;
        // }
        // header("Location: fail.php?".$url);
        header("Location: fail.php");
        exit;
    }
}?>

<?php include('../assets/includes/footer.php'); ?>