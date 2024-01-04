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

<!-- DELETE TOKEN -->
<?php if (isset($_GET['delete'])) {
    $token_delete = $_GET['delete'];

    $delete = "DELETE FROM hc_purchase_token WHERE payment_token = '$token_delete'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../tokenized-transaction/?fail';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Tokenized List</h4>
        </div>
    </div>
    
    <?php if (isset($_GET['fail'])) {
        ?>
        <!-- welcome message -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== MANAGE COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Tokenized Payment Transaction - Failed List</h5>
                    </div>
    
                    <table class="ep_table" id="datatable">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Payment ID</th>
                                <th>TRX ID</th>
                                <th>Total Amount</th>
                                <th>Payment Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select = "SELECT * FROM hc_purchase_token WHERE payment_id IS NULL AND trx_id IS NULL GROUP BY payment_token ORDER BY id ASC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                $si = 0;
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $token = $row['payment_token'];
                                    $payment_id = $row['payment_id'];
                                    $trx_id = $row['trx_id'];
                                    $total_amount = $row['total_amount'];
                                    $name = $row['name'];
                                    $email = $row['email'];
                                    $phone = $row['phone'];
                                    $token_date = $row['token_date'];
    
                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?= $si ?></td>
    
                                        <td><?= $name ?></td>
    
                                        <td><?= $email ?></td>
    
                                        <td><?= $phone ?></td>
                                        
                                        <td><?= $payment_id ?></td>
    
                                        <td><?= $trx_id ?></td>
    
                                        <td><?= $total_amount ?></td>
                                        
                                        <td><?= $token_date ?></td>
                                        
                                        <td>
                                            <div class="btn_grp">
                                                <!-- DELETE TOKEN -->
                                                <a href="../tokenized-transaction/?delete=<?= $token ?>" class="btn_icon"><i class='bx bxs-trash-alt' ></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                }
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    } else {
        ?>
        <!-- welcome message -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== MANAGE COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Tokenized Payment Transaction - Successful List</h5>
                        <div class="btn_grp">
                            <a href="../tokenized-transaction/?fail" class="button btn_sm">Failed List</a>
                        </div>
                    </div>
    
                    <table class="ep_table" id="datatable">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Payment ID</th>
                                <th>TRX ID</th>
                                <th>Total Amount</th>
                                <th>Payment Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select = "SELECT * FROM hc_purchase_token WHERE payment_id IS NOT NULL AND trx_id IS NOT NULL GROUP BY payment_token ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                $si = 0;
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $token = $row['payment_token'];
                                    $payment_id = $row['payment_id'];
                                    $trx_id = $row['trx_id'];
                                    $total_amount = $row['total_amount'];
                                    $name = $row['name'];
                                    $email = $row['email'];
                                    $phone = $row['phone'];
                                    $token_date = $row['token_date'];
    
                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?= $si ?></td>
    
                                        <td><?= $name ?></td>
    
                                        <td><?= $email ?></td>
    
                                        <td><?= $phone ?></td>
                                        
                                        <td><?= $payment_id ?></td>
    
                                        <td><?= $trx_id ?></td>
    
                                        <td><?= $total_amount ?></td>
                                        
                                        <td><?= $token_date ?></td>
                                        
                                        <td>
                                            <div class="btn_grp">
                                                <!-- EXECUTE TOKEN -->
                                                <a href="https://biohaters.com/purchase/success.php?bh_tokenized=<?= $token ?>" class="btn_icon" target="_blank"><i class='bx bx-link-external'></i></a>
                                                
                                                <!-- DELETE MODAL BUTTON -->
                                                <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#refund<?php echo $trx_id; ?>"><i class='bx bxs-share'></i></button>
                                            </div>
                                            
                                            <!-- REFUND MODAL -->
                                            <div class="modal fade" id="refund<?php echo $trx_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Refund Money</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Refund <span class ="ep_p text_semi bg_danger text_danger"><?= $total_amount ?>/-BDT</span> to <span class ="ep_p text_semi bg_danger text_danger"><?php echo $name; ?></span>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                            <form action="../refund/" method="post">
                                                                <input type="hidden" name="payment_token" id="" value="<?php echo $token; ?>">
                                                                <input type="hidden" name="paymentID" id="" value="<?php echo $payment_id; ?>">
                                                                <input type="hidden" name="trxID" id="" value="<?php echo $trx_id; ?>">
                                                                <input type="hidden" name="amount" id="" value="<?php echo $total_amount; ?>">
                                                                <button type="submit" name="refund" class="button bg_danger text_danger text_semi">Refund</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                }
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php 
    }?>
</main>

<!--=========== DATATABLE ===========-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>

<script>
/*========= DATATABLE CUSTOM =========*/
$(document).ready( function () {
    $('#datatable').DataTable( {
        dom: 'Bfrtip',
        // order: [[0, 'desc']],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>

<?php include('../assets/includes/footer.php'); ?>