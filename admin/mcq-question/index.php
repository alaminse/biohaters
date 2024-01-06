<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['exam'])) {
    $exam = $_GET['exam'];

    if (empty($exam)) {
        ?>
        <script type="text/javascript">
            window.location.href = '../exam/';
        </script>
        <?php 
    }

    // get exam name
    $select_exam  = "SELECT * FROM hc_exam WHERE id = '$exam' AND is_delete = 0";
    $sql_exam     = mysqli_query($db, $select_exam);
    $row_exam     = mysqli_fetch_assoc($sql_exam);

    $exam_id                = $row_exam['id'];
    $exam_name              = $row_exam['name'];
    $exam_course            = $row_exam['course_id'];
    $exam_total_question    = $row_exam['total_question'];

    // DELETE question
    if (isset($_POST['delete'])) {
        $question_id = mysqli_escape_string($db, $_POST['delete_id']);

        $delete = "UPDATE hc_exam_question SET is_delete = 1 WHERE id = '$question_id'";
        $sql_delete = mysqli_query($db, $delete);
        if ($sql_delete) {
            ?>
            <script type="text/javascript">
                window.location.href = '../mcq-question/?exam=<?= $exam_id ?>';
            </script>
            <?php 
        }
    }

    // CLONE question
    if (isset($_POST['clone'])) {
        $sourceExamId = $_POST['clone_exam'];
        $destinationExamId = $_POST['clone_id'];

        $select_question = "SELECT * FROM hc_exam_question WHERE exam = '$sourceExamId' AND is_delete = 0";
        $sql_question = mysqli_query($db, $select_question);
        $num_question = mysqli_num_rows($sql_question);
        if ($num_question > 0) {
            while ($row_question = mysqli_fetch_assoc($sql_question)) {
                $question_title         = mysqli_real_escape_string($db, $row_question['title']);
                $question_type          = mysqli_real_escape_string($db, $row_question['type']);
                $question_chapter       = mysqli_real_escape_string($db, $row_question['chapter']);
                $question_topic         = mysqli_real_escape_string($db, $row_question['topic']);
                $question_level         = mysqli_real_escape_string($db, $row_question['level']);
                $question_academic      = mysqli_real_escape_string($db, $row_question['academic']);
                $question_medical       = mysqli_real_escape_string($db, $row_question['medical']);
                $question_explaination  = mysqli_real_escape_string($db, $row_question['explaination']);
                $question_opt_sort      = mysqli_real_escape_string($db, $row_question['opt_sort']);

                $created_date = date('Y-m-d H:i:s', time());
            
                $insert_question = "INSERT INTO hc_exam_question (exam, course, title, type, chapter, topic, level, academic, medical, explaination, opt_sort, author, created_date) VALUES ('$destinationExamId', '$exam_course', '$question_title', '$question_type', '$question_chapter', '$question_topic', '$question_level', '$question_academic', '$question_medical', '$question_explaination', '$question_opt_sort', '$admin_id', ' $created_date')";
                
                $sql_insert = mysqli_query($db, $insert_question);
                
                if ($sql_insert) {
                    $newQuestionId = mysqli_insert_id($db);
            
                    // Step 3: Clone options for the new question
                    $sourceQuestionId = $row_question['id'];
                    $cloneOptionsQuery = "INSERT INTO hc_question_option (question, option_name, is_correct) SELECT $newQuestionId, option_name, is_correct FROM hc_question_option WHERE question = $sourceQuestionId";
            
                    mysqli_query($db, $cloneOptionsQuery);
                }
            }?>
            <script type="text/javascript">
                window.location.href = '../mcq-question/?exam=<?= $exam_id ?>';
            </script>
            <?php 
        }
    }

    // ADD QUESTION 
    if (isset($_POST['add'])) {
        $created_date = date('Y-m-d H:i:s', time());

        // Insert questions and options into the database
        foreach ($_POST["questions"] as $index => $question) {
            $question_text  = mysqli_escape_string($db, $question);
            $chapter        = $_POST['chapter'][$index];
            $topics         = mysqli_escape_string($db, $_POST['topics'][$index]);
            $level          = $_POST['level_' . ($index + 1)];
            $explaination   = mysqli_escape_string($db, $_POST['explaination'][$index]);
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

            if (isset($_POST['opt_fixed_' . ($index + 1)])) {
                $opt_fixed = 1;
            } else {
                $opt_fixed = 0;
            }

            // Insert question into the database
            $add_question = "INSERT INTO hc_exam_question (exam, course, title, type, chapter, topic, level, academic, medical, explaination, opt_sort, author, created_date) VALUES ('$exam_id', '$exam_course', '$question_text', 'MCQ', '$chapter', '$topics', '$level', '$academic', '$medical', '$explaination', '$opt_fixed', '$admin_id', '$created_date')";
            $sql_add_question = mysqli_query($db, $add_question);

            $question_id = mysqli_insert_id($db);

            // Insert options into the database
            foreach ($_POST["options_" . ($index + 1)] as $optionIndex => $option) {
                $option_text = mysqli_escape_string($db, $option);
                $is_correct_option = ($correct_option == $optionIndex) ? 1 : 0;

                // Insert option into the database
                $add_option = "INSERT INTO hc_question_option (question, option_name, is_correct) VALUES ('$question_id', '$option_text', '$is_correct_option')";
                $sql_add_option = mysqli_query($db, $add_option);
            }
        }
    }?>
<main>

    <style>
    </style>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">MCQ Questions</h4>
        </div>
    </div>
    <?php if (isset($_GET['add'])) {
        $question_qty = $_GET['add'];
        $option_qty = $_GET['option_qty'];
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
                            <?php for ($option = 0; $option < $option_qty; $option++) {
                                ?>
                                <div class="option_card">
                                    <input type="radio" name="correct_option_<?= $question ?>" value="<?= $option ?>" <?php if ($option == 0) { echo 'checked'; }?> required>
                                    <input type="text" id="" name="options_<?= $question ?>[]" placeholder="Option Name - <?= $option + 1 ?>" required>
                                </div>
                                <?php 
                            }?>
                        </div>
                        
                        <div class="grid_col_3">
                            <label for="opt-fixed<?= $question ?>">Option Sort Fixed?</label>
                            <label for="opt-fixed<?= $question ?>" class="checkbox_label">
                                <input type="checkbox" class="checkbox" name="opt_fixed_<?= $question ?>[]" id="opt-fixed<?= $question ?>">
                                <span class="checked"></span>
                                Yes
                            </label>
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
    } else {
        ?>
        <!-- NOTICE LIST -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== MANAGE NOTICE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Manage Question - <?= $exam_name ?></h5>

                        <div class="btn_grp">
                            <a href="../question-view/?exam=<?= $exam_id ?>" class="button btn_sm"><i class='bx bxs-file'></i>View</a>
                            <button type="button" class="btn_sm" data-bs-toggle="modal" data-bs-target="#pdf<?= $exam_id; ?>"><i class='bx bxs-file'></i>Get PDF</button>
                            <button type="button" class="btn_sm" data-bs-toggle="modal" data-bs-target="#clone<?= $exam_id; ?>"><i class='bx bxs-file'></i>Clone</button>
                            <button type="button" class="btn_sm" data-bs-toggle="modal" data-bs-target="#add<?= $exam_id; ?>"><i class='bx bxs-file'></i>Add</button>
                        </div>
                    </div>
                    
                    <!--print modal-->
                    <div class="modal fade" id="pdf<?= $exam_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">PDF Questions</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <span class ="ep_p text_semi bg_danger text_danger">Get PDF Question - </span>
                                    <form action="../question-pdf/" method="get" class="mb_75 mt_75">
                                        <select id="" name="ques_type">
                                            <option value="">Choose Answer Type</option>
                                            <option value="answer">With Answer</option>
                                            <option value="noanswer">Without Answer</option>
                                        </select>
                                        <input type="hidden" name="exam" id="" value="<?php echo $exam_id; ?>">
                                        <button type="submit" class="button bg_success text_success text_semi mt_75">Get PDF</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!--clone modal-->
                    <div class="modal fade" id="clone<?= $exam_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Clone Exam</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <span class ="ep_p text_semi bg_danger text_danger">Clone Exam Question to - </span>
                                    <form action="" method="post" class="mb_75 mt_75">
                                        <select id="" name="clone_exam">
                                            <option value="">Choose Exam</option>
                                            <?php $select_exam = "SELECT * FROM hc_exam WHERE id != $exam_id AND is_delete = 0 ORDER BY id DESC";
                                            $sql_exam = mysqli_query($db, $select_exam);
                                            $num_exam = mysqli_num_rows($sql_exam);
                                            if ($num_exam > 0) {
                                                while ($row_exam = mysqli_fetch_assoc($sql_exam)) {
                                                    $clone_exam_id     = $row_exam['id'];
                                                    $clone_exam_name   = $row_exam['name'];
                
                                                    echo '<option value="'.$clone_exam_id.'">'.$clone_exam_name.'</option>';
                                                }
                                            }?>
                                        </select>
                                        <input type="hidden" name="clone_id" id="" value="<?php echo $exam_id; ?>">
                                        <button type="submit" name="clone" class="button bg_success text_success text_semi mt_75">Clone</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!--add modal-->
                    <div class="modal fade" id="add<?= $exam_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Clone Exam</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="" method="get" class="mb_75 mt_75 single_col_form">
                                        <input type="hidden" name="exam" id="" value="<?= $exam_id ?>">

                                        <label for="">Question Quantity</label>
                                        <select id="" name="add">
                                            <?php for ($question_serial = 1; $question_serial <= 10; $question_serial++) {
                                                ?>
                                                <option value="<?= $question_serial ?>"><?= $question_serial ?></option>
                                                <?php 
                                            }?>
                                        </select>

                                        <label for="">Option Quantity</label>
                                        <select id="" name="option_qty">
                                            <option value="4">4</option>
                                            <option value="2">2</option>
                                        </select>

                                        <button type="submit" name="add_question" class="button bg_success text_success text_semi mt_75">ADD</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="ep_table" id="datatable">
                        <thead>
                            <tr>
                                <th>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#allDelete" style="margin-left: 1rem; padding: 0.3rem 0.3rem; border-radius: 1rem;" id="qDeleteBtn" class="button"><i class="bx bxs-trash"></i></button>
                                    <!-- DELETE MODAL -->
                                    <div class="modal fade" id="allDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Question</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Do you want to delete?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" id="modalDeleteBtn" class="button bg_danger text_danger text_semi">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th>SI</th>
                                <th>Question</th>
                                <th>Type</th>
                                <th>Author</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select = "SELECT * FROM hc_exam_question WHERE exam = '$exam_id' AND is_delete = 0 ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                $si = 0;
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $question_id            = $row['id'];
                                    $question_exam          = $row['exam'];
                                    $question_course        = $row['course'];
                                    $question_title         = $row['title'];
                                    $question_type          = $row['type'];
                                    $question_author        = $row['author'];
                                    $question_created_date  = $row['created_date'];

                                    // created date convert to text
                                    $question_created_date_text = date('d M, Y', strtotime($question_created_date));

                                    $si++;
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="question-checkbox" name="question_id[]" value="<?php echo $question_id; ?>">
                                        </td>
                                        <td><?php echo $si; ?></td>

                                        <td><?php echo $question_title; ?></td>

                                        <td><?php echo $question_type; ?></td>

                                        <td><?php $select_question_author = "SELECT * FROM admin WHERE id = '$question_author'";
                                        $sql_question_author = mysqli_query($db, $select_question_author);
                                        $num_question_author = mysqli_num_rows($sql_question_author);
                                        $row_question_author = mysqli_fetch_assoc($sql_question_author);
                                        echo $row_question_author['name'];?></td>

                                        <td><?php echo $question_created_date_text; ?></td>

                                        <td>
                                            <div class="btn_grp">
                                                <!-- EDIT BUTTON -->
                                                <a href="../mcq-edit/?question=<?php echo $question_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>
                                            
                                                <!-- DELETE MODAL BUTTON -->
                                                <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $question_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                            </div>

                                            <!-- DELETE MODAL -->
                                            <div class="modal fade" id="delete<?php echo $question_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Question</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $question_title; ?></span>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="delete_id" id="" value="<?php echo $question_id; ?>">
                                                                <button type="submit" name="delete" class="button bg_danger text_danger text_semi">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
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
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });

    $(document).ready(function() {
        $('#modalDeleteBtn').on('click', function() {
            var ids = [];
            $('.question-checkbox:checked').each(function() {
                ids.push($(this).val());
            });

            $.ajax({
                url: 'delete_items.php',
                method: 'POST',
                data: { delete_ids: ids },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(error);
                }
            });
        });
    });

</script>

<?php } else { ?><script type="text/javascript">window.location.href = '../exam/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>