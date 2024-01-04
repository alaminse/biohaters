<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

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

    // ADD QUESTION 
    if (isset($_POST['add'])) {
        $question = mysqli_escape_string($db, $_POST['cq_question']);
        
        $created_date = date('Y-m-d H:i:s', time());

        // Insert questions
        $insert_question = "INSERT INTO hc_exam_cq (exam, course, question, author, created_date) VALUES ('$exam_id', '$exam_course', '$question', '$admin_id', ' $created_date')";
        $sql_insert = mysqli_query($db, $insert_question);
        if ($sql_insert) {
            ?>
            <script type="text/javascript">
                window.location.href = '../cq-question/?exam=<?php echo $exam_id; ?>';
            </script>
            <?php 
        }
    }?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">CQ Questions</h4>
        </div>
    </div>
    <?php if (isset($_GET['add'])) {
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

                <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                    <div class="">
                        <label for="">Question*</label>
                        <textarea id="" name="cq_question" placeholder="CQ Question" rows="6"></textarea>
                    </div>
                    
                    <button type="submit" name="add" class="">Add Question</button>
                </form>
            </div>
        </div>
        <?php 
    } elseif (isset($_GET['edit'])) {
        $edit_cq = $_GET['edit'];

        if (empty($edit_cq)) {
            ?>
            <script type="text/javascript">
                window.location.href = '../cq-question/?exam=<?php echo $exam_id; ?>';
            </script>
            <?php 
        }
        
        // EDIT QUESTION 
        if (isset($_POST['edit'])) {
            $question = mysqli_escape_string($db, $_POST['cq_question']);
            
            $created_date = date('Y-m-d H:i:s', time());
    
            // Insert questions
            $insert_question = "UPDATE hc_exam_cq SET question = '$question', author = '$admin_id', created_date = '$created_date' WHERE id = '$edit_cq'";
            $sql_insert = mysqli_query($db, $insert_question);
            if ($sql_insert) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../cq-question/?exam=<?php echo $exam_id; ?>';
                </script>
                <?php 
            }
        }
        
        $select = "SELECT * FROM hc_exam_cq WHERE id = '$edit_cq' AND is_delete = 0 ORDER BY id ASC";
        $sql = mysqli_query($db, $select);
        $num = mysqli_num_rows($sql);
        if ($num > 0) {
            $si = 0;
            while ($row = mysqli_fetch_assoc($sql)) {
                $question_id            = $row['id'];
                $question_exam          = $row['exam'];
                $question_course        = $row['course'];
                $question_des           = $row['question'];
                $question_author        = $row['author'];
                $question_created_date  = $row['created_date'];

                // created date convert to text
                $question_created_date_text = date('d M, Y', strtotime($question_created_date));

                $si++;
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
        
                        <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                            <div class="">
                                <label for="">Question*</label>
                                <textarea id="" name="cq_question" placeholder="CQ Question" rows="6"><?php echo $question_des; ?></textarea>
                            </div>
                            
                            <button type="submit" name="edit" class="">Add Question</button>
                        </form>
                    </div>
                </div>
                <?php 
            }
        }
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
                            <a href="../cq-submit-list/?exam=<?= $exam_id ?>" class="button btn_sm"><i class='bx bxs-file'></i>Submit List</a>
                            <a href="../cq-question/?exam=<?= $exam_id ?>&add" class="button btn_sm"><i class='bx bxs-file'></i>Add Question</a>
                        </div>
                    </div>
                    
                    <div>
                        <?php $select = "SELECT * FROM hc_exam_cq WHERE exam = '$exam_id' AND is_delete = 0 ORDER BY id ASC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num > 0) {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $question_id            = $row['id'];
                                $question_exam          = $row['exam'];
                                $question_course        = $row['course'];
                                $question_des           = $row['question'];
                                $question_author        = $row['author'];
                                $question_created_date  = $row['created_date'];

                                // created date convert to text
                                $question_created_date_text = date('d M, Y', strtotime($question_created_date));

                                $si++;
                                ?>
                                <div class="ep_flex ep_start mb_75 text_sm">
                                    <div>
                                        <i>Last updated by - <b><?php $select_question_author = "SELECT * FROM admin WHERE id = '$question_author'";
                                        $sql_question_author = mysqli_query($db, $select_question_author);
                                        $num_question_author = mysqli_num_rows($sql_question_author);
                                        $row_question_author = mysqli_fetch_assoc($sql_question_author);
                                        echo $row_question_author['name'];?></b> || Last updated on - <b><?php echo $question_created_date_text; ?></b></i>
                                    </div>
                                    
                                    <!-- EDIT BUTTON -->
                                    <a href="../cq-question/?exam=78&edit=<?php echo $question_id; ?>" target="blank" class="btn_icon"><span class="text_sm">Edit</span> <i class="bx bxs-edit"></i></a>
                                </div>
                                
                                <div>
                                    <?php echo $question_des; ?>
                                </div>
                                <?php 
                            }
                        }?>
                    </div>
                </div>
            </div>
        </div>
        <?php 
    }?>
</main>

<!--========== CKEDITOR JS =============-->
<script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>

<script>
/*========== POST DESCRIPTION CKEDITOR =============*/
CKEDITOR.replace( 'cq_question' );
</script>

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

<?php } else { ?><script type="text/javascript">window.location.href = '../exam/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>