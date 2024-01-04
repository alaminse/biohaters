<?php include('../assets/includes/dashboard_header.php'); ?>

<?php if (isset($_POST['exam_submit'])) {
    $course_id          = $_POST['course_id'];
    $exam_id            = $_POST['exam_id'];
    $total_score        = $_POST['total_score'];
    $positive_marking   = $_POST['positive_marking'];
    $negative_marking   = $_POST['negative_marking'];
    $submited_time      = $_POST['submited_time'];
    $start_time         = $_POST['start_time'];

    // check late or in time submit
    $now = date('Y-m-d H:i:s', time());
    
    $attempt_duration = time() - strtotime($start_time);
    if ($now <= $submited_time) {
        $submission_status = 'In Time';
    } else {
        $submission_status = 'Late Submit';
    }

    $submission_time_text = date('Y-m-d g:i:s a', time());

    // fetch exam
    $select_exam = "SELECT * FROM hc_exam WHERE id = '$exam_id'";
    $sql_exam = mysqli_query($db, $select_exam);
    $row_exam = mysqli_fetch_assoc($sql_exam);
    $exam_name = $row_exam['name'];

    // check attempt
    $check_attempt = "SELECT * FROM hc_exam_attempt WHERE exam = '$exam_id' AND course = '$course_id' AND student_id = '$student_id'";
    $sql_check_attempt = mysqli_query($db, $check_attempt);
    $num_check_attempt = mysqli_num_rows($sql_check_attempt);

    if ($num_check_attempt == 0) {
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
                    // add answer
                    $add_answer = "INSERT INTO hc_attempt_answer (student_id, exam, question, submitted_option) VALUES ('$student_id', '$exam_id', '$question_id', '$student_answer')";
                } else {
                    $wrong_answers++;
                    // add answer
                    $add_answer = "INSERT INTO hc_attempt_answer (student_id, exam, question, submitted_option) VALUES ('$student_id', '$exam_id', '$question_id', '$student_answer')";
                }
            } else {
                // Student didn't attempt this question (No Touch)
                $no_touch++;
                // add answer
                $add_answer = "INSERT INTO hc_attempt_answer (student_id, exam, question, submitted_option) VALUES ('$student_id', '$exam_id', '$question_id', 0)";
            }
            
            // query answer
            mysqli_query($db, $add_answer);
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
        }

        // Prepare the score message
        $msg = "Biology Haters Exam Report -\rExam- " . $exam_name . "\rScore- " . $store_score . "\rCorrect Answer- " . $correct_answers . "\rWrong Answer- " . $wrong_answers;

        // add exam attempt
        $add_attempt = "INSERT INTO hc_exam_attempt (exam, course, student_id, roll, score, status, submission_status, attempt_date, attempt_duration) VALUES ('$exam_id', '$course_id', '$student_id', '$student_roll', '$gain_score', '$status', '$submission_status', '$now', '$attempt_duration')";
        mysqli_query($db, $add_attempt);
        
        if ($course_id != 10 && $course_id != 13) {
            // exam msg to father
            // if ($student_father_phone != '') {
            //     $phone = $student_father_phone;
            //     $to = "$phone";
            //     $token = "913518264916767232092ebe8e002b72391add1856353f4a8c3b";
            //     $message = "$msg";
            
            //     $url = "http://api.greenweb.com.bd/api.php?json";
            
            
            //     $data= array(
            //     'to'=>"$to",
            //     'message'=>"$message",
            //     'token'=>"$token"
            //     ); 
            //     $ch = curl_init(); 
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            //     curl_setopt($ch, CURLOPT_ENCODING, '');
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            // }
            
            // exam msg to mother
            if ($student_mother_phone != '') {
                $phone = $student_mother_phone;
                $to = "$phone";
                $token = "913518264916767232092ebe8e002b72391add1856353f4a8c3b";
                $message = "$msg";
            
                $url = "http://api.greenweb.com.bd/api.php?json";
            
            
                $data= array(
                'to'=>"$to",
                'message'=>"$message",
                'token'=>"$token"
                ); 
                $ch = curl_init(); 
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_ENCODING, '');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $smsresult = curl_exec($ch);
            }
        }?>
        <div class="modal_container payment_modal show-modal" id="exam-score">
            <div class="modal_content payment_content">
                <div class="modal_body">
                    <div class="payment_icon_success text_center">
                        <i class='bx bx-check' ></i>
                    </div>

                    <p class="payment_success_subtitle text_center"><?= $exam_name ?></p>
                    <p class="payment_success_title text_center"><?= $store_score ?></p>

                    <div class="payment_success_data">
                        <div class="ep_flex">
                            <div class="payment_success_properties">Correct Answer</div>
                            <div class="payment_success_value text_right"><?= $correct_answers ?></div>
                        </div>

                        <div class="ep_flex">
                            <div class="payment_success_properties">Wrong Answer</div>
                            <div class="payment_success_value text_right"><?= $wrong_answers ?></div>
                        </div>

                        <div class="ep_flex">
                            <div class="payment_success_properties">No Touch</div>
                            <div class="payment_success_value text_right"><?= $no_touch ?></div>
                        </div>

                        <div class="ep_flex">
                            <div class="payment_success_properties">Percentage</div>
                            <div class="payment_success_value text_right"><?= $score_percentage ?>%</div>
                        </div>

                        <div class="ep_flex">
                            <div class="payment_success_properties">Feedback</div>
                            <div class="payment_success_value text_right"><?= $status ?></div>
                        </div>
                    </div>

                    <div class="payment_success_summery">
                        <div class="ep_flex">
                            <div class="payment_success_properties">Submission Time</div>
                            <div class="payment_success_value text_right"><?= $submission_time_text ?></div>
                        </div>

                        <div class="ep_flex">
                            <div class="payment_success_properties">Submission Status</div>
                            <div class="payment_success_value text_right"><?= $submission_status ?></div>
                        </div>
                    </div>
                </div>

                <div class="">
                    <a href="<?= $base_url ?>exam/" class="button no_hover btn_sm m_auto">Back</a>
                </div>
            </div>
        </div>
        <?php 
    } else {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>exam/';
        </script>
        <?php 
    }
}

if (isset($_GET['exam'])) {
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
        
        $now_time = date('Y-m-d H:i:s', time());

        $submited_time = date('Y-m-d H:i:s', time() + (($exam_mcq_duration) * 60) + 6);
        $submited_time_text = date('g:i:s a', time() + (($exam_mcq_duration) * 60) + 6);

        $auto_submit_time = date('Y-m-d H:i:s', time() + (($exam_mcq_duration) * 60) + 2);
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
                    $question_id        = $row_exam_elements['id'];
                    $question_title     = $row_exam_elements['title'];
                    $question_opt_sort  = $row_exam_elements['opt_sort'];
                    
                    // fetch question option
                    if ($question_opt_sort == 1) {
                        $select_options = "SELECT * FROM hc_question_option WHERE question = '$question_id' ORDER BY id ASC";
                    } else {
                        $select_options = "SELECT * FROM hc_question_option WHERE question = '$question_id' ORDER BY RAND()";
                    }
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
                    <div id="exam-timer"></div>
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
                    
                    <!-- start time -->
                    <input type="hidden" name="start_time" id="" value="<?= $now_time ?>">
    
                    <button type="submit" class="m_auto" name="exam_submit" id="exam-submit">Submit</button>
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the submission time and convert it to milliseconds
        var submittedTime = new Date("<?= $auto_submit_time ?>").getTime();

        // Function to update the timer and submit the form when time is up
        function updateTimer() {
            // Get the current time
            var currentTime = new Date().getTime();

            // Calculate the time difference
            var timeDifference = submittedTime - currentTime;

            // Check if the time has passed
            if (timeDifference <= 0) {
                // Set the time to red
                document.getElementById("exam-timer").style.color = "red";

                // Simulate a click on the submit button
                document.getElementById("exam-submit").click();
            } else {
                // Convert the time difference to minutes and seconds
                var minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                // Display the time in the "timer" element
                document.getElementById("exam-timer").style.color = "green";
                document.getElementById("exam-timer").innerHTML = "Time remaining: " + minutes + "m " + seconds + "s";
            }
        }

        // Call the updateTimer function every second
        setInterval(updateTimer, 1000);
    });
</script>

<?php include('../assets/includes/dashboard_footer.php'); ?>