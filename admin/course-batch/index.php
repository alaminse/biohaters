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

<?php if (isset($_GET['course']) && $_GET['course'] != '') { 
    $course = $_GET['course'];

    // add batch
    $alert = '';
    if (isset($_POST['add'])) {
        $batch_name = mysqli_escape_string($db, $_POST['name']);
        $batch_start_time = mysqli_escape_string($db, $_POST['start_time']);
        $batch_end_time = mysqli_escape_string($db, $_POST['end_time']);
        $batch_class_days = '';

        if (isset($_POST['class_days'])) {
            foreach ($_POST['class_days'] as $day) {
                $batch_class_days = $day . ', ' . $batch_class_days;
            }
        }

        $batch_class_days = substr($batch_class_days, 0, -2);

        $created_date = date('Y-m-d H:i:s', time());

        if (empty($batch_name) || empty($batch_start_time) || empty($batch_end_time) || empty($batch_class_days)) {
            $alert = "<p class='warning mb_75'>Required Module Name.....</p>";
        } else {
            $add = "INSERT INTO hc_course_batch (course, name, start_time, end_time, class_days, author, created_date) VALUES ('$course', '$batch_name', '$batch_start_time', '$batch_end_time', '$batch_class_days', '$admin_id', '$created_date')";
            $sql_add = mysqli_query($db, $add);
            if ($sql_add) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../course-batch/?course=<?php echo $course; ?>';
                </script>
                <?php 
            }
        }
    }

    // delete batch
    if (isset($_POST['delete'])) {
        $batch_id = mysqli_escape_string($db, $_POST['delete_id']);

        $delete = "UPDATE hc_course_batch SET is_delete = 1 WHERE id = '$batch_id'";
        $sql_delete = mysqli_query($db, $delete);
        if ($sql_delete) {
            ?>
            <script type="text/javascript">
                window.location.href = '../course-batch/?course=<?php echo $course; ?>';
            </script>
            <?php 
        }
    }?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Course Batch</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container ep_grid grid_1_2">
            <?php if (isset($_GET['edit_id']) && $_GET['edit_id'] != '') {
                $edit_id = $_GET['edit_id'];

                // edit process
                if (isset($_POST['edit'])) {
                    $batch_name         = mysqli_escape_string($db, $_POST['name']);
                    $batch_start_time   = mysqli_escape_string($db, $_POST['start_time']);
                    $batch_end_time     = mysqli_escape_string($db, $_POST['end_time']);
                    $batch_class_days   = '';
                    
                    // previous days
                    $previous_class_days = mysqli_escape_string($db, $_POST['previous_class_days']);
                    
                    if (isset($_POST['class_days'])) {
                        foreach ($_POST['class_days'] as $day) {
                            $batch_class_days = $day . ', ' . $batch_class_days;
                        }
                    }
            
                    $batch_class_days = substr($batch_class_days, 0, -2);

                    if (empty($batch_class_days)) {
                        $batch_class_days = $previous_class_days;
                    }
            
                    $created_date = date('Y-m-d H:i:s', time());
            
                    if (empty($batch_name) || empty($batch_start_time) || empty($batch_end_time)) {
                        $alert = "<p class='warning mb_75'>Required Module Name.....</p>";
                    } else {
                        $edit = "UPDATE hc_course_batch SET name = '$batch_name', start_time = '$batch_start_time', end_time = '$batch_end_time', class_days = '$batch_class_days' WHERE id = '$edit_id'";
                        $sql_edit = mysqli_query($db, $edit);
                        if ($sql_edit) {
                            ?>
                            <script type="text/javascript">
                                window.location.href = '../course-batch/?course=<?php echo $course; ?>';
                            </script>
                            <?php 
                        }
                    }
                }

                // fetch batch
                $select = "SELECT * FROM hc_course_batch WHERE id = '$edit_id' AND is_delete = 0 ORDER BY id DESC";
                $sql = mysqli_query($db, $select);
                $num = mysqli_num_rows($sql);
                if ($num > 0) {
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $batch_id           = $row['id'];
                        $batch_name         = $row['name'];
                        $batch_course       = $row['course'];
                        $batch_start_time   = $row['start_time'];
                        $batch_end_time     = $row['end_time'];
                        $batch_class_days   = $row['class_days'];
                        ?>
                        <!--========== ADD PRODUCT CATEGORY ==========-->
                        <div class="add_category">
                            <h5 class="box_title">Edit Course Batch</h5>

                            <?php echo $alert; ?>

                            <form action="" method="post" class="single_col_form">
                                <div>
                                    <label for="">Batch Name*</label>
                                    <input type="text" id="" name="name" placeholder="Batch Name" value="<?php echo $batch_name; ?>">
                                </div>

                                <div>
                                    <label for="">Start Time*</label>
                                    <input type="time" id="" name="start_time" value="<?php echo $batch_start_time; ?>">
                                </div>

                                <div>
                                    <label for="">End Time*</label>
                                    <input type="time" id="" name="end_time" value="<?php echo $batch_end_time; ?>">
                                </div>

                                <div>
                                    <label for="">Days</label>
                                    <select class="form-select" name="class_days[]" id="multiple-select-field" data-placeholder="Choose Days" multiple>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                    </select>
                                </div>

                                <input type="hidden" id="" name="previous_class_days" value="<?php echo $batch_class_days; ?>">

                                <button type="submit" name="edit">Edit Batch</button>
                            </form>
                        </div>
                        <?php 
                    }
                }
            } else {
                ?>
                <!--========== ADD PRODUCT CATEGORY ==========-->
                <div class="add_category">
                    <h5 class="box_title">Add Course Batch</h5>

                    <?php echo $alert; ?>

                    <form action="" method="post" class="single_col_form">
                        <div>
                            <label for="">Batch Name*</label>
                            <input type="text" id="" name="name" placeholder="Batch Name">
                        </div>

                        <div>
                            <label for="">Start Time*</label>
                            <input type="time" id="" name="start_time">
                        </div>

                        <div>
                            <label for="">End Time*</label>
                            <input type="time" id="" name="end_time">
                        </div>

                        <div>
                            <label for="">Days</label>
                            <select class="form-select" name="class_days[]" id="multiple-select-field" data-placeholder="Choose Days" multiple>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                            </select>
                        </div>

                        <button type="submit" name="add">Add Batch</button>
                    </form>
                </div>
                <?php 
            }?>

            <!--========== MANAGE PRODUCT CATEGORY ==========-->
            <div class="mng_category">
                <h5 class="box_title">Manage Course Batch</h5>

                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Author</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_course_batch WHERE course = '$course' AND is_delete = 0 ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num == 0) {
                            echo "<tr><td colspan='4' class='text_center'>There are no Course Batch</td></tr>";
                        } else {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $batch_id           = $row['id'];
                                $batch_name         = $row['name'];
                                $batch_course       = $row['course'];
                                $batch_start_time   = $row['start_time'];
                                $batch_end_time     = $row['end_time'];
                                $batch_class_days   = $row['class_days'];
                                $batch_author       = $row['author'];

                                $batch_start_time = date('h:i a', strtotime($batch_start_time));
                                $batch_end_time = date('h:i a', strtotime($batch_end_time));
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td>
                                        <div><strong><?php echo $batch_name; ?></strong></div>
                                        <div>
                                            <?php echo $batch_class_days; ?> || <?php echo $batch_start_time . ' - ' . $batch_end_time; ?>
                                        </div>
                                    </td>

                                    <td><?php $select_batch_course = "SELECT * FROM hc_course WHERE id = '$batch_course'";
                                    $sql_batch_course = mysqli_query($db, $select_batch_course);
                                    $num_batch_course = mysqli_num_rows($sql_batch_course);
                                    if ($num_batch_course == 0) {
                                        echo "--";
                                    } else {
                                        $row_batch_course = mysqli_fetch_assoc($sql_batch_course);
                                        echo $row_batch_course['name'];
                                    }?></td>

                                    <td><?php $select_batch_author = "SELECT * FROM admin WHERE id = '$batch_author'";
                                    $sql_batch_author = mysqli_query($db, $select_batch_author);
                                    $num_batch_author = mysqli_num_rows($sql_batch_author);
                                    $row_batch_author = mysqli_fetch_assoc($sql_batch_author);
                                    echo $row_batch_author['name'];?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <!-- EDIT BUTTON -->
                                            <a href="../course-batch/?course=<?php echo $course; ?>&edit_id=<?php echo $batch_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>

                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $batch_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                        </div>

                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="delete<?php echo $batch_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Batch</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $batch_name; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <!-- DELETE BUTTON -->
                                                        <form action="" method="post">
                                                            <input type="hidden" name="delete_id" id="" value="<?php echo $batch_id; ?>">
                                                            <button type="submit" name="delete" class="button bg_danger text_danger text_semi">Delete</button>
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
</main>
<?php } else { ?><script type="text/javascript">window.location.href = '../course/';</script><?php } ?>

<!--=========== SELECT2 ===========-->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$( '#multiple-select-field' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
} );
</script>

<?php include('../assets/includes/footer.php'); ?>