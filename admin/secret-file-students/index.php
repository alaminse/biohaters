<?php include('../assets/includes/header.php'); ?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Secret Files Students</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE COURSE ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Manage Students - Secret Files</h5>
                </div>

                <table class="ep_table" id="datatable">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Name</th>
                            <th>Roll</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Token</th>
                            <th>Entry Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_secret_file_entry ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num > 0) {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $entry_id               = $row['id'];
                                $entry_student_id       = $row['student_id'];
                                $entry_token            = $row['token'];
                                $entry_purchase_date    = $row['purchase_date'];

                                // joined date convert to text
                                $entry_purchase_date_text = date('d M, Y || h:i a', strtotime($entry_purchase_date));
                                
                                // fetch student
                                $select_student = "SELECT * FROM hc_student WHERE id = '$entry_student_id'";
                                $sql_student = mysqli_query($db, $select_student);
                                $num_student = mysqli_num_rows($sql_student);
                                if ($num_student > 0) {
                                    while ($row_student = mysqli_fetch_assoc($sql_student)) {
                                        $student_id     = $row_student['id'];
                                        $student_name   = $row_student['name'];
                                        $student_email  = $row_student['email'];
                                        $student_phone  = $row_student['phone'];
                                        $student_roll   = $row_student['roll'];
                                    }
                                }

                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>

                                    <td><?php echo $student_name; ?></td>

                                    <td><?php echo $student_roll; ?></td>

                                    <td><?php echo $student_phone; ?></td>
                                    
                                    <td><?php echo $student_email; ?></td>
                                    
                                    <td><?php echo $entry_token; ?></td>
                                    
                                    <td><?php echo $entry_purchase_date_text; ?></td>
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
    $('#datatable').DataTable({
        dom: 'Bfrtip',
        // order: [[0, 'desc']],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>

<?php include('../assets/includes/footer.php'); ?>