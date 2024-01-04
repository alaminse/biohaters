<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['question'])) {
    $question = $_GET['question'];

    if (empty($question)) {
        ?>
        <script type="text/javascript">
            window.location.href = '../exam/';
        </script>
        <?php 
    }

    // get question data
    $select_question_data  = "SELECT * FROM hc_exam_question WHERE id = '$question' AND is_delete = 0";
    $sql_question_data     = mysqli_query($db, $select_question_data);
    $row_question_data     = mysqli_fetch_assoc($sql_question_data);

    $question_data_id           = $row_question_data['id'];
    $question_data_exam         = $row_question_data['exam'];
    $question_data_course       = $row_question_data['course'];
    $question_data_title        = $row_question_data['title'];
    $question_data_type         = $row_question_data['type'];
    $question_data_chapter      = $row_question_data['chapter'];
    $question_data_topic        = $row_question_data['topic'];
    $question_data_level        = $row_question_data['level'];
    $question_data_academic     = $row_question_data['academic'];
    $question_data_medical      = $row_question_data['medical'];
    $question_data_explaination = $row_question_data['explaination'];
    $question_data_opt_sort = $row_question_data['opt_sort'];

    $question_data = array(
        'id'            => $question_data_id,
        'exam'          => $question_data_exam,
        'course'        => $question_data_course,
        'title'         => $question_data_title,
        'type'          => $question_data_type,
        'chapter'       => $question_data_chapter,
        'topic'         => $question_data_topic,
        'level'         => $question_data_level,
        'academic'      => $question_data_academic,
        'medical'       => $question_data_medical,
        'explaination'  => $question_data_explaination,
        'opt_sort'      => $question_data_opt_sort,
    );

    // get question options
    $select_options  = "SELECT * FROM hc_question_option WHERE question = '$question'";
    $sql_options     = mysqli_query($db, $select_options);
    while ($row_options = mysqli_fetch_assoc($sql_options)) {
        $option_id      = $row_options['id'];
        $option_text    = $row_options['option_name'];
        $option_correct = $row_options['is_correct'];

        $question_data['options'][] = array(
            'option_id'         => $option_id,
            'option_text'       => $option_text,
            'option_correct'    => $option_correct,
        );
    }
    
    // EDIT QUESTION 
    if (isset($_POST['edit'])) {
        $questionId = $_POST['question_id']; // Assuming you have a hidden field with question ID

        $newTitle = mysqli_escape_string($db, $_POST['questions']);
        $newChapter = $_POST['chapter'];
        $newTopics = mysqli_escape_string($db, $_POST['topics']);
        $newExplanation = mysqli_escape_string($db, $_POST['explaination']);
        $newLevel = $_POST['level'];
        $newAcademic = isset($_POST['academic']) ? 1 : 0;
        $newMedical = isset($_POST['medical']) ? 1 : 0;
        $newOptFixed = isset($_POST['opt_fixed']) ? 1 : 0;

        // Update question details in the database
        $updateQuery = "UPDATE hc_exam_question SET title = '$newTitle', chapter = '$newChapter', topic = '$newTopics', level = '$newLevel', academic = '$newAcademic', medical = '$newMedical', explaination = '$newExplanation', opt_sort = '$newOptFixed' WHERE id = $questionId";

        $result = mysqli_query($db, $updateQuery);

        if ($result) {
            // Update options if necessary
            foreach ($question_data['options'] as $option) {
                $optionId = $option['option_id'];
                $newOptionText = $_POST['options_' . $optionId];
                $isCorrect = ($optionId == $_POST['correct_option']) ? 1 : 0;
        
                $newOptionText = mysqli_real_escape_string($db, $newOptionText);
        
                $updateOptionQuery = "UPDATE hc_question_option SET option_name = '$newOptionText', is_correct = $isCorrect WHERE id = $optionId";
        
                mysqli_query($db, $updateOptionQuery);
            }?>
            <script type="text/javascript">
                window.location.href = '../mcq-question/?exam=<?= $question_data_exam ?>';
            </script>
            <?php 
        }
    }?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">MCQ Questions</h4>
        </div>
    </div>
    
    <!-- question LIST -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== ADD question ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Edit Question - <?= $question_data['title'] ?></h5>
                </div>
            </div>

            <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                <div class="grid_col_2">
                    <label for="">Question Title - 1*</label>
                    <input type="text" id="" name="questions" placeholder="Question Title" value="<?= $question_data['title'] ?>" required>
                </div>

                <div>
                    <label for="">Chapter*</label>
                    <select id="" name="chapter" required>
                        <option value="">Choose Chapter</option>
                        <?php $select = "SELECT * FROM hc_chapter WHERE is_delete = 0 ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num > 0) {
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $chapter_id     = $row['id'];
                                $chapter_name   = $row['chapter'];
                                ?>
                                <option value="<?= $chapter_id ?>" <?php if ($chapter_id == $question_data['chapter']) { echo 'selected'; }?>><?= $chapter_name ?></option>
                                <?php 
                            }
                        }?>
                    </select>
                </div>

                <div class="option_container grid_col_3">
                    <?php foreach ($question_data['options'] as $option) { ?>
                        <!--=== Option ===-->
                        <div class="option_card">
                            <input type="hidden" name="option_id" value="<?= $option['option_id'] ?>">
                            <input type="radio" name="correct_option" value="<?= $option['option_id'] ?>" <?php if ($option['option_correct'] == '1') { echo 'checked'; } ?> required>
                            <input type="text" id="" name="options_<?= $option['option_id'] ?>" placeholder="" value="<?= $option['option_text'] ?>" required>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="grid_col_3">
                    <label for="opt-fixed<?= $question ?>">Option Sort Fixed?</label>
                    <label for="opt-fixed<?= $question ?>" class="checkbox_label">
                        <input type="checkbox" class="checkbox" name="opt_fixed" id="opt-fixed<?= $question ?>" <?php if ($question_data['opt_sort'] == '1') { echo 'checked'; } ?>>
                        <span class="checked"></span>
                        Yes
                    </label>
                </div>

                <div class="levels">
                    <h5 class="mb_75">Levels</h5>
                    
                    <div>
                        <input type="radio" name="level" value="easy" id="easy<?= $question_data['id'] ?>"
                            <?php if ($question_data['level'] == 'easy') { echo 'checked'; } ?>>
                        <label for="easy<?= $question_data['id'] ?>">Easy</label>
                    </div>

                    <div>
                        <input type="radio" name="level" value="typical" id="typical<?= $question_data['id'] ?>"
                            <?php if ($question_data['level'] == 'typical') { echo 'checked'; } ?>>
                        <label for="typical<?= $question_data['id'] ?>">Typical</label>
                    </div>

                    <div>
                        <input type="radio" name="level" value="advance" id="advance<?= $question_data['id'] ?>"
                            <?php if ($question_data['level'] == 'advance') { echo 'checked'; } ?>>
                        <label for="advance<?= $question_data['id'] ?>">Advance</label>
                    </div>
                </div>

                <div class="levels">
                    <h5 class="mb_75">Test</h5>
                    
                    <div>
                        <input type="checkbox" name="academic" value="academic" id="academic" <?php if ($question_data['academic'] == '1') { echo 'checked'; } ?>>
                        <label for="academic">Academic</label>
                    </div>

                    <div>
                        <input type="checkbox" name="medical" value="medical" id="medical"
                            <?php if ($question_data['medical'] == '1') { echo 'checked'; } ?>>
                        <label for="medical">Medical</label>
                    </div>
                </div>

                <div class="grid_col_3">
                    <label for="">Topics</label>
                    <textarea id="" name="topics" placeholder="Topics" rows="2"><?= $question_data['topic'] ?></textarea>
                </div>

                <div class="grid_col_3 mb_2">
                    <label for="">Explaination</label>
                    <textarea id="" name="explaination" placeholder="Explaination" rows="2"><?= $question_data['explaination'] ?></textarea>
                </div>

                <!-- get question id -->
                <input type="hidden" name="question_id" value="<?= $question_data['id'] ?>">

                <button type="submit" name="edit" class="grid_col_3">Edit Question</button>
            </form>
        </div>
    </div>
</main>

<?php } else { ?><script type="text/javascript">window.location.href = '../exam/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>