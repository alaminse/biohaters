<?php include('../assets/includes/dashboard_header.php'); ?>

<?php if (isset($_GET['exam'])) {
    $exam_id = $_GET['exam'];

    if (empty($exam_id)) {
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
        $exam_id            = $row_exam['id'];
        $exam_name          = $row_exam['name'];
        $exam_course        = $row_exam['course_id'];
        $exam_cq            = $row_exam['cq'];
        $exam_mark          = $row_exam['mark'];
        $exam_cq_duration   = $row_exam['cq_duration'];
        $exam_date          = $row_exam['created_date'];
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

    // if this course is my course then exam start
    if ($course_found) {
        // check attempt
        $check_attempt = "SELECT * FROM hc_exam_attempt WHERE exam = '$exam_id' AND course = '$exam_course' AND student_id = '$student_id'";
        $sql_check_attempt = mysqli_query($db, $check_attempt);
        $num_check_attempt = mysqli_num_rows($sql_check_attempt);

        if ($num_check_attempt > 0) {
            // fetch attempt details
            $attempt_details = "SELECT * FROM hc_attempt_answer WHERE student_id = '$student_id' AND exam = '$exam_id'";
            $sql_attempt_details = mysqli_query($db, $attempt_details);
            $num_attempt_details = mysqli_num_rows($sql_attempt_details);
            if ($num_attempt_details > 0) {
                $examData = array(
                    'exam_id' => $exam_id,
                    'questions' => array(),
                );
                // fetch questions
                while ($row_attempt_details = mysqli_fetch_assoc($sql_attempt_details)) {
                    $question_id    = $row_attempt_details['question'];
                    $given_option   = $row_attempt_details['submitted_option'];

                    // fetch question data
                    $question_data = "SELECT * FROM hc_exam_question WHERE id = '$question_id'";
                    $sql_question_data = mysqli_query($db, $question_data);
                    $row_question_data = mysqli_fetch_assoc($sql_question_data);
                    $question_title         = $row_question_data['title'];
                    $question_topic         = $row_question_data['topic'];
                    $question_explaination  = $row_question_data['explaination'];

                    // fetch option data
                    $given_option_data = "SELECT * FROM hc_question_option WHERE id = '$given_option'";
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
                            'given_answer'          => $given_option,
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
            }
        } else {
            ?>
            <script type="text/javascript">
                window.location.href = '<?= $base_url ?>exam/';
            </script>
            <?php 
        }?>
        <!--=========== PAGE TITLE SECTION ===========-->
        <section class="page_section hc_section">
            <div class="hc_container">
                <h3 class="hc_page_title">Exam Details - <?= $exam_name ?></h3>
                <h5 class="hc_page_subtitle">Mark: <?= $exam_total_question * $exam_mark_per_question ?></h5>
                <h5 class="hc_page_subtitle">Duration: <?= $exam_mcq_duration ?> minutes</h5>
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
                                <div>টপিকঃ <?php echo $question['question_topic']; ?></div>
                                <div>ব্যাখ্যাঃ <?php echo $question['question_explaination']; ?></div>
                            </div>
                        </div>
                        <?php 
                    }
                }?>
                
                <div class="ep_flex ep_end">
                    <a href="<?= $base_url ?>download-attempt-details/?exam=<?= $exam_id ?>" class="button btn_sm">Download PDF</a>
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
    }
}?>
<!--=========== PAGE TITLE SECTION ===========-->
<section class="page_section hc_section">
    <div class="ep_flex hc_container">
        <h3 class="hc_page_title">Free Honey For You</h3>
    </div>
</section>

<!--=========== MARKED BOOK SECTION ===========-->
<section class="hc_section">
    <!--==== MARKED BOOK SUBJECT TAB ====-->
    <div class="marked_book_tabs hc_container ep_flex ep_start">
        <a href="<?= $base_url ?>marked-book-pdf/" class="marked_book_tab button <?php if (!isset($_GET['physics']) && !isset($_GET['chemistry'])) { echo 'active'; }?>">Biology</a>
        <a href="<?= $base_url ?>marked-book-pdf/?physics" class="marked_book_tab button <?php if (isset($_GET['physics'])) { echo 'active'; }?>">Physics</a>
        <a href="<?= $base_url ?>marked-book-pdf/?chemistry" class="marked_book_tab button <?php if (isset($_GET['chemistry'])) { echo 'active'; }?>">Chemistry</a>
    </div>
    
    <div class="marked_book_container hc_container ep_grid">
        <?php // fetch marked book chapter list
        if (isset($_GET['physics'])) {
            $select = "SELECT * FROM hc_marked_book_chapter WHERE subject IN ('পদার্থবিজ্ঞান প্রথম পত্র','পদার্থবিজ্ঞান দ্বিতীয় পত্র') AND is_delete = 0 ORDER BY id ASC";
        } elseif (isset($_GET['chemistry'])) {
            $select = "SELECT * FROM hc_marked_book_chapter WHERE subject IN ('রসায়ন প্রথম পত্র','রসায়ন দ্বিতীয় পত্র') AND is_delete = 0 ORDER BY id ASC";
        } else {
            $select = "SELECT * FROM hc_marked_book_chapter WHERE subject IN ('উদ্ভিদবিজ্ঞান','প্রাণীবিজ্ঞান') AND is_delete = 0 ORDER BY id ASC";
        }
        $sql = mysqli_query($db, $select);
        $num = mysqli_num_rows($sql);
        if ($num > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $chapter_list_id            = $row['id'];
                $chapter_list_subject       = $row['subject'];
                $chapter_list_chapter       = $row['chapter'];
                
                // select existing pdf writer
                $select_writer = "SELECT * FROM hc_marked_book_pdf WHERE chapter = '$chapter_list_id' AND is_delete = 0 ORDER BY id ASC";
                $sql_writer = mysqli_query($db, $select_writer);
                $num_writer = mysqli_num_rows($sql_writer);
                if ($num_writer > 0) {
                    ?>
                    <div class="marked_book_card">
                        <h4 class="marked_book_title"><?= $chapter_list_chapter ?></h4>
                        <h6 class="marked_book_subtitle">- <?= $chapter_list_subject ?></h6>
                        
                        <table class="marked_book_table w_100">
                            <thead>
                                <tr>
                                    <th>লেখক</th>
                                    <!--<th>পিডিএফ</th>-->
                                    <th colspan="2">কন্টেন্ট</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row_writer = mysqli_fetch_assoc($sql_writer)) {
                                    $pdf_id             = $row_writer['id'];
                                    $pdf_writer         = $row_writer['writer'];
                                    $pdf_marked_doc     = $row_writer['marked_doc'];
                                    $pdf_marked_solve   = $row_writer['mcq_solve'];
                                    $pdf_marked_class   = $row_writer['class_video'];
                                    ?>
                                    <tr>
                                        <td rowspan="3" class="text_bold"><?= $pdf_writer ?></td>
                                        
                                        <td class="text_bold">দাগানো বই</td>
                                        
                                        <td>
                                            <?php if ($pdf_marked_doc != '') {
                                                ?>
                                                <a href="<?= $pdf_marked_doc ?>" download><i class='bx bxs-download'></i>Download</a>
                                                <?php
                                            } else { echo '-/-'; }?>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="text_bold">অনুশীলনী সলভ</td>
                                        
                                        <td>
                                            <?php if ($pdf_marked_solve != '') {
                                                ?>
                                                <a href="<?= $pdf_marked_solve ?>" download><i class='bx bxs-download'></i>Download</a>
                                                <?php
                                            } else { echo '-/-'; }?>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="text_bold">সলভ ক্লাস</td>
                                        
                                        <td>
                                            <?php if ($pdf_marked_class != '') {
                                                ?>
                                                <a href="<?= $pdf_marked_class ?>" target="_blank"><i class='bx bxs-video' ></i>View</a>
                                                <?php
                                            } else { echo '-/-'; }?>
                                        </td>
                                    </tr>
                                    <?php 
                                }?>
                            </tbody>
                        </table>
                    </div>
                    <?php 
                }
            }
        }?>
    </div>
</section>

<?php include('../assets/includes/dashboard_footer.php'); ?>