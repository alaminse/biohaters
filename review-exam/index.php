<?php include('../assets/includes/dashboard_header.php'); ?>

<?php if (isset($_POST['exam_submit'])) {
    $course_id          = $_POST['course_id'];
    $exam_id            = $_POST['exam_id'];
    $total_score        = $_POST['total_score'];
    $positive_marking   = $_POST['positive_marking'];
    $negative_marking   = $_POST['negative_marking'];
    $submited_time      = $_POST['submited_time'];

    // check late or in time submit
    $now = date('Y-m-d H:i:s', time());
    if ($now <= $submited_time) {
        $submission_status = 'In Time';
    } else {
        $submission_status = 'Late Submit';
    }

    $submission_time_text = date('g:i:s a', time());

    // fetch exam
    $select_exam = "SELECT * FROM hc_exam WHERE id = '$exam_id'";
    $sql_exam = mysqli_query($db, $select_exam);
    $row_exam = mysqli_fetch_assoc($sql_exam);
    $exam_name = $row_exam['name'];
    $exam_duration = $row_exam['mcq_duration'];

    // check attempt
    $check_attempt = "SELECT * FROM hc_exam_attempt WHERE exam = '$exam_id' AND course = '$course_id' AND student_id = '$student_id'";
    $sql_check_attempt = mysqli_query($db, $check_attempt);
    $num_check_attempt = mysqli_num_rows($sql_check_attempt);

    if ($num_check_attempt > 0) {
        // Initialize variables to track the score
        $correct_answers = 0;
        $wrong_answers = 0;
        $no_touch = 0;

        foreach ($_POST['question'] as $question_id) {
            // Check if the question was attempted by the student
            if (isset($_POST['answer_' . $question_id])) {
                // Student attempted this question, get the submitted answer
                $student_answer = $_POST['answer_' . $question_id];

                // Fetch the correct option ID for the question
                $select_correct_answer = "SELECT * FROM hc_question_option WHERE question = '$question_id' AND is_correct = 1";
                $sql_correct_answer = mysqli_query($db, $select_correct_answer);
                $num_correct_answer = mysqli_num_rows($sql_correct_answer);
                if ($num_correct_answer > 0) {
                    $row_correct_answer = mysqli_fetch_assoc($sql_correct_answer);
                    $correct_answer_id    = $row_correct_answer['id'];
                    $correct_answer_title = $row_correct_answer['option_name'];
                }
    
                // Check if the student's answer is correct or wrong
                if ($student_answer == $correct_answer_id) {
                    $correct_answers++;
                } else {
                    $wrong_answers++;
                }
            } else {
                // Student didn't attempt this question (No Touch)
                $no_touch++;
            }
        }
        
        $examData = array(
            'exam_id' => $exam_id,
            'questions' => array(),
        );
        
        foreach ($_POST['question'] as $question_id) {
            // fetch question data
            $question_data = "SELECT * FROM hc_exam_question WHERE id = '$question_id'";
            $sql_question_data = mysqli_query($db, $question_data);
            $row_question_data = mysqli_fetch_assoc($sql_question_data);
            $question_title         = $row_question_data['title'];
            $question_topic         = $row_question_data['topic'];
            $question_explaination  = $row_question_data['explaination'];
            
            // Check if the question was attempted by the student
            if (isset($_POST['answer_' . $question_id])) {
                // Student attempted this question, get the submitted answer
                $student_answer = $_POST['answer_' . $question_id];
            } else {
                // Student didn't attempt this question
                $student_answer = 0;
            }
            
            // fetch option data
            $given_option_data = "SELECT * FROM hc_question_option WHERE id = '$student_answer'";
            $sql_given_option_data = mysqli_query($db, $given_option_data);
            $num_given_option_data = mysqli_num_rows($sql_given_option_data);
            if ($num_given_option_data > 0) {
                $row_given_option_data = mysqli_fetch_assoc($sql_given_option_data);
                $given_option_text     = $row_given_option_data['option_name'];
            } else {
                $given_option_text     = '';
            }

            // fetch correct data
            $correct_data = "SELECT * FROM hc_question_option WHERE question = '$question_id' AND is_correct = 1";
            $sql_correct_data = mysqli_query($db, $correct_data);
            $row_correct_data = mysqli_fetch_assoc($sql_correct_data);
            $correct_data_id = $row_correct_data['id'];
            $correct_data_text = $row_correct_data['option_name'];
            
            // fetch question option
            $select_options = "SELECT * FROM hc_question_option WHERE question = '$question_id' ORDER BY id ASC";
            $sql_options = mysqli_query($db, $select_options);
            $num_options = mysqli_num_rows($sql_options);
            if ($num_options > 0) {
                $question = array(
                    'question_id'           => $question_id,
                    'question_text'         => $question_title,
                    'question_topic'        => $question_topic,
                    'question_explaination' => $question_explaination,
                    'given_answer'          => $student_answer,
                    'correct_answer'        => $correct_data_id,
                );
                
                while ($row_options = mysqli_fetch_assoc($sql_options)) {
                    $option_id      = $row_options['id'];
                    $option_title   = $row_options['option_name'];
                    $option_correct = $row_options['is_correct'];
                    $question['options'][] = array('option_id' => $option_id, 'option_text' => $option_title, 'is_correct' => $option_correct);
                }
            }

            $examData['questions'][] = $question;
        }

        // Calculate the score based on your marking scheme
        $gain_score = ($correct_answers * $positive_marking) - ($wrong_answers * $negative_marking);

        // Calculate the percentage
        $score_percentage = round((($gain_score / $total_score) * 100), 2);

        $store_score = $gain_score . ' out of ' . $total_score;

        // Determine the status based on the percentage
        if ($score_percentage >= 85) {
            $status = 'Excellent';
        } elseif (($score_percentage >= 70) && ($score_percentage < 85)) {
            $status = 'Good';
        } elseif (($score_percentage >= 55) && ($score_percentage < 70)) {
            $status = 'Satisfactory';
        } elseif ($score_percentage < 55) {
            $status = 'Not Satisfactory';
        }?>
        <!--=========== PAGE TITLE SECTION ===========-->
        <section class="page_section hc_section">
            <div class="hc_container">
                <h3 class="hc_page_title">Exam - <?= $exam_name ?></h3>
                <h5 class="hc_page_subtitle">Mark: <?= $total_score ?> | Duration: <?= $exam_duration ?> Minutes</h5>
                <h5 class="hc_page_subtitle">Correct: <?= $correct_answers ?> | Wrong: <?= $wrong_answers ?></h5>
                <h5 class="hc_page_subtitle">Untouch: <?= $no_touch ?> | Obtained: <?= $store_score ?></h5>
                <h5 class="hc_page_subtitle">Percentage: <?= $score_percentage ?>% | Status: <?= $status ?></h5>
                <h5 class="hc_page_subtitle">Submission Time: <?= $submission_time_text ?> | Submission Status: <?= $submission_status ?></h5>
            </div>
        </section>

        <!--=========== RESOURCE SECTION ===========-->
        <section class="hc_section">
            <div class="exam_attempt_container hc_container ep_grid">
                <?php if (isset($examData['questions']) && !empty($examData['questions'])) {
                    $si = 0;
                    $correct_answers = 0;
                    $wrong_answers = 0;
                    $no_touch = 0;
                    foreach ($examData['questions'] as $question) {
                        $si++;
                        ?>
                        <!--=== Single Question ===-->
                        <div class="exam_attempt_single">
                            <?php $feedback_status = '';
                            if ($question['given_answer'] == '0') {
                                $no_touch++;
                                $feedback_status = 'notouch';
                                $feedback_status_text = '<div class="feedback_status untouched_option"><i class="bx bx-error-circle"></i> Untouch</div>';
                            } else {
                                if ($question['given_answer'] == $question['correct_answer']) {
                                    $correct_answers++;
                                    $feedback_status = 'correct';
                                    $feedback_status_text = '<div class="feedback_status correct_option"><i class="bx bx-check-circle"></i> Correct</div>';
                                } else {
                                    $wrong_answers++;
                                    $feedback_status = 'wrong';
                                    $feedback_status_text = '<div class="feedback_status wrong_option"><i class="bx bx-x-circle"></i> Wrong</div>';
                                }
                            }?>
                            
                            <!--=== Question ===-->
                            <div class="exam_attempt_single_question">
                                <?= $si ?>. <?= $question['question_text'] . ' ' . $feedback_status_text ?>
                            </div>
                            
                            <!--=== Options ===-->
                            <div class="exam_attempt_single_options">
                                <?php $opt = range('a', 'z'); // #00a900
                                foreach ($question['options'] as $index => $option) { $current_opt = $opt[$index]; ?>
                                    <!--=== Option ===-->
                                    <div class="exam_attempt_single_option <?php if ($feedback_status == 'notouch') {
                                        if ($option['option_id'] == $question['correct_answer']) { echo 'untouched_option'; }
                                    } elseif ($feedback_status == 'correct') {
                                        if ($option['option_id'] == $question['correct_answer']) { echo 'correct_option'; }
                                    } elseif ($feedback_status == 'wrong') {
                                        if ($option['option_id'] == $question['correct_answer']) { echo 'correct_option'; } elseif ($option['option_id'] == $question['given_answer']) { echo 'wrong_option'; }
                                    }?>">
                                        <?= '(' . $current_opt . ')'; ?>
                                        <label for="option_<?= $option['option_id'] ?>"><?= $option['option_text'] ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                            
                            <div class="mt_75 answer_reference">
                                <?php if ( $question['question_topic'] != '') {
                                    ?>
                                    <div>টপিকঃ <?php echo $question['question_topic']; ?></div>
                                    <?php 
                                }?>
                                <?php if ( $question['question_explaination'] != '') {
                                    ?>
                                    <div>ব্যাখ্যাঃ <?php echo $question['question_explaination']; ?></div>
                                    <?php 
                                }?>
                            </div>
                        </div>
                        <?php 
                    }
                }?>
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
} elseif (isset($_GET['exam'])) {
    $exam_id = $_GET['exam'];

    if ($exam_id == '') {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>exam/';
        </script>
        <?php 
    }

    // fetch exam 
    $select_exam = "SELECT * FROM hc_exam WHERE id = '$exam_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
    $sql_exam = mysqli_query($db, $select_exam);
    $num_exam = mysqli_num_rows($sql_exam);
    if ($num_exam > 0) {
        $row_exam = mysqli_fetch_assoc($sql_exam);
        $exam_id                = $row_exam['id'];
        $exam_name              = $row_exam['name'];
        $exam_course            = $row_exam['course_id'];
        $exam_mcq               = $row_exam['mcq'];
        $exam_total_question    = $row_exam['total_question'];
        $exam_mark_per_question = $row_exam['mark_per_question'];
        $exam_negative_marking  = $row_exam['negative_marking'];
        $exam_mcq_duration      = $row_exam['mcq_duration'];
        $exam_date              = $row_exam['created_date'];

        $now = date('g:i:s a', time());

        $submited_time = date('Y-m-d H:i:s', time() + (($exam_mcq_duration + 1) * 60));
        $submited_time_text = date('g:i:s a', time() + (($exam_mcq_duration + 1) * 60));
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
    
    // check attempt
    $check_attempt = "SELECT * FROM hc_exam_attempt WHERE exam = '$exam_id' AND course = '$exam_course' AND student_id = '$student_id'";
    $sql_check_attempt = mysqli_query($db, $check_attempt);
    $num_check_attempt = mysqli_num_rows($sql_check_attempt);

    if ($num_check_attempt == 0) {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>exam/';
        </script>
        <?php 
    }
    
    // intialize now time
    $now_scheduled = date('Y-m-d H:i:s', time());

    // if this course is my course then exam start
    if ($course_found) {
        if ($now_scheduled >= $exam_date) {
            // fetch exam elements
            $select_exam_elements = "SELECT * FROM hc_exam_question WHERE exam = '$exam_id' AND course = '$exam_course' AND type = 'MCQ' AND is_delete = 0 ORDER BY RAND() LIMIT $exam_total_question";
            $sql_exam_elements = mysqli_query($db, $select_exam_elements);
            $num_exam_elements = mysqli_num_rows($sql_exam_elements);
    
            if ($num_exam_elements > 0) {
                $examData = array(
                    'exam_id' => $exam_id,
                );
                // Mock exam data (Replace this with data fetched from the database)
                while ($row_exam_elements = mysqli_fetch_assoc($sql_exam_elements)) {
                    $question_id    = $row_exam_elements['id'];
                    $question_title = $row_exam_elements['title'];
    
                    // fetch question option
                    $select_options = "SELECT * FROM hc_question_option WHERE question = '$question_id' ORDER BY RAND()";
                    $sql_options = mysqli_query($db, $select_options);
                    $num_options = mysqli_num_rows($sql_options);
                    if ($num_options > 0) {
                        $question = array(
                            'question_id' => $question_id,
                            'question_text' => $question_title,
                        );
                        while ($row_options = mysqli_fetch_assoc($sql_options)) {
                            $option_id    = $row_options['id'];
                            $option_title = $row_options['option_name'];
                            $question['options'][] = array('option_id' => $option_id, 'option_text' => $option_title);
                        }
                    }
    
                    $examData['questions'][] = $question;
                }
            }?>
            <!--=========== PAGE TITLE SECTION ===========-->
            <section class="page_section hc_section">
                <div class="hc_container">
                    <h3 class="hc_page_title">Exam - <?= $exam_name ?></h3>
                    <h5 class="hc_page_subtitle">Mark: <?= $exam_total_question * $exam_mark_per_question ?></h5>
                    <h5 class="hc_page_subtitle">Duration: <?= $exam_mcq_duration ?> minutes</h5>
                    <h5 class="hc_page_subtitle">Last Submit Time: <?= $submited_time_text ?></h5>
                </div>
            </section>
    
            <!--=========== RESOURCE SECTION ===========-->
            <section class="hc_section">
                <form action="" method="post" class="exam_attempt_container hc_container ep_grid">
                    <?php $si = 0;
                    foreach ($examData['questions'] as $question) { $si++; ?>
                        <!--=== Single Question ===-->
                        <div class="exam_attempt_single">
                            <!--=== Question ===-->
                            <input type="hidden" name="question[]" id="" value="<?= $question['question_id'] ?>">
                            <div class="exam_attempt_single_question">
                                <?= $si ?>. <?= $question['question_text'] ?>
                            </div>
    
                            <!--=== Options ===-->
                            <div class="exam_attempt_single_options">
                                <?php foreach ($question['options'] as $option) { ?>
                                    <!--=== Option ===-->
                                    <div class="exam_attempt_single_option">
                                        <input type="radio" id="option_<?= $option['option_id'] ?>" name="answer_<?= $question['question_id'] ?>" value="<?= $option['option_id'] ?>">
                                        <label for="option_<?= $option['option_id'] ?>"><?= $option['option_text'] ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <!-- course id -->
                    <input type="hidden" name="course_id" id="" value="<?= $exam_course ?>">
    
                    <!-- exam id -->
                    <input type="hidden" name="exam_id" id="" value="<?= $exam_id ?>">
    
                    <!-- total score -->
                    <input type="hidden" name="total_score" id="" value="<?= $exam_total_question * $exam_mark_per_question ?>">
    
                    <!-- positive marking -->
                    <input type="hidden" name="positive_marking" id="" value="<?= $exam_mark_per_question ?>">
    
                    <!-- negative marking -->
                    <input type="hidden" name="negative_marking" id="" value="<?= $exam_negative_marking ?>">
    
                    <!-- submited time -->
                    <input type="hidden" name="submited_time" id="" value="<?= $submited_time ?>">
    
                    <button type="submit" class="m_auto" name="exam_submit">Submit</button>
                </form>
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