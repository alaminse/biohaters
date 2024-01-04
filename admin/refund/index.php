<?php include('../assets/includes/header.php'); ?>

<?php
include 'token.php'; 
$credentials_json = file_get_contents('config.json'); 
$credentials_arr = json_decode($credentials_json,true);

if (isset($_POST['refund'])) {
    $payment_token  = $_POST['payment_token'];
    $payment_id     = $_POST['paymentID'];
    $trx_id         = $_POST['trxID'];
    $amount         = $_POST['amount'];
    
    // check purchase
    $check_purchase = "SELECT * FROM hc_purchase WHERE payment_token = '$payment_token' AND payment_id = '$payment_id' AND trx_id = '$trx_id'";
    $sql_check_purchase = mysqli_query($db, $check_purchase);
    $num_check_purchase = mysqli_num_rows($sql_check_purchase);
    if ($num_check_purchase > 0) {
        while ($row_check_purchase = mysqli_fetch_assoc($sql_check_purchase)) {
            $purchase_id = $row_check_purchase['id'];
            
            // check transaction
            $check_transaction = "SELECT * FROM hc_transaction WHERE reference = '$purchase_id' AND payment_id = '$payment_id' AND trx_id = '$trx_id'";
            $sql_check_transaction = mysqli_query($db, $check_transaction);
            $num_check_transaction = mysqli_num_rows($sql_check_transaction);
            if ($num_check_transaction > 0) {
                while ($row_check_transaction = mysqli_fetch_assoc($sql_check_transaction)) {
                    $transaction_id = $row_check_transaction['id'];
                    
                    // delete transaction
                    $delete_transaction = "DELETE FROM hc_transaction WHERE id = '$transaction_id'";
                    $sql_delete_transaction = mysqli_query($db, $delete_transaction);
                }
            }
            
            // EXPIRED purchase
            $expired_purchase = "UPDATE hc_purchase SET is_expired = 1 WHERE id = '$purchase_id'";
            $sql_expired_purchase = mysqli_query($db, $expired_purchase);
        }
    }
    
    if ($sql_delete_transaction && $sql_expired_purchase) {
        function refund()
        {        
            getToken();
            global $credentials_arr;
            $post_token = array(
                'paymentID' => $_POST['paymentID'],
                'amount' => $_POST['amount'],
                'trxID' => $_POST['trxID'],
                'sku' => 'sku',
                'reason' => 'Quality issue'
            );
    
            $url = curl_init($credentials_arr['base_url']."/checkout/payment/refund");
            $post_token = json_encode($post_token);
            $header = array(
                'Content-Type:application/json',
                'Authorization:'. $_SESSION["token"],
                'X-APP-Key:'. $credentials_arr['app_key']
            );
    
            curl_setopt($url, CURLOPT_HTTPHEADER, $header);
            curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($url, CURLOPT_POSTFIELDS, $post_token);
            curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
            $result_data = curl_exec($url);
            curl_close($url);
    
            $response = json_decode($result_data, true);
    
            return $result_data;
        }
        
        echo "<div class='bg_danger text_danger text_semi'>" . refund() . "</div>";
    }
}?>

<?php include('../assets/includes/footer.php'); ?>