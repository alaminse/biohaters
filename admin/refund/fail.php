<?php include('../assets/includes/header.php'); ?>

<div class="modal_container payment_modal show-modal" id="">
    <div class="modal_content payment_content">
        <div class="modal_body">
            <div class="payment_icon_error text_center">
                <i class='bx bxs-error'></i>
            </div>
            
            <?php // if (isset($_GET['bh_tokenized'])) {
                // $bh_tokenized = $_GET['bh_tokenized'];
                
                // // fetch tokenized list
                // $tokenized_list = "SELECT * FROM hc_purchase_token WHERE payment_token = '$bh_tokenized'";
                // $sql_tokenized_list = mysqli_query($db, $tokenized_list);
                // $num_tokenized_list = mysqli_num_rows($sql_tokenized_list);
                // if ($num_tokenized_list > 0) {
                //     while ($row_tokenized_list = mysqli_fetch_assoc($sql_tokenized_list)) {
                //         $tokenized_id = $row_tokenized_list['id'];
                        
                //         // delete unpaid token
                //         $delete_token = "DELETE FROM hc_purchase_token WHERE payment_token = '$bh_tokenized'";
                //         mysqli_query($db, $delete_token);
                //     }
                // }
            // }?>

            <p class="payment_success_subtitle text_center">
                <?php if(isset($_GET['statusMessage'])){
                    echo $_GET['statusMessage'];
                } else {
                    echo 'Payment Failed!';
                }?>
            </p>
        </div>

        <div class="">
            <a href="<?= $base_url ?>" class="button no_hover btn_sm m_auto">Go Back</a>
        </div>
    </div>
</div>

<?php include('../assets/includes/footer.php'); ?>