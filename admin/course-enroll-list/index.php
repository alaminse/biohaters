<?php include('../assets/includes/header.php'); ?>

<!-- TRANSFER batch -->
<?php if (isset($_POST['transfer_batch'])) {
    $transfer_id        = mysqli_escape_string($db, $_POST['transfer_id']);
    $transfer_batch_id    = mysqli_escape_string($db, $_POST['transfer_batch_id']);
    $transfer_roll    = mysqli_escape_string($db, $_POST['transfer_roll']);

    // transfer query
    $transfer_student       = "UPDATE hc_batch_student SET batch = '$transfer_batch_id' WHERE purchase_id = '$transfer_id' AND roll = '$transfer_roll'";
    $sql_transfer_student   = mysqli_query($db, $transfer_student);
    if ($sql_transfer_student) {
        ?>
        <script type="text/javascript">
            window.location.href = '../course/';
        </script>
        <?php 
    }
}?>

<!-- TRANSFER COURSE -->
<?php if (isset($_POST['transfer'])) {
    $transfer_id        = mysqli_escape_string($db, $_POST['transfer_id']);
    $transfer_course    = mysqli_escape_string($db, $_POST['transfer_course']);

    // transfer query
    $transfer_student       = "UPDATE hc_purchase_details SET item_id = '$transfer_course' WHERE id = '$transfer_id'";
    $sql_transfer_student   = mysqli_query($db, $transfer_student);
    if ($sql_transfer_student) {
        ?>
        <script type="text/javascript">
            window.location.href = '../course/';
        </script>
        <?php 
    }
}?>

<!-- GET COURSE -->
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
    $course_type    = $row_course['type'];
    
    if ($course_id == 'commando') {
        $course_name = 'BH Quiz Commando';
        $course_type = 1;
    }
}?>

<!-- COURSE OUT -->
<?php if (isset($_POST['course_out'])) {
    $course_out_id = mysqli_escape_string($db, $_POST['course_out_id']);

    // course out
    $course_out_student       = "UPDATE hc_purchase SET is_expired = 1 WHERE id = '$course_out_id'";
    $sql_course_out_student   = mysqli_query($db, $course_out_student);
    if ($sql_course_out_student) {
        ?>
        <script type="text/javascript">
            window.location.href = '../course-enroll-list/?course=<?= $course_id ?>';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Enrolled List</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE COURSE ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title"><?= $course_name; ?> - Enrolled List</h5>
                    <div class="btn_grp">
                        <a href="../course-out-list/?course=<?php echo $course_id; ?>" class="button btn_sm">Course Out List</a>
                        <a href="../due-list/?course=<?php echo $course_id; ?>" class="button btn_sm">Due Payment List</a>
                        <a href="../course-new-enroll/?course=<?php echo $course_id; ?>" class="button btn_sm">Course New Enrollment</a>
                    </div>
                </div>

                <table class="ep_table" id="datatable">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>P.ID</th>
                            <th>S.ID</th>
                            <th>Name</th>
                            <th>Roll</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <?php if($course_type == 0) {
                                ?>
                                <th>Batch</th>
                                <?php
                            }?>
                            <th>Paid</th>
                            <th>College</th>
                            <th>Purchase Date</th>
                            <th>Action</th>
                            <th>OUT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_purchase_details WHERE purchase_item = '1' AND item_id = '$course_id' GROUP BY student_id ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num > 0) {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $course_stutdent = $row['student_id'];
                                $purchase_id = $row['purchase_id'];
                                
                                if ($course_id != 'commando') {
                                    // fetch expired
                                    $select_expired = "SELECT * FROM hc_purchase WHERE id = '$purchase_id'";
                                    $sql_expired = mysqli_query($db, $select_expired);
                                    $row_expired = mysqli_fetch_assoc($sql_expired);
                                    $is_expired = $row_expired['is_expired'];
                                } else {
                                    $is_expired = 0;
                                }
                                
                                if ($course_id == 'commando') {
                                    $purchase_date  = $row['purchase_date'];
                                } else {
                                    $purchase_date  = $row['payment_time'];
                                    $purchase_details_id = $row['id'];
                                    $paid = $row['paid_amount'];
                                }

                                // purchase date convert to text
                                $purchase_date_text = date('d M, Y', strtotime($purchase_date));
                                
                                // fetch student
                                $select_stutdent = "SELECT * FROM hc_student WHERE id = '$course_stutdent'";
                                $sql_stutdent = mysqli_query($db, $select_stutdent);
                                $num_stutdent = mysqli_num_rows($sql_stutdent);
                                if ($num_stutdent > 0) {
                                    $row_stutdent       = mysqli_fetch_assoc($sql_stutdent);
                                    $stutdent_id        = $row_stutdent['id'];
                                    $stutdent_name      = $row_stutdent['name'];
                                    $stutdent_email     = $row_stutdent['email'];
                                    $stutdent_phone     = $row_stutdent['phone'];
                                    $stutdent_roll      = $row_stutdent['roll'];
                                    $stutdent_college   = $row_stutdent['college'];
                                } else {
                                    $stutdent_name      = '';
                                    $stutdent_email     = '';
                                    $stutdent_phone     = '';
                                    $stutdent_roll      = '';
                                    $stutdent_college   = '';
                                }
                                
                                // fetch batch
                                if ($course_type == 0) {
                                    $select_batch = "SELECT * FROM hc_batch_student WHERE purchase_id = '$purchase_id' AND roll = '$stutdent_roll'";
                                    $sql_batch = mysqli_query($db, $select_batch);
                                    $num_batch = mysqli_num_rows($sql_batch);
                                    if ($num_batch > 0) {
                                        $row_batch  = mysqli_fetch_assoc($sql_batch);
                                        $batch_id   = $row_batch['batch'];
                                        
                                        // fetch batch name
                                        $select_batch_name = "SELECT * FROM hc_course_batch WHERE id = '$batch_id'";
                                        $sql_batch_name = mysqli_query($db, $select_batch_name);
                                        $row_batch_name = mysqli_fetch_assoc($sql_batch_name);
                                        $batch_name = $row_batch_name['name'];
                                        $batch_time = $row_batch_name['start_time'];
                                        
                                        $batch_time = date('h:i a', strtotime($batch_time));
                                    } else {
                                        $batch_time = '';
                                    }
                                }

                                $si++;
                                if ($is_expired == 0) {
                                    ?>
                                    <tr>
                                        <td><?= $si ?></td>
                                        
                                        <td><?= $purchase_id ?></td>
                                        
                                        <td><?= $course_stutdent ?></td>
                                        
                                        <td><?= $stutdent_name ?></td>
    
                                        <td><?= $stutdent_roll ?></td>
    
                                        <td><?= $stutdent_phone ?></td>
    
                                        <td><?= $stutdent_email ?></td>
                                        
                                        <?php if($course_type == 0) {
                                            ?>
                                            <td><?= $batch_time ?></td>
                                            <?php
                                        }?>
                                        
                                        <td><?= $paid ?></td>
    
                                        <td><?= $stutdent_college ?></td>
    
                                        <td><?= $purchase_date_text ?></td>
                                        
                                        <?php if ($course_type == 0) {
                                            ?>
                                            <td>
                                                <div class="btn_grp">
                                                    <!-- TRANSFER MODAL BUTTON -->
                                                    <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#transfer-batch<?php echo $stutdent_id; ?>"><i class='bx bx-transfer'></i></button>
                                                </div>
            
                                                <!-- TRANSFER MODAL -->
                                                <div class="modal fade" id="transfer-batch<?php echo $stutdent_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Tranfer Student</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <span class ="ep_p text_semi bg_danger text_danger"><?php echo $stutdent_name; ?></span> Transfer Student to - 
                                                                <form action="" method="post" class="mb_75 mt_75">
                                                                    <select id="" name="transfer_batch_id">
                                                                        <option value="">Choose Batch</option>
                                                                        <?php $select_batch = "SELECT * FROM hc_course_batch WHERE course = '$course_id'";
                                                                        $sql_batch = mysqli_query($db, $select_batch);
                                                                        $num_batch = mysqli_num_rows($sql_batch);
                                                                        if ($num_batch > 0) {
                                                                            while ($row_batch = mysqli_fetch_assoc($sql_batch)) {
                                                                                $batch_id     = $row_batch['id'];
                                                                                $batch_name   = $row_batch['name'];
                                                                                $batch_time = $row_batch['start_time'];
                                            
                                                                                $batch_time = date('h:i a', strtotime($batch_time));
                                            
                                                                                echo '<option value="'.$batch_id.'">'.$batch_time.'</option>';
                                                                            }
                                                                        }?>
                                                                    </select>
                                                                    <input type="hidden" name="transfer_id" id="" value="<?php echo $purchase_id; ?>">
                                                                    <input type="hidden" name="transfer_roll" id="" value="<?php echo $stutdent_roll; ?>">
                                                                    <button type="submit" name="transfer_batch" class="button bg_success text_success text_semi mt_75">Transfer</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <?php 
                                        }?>
                                        
                                        <?php if ($course_type != 0) {
                                            ?>
                                            <td>
                                                <div class="btn_grp">
                                                    <!-- TRANSFER MODAL BUTTON -->
                                                    <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#transfer<?php echo $stutdent_id; ?>"><i class='bx bx-transfer'></i></button>
                                                </div>
            
                                                <!-- TRANSFER MODAL -->
                                                <div class="modal fade" id="transfer<?php echo $stutdent_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Tranfer Student</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <span class ="ep_p text_semi bg_danger text_danger"><?php echo $stutdent_name; ?></span> Transfer Student to - 
                                                                <form action="" method="post" class="mb_75 mt_75">
                                                                    <select id="" name="transfer_course">
                                                                        <option value="">Choose Course</option>
                                                                        <?php $select_course = "SELECT * FROM hc_course WHERE is_delete = 0 ORDER BY id DESC";
                                                                        $sql_course = mysqli_query($db, $select_course);
                                                                        $num_course = mysqli_num_rows($sql_course);
                                                                        if ($num_course > 0) {
                                                                            while ($row_course = mysqli_fetch_assoc($sql_course)) {
                                                                                $course_id_opt     = $row_course['id'];
                                                                                $course_name_opt   = $row_course['name'];
                                            
                                                                                echo '<option value="'.$course_id_opt.'">'.$course_name_opt.'</option>';
                                                                            }
                                                                        }?>
                                                                    </select>
                                                                    <input type="hidden" name="transfer_id" id="" value="<?php echo $purchase_details_id; ?>">
                                                                    <button type="submit" name="transfer" class="button bg_success text_success text_semi mt_75">Transfer</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <?php 
                                        }?>
                                        
                                        <td>
                                            <div class="btn_grp">
                                                <!-- TRANSFER MODAL BUTTON -->
                                                <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#out<?php echo $purchase_id; ?>"><i class='bx bx-exit'></i></button>
                                            </div>
        
                                            <!-- TRANSFER MODAL -->
                                            <div class="modal fade" id="out<?php echo $purchase_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Course Out Student</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <span class ="ep_p text_semi bg_danger text_danger"><?php echo $stutdent_name; ?></span> out from <?php echo $course_name; ?> ?
                                                            <form action="" method="post" class="mb_75 mt_75">
                                                                <input type="hidden" name="course_out_id" id="" value="<?php echo $purchase_id; ?>">
                                                                <button type="submit" name="course_out" class="button bg_success text_success text_semi mt_75">Course Out</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
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