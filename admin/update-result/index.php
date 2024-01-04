<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['exam'])) {
    $exam_id = $_GET['exam'];

    if (empty($exam_id)) {
        ?>
        <script type="text/javascript">
            window.location.href = '../exam/';
        </script>
        <?php 
    }

    // fetch exam
    $select_exam = "SELECT * FROM hc_exam WHERE id = '$exam_id' AND status = 1 AND is_delete = 0";
    $sql_exam = mysqli_query($db, $select_exam);
    $num_exam = mysqli_num_rows($sql_exam);
    if ($num_exam > 0) {
        while ($row_exam = mysqli_fetch_assoc($sql_exam)) {
            $exam_id                = $row_exam['id'];
            $exam_name              = $row_exam['name'];
            $exam_course            = $row_exam['course_id'];
            $exam_mcq               = $row_exam['mcq'];
            $exam_total_question    = $row_exam['total_question'];
            $exam_mark_per_question = $row_exam['mark_per_question'];
            $exam_negative_marking  = $row_exam['negative_marking'];
            $exam_cq                = $row_exam['cq'];
            $exam_mark              = $row_exam['mark'];
            $exam_mcq_duration      = $row_exam['mcq_duration'];
            $exam_cq_duration       = $row_exam['cq_duration'];
            $exam_valid_time        = $row_exam['valid_time'];
            $exam_date              = $row_exam['created_date'];

            $exam_date_text = date('d M, Y', strtotime($exam_date));

            // create rank board array
            $rank_data = array(
                'exam_id' => $exam_id,
                'exam_name' => $exam_name,
            );

            // fetch all student from attempt
            $select_attempt = "SELECT * FROM hc_exam_attempt WHERE exam = '$exam_id' AND course = '$exam_course'";
            $sql_attempt = mysqli_query($db, $select_attempt);
            $num_attempt = mysqli_num_rows($sql_attempt);
            if ($num_attempt > 0) {
                while ($row_attempt = mysqli_fetch_assoc($sql_attempt)) {
                    $student_id     = $row_attempt['student_id'];
                    $student_roll   = $row_attempt['roll'];
                    $student_submit_time = $row_attempt['attempt_date'];

                    $student_data = array(
                        'student_id' => $student_id,
                        'student_roll' => $student_roll,
                    );
                    
                    // fetch student info
                    $select_student_info = "SELECT * FROM hc_student WHERE id = '$student_id' AND roll = '$student_roll'";
                    $sql_student_info = mysqli_query($db, $select_student_info);
                    $num_student_info = mysqli_num_rows($sql_student_info);
                    if ($num_student_info > 0) {
                        while ($row_student_info = mysqli_fetch_assoc($sql_student_info)) {
                            $student_name       = $row_student_info['name'];
                            $student_college    = $row_student_info['college'];
                            
                            $student_data['student_name'] = $student_name;
                            $student_data['student_college'] = $student_college;
                        }
                    }

                    // fetch attempt data
                    $select_attempt_data = "SELECT * FROM hc_attempt_answer WHERE student_id = '$student_id' AND exam = '$exam_id'";
                    $sql_attempt_data = mysqli_query($db, $select_attempt_data);
                    $num_attempt_data = mysqli_num_rows($sql_attempt_data);
                    if ($num_attempt_data > 0) {
                        $correct_answers = 0;
                        $wrong_answers = 0;
                        $no_touch = 0;
                        while ($row_attempt_data = mysqli_fetch_assoc($sql_attempt_data)) {
                            $question_id        = $row_attempt_data['question'];
                            $submitted_option   = $row_attempt_data['submitted_option'];

                            // fetch question correct answer
                            $correct_data = "SELECT * FROM hc_question_option WHERE question = '$question_id' AND is_correct = 1";
                            $sql_correct_data = mysqli_query($db, $correct_data);
                            $row_correct_data = mysqli_fetch_assoc($sql_correct_data);
                            $correct_data_id      = $row_correct_data['id'];
                            $correct_data_text    = $row_correct_data['option_name'];
                            
                            // calculate mark
                            if ($submitted_option == 0) {
                                $no_touch++;
                            } else {
                                if ($submitted_option == $correct_data_id) {
                                    $correct_answers++;
                                } else {
                                    $wrong_answers++;
                                }
                            }
                        }

                        // define mark
                        $gain_mark = ($correct_answers * $exam_mark_per_question) - ($wrong_answers * $exam_negative_marking);
                        $total_mark = $exam_total_question * $exam_mark_per_question;

                        $student_data['gain_mark'] = $gain_mark;
                        $student_data['total_mark'] = $total_mark;
                        $student_data['submit_time'] = $student_submit_time;
                    }

                    $rank_data['student_data'][] = $student_data;
                }
            }
        }
    }
    
    if (!empty($rank_data['student_data'])) {
        // Loop through the sorted student data and display the data
        foreach ($rank_data['student_data'] as $key => $student_data) {
            $student_id     = $student_data['student_id'];
            $student_roll   = $student_data['student_roll'];
            $gain_mark      = $student_data['gain_mark'];
            
            // update mark of student
            $update_mark = "UPDATE hc_exam_attempt SET score = '$gain_mark' WHERE exam = '$exam_id' AND student_id = '$student_id'";
            $sql_update = mysqli_query($db, $update_mark);
        }?>
        <script type="text/javascript">
            window.location.href = '../exam-result/?exam=<?= $exam_id ?>';
        </script>
        <?php 
    }
} else {
    ?>
    <script type="text/javascript">
        window.location.href = '../exam/';
    </script>
    <?php 
}?>

<?php include('../assets/includes/footer.php'); ?>