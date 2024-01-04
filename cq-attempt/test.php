<?php include('../assets/includes/dashboard_header.php'); ?>

<?php if (isset($_GET['exam'])) {
    $exam_id = $_GET['exam'];

    if ($exam_id == '') {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>exam/';
        </script>
        <?php 
    }

    // fetch exam 
    $select_exam = "SELECT * FROM hc_exam WHERE id = '$exam_id' AND status = 0 AND is_delete = 0 ORDER BY id DESC";
    $sql_exam = mysqli_query($db, $select_exam);
    $num_exam = mysqli_num_rows($sql_exam);
    if ($num_exam > 0) {
        $row_exam = mysqli_fetch_assoc($sql_exam);
        $exam_id                = $row_exam['id'];
        $exam_name              = $row_exam['name'];
        $exam_course            = $row_exam['course_id'];
        $exam_cq                = $row_exam['cq'];
        $exam_mark              = $row_exam['mark'];
        $exam_cq_duration       = $row_exam['cq_duration'];
        $exam_date              = $row_exam['created_date'];
    } else {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>exam/';
        </script>
        <?php 
    }

    // check this exam course is my course
    $course_found = false;
    foreach ($result['my_courses'] as $my_courses) {
        if ($my_courses['item_id'] === $exam_course) {
            $course_found = true;
            break;
        }
    }
    
    // intialize now time
    $now_scheduled = date('Y-m-d H:i:s', time());
    
    // quiz commando
    $select_commando = "SELECT * FROM hc_commando WHERE student_id = '$student_id'";
    $sql_commando = mysqli_query($db, $select_commando);
    $num_commando = mysqli_num_rows($sql_commando);

    // if this course is my course then exam start
    if ($course_found || ($num_commando > 0)) {
        if ($now_scheduled >= $exam_date) {
            // fetch exam elements
            $select_exam_elements = "SELECT * FROM hc_exam_cq WHERE exam = '$exam_id' AND course = '$exam_course' AND is_delete = 0";
            $sql_exam_elements = mysqli_query($db, $select_exam_elements);
            $num_exam_elements = mysqli_num_rows($sql_exam_elements);
    
            if ($num_exam_elements > 0) {
                // Mock exam data
                while ($row_exam_elements = mysqli_fetch_assoc($sql_exam_elements)) {
                    $question = $row_exam_elements['question'];
                }
            }
            
            // fetch cq attendance 
            $select_cq_attempt = "SELECT * FROM hc_cq_attempt WHERE student_id = '$student_id' AND exam = '$exam_id' AND submitted_pdf != '' AND submission_status != '' AND submit_time != ''";
            $sql_cq_attempt = mysqli_query($db, $select_cq_attempt);
            $num_cq_attempt = mysqli_num_rows($sql_cq_attempt);
            if ($num_cq_attempt == 0) {
                // fetch cq status 
                $select_cq_status = "SELECT * FROM hc_cq_attempt WHERE student_id = '$student_id' AND exam = '$exam_id'";
                $sql_cq_status = mysqli_query($db, $select_cq_status);
                $num_cq_status = mysqli_num_rows($sql_cq_status);
                if ($num_cq_status > 0) {
                    $row_cq_status = mysqli_fetch_assoc($sql_cq_status);
                    
                    $cq_id                   = $row_cq_status['id'];
                    $cq_submitted_pdf        = $row_cq_status['submitted_pdf'];
                    $cq_submission_status    = $row_cq_status['submission_status'];
                    $cq_start_time           = $row_cq_status['start_time'];
                    $cq_end_time             = $row_cq_status['end_time'];
                    $cq_submit_time          = $row_cq_status['submit_time'];
                } else {
                    $cq_start_time = date('Y-m-d H:i:s', time());
                    $cq_end_time = date('Y-m-d H:i:s', time() + (($exam_cq_duration + 10) * 60));
                    
                    // insert cq attempt entry
                    $insert_entry = "INSERT INTO hc_cq_attempt (student_id, roll, exam, start_time, end_time) VALUES ('$student_id', '$student_roll', '$exam_id', '$cq_start_time', '$cq_end_time')";
                    $sql_entry = mysqli_query($db, $insert_entry);
                    
                    // get cq id by insert cq attempt entry
                    $cq_id = mysqli_insert_id($db);
                }
                
                $cq_start_time_text = date('d-M-Y | h:i:s a', strtotime($cq_start_time));
                $cq_end_time_text   = date('d-M-Y | h:i:s a', strtotime($cq_end_time));
            } else {
                ?>
                <script type="text/javascript">
                    window.location.href = '<?= $base_url ?>exam/';
                </script>
                <?php 
            }
            
            $alert = '';
            if (isset($_POST['update'])) {
                $exam_pdf       = $_FILES['exam_pdf']['name'];
                $tmp_exam_pdf   = $_FILES['exam_pdf']['tmp_name'];
                if (empty($exam_pdf)) {
                    $alert = '<p class="danger text_center mb_75">Choose a file</p>';
                } else {
                    $array_pdf = explode('.', $exam_pdf);
                    $extension_pdf = end($array_pdf);
            
                    if ($extension_pdf == 'pdf') {
                        $random = rand(0, 999999);
                        $roll = $student_roll;
                        $up_date = date('Ymdhis');
            
                        $final_pdf = "../assets/cq_attempt/hc_".$random."_".$exam_id."_".$roll."_".$up_date."_".$exam_pdf;
            
                        // upload directory
                        $upload_directory = "../admin/assets/cq_attempt/hc_".$random."_".$exam_id."_".$roll."_".$up_date."_".$exam_pdf;
                        
                        $submit_time = date('Y-m-d H:i:s', time());
                        
                        if ($submit_time <= $cq_end_time) {
                            $submission_status = 'In Time';
                        } else {
                            $submission_status = 'Late Submit';
                        }
            
                        move_uploaded_file($tmp_exam_pdf, $upload_directory);
            
                        $update = "UPDATE hc_cq_attempt SET submitted_pdf = '$final_pdf', submission_status = '$submission_status', submit_time = '$submit_time' WHERE student_id = '$student_id' AND roll = '$student_roll' AND exam = '$exam_id'";
                        $sql = mysqli_query($db, $update);
                        if ($sql) {
                            $alert =    '<div class="modal_container payment_modal show-modal" id="update-personal-success">
                                            <div class="modal_content payment_content">
                                                <div class="modal_body">
                                                    <div class="payment_icon_success text_center">
                                                        <i class="bx bx-check"></i>
                                                    </div>
    
                                                    <p class="payment_success_subtitle text_center">Submit Successfully!</p>
                                                </div>
    
                                                <div class="">
                                                    <a href="' . $base_url . 'exam/" class="button no_hover btn_sm m_auto">Back</a>
                                                </div>
                                            </div>
                                        </div>';
                        }
                    } else {
                        $alert = '<p class="danger text_center mb_75">Give only PDF file</p>';
                    }
                }
            }?>
            <!--=========== PAGE TITLE SECTION ===========-->
            <section class="page_section hc_section">
                <div class="hc_container">
                    <h3 class="hc_page_title">Exam - <?= $exam_name ?></h3>
                    <h5 class="hc_page_subtitle">Mark: <?= $exam_mark ?></h5>
                    <h5 class="hc_page_subtitle">Duration: <?= $exam_cq_duration ?> minutes</h5>
                    <h5 class="hc_page_subtitle">You Started at: <?= $cq_start_time_text ?></h5>
                    <h5 class="hc_page_subtitle">Your End Time: <?= $cq_end_time_text ?></h5>
                </div>
            </section>
    
            <!--=========== RESOURCE SECTION ===========-->
            <section class="hc_section">
                <div class="hc_container">
                    <?= $question ?>
                </div>
            </section>
            
            <!--=========== EXAM PDF FORM SECTION ===========-->
            <section class="hc_section">
                <div class="hc_container">
                    <h4 class="form_title text_center">Exam PDF</h4>
                    <p class="form_subtitle text_center">Submit your PDF to close the exam</p>
                    
                    <?= $alert ?>
            
                    <!--===== EXAM PDF UPLOAD FORM =====-->
                    <form action="" method="post" class="ep_grid ep_center" enctype="multipart/form-data">
                        <div class="input_grp">
                            <input type="file" name="exam_pdf" id="" class="input_sm trasparent">
                        </div>
            
                        <div class="input_grp">
                            <button type="submit" name="update" class="m_auto btn_sm">Submit</button>
                        </div>
                    </form>
                </div>
            </section>
            <?php 
        } else {
            ?>
            <script type="text/javascript">
                window.location.href = '<?= $base_url ?>exam/';
            </script>
            <?php 
        }
    } else {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>exam/';
        </script>
        <?php 
    }
}?>

<!--=========== CUSTOM ===========-->
<script>
    document.addEventListener('contextmenu', event => event.preventDefault());
</script>

<?php include('../assets/includes/dashboard_footer.php'); ?>