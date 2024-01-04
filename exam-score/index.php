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
    <section class="dashboard_section hc_section">
        <div class="dashboard_container hc_container ep_grid">
            <!--==== MAIN CONTENT ====-->
            <div class="main_content ep_grid">
                <?php if (isset($result['my_courses'])) {
                    foreach ($result['my_courses'] as $key => $my_courses) {
                        // courses id
                        $my_courses_id = $my_courses['item_id'];
    
                        // fetch my course
                        $select_my_course  = "SELECT * FROM hc_course WHERE id = '$my_courses_id' AND type = 1 AND status = 1 AND is_delete = 0";
                        $sql_my_course     = mysqli_query($db, $select_my_course);
                        $num_my_course     = mysqli_num_rows($sql_my_course);
                        if ($num_my_course > 0) {
                            $row_my_course = mysqli_fetch_assoc($sql_my_course);
    
                            // my course id
                            $my_course_id   = $row_my_course['id'];
                            $my_course_name = $row_my_course['name'];
    
                            $si = 0;
    
                            // fetch exam
                            $select_exam = "SELECT * FROM hc_exam WHERE id = '$exam_id' AND course_id = '$my_course_id' AND status = 1 AND is_delete = 0";
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
                                    $select_attempt = "SELECT * FROM hc_exam_attempt WHERE exam = '$exam_id' AND course = '$my_course_id' AND submission_status = 'In Time' AND attempt_date <= '$exam_valid_time'";
                                    $sql_attempt = mysqli_query($db, $select_attempt);
                                    $num_attempt = mysqli_num_rows($sql_attempt);
                                    if ($num_attempt > 0) {
                                        while ($row_attempt = mysqli_fetch_assoc($sql_attempt)) {
                                            $attempt_student_id     = $row_attempt['student_id'];
                                            $attempt_student_roll   = $row_attempt['roll'];
    
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
    
                                            // fetch attempt data
                                            $select_attempt_data = "SELECT * FROM hc_attempt_answer WHERE student_id = '$attempt_student_id' AND exam = '$exam_id'";
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
                                            }
    
                                            $rank_data['student_data'][] = $student_data;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }?>
                <!--==== LEADERBOARD ====-->
                <div class="dashboard_scorecard">
                    <?php if (!empty($rank_data['student_data'])) {
                        ?>
                        <div class="ep_flex mb_75">
                            <h4 class="hc_card_title">Scoreboard - <?= $rank_data['exam_name'] ?></h4>
                        </div>
                        <?php 
                    }?>
                    <table class="scoreboard_table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Roll</th>
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
                                $result_student_id         = $student_data['student_id'];
                                $result_student_roll       = $student_data['student_roll'];
                                $result_student_name       = $student_data['student_name'];
                                $result_student_college    = $student_data['student_college'];
                                $result_gain_mark          = $student_data['gain_mark'];
                                $result_total_mark         = $student_data['total_mark'];
    
                                // Check for ties in ranks
                                if ($key > 0 && $student_data['gain_mark'] !== $rank_data['student_data'][$key - 1]['gain_mark']) {
                                    // If the current gain marks are different from the previous student, update the rank
                                    $rank++;
                                }?>
                                <tr>
                                    <td><?= $rank ?></td>
                                    <td><?= $result_student_name ?></td>
                                    <td><?= $result_student_roll ?></td>
                                    <td><?= $result_gain_mark . ' / ' . $total_mark ?></td>
                                    <td><?= $result_student_college ?></td>
                                </tr>
                                <?php 
                            }
                        }?>
                    </tbody>
                </table>
                </div>
    
                <!--==== RESULT GRAPH ====-->
                <div id="result" class="w_100 result_graph"></div>
            </div>
    
            <!--==== EXTRA CONTENT ====-->
            <div class="extra_content ep_grid">
                <!--==== COURSE ====-->
                <div class="hc_card">
                    <div class="ep_flex mb_75">
                        <h4 class="hc_card_title">My Courses</h4>
                        <a href="<?= $base_url ?>student-course/">See All</a>
                    </div>
                    
                    <?php if (isset($result['my_courses'])) {
                        ?>
                        <div class="ep_grid course_list">
                            <?php foreach ($result['my_courses'] as $key => $my_courses) {
                                // courses id
                                $my_courses_id = $my_courses['item_id'];
                    
                                // fetch my course
                                $select_my_course  = "SELECT * FROM hc_course WHERE id = '$my_courses_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
                                $sql_my_course     = mysqli_query($db, $select_my_course);
                                $num_my_course     = mysqli_num_rows($sql_my_course);
                                if ($num_my_course > 0) {
                                    $row_my_course = mysqli_fetch_assoc($sql_my_course);
                    
                                    // my course variable
                                    $my_course_id              = $row_my_course['id'];
                                    $my_course_name            = $row_my_course['name'];
                                    $my_course_type            = $row_my_course['type'];
                                    $my_course_category        = $row_my_course['category'];
                                    $my_course_day_schedule    = $row_my_course['day_schedule'];
                                    $my_course_time_schedule   = $row_my_course['time_schedule'];
                                    $my_course_trailer         = $row_my_course['trailer'];
                                    $my_course_status          = $row_my_course['status'];
                                    $my_course_tags            = $row_my_course['tags'];
                                    $my_course_des             = $row_my_course['description'];
                                    $my_course_price           = $row_my_course['price'];
                                    $my_course_sale            = $row_my_course['sale_price'];
                                    $my_course_duration        = $row_my_course['duration'];
                                    $my_course_expired_date    = $row_my_course['expired_date'];
                                    $my_course_cover_photo     = $row_my_course['cover_photo'];
                                    $my_course_author          = $row_my_course['author'];
                                    $my_course_created_date    = $row_my_course['created_date'];
                    
                                    // connected with course
                                    $select_course_lecture = "SELECT COUNT(id) as course_lecture FROM hc_course_lecture WHERE course = '$my_course_id' AND is_delete = 0";
                                    $sql_course_lecture = mysqli_query($db, $select_course_lecture);
                                    $row_course_lecture = mysqli_fetch_assoc($sql_course_lecture);
                                    $course_lecture = $row_course_lecture['course_lecture'];
                                    ?>
                                    <!-- COURSE CARD -->
                                    <div class="dasboard_course_card ep_flex">
                                        <h5 class="dasboard_course_card_title"><?= $my_course_name ?></h5>
                
                                        <a href="<?= $base_url ?>course-view-lecture/?course=<?= $my_course_id ?>"><i class='bx bx-play-circle'></i></a>
                                    </div>
                                    <?php 
                                }
                            }?>
                        </div>
                        <?php 
                    }?>
                </div>
    
                <!--==== CHAPTER ====-->
                <div class="hc_card">
                    <div class="ep_flex mb_75">
                        <h4 class="hc_card_title">My Chapters</h4>
                        <a href="<?= $base_url ?>student-chapter/">See All</a>
                    </div>
                    
                    <?php if (isset($result['my_chapters'])) {
                        ?>
                        <div class="ep_grid chapter_list">
                            <?php foreach ($result['my_chapters'] as $key => $my_chapters) {
                                // chapter id
                                $my_chapters_id = $my_chapters['item_id'];
                    
                                // fetch my chapter
                                $select_my_chapter  = "SELECT * FROM hc_chapter WHERE id = '$my_chapters_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
                                $sql_my_chapter     = mysqli_query($db, $select_my_chapter);
                                $num_my_chapter     = mysqli_num_rows($sql_my_chapter);
                                if ($num_my_chapter > 0) {
                                    $row_my_chapter = mysqli_fetch_assoc($sql_my_chapter);
                    
                                    // my chapter variable
                                    $my_chapter_id             = $row_my_chapter['id'];
                                    $my_chapter_name           = $row_my_chapter['chapter'];
                                    $my_chapter_subject        = $row_my_chapter['subject'];
                                    $my_chapter_price          = $row_my_chapter['price'];
                                    $my_chapter_sale           = $row_my_chapter['sale_price'];
                                    $my_chapter_cover_photo    = $row_my_chapter['cover_photo'];
                                    $my_chapter_status         = $row_my_chapter['status'];
                                    $my_chapter_author         = $row_my_chapter['author'];
                                    $my_chapter_created_date   = $row_my_chapter['created_date'];
                    
                                    // connected with chapter
                                    $select_chapter_lecture = "SELECT COUNT(id) as chapter_lecture FROM hc_chapter_lecture WHERE chapter = '$my_chapter_id' AND is_delete = 0";
                                    $sql_chapter_lecture = mysqli_query($db, $select_chapter_lecture);
                                    $row_chapter_lecture = mysqli_fetch_assoc($sql_chapter_lecture);
                                    $chapter_lecture = $row_chapter_lecture['chapter_lecture'];
                                    ?>
                                    <!-- CHAPTER CARD -->
                                    <div class="dasboard_course_card ep_flex">
                                        <h5 class="dasboard_course_card_title"><?= $my_chapter_name ?></h5>
                
                                        <a href="<?= $base_url ?>chapter-view-lecture/?chapter=<?= $my_chapter_id ?>"><i class='bx bx-play-circle'></i></a>
                                    </div>
                                    <?php 
                                }
                            }?>
                        </div>
                        <?php 
                    }?>
                </div>
                
                <!--==== NOTICE ====-->
                <div class="hc_card">
                    <h4 class="hc_card_title">Notice Board</h4>
                    <h5 class="hc_card_subtitle">Latest Notice Info</h5>
    
                    <div class="notice_list">
                        <?php if (isset($result['notice'])) {
                            $si = 0;
                            foreach ($result['notice'] as $key => $notice) {
                                // notice id
                                $notice_id = $notice['id'];
                                $notice_created_date = $notice['created_date'];
                                
                                // joined date convert to text
                                $notice_created_date_text = date('d M, Y | h:i:s a', strtotime($notice_created_date));
        
                                $si++;
                                if ($notice_id != 1) {
                                    ?>
                                    <!-- notice card -->
                                    <a href="<?= $base_url ?>notice-view/?notice=<?= $notice_id ?>" class="notice_card">
                                        <div class="notice_content">
                                            <i class='bx bx-bell'></i>
                                        </div>
                
                                        <div class="notice_data ep_flex">
                                            <div>
                                                <div class="notice_title"><?= $notice['name'] ?></div>
                                                <div class="notice_date"><?= $notice_created_date_text ?></div>
                                            </div>
                
                                            <i class='bx bx-right-arrow-alt' ></i>
                                        </div>
                                    </a>
                                    <?php 
                                }
                            }
                        }?>
                    </div>
                </div>
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