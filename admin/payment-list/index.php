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

<?php $today = date('Y-m-d', time()); ?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Payment List</h4>
        </div>
    </div>
    
    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <div class="ep_flex mb_75">
                <h5 class="box_title">Search Payment List</h5>
            </div>
            
            <div class="mb_75">
                <form action="" method="get" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Search Payment List*</label>
                        <input type="text" id="search-payment" name="pay_info" placeholder="Roll Number, Phone Number">
                    </div>
                    
                    <button type="submit" name="search">Search</button>
    			</form>
            </div>
            
            <table class="ep_table">
                <thead>
                    <tr>
                        <th>SI</th>
                        <th>Name</th>
                        <th>Roll</th>
                        <th>Phone</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Due Amount</th>
                        <th>Admitted Date</th>
                        <th>Last Payment Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_GET['search'])) {
                        $search_pay_info = $_GET['pay_info'];
                    
                        $search_data = "SELECT * FROM hc_student WHERE phone LIKE '%$search_pay_info%' OR roll LIKE '%$search_pay_info%'";
                        $search_sql = mysqli_query($db, $search_data);
                        $search_num = mysqli_num_rows($search_sql);
                    
                        if ($search_num > 0) {
                            $si = 0;
                            while ($search = mysqli_fetch_assoc($search_sql)) {
                                $search_student_id  = $search['id'];
                                
                                $select = "SELECT * FROM hc_purchase_details WHERE student_id = '$search_student_id' GROUP BY purchase_id ORDER BY purchase_id DESC";
                                $sql = mysqli_query($db, $select);
                                $num = mysqli_num_rows($sql);
                                if ($num > 0) {
                                    $si = 0;
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        $payment_purchase_id    = $row['purchase_id'];
                                        $payment_student_id     = $row['student_id'];
        
                                        // joined date convert to text
                                        // $exam_created_date_text = date('d M, Y', strtotime($exam_created_date));
                                        
                                        // fetch student data
                                        $select_student = "SELECT * FROM hc_student WHERE id = '$payment_student_id'";
                                        $sql_student = mysqli_query($db, $select_student);
                                        $num_student = mysqli_num_rows($sql_student);
                                        if ($num_student > 0) {
                                            while ($row_student = mysqli_fetch_assoc($sql_student)) {
                                                $name   = $row_student['name'];
                                                $roll   = $row_student['roll'];
                                                $phone  = $row_student['phone'];
                                            }
                                        }
                                        
                                        // initialize paid amount
                                        $total_paid = 0;
                                        
                                        // fetch paid amount
                                        $select_amount = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$payment_purchase_id'";
                                        $sql_amount = mysqli_query($db, $select_amount);
                                        $num_amount = mysqli_num_rows($sql_amount);
                                        if ($num_amount > 0) {
                                            while ($row_amount = mysqli_fetch_assoc($sql_amount)) {
                                                $paid   = $row_amount['paid_amount'];
                                                
                                                $total_paid += $paid;
                                            }
                                        }
                                        
                                        // fetch total amount & last payment date
                                        $select_last_payment = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$payment_purchase_id' ORDER BY id DESC LIMIT 1";
                                        $sql_last_payment = mysqli_query($db, $select_last_payment);
                                        $num_last_payment = mysqli_num_rows($sql_last_payment);
                                        if ($num_last_payment > 0) {
                                            while ($row_last_payment = mysqli_fetch_assoc($sql_last_payment)) {
                                                $total_amount = $row_last_payment['price'];
                                                $last_payment = $row_last_payment['payment_time'];
                                                
                                                // last payment date convert to text
                                                $last_payment_txt = date('d M, Y', strtotime($last_payment));
                                            }
                                        }
                                        
                                        // fetch admitted date
                                        $select_admitted = "SELECT * FROM hc_purchase WHERE id = '$payment_purchase_id'";
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
        
                                        $si++;
                                        ?>
                                        <tr>
                                            <td><?php echo $si; ?></td>
        
                                            <td><?php echo $name; ?></td>
        
                                            <td><?php echo $roll;?></td>
        
                                            <td><?php echo $phone; ?></td>
        
                                            <td><?php echo $total_amount; ?></td>
        
                                            <td><?php echo $total_paid; ?></td>
                                            
                                            <td><?php echo $due; ?></td>
        
                                            <td><?php echo $admit_date_txt; ?></td>
        
                                            <td><?php echo $last_payment_txt; ?></td>
        
                                            <td>
                                                <div class="btn_grp">
                                                    <!-- RESULT BUTTON -->
                                                    <a href="../payment-invoice-print/?payment=<?php echo $payment_purchase_id; ?>" target="_blank" class="btn_icon"><i class='bx bxs-printer' ></i></a>
                                                    
                                                    <!-- EDIT BUTTON -->
                                                    <a href="../payment-details/?payment=<?php echo $payment_purchase_id; ?>" class="btn_icon"><i class='bx bx-receipt'></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php 
                                    }
                                }
                            }
                        } else {
                            echo '<tr><td colspan="10">No data in this database.....</td></tr>';
                        }
                    }?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE COURSE ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Payment List</h5>
                    <!--<a href="../exam/?add" class="button btn_sm"><i class='bx bx-task'></i>Add Exam</a>-->
                </div>

                <table class="ep_table" id="datatable">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Name</th>
                            <th>Roll</th>
                            <th>Phone</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Due Amount</th>
                            <th>Admitted Date</th>
                            <th>Last Payment Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_purchase_details WHERE price > 0 AND DATE(payment_time) = '$today' GROUP BY purchase_id ORDER BY purchase_id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num > 0) {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $payment_purchase_id    = $row['purchase_id'];
                                $payment_student_id     = $row['student_id'];

                                // joined date convert to text
                                // $exam_created_date_text = date('d M, Y', strtotime($exam_created_date));
                                
                                // fetch student data
                                $select_student = "SELECT * FROM hc_student WHERE id = '$payment_student_id'";
                                $sql_student = mysqli_query($db, $select_student);
                                $num_student = mysqli_num_rows($sql_student);
                                if ($num_student > 0) {
                                    while ($row_student = mysqli_fetch_assoc($sql_student)) {
                                        $name   = $row_student['name'];
                                        $roll   = $row_student['roll'];
                                        $phone  = $row_student['phone'];
                                    }
                                }
                                
                                // initialize paid amount
                                $total_paid = 0;
                                
                                // fetch paid amount
                                $select_amount = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$payment_purchase_id'";
                                $sql_amount = mysqli_query($db, $select_amount);
                                $num_amount = mysqli_num_rows($sql_amount);
                                if ($num_amount > 0) {
                                    while ($row_amount = mysqli_fetch_assoc($sql_amount)) {
                                        $paid   = $row_amount['paid_amount'];
                                        
                                        $total_paid += $paid;
                                    }
                                }
                                
                                // fetch total amount & last payment date
                                $select_last_payment = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$payment_purchase_id' ORDER BY id DESC LIMIT 1";
                                $sql_last_payment = mysqli_query($db, $select_last_payment);
                                $num_last_payment = mysqli_num_rows($sql_last_payment);
                                if ($num_last_payment > 0) {
                                    while ($row_last_payment = mysqli_fetch_assoc($sql_last_payment)) {
                                        $total_amount = $row_last_payment['price'];
                                        $last_payment = $row_last_payment['payment_time'];
                                        
                                        // last payment date convert to text
                                        $last_payment_txt = date('d M, Y', strtotime($last_payment));
                                    }
                                }
                                
                                // fetch admitted date
                                $select_admitted = "SELECT * FROM hc_purchase WHERE id = '$payment_purchase_id'";
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

                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>

                                    <td><?php echo $name; ?></td>

                                    <td><?php echo $roll;?></td>

                                    <td><?php echo $phone; ?></td>

                                    <td><?php echo $total_amount; ?></td>

                                    <td><?php echo $total_paid; ?></td>
                                    
                                    <td><?php echo $due; ?></td>

                                    <td><?php echo $admit_date_txt; ?></td>

                                    <td><?php echo $last_payment_txt; ?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <!-- RESULT BUTTON -->
                                            <a href="../payment-invoice-print/?payment=<?php echo $payment_purchase_id; ?>" target="_blank" class="btn_icon"><i class='bx bxs-printer' ></i></a>
                                            
                                            <!-- EDIT BUTTON -->
                                            <a href="../payment-details/?payment=<?php echo $payment_purchase_id; ?>" class="btn_icon"><i class='bx bx-receipt'></i></a>
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