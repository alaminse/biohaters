<?php include('../assets/includes/header.php'); ?>

<!-- DELETE EXAM -->
<?php if (isset($_POST['delete'])) {
    $exam_id = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_exam SET is_delete = 1 WHERE id = '$exam_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../exam/';
        </script>
        <?php 
    }
}?>

<!-- ADD EXAM -->
<?php $alert = '';
if (isset($_POST['add'])) {
    $name = mysqli_escape_string($db, $_POST['name']);
    $course = mysqli_escape_string($db, $_POST['course']);
    $status = mysqli_escape_string($db, $_POST['status']);
    $valid_time = $_POST['valid_time'];
    
    // scheduled time
    $scheduled  = $_POST['scheduled'];

    // mcq details
    $total_question = '';
    $mark_per_question = '';
    $negative_marking = '';
    $mcq_duration_number = '';

    // cq details
    $cq_mark = '';
    $cq_duration_number = '';

    function add_exam($db, $admin_id, $name, $course, $status, $is_mcq, $total_question, $mark_per_question, $negative_marking, $mcq_duration_number, $is_cq, $cq_mark, $cq_duration_number, $valid_time, $scheduled) 
    {
        // $created_date = date('Y-m-d H:i:s', time());

        // add exam
        $add = "INSERT INTO hc_exam (name, course_id, mcq, total_question, mark_per_question, negative_marking, cq, mark, mcq_duration, cq_duration, valid_time, status, author, created_date) VALUES ('$name', '$course', '$is_mcq', '$total_question', '$mark_per_question', '$negative_marking', '$is_cq', '$cq_mark', '$mcq_duration_number', '$cq_duration_number', '$valid_time', '$status', '$admin_id', '$scheduled')";
        $sql_add = mysqli_query($db, $add);
        if ($sql_add) {
            ?>
            <script type="text/javascript">
                window.location.href = '../exam/';
            </script>
            <?php 
        }
    }

    if (empty($name) || $course == '' || $scheduled == '') {
        $alert = "<p class='warning mb_75'>Required Fields.....</p>";
    }

    if (isset($_POST['is_mcq'])) {
        $is_mcq = 1;

        // mcq details
        $total_question = mysqli_escape_string($db, $_POST['total_question']);
        $mark_per_question = mysqli_escape_string($db, $_POST['mark_per_question']);
        $negative_marking = mysqli_escape_string($db, $_POST['negative_marking']);
        $mcq_duration_number = mysqli_escape_string($db, $_POST['mcq_duration_number']);
        if (empty($total_question) || empty($mark_per_question) || empty($negative_marking) || empty($mcq_duration_number)) {
            $alert = "<p class='warning mb_75'>Required MCQ Details.....</p>";
        }
    } else {
        $is_mcq = 0;
    }

    if (isset($_POST['is_cq'])) {
        $is_cq = 1;

        // cq details
        $cq_mark = mysqli_escape_string($db, $_POST['cq_mark']);
        $cq_duration_number = mysqli_escape_string($db, $_POST['cq_duration_number']);
        if (empty($cq_mark) || empty($cq_duration_number)) {
            $alert = "<p class='warning mb_75'>Required CQ Details.....</p>";
        }
    } else {
        $is_cq = 0;
    }

    if (($is_mcq == 1) && ($is_cq == 1)) {
        if (!empty($total_question) && !empty($mark_per_question) && !empty($negative_marking) && !empty($mcq_duration_number) && !empty($cq_mark) && !empty($cq_duration_number)) {
            echo add_exam($db, $admin_id, $name, $course, $status, $is_mcq, $total_question, $mark_per_question, $negative_marking, $mcq_duration_number, $is_cq, $cq_mark, $cq_duration_number, $valid_time, $scheduled);
        } else {
            $alert = "<p class='warning mb_75'>Required MCQ or CQ Details.....</p>";
        }
    } elseif (($is_mcq == 1) && ($is_cq == 0)) {
        if (!empty($total_question) && !empty($mark_per_question) && !empty($negative_marking) && !empty($mcq_duration_number)) {
            echo add_exam($db, $admin_id, $name, $course, $status, $is_mcq, $total_question, $mark_per_question, $negative_marking, $mcq_duration_number, $is_cq, $cq_mark, $cq_duration_number, $valid_time, $scheduled);
        }
    } elseif (($is_mcq == 0) && ($is_cq == 1)) {
        if (!empty($cq_mark) && !empty($cq_duration_number)) {
            echo add_exam($db, $admin_id, $name, $course, $status, $is_mcq, $total_question, $mark_per_question, $negative_marking, $mcq_duration_number, $is_cq, $cq_mark, $cq_duration_number, $valid_time, $scheduled);
        }
    } elseif (($is_mcq == 0) && ($is_cq == 0)) {
        $alert = "<p class='warning mb_75'>Required MCQ or CQ Details.....</p>";
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Exam</h4>
        </div>
    </div>

    <?php if (isset($_GET['add'])) {
        ?>
        <!-- welcome message -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== MANAGE COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Add Exam</h5>
                    </div>
                </div>

                <?php echo $alert; ?>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Exam Title*</label>
                        <input type="text" id="" name="name" placeholder="Exam Title">
                    </div>

                    <div>
                        <label for="">Course*</label>
                        <select id="" name="course">
                            <option value="">Choose Course</option>
                            <?php $select = "SELECT * FROM hc_course WHERE is_delete = 0 ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $course_id     = $row['id'];
                                    $course_name   = $row['name'];

                                    echo '<option value="'.$course_id.'">'.$course_name.'</option>';
                                }
                            }?>
                        </select>
                    </div>

                    <div>
                        <label for="">Status</label>
                        <select id="" name="status">
                            <option value="0">Choose Status</option>
                            <option value="1">Published</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="">Scheduled Time*</label>
                        <input type="datetime-local" id="" name="scheduled">
                    </div>

                    <div>
                        <label for="">Valid Time*</label>
                        <input type="datetime-local" id="" name="valid_time">
                    </div>

                    <div class="grid_col_3">
                        <label for="mcq">MCQ?</label>
                        <label for="mcq" class="checkbox_label">
                            No 
                            <input type="checkbox" class="checkbox" name="is_mcq" id="mcq">
                            <span class="checked"></span>
                            Yes
                        </label>
                    </div>

                    <div class="grid_col_3 double_col_form" id="resultMcq"></div>

                    <div class="grid_col_3">
                        <label for="cq">CQ?</label>
                        <label for="cq" class="checkbox_label">
                            No 
                            <input type="checkbox" class="checkbox" name="is_cq" id="cq">
                            <span class="checked"></span>
                            Yes
                        </label>
                    </div>

                    <div class="grid_col_3 double_col_form" id="resultCq"></div>

                    <button type="submit" name="add">Add Exam</button>
                </form>
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
                        <h5 class="box_title">Manage Exam</h5>
                        <a href="../exam/?add" class="button btn_sm"><i class='bx bx-task'></i>Add Exam</a>
                    </div>

                    <table class="ep_table" id="datatable">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Title</th>
                                <th>Course Name</th>
                                <th>Total Attendance</th>
                                <th>Status</th>
                                <th>Author</th>
                                <th>Published On</th>
                                <th>MCQ</th>
                                <th>CQ</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select = "SELECT * FROM hc_exam WHERE is_delete = 0 ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                $si = 0;
                                $now = date('Y-m-d H:i:s', time());
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $exam_id            = $row['id'];
                                    $exam_name          = $row['name'];
                                    $exam_course_id     = $row['course_id'];
                                    $exam_mcq           = $row['mcq'];
                                    $exam_cq            = $row['cq'];
                                    $exam_status        = $row['status'];
                                    $exam_author        = $row['author'];
                                    $exam_created_date  = $row['created_date'];
                                    
                                    $exam_scheduled = date('h:i:s a', strtotime($exam_created_date));

                                    // joined date convert to text
                                    $exam_created_date_text = date('d M, Y', strtotime($exam_created_date));

                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?php echo $si; ?></td>

                                        <td><?php echo $exam_name; ?></td>

                                        <td><?php if ($exam_course_id == '0') {
                                            echo 'BH Quiz Commando';
                                        } else {
                                            $select_course_name = "SELECT * FROM hc_course WHERE id = '$exam_course_id'";
                                            $sql_course_name = mysqli_query($db, $select_course_name);
                                            $num_course_name = mysqli_num_rows($sql_course_name);
                                            $row_course_name = mysqli_fetch_assoc($sql_course_name);
                                            echo $row_course_name['name'];
                                        }?></td>

                                        <td><?php echo "0"; ?></td>

                                        <td><?php if ($exam_status == 1) {
                                            if ($now >= $exam_created_date) {
                                                echo '<div class="ep_badge bg_success text_success">Published</div>';
                                            } else {
                                                echo '<div class="ep_badge bg_info text_info">Scheduled : ' . $exam_scheduled . '</div>';
                                            }
                                        } else {
                                            echo '<div class="ep_badge bg_danger text_danger">Draft</div>';
                                        }?></td>

                                        <td><?php $select_exam_author = "SELECT * FROM admin WHERE id = '$exam_author'";
                                        $sql_exam_author = mysqli_query($db, $select_exam_author);
                                        $num_exam_author = mysqli_num_rows($sql_exam_author);
                                        $row_exam_author = mysqli_fetch_assoc($sql_exam_author);
                                        echo $row_exam_author['name'];?></td>

                                        <td><?php echo $exam_created_date_text; ?></td>

                                        <td><?php if ($exam_mcq == 1) {
                                            // MCQ QUESTION BUTTON
                                            echo '<a href="../mcq-question/?exam=' . $exam_id . '" class="btn_icon"><i class="bx bx-select-multiple"></i></a>';
                                        }?></td>

                                        <td><?php if ($exam_cq == 1) {
                                            // CQ QUESTION BUTTON
                                            echo '<a href="../cq-question/?exam=' . $exam_id . '" class="btn_icon"><i class="bx bx-detail"></i></a>';
                                        }?></td>

                                        <td>
                                            <div class="btn_grp">
                                                <!-- RESULT BUTTON -->
                                                <a href="../exam-result/?exam=<?php echo $exam_id; ?>" class="btn_icon"><i class='bx bxs-bar-chart-alt-2'></i></a>
                                                
                                                <!-- EDIT BUTTON -->
                                                <a href="../exam-edit/?exam=<?php echo $exam_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>
                                            
                                                <!-- DELETE MODAL BUTTON -->
                                                <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $exam_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                            </div>

                                            <!-- DELETE MODAL -->
                                            <div class="modal fade" id="delete<?php echo $exam_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $exam_name; ?></span>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="delete_id" id="" value="<?php echo $exam_id; ?>">
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

<script>
$(document).ready(function() {
    // When the checkbox state changes
    $('#mcq').on('change', function() {
        if (this.checked) {
            // Call the PHP script via AJAX
            $.ajax({
                url: 'mcq.php', // Path to your PHP script
                method: 'POST',
                data: { checkboxStatus: 1 }, // Sending checkbox status to the PHP script
                success: function(response_mcq) {
                    // Display the response from PHP in the resultContainer div
                    $('#resultMcq').html(response_mcq);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error: ', error);
                }
            });
        } else {
            // If the checkbox is unchecked, clear the resultContainer div
            $('#resultMcq').html('');
        }
    });
});

$(document).ready(function() {
    // When the checkbox state changes
    $('#cq').on('change', function() {
        if (this.checked) {
            // Call the PHP script via AJAX
            $.ajax({
                url: 'cq.php', // Path to your PHP script
                method: 'POST',
                data: { checkboxStatus: 1 }, // Sending checkbox status to the PHP script
                success: function(response_cq) {
                    // Display the response from PHP in the resultContainer div
                    $('#resultCq').html(response_cq);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error: ', error);
                }
            });
        } else {
            // If the checkbox is unchecked, clear the resultContainer div
            $('#resultCq').html('');
        }
    });
});
</script>

<?php include('../assets/includes/footer.php'); ?>