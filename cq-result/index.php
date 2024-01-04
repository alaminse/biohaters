<?php include('../assets/includes/dashboard_header.php'); ?>

<?php if (isset($_GET['exam'])) {
    $exam_id = $_GET['exam'];
    
    if (empty($exam_id)) {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>exam/';
        </script>
        <?php 
    }?>
    <!--==== MAIN CONTAINER ====-->
    <section class="cq_result_section hc_section">
        <div class="cq_result_container hc_container ep_grid">
            <!--==== MAIN CONTENT ====-->
            <div class="cq_main_content ep_grid">
                <?php // fetch exam
                $select_exam = "SELECT * FROM hc_exam WHERE id = '$exam_id' AND status = 1 AND is_delete = 0";
                $sql_exam = mysqli_query($db, $select_exam);
                $num_exam = mysqli_num_rows($sql_exam);
                if ($num_exam > 0) {
                    while ($row_exam = mysqli_fetch_assoc($sql_exam)) {
                        $exam_id                = $row_exam['id'];
                        $exam_name              = $row_exam['name'];
                        $exam_cq                = $row_exam['cq'];
                        $exam_mark              = $row_exam['mark'];
                        $exam_cq_duration       = $row_exam['cq_duration'];
                        $exam_date              = $row_exam['created_date'];

                        $exam_date_text = date('d M, Y', strtotime($exam_date));
                        
                        if ($exam_cq == 1) {
                            // fetch all student from attempt
                            $select_attempt = "SELECT * FROM hc_cq_attempt WHERE student_id = '$student_id' AND exam = '$exam_id' AND checked_pdf != '' AND submission_status = 'In Time'";
                            $sql_attempt = mysqli_query($db, $select_attempt);
                            $num_attempt = mysqli_num_rows($sql_attempt);
                            if ($num_attempt > 0) {
                                while ($row_attempt = mysqli_fetch_assoc($sql_attempt)) {
                                    $attempt_student_id     = $row_attempt['student_id'];
                                    $attempt_student_roll   = $row_attempt['roll'];
                                    $attempt_student_pdf   = $row_attempt['checked_pdf'];
                                    
                                    // detect path of student profile
                                    $attempt_student_pdf = substr($attempt_student_pdf, 2);
                                    $attempt_student_pdf = $base_url . 'admin' . $attempt_student_pdf;
                                    
                                    // fetch student info
                                    $select_student_info = "SELECT * FROM hc_student WHERE id = '$attempt_student_id' AND roll = '$attempt_student_roll'";
                                    $sql_student_info = mysqli_query($db, $select_student_info);
                                    $num_student_info = mysqli_num_rows($sql_student_info);
                                    if ($num_student_info > 0) {
                                        while ($row_student_info = mysqli_fetch_assoc($sql_student_info)) {
                                            $attempt_student_name       = $row_student_info['name'];
                                            $attempt_student_college    = $row_student_info['college'];
                                        }
                                    }
    
                                    // fetch attempt data
                                    $select_attempt_data = "SELECT * FROM hc_cq_marking WHERE student_id = '$attempt_student_id' AND exam = '$exam_id'";
                                    $sql_attempt_data = mysqli_query($db, $select_attempt_data);
                                    $num_attempt_data = mysqli_num_rows($sql_attempt_data);
                                    if ($num_attempt_data > 0) {
                                        $cq_mark = 0;
                                        ?>
                                        <section class="hc_section">    
                                            <div class="cq_result_container hc_container ep_grid">
                                                <div class="cq_result_card">
                                                    <h4 class="cq_result_title">Review Details</h4>
                                                    <h6 class="cq_result_subtitle">- <?= $attempt_student_roll ?></h6>
                                                    
                                                    <table class="cq_result_table w_100">
                                                        <thead>
                                                            <tr>
                                                                <th>Question</th>
                                                                <th>Mark</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php while ($row_attempt_data = mysqli_fetch_assoc($sql_attempt_data)) {
                                                                $question_reference   = $row_attempt_data['question_reference'];
                                                                $marking   = $row_attempt_data['marking'];
                                                                $comments   = $row_attempt_data['comments'];
                                                                $cq_mark += $marking;
                                                                ?>
                                                                <tr>
                                                                    <td><?= $question_reference ?></td>
                                                                    <td><?= $marking ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">Note: <?= $comments ?></td>
                                                                </tr>
                                                                <?php 
                                                                
                                                            }?>
                                        
                                                            <tr>
                                                                <td>Total Mark</td>
                                                                <td><?= $cq_mark ?>/<?= $exam_mark ?></td>
                                                            </tr>
                                        
                                                            <tr>
                                                                <td>Checked PDF</td>
                                                                <td>
                                                                    <a href="<?= $attempt_student_pdf ?>"><i class='bx bxs-download'></i>Download</a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </section>
                                        <?php 
                                    }
                                }
                            }
                        }
                    }
                }?>
            </div>
            
            <?php // fetch exam
            $select_exam = "SELECT * FROM hc_exam WHERE id = '$exam_id' AND status = 1 AND is_delete = 0";
            $sql_exam = mysqli_query($db, $select_exam);
            $num_exam = mysqli_num_rows($sql_exam);
            if ($num_exam > 0) {
                while ($row_exam = mysqli_fetch_assoc($sql_exam)) {
                    $exam_id                = $row_exam['id'];
                    $exam_name              = $row_exam['name'];
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
                    
                    // calcaulate actual scoreboard result valid time
                    // $exam_valid_time = date('Y-m-d H:i:s', (strtotime($exam_valid_time) + ($exam_mcq_duration * 60)));

                    // create rank board array
                    $rank_data = array(
                        'exam_id' => $exam_id,
                        'exam_name' => $exam_name,
                    );

                    // fetch all student from attempt
                    $select_attempt = "SELECT * FROM hc_exam_attempt WHERE exam = '$exam_id' AND submission_status = 'In Time' AND attempt_date <= '$exam_valid_time'";
                    $sql_attempt = mysqli_query($db, $select_attempt);
                    $num_attempt = mysqli_num_rows($sql_attempt);
                    if ($num_attempt > 0) {
                        while ($row_attempt = mysqli_fetch_assoc($sql_attempt)) {
                            $attempt_student_id     = $row_attempt['student_id'];
                            $attempt_student_roll   = $row_attempt['roll'];
                            $attempt_student_score  = $row_attempt['score'];

                            $student_data = array(
                                'student_id' => $attempt_student_id,
                                'student_roll' => $attempt_student_roll,
                            );
                            
                            // fetch student info
                            $select_student_info = "SELECT * FROM hc_student WHERE id = '$attempt_student_id' AND roll = '$attempt_student_roll'";
                            $sql_student_info = mysqli_query($db, $select_student_info);
                            $num_student_info = mysqli_num_rows($sql_student_info);
                            if ($num_student_info > 0) {
                                while ($row_student_info = mysqli_fetch_assoc($sql_student_info)) {
                                    $attempt_student_name       = $row_student_info['name'];
                                    $attempt_student_college    = $row_student_info['college'];
                                    
                                    $student_data['student_name'] = $attempt_student_name;
                                    $student_data['student_college'] = $attempt_student_college;
                                }
                            }
                            
                            // fetch all student from cq attempt
                            $select_cq_attempt = "SELECT * FROM hc_cq_attempt WHERE student_id = '$attempt_student_id' AND exam = '$exam_id' AND submission_status = 'In Time'";
                            $sql_cq_attempt = mysqli_query($db, $select_cq_attempt);
                            $num_cq_attempt = mysqli_num_rows($sql_cq_attempt);
                            if ($num_cq_attempt > 0) {
                                // fetch attempt data
                                $select_attempt_data = "SELECT * FROM hc_cq_marking WHERE student_id = '$attempt_student_id' AND exam = '$exam_id'";
                                $sql_attempt_data = mysqli_query($db, $select_attempt_data);
                                $num_attempt_data = mysqli_num_rows($sql_attempt_data);
                                if ($num_attempt_data > 0) {
                                    $cq_mark = 0;
                                    while ($row_attempt_data = mysqli_fetch_assoc($sql_attempt_data)) {
                                        $marking   = $row_attempt_data['marking'];
                                        $cq_mark += $marking;
                                    }
                                }
                            } else {
                                $cq_mark = 0;
                            }
                            
                            // define mark
                            $gain_mark = $attempt_student_score + $cq_mark;
                            $total_mark = ($exam_total_question * $exam_mark_per_question) + $exam_mark;
                            
                            $student_data['mcq_mark'] = $attempt_student_score;
                            $student_data['cq_mark'] = $cq_mark;
                            $student_data['gain_mark'] = $gain_mark;
                            $student_data['total_mark'] = $total_mark;

                            $rank_data['student_data'][] = $student_data;
                        }
                    }
                }
            }?>
            
            <!--==== LEADERBOARD ====-->
            <div class="">
                <?php if (!empty($rank_data['student_data'])) {
                    ?>
                    <table class="scoreboard_table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>Roll</th>
                                <th>MCQ</th>
                                <th>CQ</th>
                                <th>Mark</th>
                                <th>College</th>
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
                                
                                foreach ($rank_data['student_data'] as $key => $student_data) {
                                    $result_student_id          = $student_data['student_id'];
                                    $result_student_roll        = $student_data['student_roll'];
                                    $result_student_name        = $student_data['student_name'];
                                    $result_student_college     = $student_data['student_college'];
                                    $result_gain_mcq            = $student_data['mcq_mark'];
                                    $result_gain_cq             = $student_data['cq_mark'];
                                    $result_gain_mark           = $student_data['gain_mark'];
                                    $result_total_mark          = $student_data['total_mark'];
        
                                    // Check for ties in ranks
                                    if ($key > 0 && $student_data['gain_mark'] !== $rank_data['student_data'][$key - 1]['gain_mark']) {
                                        // If the current gain marks are different from the previous student, update the rank
                                        $rank++;
                                    }?>
                                    <tr>
                                        <td><?= $rank ?></td>
                                        <td><?= $result_student_name ?></td>
                                        <td><?= $result_student_roll ?></td>
                                        <td><?= $result_gain_mcq ?></td>
                                        <td><?= $result_gain_cq ?></td>
                                        <td><?= $result_gain_mark . ' / ' . $total_mark ?></td>
                                        <td><?= $result_student_college ?></td>
                                    </tr>
                                    <?php 
                                }
                            }?>
                        </tbody>
                    </table>
                    <?php 
                }?>
            </div>
        </div>
    </section>
    <?php 
} else {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>exam/';
    </script>
    <?php 
}?>

<?php include('../assets/includes/dashboard_footer.php'); ?>