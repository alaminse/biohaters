<?php include('../assets/includes/header.php'); ?>

<?php 
$subject = "SELECT * FROM hc_subject";
$subject_sql = mysqli_query($db, $subject);
$search_subject_id = '';
$subject_name = 'Zoology';

if(isset($_GET['subject'])) {
    $subject_name = $_GET['subject'];
}

$search_subject_id = "SELECT `id` FROM hc_subject WHERE subject = '$subject_name'";
$search_subject_id_result = mysqli_query($db, $search_subject_id);

if($search_subject_id_result && mysqli_num_rows($search_subject_id_result) > 0) {
    $row = mysqli_fetch_assoc($search_subject_id_result);
    $search_subject_id = $row['id'];
}

// fetch chapter
$select = "SELECT * FROM hc_chapter WHERE subject = $search_subject_id";
$sql = mysqli_query($db, $select);

$num = mysqli_num_rows($sql); 
if ($num > 0) {
    // Initialize an empty array to store chapter data
    $chapters = array();
    while ($row = mysqli_fetch_assoc($sql)) {
        $id = $row['id'];
        $chapter = $row['chapter'];
        $subject = $row['subject'];
        
        if ($subject == 1) {
            $cover_photo = 'botany.png';
        } elseif ($subject == 2) {
            $cover_photo = 'heart.png';
        }

        // fetch questions by chapter
        $select_questions = "SELECT * FROM hc_question_bank WHERE chapter = '$id'";
        $sql_questions = mysqli_query($db, $select_questions);
        $num_questions = mysqli_num_rows($sql_questions); 
        
        // Create an array to store chapter data
        $chapter_data = array(
            'id' => $id,
            'chapter' => $chapter,
            'subject' => $subject,
            'cover_photo' => $cover_photo ?? '',
            'questions_qty' => $num_questions,
        );
        
        // Add the chapter data to the main chapter array
        $chapters[] = $chapter_data;
    }
}?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Questions Bank</h4>
        </div>
    </div>
    
    <?php if (isset($_GET['question_type']) && isset($_GET['question_qty']) && ($_GET['question_type'] != '') && ($_GET['question_qty'] != '')) {
        $question_type = $_GET['question_type'];
        $question_qty = $_GET['question_qty'];
        
        // ADD QUESTION 
        if (isset($_POST['add'])) {
            $created_date = date('Y-m-d H:i:s', time());

            // Insert questions and options into the database
            foreach ($_POST["questions"] as $index => $question) {
                $question_text  = mysqli_escape_string($db, $question);
                $chapter        = $_POST['chapter'][$index];
                $topics         = $_POST['topics'][$index];
                $level          = $_POST['level_' . ($index + 1)];
                $explaination   = $_POST['explaination'][$index];
                $correct_option = $_POST["correct_option_" . ($index + 1)];

                if (isset($_POST['academic' . ($index + 1)])) {
                    $academic = 1;
                } else {
                    $academic = 0;
                }

                if (isset($_POST['medical' . ($index + 1)])) {
                    $medical = 1;
                } else {
                    $medical = 0;
                }

                // Insert question into the database
                $add_question = "INSERT INTO hc_question_bank (title, type, chapter, topic, level, academic, medical, explaination, author, created_date) VALUES ('$question_text', '$question_type', '$chapter', '$topics', '$level', '$academic', '$medical', '$explaination', '$admin_id', '$created_date')";
                $sql_add_question = mysqli_query($db, $add_question);

                $question_id = mysqli_insert_id($db);

                // Insert options into the database
                foreach ($_POST["options_" . ($index + 1)] as $optionIndex => $option) {
                    $option_text = mysqli_escape_string($db, $option);
                    $is_correct_option = ($correct_option == $optionIndex) ? 1 : 0;

                    // Insert option into the database
                    $add_option = "INSERT INTO hc_question_bank_option (question, option_name, is_correct) VALUES ('$question_id', '$option_text', '$is_correct_option')";
                    $sql_add_option = mysqli_query($db, $add_option);
                }
            }?>
            <script type="text/javascript">
                window.location.href = '../question-bank/';
            </script>
            <?php 
        }
        
        if ($question_type == 'MCQ') {
            ?>
            <!-- question LIST -->
            <div class="ep_section">
                <div class="ep_container">
                    <!--========== ADD question ==========-->
                    <div class="mng_category">
                        <div class="ep_flex mb_75">
                            <h5 class="box_title">Add Question</h5>
                        </div>
                    </div>

                    <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                        <?php for ($question = 1; $question <= $question_qty; $question++) { 
                            ?>
                            <div class="grid_col_2">
                                <label for="">Question Title - <?= $question ?>*</label>
                                <input type="text" id="" name="questions[]" placeholder="Question Title" required>
                            </div>

                            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                            <script>
                                $(document).ready(function() {
                                    $('.js-example-basic-single').select2();
                                });
                            </script>
                            
                            <div>
                                <label for="">Chapter*</label>
                                <select id="" name="chapter[]" required class="js-example-basic-single">
                                    <option value="">Choose Chapter</option>
                                    <?php $select = "SELECT * FROM hc_chapter WHERE is_delete = 0 ORDER BY id DESC";
                                    $sql = mysqli_query($db, $select);
                                    $num = mysqli_num_rows($sql);
                                    if ($num > 0) {
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                            $chapter_id     = $row['id'];
                                            $chapter_name   = $row['chapter'];
                                            echo '<option value="'.$chapter_id.'">'.$chapter_name.'</option>';
                                        }
                                    }?>
                                </select>
                            </div>

                            <div class="option_container grid_col_3">
                                <?php for ($option = 0; $option < 4; $option++) {
                                    ?>
                                    <div class="option_card">
                                        <input type="radio" name="correct_option_<?= $question ?>" value="<?= $option ?>" <?php if ($option == 0) { echo 'checked'; }?> required>
                                        <input type="text" id="" name="options_<?= $question ?>[]" placeholder="Option Name - <?= $option + 1 ?>" required>
                                    </div>
                                    <?php 
                                }?>
                            </div>

                            <div class="levels">
                                <h5 class="mb_75">Levels</h5>
                                
                                <div>
                                    <input type="radio" name="level_<?= $question ?>" value="easy" id="easy<?= $question ?>">
                                    <label for="easy<?= $question ?>">Easy</label>
                                </div>

                                <div>
                                    <input type="radio" name="level_<?= $question ?>" value="typical" id="typical<?= $question ?>">
                                    <label for="typical<?= $question ?>">Typical</label>
                                </div>

                                <div>
                                    <input type="radio" name="level_<?= $question ?>" value="advance" id="advance<?= $question ?>">
                                    <label for="advance<?= $question ?>">Advance</label>
                                </div>
                            </div>

                            <div class="levels">
                                <h5 class="mb_75">Test</h5>
                                
                                <div>
                                    <input type="checkbox" name="academic<?= $question ?>[]" value="academic" id="academic<?= $question ?>">
                                    <label for="academic<?= $question ?>">Academic</label>
                                </div>

                                <div>
                                    <input type="checkbox" name="medical<?= $question ?>[]" value="medical" id="medical<?= $question ?>">
                                    <label for="medical<?= $question ?>">Medical</label>
                                </div>
                            </div>

                            <div class="grid_col_3">
                                <label for="">Topics</label>
                                <textarea id="" name="topics[]" placeholder="Topics" rows="2"></textarea>
                            </div>

                            <div class="grid_col_3 mb_2">
                                <label for="">Explaination</label>
                                <textarea id="" name="explaination[]" placeholder="Explaination" rows="2"></textarea>
                            </div>
                            <?php 
                        }?>

                        <button type="submit" name="add" class="grid_col_3">Add Question</button>
                    </form>
                </div>
            </div>
            <?php 
        } elseif ($question_type == 'True/False') {
            ?>
            <!-- question LIST -->
            <div class="ep_section">
                <div class="ep_container">
                    <!--========== ADD question ==========-->
                    <div class="mng_category">
                        <div class="ep_flex mb_75">
                            <h5 class="box_title">Add Question</h5>
                        </div>
                    </div>

                    <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                        <?php for ($question = 1; $question <= $question_qty; $question++) { 
                            ?>
                            <div class="grid_col_2">
                                <label for="">Question Title - <?= $question ?>*</label>
                                <input type="text" id="" name="questions[]" placeholder="Question Title" required>
                            </div>

                            <div>
                                <label for="">Chapter*</label>
                                <select id="" name="chapter[]" required>
                                    <option value="">Choose Chapter</option>
                                    <?php $select = "SELECT * FROM hc_chapter WHERE is_delete = 0 ORDER BY id DESC";
                                    $sql = mysqli_query($db, $select);
                                    $num = mysqli_num_rows($sql);
                                    if ($num > 0) {
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                            $chapter_id     = $row['id'];
                                            $chapter_name   = $row['chapter'];

                                            echo '<option value="'.$chapter_id.'">'.$chapter_name.'</option>';
                                        }
                                    }?>
                                </select>
                            </div>

                            <div class="option_container grid_col_3">
                                <?php for ($option = 0; $option < 2; $option++) {
                                    ?>
                                    <div class="option_card">
                                        <input type="radio" name="correct_option_<?= $question ?>" value="<?= $option ?>" <?php if ($option == 0) { echo 'checked'; }?> required>
                                        <input type="text" id="" name="options_<?= $question ?>[]" value="<?php if ($option == 0) { echo 'True'; } else { echo 'False'; }?>" readonly="" required>
                                    </div>
                                    <?php 
                                }?>
                            </div>

                            <div class="levels">
                                <h5 class="mb_75">Levels</h5>
                                
                                <div>
                                    <input type="radio" name="level_<?= $question ?>" value="easy" id="easy<?= $question ?>">
                                    <label for="easy<?= $question ?>">Easy</label>
                                </div>

                                <div>
                                    <input type="radio" name="level_<?= $question ?>" value="typical" id="typical<?= $question ?>">
                                    <label for="typical<?= $question ?>">Typical</label>
                                </div>

                                <div>
                                    <input type="radio" name="level_<?= $question ?>" value="advance" id="advance<?= $question ?>">
                                    <label for="advance<?= $question ?>">Advance</label>
                                </div>
                            </div>

                            <div class="levels">
                                <h5 class="mb_75">Test</h5>
                                
                                <div>
                                    <input type="checkbox" name="academic<?= $question ?>[]" value="academic" id="academic<?= $question ?>">
                                    <label for="academic<?= $question ?>">Academic</label>
                                </div>

                                <div>
                                    <input type="checkbox" name="medical<?= $question ?>[]" value="medical" id="medical<?= $question ?>">
                                    <label for="medical<?= $question ?>">Medical</label>
                                </div>
                            </div>

                            <div class="grid_col_3">
                                <label for="">Topics</label>
                                <textarea id="" name="topics[]" placeholder="Topics" rows="2"></textarea>
                            </div>

                            <div class="grid_col_3 mb_2">
                                <label for="">Explaination</label>
                                <textarea id="" name="explaination[]" placeholder="Explaination" rows="2"></textarea>
                            </div>
                            <?php 
                        }?>

                        <button type="submit" name="add" class="grid_col_3">Add Question</button>
                    </form>
                </div>
            </div>
            <?php 
        }
    } elseif (isset($_GET['chapter']) && ($_GET['chapter'] != '')) {
        $chapter_id = $_GET['chapter'];

        // fetch chapter
        $select_chapter = "SELECT * FROM hc_chapter WHERE id = '$chapter_id' AND is_delete = 0";
        $sql_chapter = mysqli_query($db, $select_chapter);
        $num_chapter = mysqli_num_rows($sql_chapter);
        if ($num_chapter > 0) {
            while ($row_chapter = mysqli_fetch_assoc($sql_chapter)) {
                $chapter_id     = $row_chapter['id'];
                $chapter_name   = $row_chapter['chapter'];

                $questionsData = array(
                    'chapter_id'   => $chapter_id,
                    'chapter_name' => $chapter_name,
                );

                // fetch questions
                $select_questions = "SELECT * FROM hc_question_bank WHERE chapter = '$chapter_id' AND is_delete = 0 ORDER BY id DESC";
                $sql_questions = mysqli_query($db, $select_questions);
                $num_questions = mysqli_num_rows($sql_questions);
                if ($num_questions > 0) {
                    // Mock exam data (Replace this with data fetched from the database)
                    while ($row_questions = mysqli_fetch_assoc($sql_questions)) {
                        $question_id            = $row_questions['id'];
                        $question_title         = $row_questions['title'];
                        $question_topic         = $row_questions['topic'];
                        $question_explaination  = $row_questions['explaination'];

                        // fetch question option
                        $select_options = "SELECT * FROM hc_question_bank_option WHERE question = '$question_id' ORDER BY id ASC";
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

                        $questionsData['questions'][] = $question;
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
                        <h5 class="box_title">Question View - <?= $questionsData['chapter_name']; ?></h5>
                    </div>
                    
                    <div class="exam_attempt_container ep_grid">
                        <?php $si = 0;
                        if (isset($questionsData['questions'])) {
                            foreach ($questionsData['questions'] as $question) { $si++; ?>
                                <!--=== Single Question ===-->
                                <div class="exam_attempt_single">
                                    <!--=== Question ===-->
                                    <input type="hidden" name="question[]" id="" value="<?= $question['question_id'] ?>">
                                    <div class="exam_attempt_single_question">
                                        <?= $si . '. ' . $question['question_text'] ?>
                                        
                                        <!-- EDIT BUTTON -->
                                        <a href="../question-edit/?question=<?php echo $question['question_id']; ?>" target="blank" class="btn_icon"><span class="text_sm">Edit</span> <i class="bx bxs-edit"></i></a>
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
                            <?php }
                        }?>
                    </div>
                </div>
            </div>
        </div>
        <?php 
    } else {
        ?>
        <!-- profile photo setting -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== MANAGE NOTICE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Question By Chapter & Topic</h5>
                        
                        <div class="btn_grp">
                            <button type="button" class="button btn_sm" data-bs-toggle="modal" data-bs-target="#add">Add Question</button>

                            <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add Questions</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="" method="get" class="mb_75 mt_75 single_col_form">
                                                <label for="">Question Type</label>
                                                <select id="" name="question_type">
                                                    <option value="">Choose Question Type</option>
                                                    <option value="MCQ">MCQ</option>
                                                    <option value="True/False">True/False</option>
                                                    <!-- <option value="Bohupodi">Bohupodi</option> -->
                                                </select>

                                                <label for="">Question Quantity</label>
                                                <input type="number" name="question_qty" id="" placeholder="Question Quantity">

                                                <button type="submit" name="add_question" class="button bg_success text_success text_semi mt_75">ADD</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div> 

                <div class="subject_header ep_container">
                    <ul class="subject_header_ul">
                        <li class="button btn_sm">
                            <a class="subject_btn" <?php if ($subject_name == 'Zoology') : ?> 
                                style="color:  rgb(255, 251, 0); font-weight: bold;"
                            <?php endif; ?> href="../question-bank/?subject=<?php echo 'Zoology'; ?>">
                                প্রাণীবিজ্ঞান
                            </a>
                        </li>
                        <li class="button btn_sm">
                            <a class="subject_btn" <?php if ($subject_name == 'Botany') : ?> 
                                style="color:  rgb(255, 251, 0); font-weight: bold;"
                            <?php endif; ?> href="../question-bank/?subject=<?php echo 'Botany'; ?>">
                                উদ্ভিদবিজ্ঞান
                            </a>
                        </li>
                        <li class="button btn_sm">
                            <a class="subject_btn" <?php if ($subject_name == 'রসায়ন ১ম পত্র') : ?> 
                                style="color:  rgb(255, 251, 0); font-weight: bold;"
                            <?php endif; ?> href="../question-bank/?subject=<?php echo 'রসায়ন ১ম পত্র'; ?>">
                                রসায়ন ১ম পত্র
                            </a>
                        </li>
                        <li class="button btn_sm">
                            <a class="subject_btn" <?php if ($subject_name == 'রসায়ন ২য় পত্র') : ?> 
                                style="color:  rgb(255, 251, 0); font-weight: bold;"
                            <?php endif; ?> href="../question-bank/?subject=<?php echo 'রসায়ন ২য় পত্র'; ?>">
                                রসায়ন ২য় পত্র
                            </a>
                        </li>
                        <li class="button btn_sm">
                            <a class="subject_btn" <?php if ($subject_name == 'পদার্থবিজ্ঞান ১ম পত্র') : ?> 
                                style="color:  rgb(255, 251, 0); font-weight: bold;"
                            <?php endif; ?> href="../question-bank/?subject=<?php echo 'পদার্থবিজ্ঞান ১ম পত্র'; ?>">
                                পদার্থবিজ্ঞান ১ম পত্র
                            </a>
                        </li>
                        <li class="button btn_sm">
                            <a class="subject_btn" <?php if ($subject_name == 'পদার্থবিজ্ঞান ২য় পত্র') : ?> 
                                style="color:  rgb(255, 251, 0); font-weight: bold;"
                            <?php endif; ?> href="../question-bank/?subject=<?php echo 'পদার্থবিজ্ঞান ২য় পত্র'; ?>">
                                পদার্থবিজ্ঞান ২য় পত্র
                            </a>
                        </li>
                        <li class="button btn_sm">
                            <a class="subject_btn" <?php if ($subject_name == 'Others') : ?> 
                                style="color:  rgb(255, 251, 0); font-weight: bold;"
                            <?php endif; ?> href="../question-bank/?subject=<?php echo 'Others'; ?>">
                                অন্যান্য
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="ep_container widgets_question_bank">
                <?php foreach ($chapters as $chapter_data) {
                    $id             = $chapter_data['id'];
                    $chapter        = $chapter_data['chapter'];
                    $cover_photo    = $chapter_data['cover_photo'] ?? '';
                    $questions_qty  = $chapter_data['questions_qty'];
                    ?>
                    <!-- chapter -->
                    <a href="../question-bank/?chapter=<?= $id ?>" class="ep_grid notice_card h_max ep_card">
                        <img src="../assets/img/<?= $cover_photo ?>" alt="">
                        <h5><?= $chapter ?></h5>
                        <p>Stored Questions || <?= $questions_qty ?></p>
                    </a>
                    <?php 
                }?>
            </div>
        </div>
        <?php 
    }?>
</main>

<link rel="stylesheet" href="style.css">
<?php include('../assets/includes/footer.php'); ?>