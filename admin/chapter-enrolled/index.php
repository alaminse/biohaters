<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 3 || $admin_role == 6 || $admin_role == 7 || $admin_role == 8) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Chapter Enrolled</h4>
        </div>
    </div>
    
    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE COURSE ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Payment of Chapter</h5>
                </div>

                <table class="ep_table" id="datatable">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Chapter</th>
                            <th>Amount</th>
                            <th>Payment Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_purchase_token WHERE payment_id IS NOT NULL AND trx_id IS NOT NULL AND purchase_item = 2 ORDER BY token_date ASC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num > 0) {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $token = $row['payment_token'];
                                $payment_id = $row['payment_id'];
                                $trx_id = $row['trx_id'];
                                $price = $row['price'];
                                $item_id = $row['item_id'];
                                $name = $row['name'];
                                $email = $row['email'];
                                $phone = $row['phone'];
                                $token_date = $row['token_date'];
                                
                                $select_chapter = "SELECT * FROM hc_chapter WHERE id = '$item_id'";
                                $sql_chapter = mysqli_query($db, $select_chapter);
                                $num_chapter = mysqli_num_rows($sql_chapter);
                                if ($num_chapter > 0) {
                                    $row_chapter = mysqli_fetch_assoc($sql_chapter);
                                    $item_name = $row_chapter['chapter'];
                                }

                                $si++;
                                ?>
                                <tr>
                                    <td><?= $si ?></td>

                                    <td><?= $name ?></td>

                                    <td><?= $email ?></td>

                                    <td><?= $phone ?></td>
                                    
                                    <td><?= $item_name ?></td>

                                    <td><?= $price ?></td>
                                    
                                    <td><?= $token_date ?></td>
                                </tr>
                                <?php 
                            }
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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