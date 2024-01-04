<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['course'])) {
    $course_id = $_GET['course'];

    if (empty($course_id)) {
        ?>
        <script type="text/javascript">
            window.location.href = '../course/';
        </script>
        <?php 
    }

    // get course name
    $select_course  = "SELECT * FROM hc_course WHERE id = '$course_id' AND is_delete = 0";
    $sql_course     = mysqli_query($db, $select_course);
    $row_course     = mysqli_fetch_assoc($sql_course);
    $course_name    = $row_course['name'];
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Due Payment List</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE Due Payment ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title"><?= $course_name; ?> - Due Payment</h5>
                </div>

                <table class="ep_table" id="datatable">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Roll</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Price</th>
                            <th>Paid Amount</th>
                            <th>Due Amount</th>
                            <th>Purchase Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT *, SUM(paid_amount) as total_paid FROM hc_purchase_details WHERE purchase_item = '1' AND item_id = '$course_id' GROUP BY student_id ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num > 0) {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $course_stutdent = $row['student_id'];
                                $course_price = $row['price'];
                                $course_total_paid = $row['total_paid'];

                                // due count
                                $course_due = $course_price - $course_total_paid;

                                if ($course_due != 0) {
                                    // fetch student
                                    $select_stutdent = "SELECT * FROM hc_student WHERE id = '$course_stutdent'";
                                    $sql_stutdent = mysqli_query($db, $select_stutdent);
                                    $num_stutdent = mysqli_num_rows($sql_stutdent);
                                    if ($num_stutdent > 0) {
                                        $row_stutdent       = mysqli_fetch_assoc($sql_stutdent);
                                        $stutdent_id        = $row_stutdent['id'];
                                        $stutdent_name      = $row_stutdent['name'];
                                        $stutdent_phone     = $row_stutdent['phone'];
                                        $stutdent_roll      = $row_stutdent['roll'];
                                    }

                                    // fetch course entry
                                    $select_entry = "SELECT * FROM hc_purchase_details WHERE purchase_item = '1' AND item_id = '$course_id' AND student_id = '$stutdent_id' ORDER BY id ASC LIMIT 1";
                                    $sql_entry = mysqli_query($db, $select_entry);
                                    $num_entry = mysqli_num_rows($sql_entry);
                                    if ($num_entry > 0) {
                                        $row_entry      = mysqli_fetch_assoc($sql_entry);
                                        $purchase_date  = $row_entry['payment_time'];

                                        // purchase date convert to text
                                        $purchase_date_text = date('d M, Y', strtotime($purchase_date));
                                    }

                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?= $si ?></td>

                                        <td><?= $stutdent_roll ?></td>
                                        
                                        <td><?= $stutdent_name ?></td>

                                        <td><?= $stutdent_phone ?></td>

                                        <td><?= $course_price ?></td>

                                        <td><?= $course_total_paid ?></td>

                                        <td><?= $course_due ?></td>

                                        <td><?= $purchase_date_text ?></td>
                                    </tr>
                                    <?php 
                                }
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