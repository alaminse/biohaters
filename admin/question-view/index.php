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
        $select_exam = "SELECT * FROM hc_exam WHERE id = '$exam_id' AND is_delete = 0";
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

                // fetch exam elements
                $select_exam_elements = "SELECT * FROM hc_exam_question WHERE exam = '$exam_id' AND course = '$exam_course' AND type = 'MCQ' AND is_delete = 0 ORDER BY id ASC";
                $sql_exam_elements = mysqli_query($db, $select_exam_elements);
                $num_exam_elements = mysqli_num_rows($sql_exam_elements);
                if ($num_exam_elements > 0) {
                    $examData = array(
                        'exam_id'   => $exam_id,
                        'exam_name' => $exam_name,
                    );
                    // Mock exam data (Replace this with data fetched from the database)
                    while ($row_exam_elements = mysqli_fetch_assoc($sql_exam_elements)) {
                        $question_id    = $row_exam_elements['id'];
                        $question_title = $row_exam_elements['title'];
                        $question_topic = $row_exam_elements['topic'];
                        $question_explaination = $row_exam_elements['explaination'];
        
                        // fetch question option
                        $select_options = "SELECT * FROM hc_question_option WHERE question = '$question_id' ORDER BY id ASC";
                        $sql_options = mysqli_query($db, $select_options);
                        $num_options = mysqli_num_rows($sql_options);
                        if ($num_options > 0) {
                            $question = array(
                                'question_id' => $question_id,
                                'question_text' => $question_title,
                                'question_topic' => $question_topic,
                                'question_explaination' => $question_explaination,
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
            }
        }?>
        <!-- welcome message -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== MANAGE COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Question View - <?= $examData['exam_name']; ?></h5>
                    </div>
                    
                    <div class="exam_attempt_container ep_grid">
                        <?php $si = 0;
                        foreach ($examData['questions'] as $question) { $si++; ?>
                            <!--=== Single Question ===-->
                            <div class="exam_attempt_single">
                                <!--=== Question ===-->
                                <input type="hidden" name="question[]" id="" value="<?= $question['question_id'] ?>">
                                <div class="exam_attempt_single_question">
                                    <?= $si . '. ' . $question['question_text'] ?>
                                    
                                    <!-- EDIT BUTTON -->
                                    <a href="../mcq-edit/?question=<?php echo $question['question_id']; ?>" target="blank" class="btn_icon"><span class="text_sm">Edit</span> <i class="bx bxs-edit"></i></a>
                                </div>
        
                                <!--=== Options ===-->
                                <div class="exam_attempt_single_options">
                                    <?php $opt = range('a', 'z');
                                    foreach ($question['options'] as $index => $option) { $current_opt = $opt[$index]; ?>
                                        <!--=== Option ===-->
                                        <div class="exam_attempt_single_option <?php if ($option['is_correct'] == 1) { echo 'red_text'; }?>">
                                            <?= '(' . $current_opt . ')'; ?>
                                            <label for="option_<?= $option['option_id'] ?>"><?= $option['option_text'] ?></label>
                                        </div>
                                    <?php } ?>
                                </div>
                                
                                <div class="mt_75">
                                    <div>Topic: <?php echo $question['question_topic']; ?></div>
                                    <div>Explaination: <?php echo $question['question_explaination']; ?></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
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

<?php include('../assets/includes/footer.php'); ?>