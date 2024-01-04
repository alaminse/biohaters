<?php include('../assets/includes/header.php'); ?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Exam</h4>
        </div>
    </div>

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
                
                $exam_valid_time = date('Y-m-d H:i:s', (strtotime($exam_valid_time) + ($exam_mcq_duration * 60)));

                // create rank board array
                $rank_data = array(
                    'exam_id' => $exam_id,
                    'exam_name' => $exam_name,
                );

                // fetch all student from attempt
                $select_attempt = "SELECT * FROM hc_exam_attempt WHERE exam = '$exam_id' AND course = '$exam_course' AND (submission_status = 'Late Submit' OR attempt_date > '$exam_valid_time')";
                $sql_attempt = mysqli_query($db, $select_attempt);
                $num_attempt = mysqli_num_rows($sql_attempt);
                if ($num_attempt > 0) {
                    while ($row_attempt = mysqli_fetch_assoc($sql_attempt)) {
                        $student_id                 = $row_attempt['student_id'];
                        $student_roll               = $row_attempt['roll'];
                        $student_score          = $row_attempt['score'];
                        $student_submission_status  = $row_attempt['submission_status'];
                        $student_attempt_date       = $row_attempt['attempt_date'];
                        
                        $student_attempt_date       = date('d M, Y || h:i:s a', strtotime($student_attempt_date));

                        $student_data = array(
                            'student_id'        => $student_id,
                            'student_roll'      => $student_roll,
                            'submission_status' => $student_submission_status,
                            'attempt_date'      => $student_attempt_date,
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

                        // // fetch attempt data
                        // $select_attempt_data = "SELECT * FROM hc_attempt_answer WHERE student_id = '$student_id' AND exam = '$exam_id'";
                        // $sql_attempt_data = mysqli_query($db, $select_attempt_data);
                        // $num_attempt_data = mysqli_num_rows($sql_attempt_data);
                        // if ($num_attempt_data > 0) {
                        //     $correct_answers = 0;
                        //     $wrong_answers = 0;
                        //     $no_touch = 0;
                        //     while ($row_attempt_data = mysqli_fetch_assoc($sql_attempt_data)) {
                        //         $question_id        = $row_attempt_data['question'];
                        //         $submitted_option   = $row_attempt_data['submitted_option'];

                        //         // fetch question correct answer
                        //         $correct_data = "SELECT * FROM hc_question_option WHERE question = '$question_id' AND is_correct = 1";
                        //         $sql_correct_data = mysqli_query($db, $correct_data);
                        //         $row_correct_data = mysqli_fetch_assoc($sql_correct_data);
                        //         $correct_data_id      = $row_correct_data['id'];
                        //         $correct_data_text    = $row_correct_data['option_name'];
                                
                        //         // calculate mark
                        //         if ($submitted_option == 0) {
                        //             $no_touch++;
                        //         } else {
                        //             if ($submitted_option == $correct_data_id) {
                        //                 $correct_answers++;
                        //             } else {
                        //                 $wrong_answers++;
                        //             }
                        //         }
                        //     }

                        //     // define mark
                        //     $gain_mark = ($correct_answers * $exam_mark_per_question) - ($wrong_answers * $exam_negative_marking);
                        //     $total_mark = $exam_total_question * $exam_mark_per_question;

                        //     $student_data['gain_mark'] = $gain_mark;
                        //     $student_data['total_mark'] = $total_mark;
                        // }
                        
                        $gain_mark = $student_score;
                        $total_mark = $exam_total_question * $exam_mark_per_question;
                        
                        $student_data['gain_mark'] = $gain_mark;
                        $student_data['total_mark'] = $total_mark;
                        $student_data['submit_time'] = $student_submit_time;

                        $rank_data['student_data'][] = $student_data;
                    }
                }
            }
        }?>
        <!-- welcome message -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== MANAGE COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Late Submit Result - <?php echo $exam_name; ?></h5>
                    </div>

                    <table class="ep_table" id="datatable">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>Roll</th>
                                <th>Mark</th>
                                <th>Submission Status</th>
                                <th>Submission Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rank_data['student_data'])) {
                                // Sort the student data based on gain marks in descending order
                                usort($rank_data['student_data'], function ($a, $b) {
                                    return ($b['gain_mark'] <=> $a['gain_mark']);
                                });
        
                                // Initialize rank counter and previous gain marks
                                $rank = 1;
        
                                // Loop through the sorted student data and display the data
                                foreach ($rank_data['student_data'] as $key => $student_data) {
                                    $student_id                 = $student_data['student_id'];
                                    $student_roll               = $student_data['student_roll'];
                                    $student_submission_status  = $student_data['submission_status'];
                                    $student_attempt_date       = $student_data['attempt_date'];
                                    $student_name               = $student_data['student_name'];
                                    $student_college            = $student_data['student_college'];
                                    $gain_mark                  = $student_data['gain_mark'];
                                    $total_mark                 = $student_data['total_mark'];
        
                                    // Check for ties in ranks
                                    if ($key > 0 && $student_data['gain_mark'] !== $rank_data['student_data'][$key - 1]['gain_mark']) {
                                        // If the current gain marks are different from the previous student, update the rank
                                        $rank++;
                                    }?>
                                    <tr>
                                        <td><?= $rank ?></td>
                                        <td><?= $student_name ?></td>
                                        <td><?= $student_roll ?></td>
                                        <td><?= $gain_mark . ' out of ' . $total_mark ?></td>
                                        <td><?= $student_submission_status ?></td>
                                        <td><?= $student_attempt_date ?></td>
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

<?php include('../assets/includes/footer.php'); ?>