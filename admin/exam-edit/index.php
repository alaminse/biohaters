<?php include('../assets/includes/header.php'); ?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Exam</h4>
        </div>
    </div>

    <?php if (isset($_GET['exam'])) {
        $edit_id = $_GET['exam'];

        if (empty($edit_id)) {
            ?>
            <script type="text/javascript">
                window.location.href = '../exam/';
            </script>
            <?php 
        }

        $select = "SELECT * FROM hc_exam WHERE id = '$edit_id' AND is_delete = 0 ORDER BY id DESC";
        $sql = mysqli_query($db, $select);
        $num = mysqli_num_rows($sql);
        $row = mysqli_fetch_assoc($sql);
        $exam_id                = $row['id'];
        $exam_name              = $row['name'];
        $exam_course_id         = $row['course_id'];
        $exam_mcq               = $row['mcq'];
        $exam_total_question    = $row['total_question'];
        $exam_mark_per_question = $row['mark_per_question'];
        $exam_negative_marking  = $row['negative_marking'];
        $exam_cq                = $row['cq'];
        $exam_mark              = $row['mark'];
        $exam_mcq_duration      = $row['mcq_duration'];
        $exam_cq_duration       = $row['cq_duration'];
        $exam_valid_time        = $row['valid_time'];
        $exam_status            = $row['status'];
        $exam_author            = $row['author'];
        $exam_created_date      = $row['created_date'];

        // EDIT EXAM
        $alert = '';
        if (isset($_POST['edit'])) {
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

            function edit_exam($db, $name, $course, $status, $is_mcq, $total_question, $mark_per_question, $negative_marking, $mcq_duration_number, $is_cq, $cq_mark, $cq_duration_number, $exam_id, $valid_time, $scheduled) 
            {
                // edit exam
                $edit = "UPDATE hc_exam SET name = '$name', course_id = '$course', mcq = '$is_mcq', total_question = '$total_question', mark_per_question = '$mark_per_question', negative_marking = '$negative_marking', cq = '$is_cq', mark = '$cq_mark', mcq_duration = '$mcq_duration_number', cq_duration = '$cq_duration_number', valid_time = '$valid_time', status = '$status', created_date = '$scheduled' WHERE id = '$exam_id'";
                $sql_edit = mysqli_query($db, $edit);
                
                // change question course if any
                $select_question = "SELECT * FROM hc_exam_question WHERE exam = '$exam_id'";
                $sql_question = mysqli_query($db, $select_question);
                $num_question = mysqli_num_rows($sql_question);
                if ($num_question > 0) {
                    while ($row_question = mysqli_fetch_assoc($sql_question)) {
                        $question_id = $row_question['id'];
                        
                        $edit_question = "UPDATE hc_exam_question SET course = '$course' WHERE id = '$question_id'";
                        mysqli_query($db, $edit_question);
                    }
                }?>
                <script type="text/javascript">
                    window.location.href = '../exam/';
                </script>
                <?php 
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
                    echo edit_exam($db, $name, $course, $status, $is_mcq, $total_question, $mark_per_question, $negative_marking, $mcq_duration_number, $is_cq, $cq_mark, $cq_duration_number, $exam_id, $valid_time, $scheduled);
                } else {
                    $alert = "<p class='warning mb_75'>Required MCQ or CQ Details.....</p>";
                }
            } elseif (($is_mcq == 1) && ($is_cq == 0)) {
                if (!empty($total_question) && !empty($mark_per_question) && !empty($negative_marking) && !empty($mcq_duration_number)) {
                    echo edit_exam($db, $name, $course, $status, $is_mcq, $total_question, $mark_per_question, $negative_marking, $mcq_duration_number, $is_cq, $cq_mark, $cq_duration_number, $exam_id, $valid_time, $scheduled);
                }
            } elseif (($is_mcq == 0) && ($is_cq == 1)) {
                if (!empty($cq_mark) && !empty($cq_duration_number)) {
                    echo edit_exam($db, $name, $course, $status, $is_mcq, $total_question, $mark_per_question, $negative_marking, $mcq_duration_number, $is_cq, $cq_mark, $cq_duration_number, $exam_id, $valid_time, $scheduled);
                }
            } elseif (($is_mcq == 0) && ($is_cq == 0)) {
                // edit exam
                $edit = "UPDATE hc_exam SET name = '$name', course_id = '$course', valid_time = '$valid_time', status = '$status', created_date = '$scheduled' WHERE id = '$exam_id'";
                $sql_edit = mysqli_query($db, $edit);
                
                // change question course if any
                $select_question = "SELECT * FROM hc_exam_question WHERE exam = '$exam_id'";
                $sql_question = mysqli_query($db, $select_question);
                $num_question = mysqli_num_rows($sql_question);
                if ($num_question > 0) {
                    while ($row_question = mysqli_fetch_assoc($sql_question)) {
                        $question_id = $row_question['id'];
                        
                        $edit_question = "UPDATE hc_exam_question SET course = '$course' WHERE id = '$question_id'";
                        mysqli_query($db, $edit_question);
                    }
                }?>
                <script type="text/javascript">
                    window.location.href = '../exam/';
                </script>
                <?php 
            }
        }?>
        <!-- welcome message -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== MANAGE COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Edit Exam</h5>
                    </div>
                </div>

                <?php echo $alert; ?>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Exam Title*</label>
                        <input type="text" id="" name="name" placeholder="Exam Title" value="<?= $exam_name ?>">
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
                                    ?>
                                    <option value="<?= $course_id ?>" <?php if ($exam_course_id == $course_id) {echo "selected";} ?>><?= $course_name ?></option>
                                    <?php 
                                }
                            }?>
                        </select>
                    </div>

                    <div>
                        <label for="">Status</label>
                        <select id="" name="status">
                            <option value="0">Choose Status</option>
                            <option value="1" <?php if ($exam_status == '1') {echo "selected";} ?>>Published</option>
                            <option value="0" <?php if ($exam_status == '0') {echo "selected";} ?>>Draft</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="">Scheduled Time*</label>
                        <input type="datetime-local" id="" name="scheduled" value="<?= $exam_created_date ?>">
                    </div>

                    <div>
                        <label for="">Valid Time*</label>
                        <input type="datetime-local" id="" name="valid_time" value="<?= $exam_valid_time ?>">
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

                    <button type="submit" name="edit">Edit Exam</button>
                </form>
            </div>
        </div>
        <?php 
    } else {
        ?>
        <script type="text/javascript">
            window.location.href = '../exam/';
        </script>
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