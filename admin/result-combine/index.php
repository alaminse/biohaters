<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_POST['delete'])) {
    $list_id = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_combined_list SET is_delete = 1 WHERE id = '$list_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        $delete_result = "DELETE FROM hc_combined_result WHERE combined_list_id = '$list_id'";
        $sql_delete_result = mysqli_query($db, $delete_result);
        ?>
        <script type="text/javascript">
            window.location.href = '../result-combine/?list';
        </script>
        <?php 
    }
}?>

<?php if (isset($_POST['edit'])) {
    $edit_id = mysqli_escape_string($db, $_POST['edit_id']);
    $edit_combined_title = mysqli_escape_string($db, $_POST['combined_title']);
    $edit_scheduled = $_POST['scheduled'];

    $edit = "UPDATE hc_combined_list SET name = '$edit_combined_title', created_date = '$edit_scheduled' WHERE id = '$edit_id'";
    $sql_edit = mysqli_query($db, $edit);
    if ($sql_edit) {
        ?>
        <script type="text/javascript">
            window.location.href = '../result-combine/?list';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Result Combination</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <?php if (isset($_GET['course'])) {
                $get_course_id = $_GET['course'];
                
                // fetch course name
                $select_course = "SELECT * FROM hc_course WHERE id = '$get_course_id' AND is_delete = 0 ORDER BY id DESC";
                $sql_course = mysqli_query($db, $select_course);
                $num_course = mysqli_num_rows($sql_course);
                if ($num_course > 0) {
                    while ($row_course = mysqli_fetch_assoc($sql_course)) {
                        $course_id      = $row_course['id'];
                        $course_name    = $row_course['name'];
                    }
                }?>
                <!--========== MANAGE COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">All Exam - <?= $course_name ?></h5>
                    </div>
                </div>
                
                <form action="../combined-list/" method="get" class="" enctype="">
                    <table class="ep_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Published Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select_exam = "SELECT * FROM hc_exam WHERE course_id = '$course_id' AND mcq = 1 AND status = 1 AND is_delete = 0 ORDER BY created_date DESC";
                            $sql_exam = mysqli_query($db, $select_exam);
                            $num_exam = mysqli_num_rows($sql_exam);
                            if ($num_exam > 0) {
                                while ($row_exam = mysqli_fetch_assoc($sql_exam)) {
                                    $exam_id              = $row_exam['id'];
                                    $exam_name            = $row_exam['name'];
                                    $exam_created_date    = $row_exam['created_date'];
                                    
                                    $exam_created_date = date('M d, Y | h:i a', strtotime($exam_created_date));
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="select_exam[]" value="<?= $exam_id ?>">
                                        </td>
                                        
                                        <td>
                                            <div><?php echo $exam_name; ?></div>
                                        </td>
                                        
                                        <td>
                                            <div><?php echo $exam_created_date; ?></div>
                                        </td>
                                    </tr>
                                    <?php 
                                }
                            }?>
                        </tbody>
                    </table>
                    
                    <input type="hidden" name="course" value="<?= $course_id ?>">
                    
                    <div class="btn_grp mt_75">
                        <button type="submit">Submit To Combine</button>
                        <a href="../combined-list/?course=<?= $course_id ?>" class="button">All Exam Combine</a>
                    </div>
                </form>
                <?php 
            } elseif (isset($_GET['list'])) {
                ?>
                <!--========== MANAGE COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Combined List</h5>
                    </div>
                </div>
                
                <table class="ep_table" id="datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th>Published Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php // fetch combine list
                        $select_list = "SELECT * FROM hc_combined_list WHERE is_delete = 0 ORDER BY id DESC";
                        $sql_list = mysqli_query($db, $select_list);
                        $num_list = mysqli_num_rows($sql_list);
                        if ($num_list > 0) {
                            $si = 0;
                            $now = date('Y-m-d H:i:s', time());
                            while ($row_list = mysqli_fetch_assoc($sql_list)) {
                                $list_id            = $row_list['id'];
                                $list_name          = $row_list['name'];
                                $list_course        = $row_list['course'];
                                $list_author        = $row_list['author'];
                                $list_created_date  = $row_list['created_date'];
                                
                                $list_scheduled = date('h:i:s a', strtotime($list_created_date));

                                // joined date convert to text
                                $list_created_date_text = date('d M, Y', strtotime($list_created_date));
                                
                                $si++;
                                
                                // course name
                                $select_course = "SELECT * FROM hc_course WHERE id = '$list_course'";
                                $sql_course = mysqli_query($db, $select_course);
                                $num_course = mysqli_num_rows($sql_course);
                                if ($num_course > 0) {
                                    $row_course = mysqli_fetch_assoc($sql_course);
                                    $course_name = $row_course['name'];
                                }
                                
                                // author name
                                $select_author = "SELECT * FROM admin WHERE id = '$list_author'";
                                $sql_author = mysqli_query($db, $select_author);
                                $num_author = mysqli_num_rows($sql_author);
                                if ($num_author > 0) {
                                    $row_author = mysqli_fetch_assoc($sql_author);
                                    $author_name = $row_author['name'];
                                }?>
                                <tr>
                                    <td><?= $si ?></td>
                                    
                                    <td>
                                        <div><?php echo $list_name; ?></div>
                                    </td>
                                    
                                    <td>
                                        <div><?php echo $course_name; ?></div>
                                    </td>
                                    
                                    <td><?php if ($now >= $list_created_date) {
                                        echo '<div class="ep_badge bg_success text_success">Published</div>';
                                    } else {
                                        echo '<div class="ep_badge bg_info text_info">Scheduled : ' . $list_scheduled . '</div>';
                                    }?></td>
                                    
                                    <td>
                                        <div><?php echo $author_name; ?></div>
                                    </td>
                                    
                                    <td>
                                        <div><?php echo $list_created_date_text; ?></div>
                                    </td>
                                    
                                    <td>
                                        <div class="btn_grp">
                                            <!-- EDIT BUTTON -->
                                            <a href="../result-combine/?list_edit=<?php echo $list_id; ?>" class="btn_icon"><i class='bx bxs-edit' ></i></a>
                                            
                                            <!-- RESULT BUTTON -->
                                            <a href="../combined-result-data/?list=<?php echo $list_id; ?>" class="btn_icon"><i class='bx bxs-show'></i></a>
                                            
                                            <!-- UPDATE MODAL BUTTON -->
                                            <!--<button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#update<?php // echo $list_id; ?>"><i class='bx bx-upload' ></i></button>-->
                                        
                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $list_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                        </div>

                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="delete<?php echo $list_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $list_name; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <form action="" method="post">
                                                            <input type="hidden" name="delete_id" id="" value="<?php echo $list_id; ?>">
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
                <?php 
            } elseif (isset($_GET['list_edit'])) {
                $get_list_id = $_GET['list_edit'];
                
                // get list data
                $select_combined_list = "SELECT * FROM hc_combined_list WHERE id = '$get_list_id' AND is_delete = 0";
                $sql_combined_list = mysqli_query($db, $select_combined_list);
                $num_combined_list = mysqli_num_rows($sql_combined_list);
                if ($num_combined_list > 0) {
                    while ($row_combined_list = mysqli_fetch_assoc($sql_combined_list)) {
                        $combined_list_id      = $row_combined_list['id'];
                        $combined_list_name    = $row_combined_list['name'];
                        $combined_list_date    = $row_combined_list['created_date'];
                    }
                }?>
                <form action="" method="post" class="ep_grid mb_75">
                    <div class="double_col_form">
                        <div>
                            <label>Combined List Title*</label>
                            <input type="text" name="combined_title" placeholder="Write a combined title" value="<?= $combined_list_name ?>" required>
                        </div>
                        
                        <div>
                            <label>Result Publish Schedule*</label>
                            <input type="datetime-local" id="" name="scheduled" value="<?= $combined_list_date ?>" required>
                        </div>
                    </div>
                    
                    <input type="hidden" name="edit_id" value="<?= $combined_list_id ?>">
                    
                    <button type="submit" name="edit">Publish</button>
                </form>
                <?php 
            } else {
                ?>
                <!--========== MANAGE COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">All Course</h5>
                        <a href="../result-combine/?list" class="button btn_sm"><i class='bx bx-task'></i>Combined List</a>
                    </div>
                </div>
                
                <div class="course_widget_container ep_grid">
                    <?php $select = "SELECT * FROM hc_course WHERE type = 1 AND is_delete = 0 ORDER BY id DESC";
                    $sql = mysqli_query($db, $select);
                    $num = mysqli_num_rows($sql);
                    if ($num > 0) {
                        while ($row = mysqli_fetch_assoc($sql)) {
                            $course_id      = $row['id'];
                            $course_name    = $row['name'];
                            $course_type    = $row['type'];
                            
                            // fetch exam
                            $select_exam = "SELECT * FROM hc_exam WHERE course_id = '$course_id' AND status = 1 AND is_delete = 0";
                            $sql_exam = mysqli_query($db, $select_exam);
                            $num_exam = mysqli_num_rows($sql_exam);
                            ?>
                            <a href="../result-combine/?course=<?= $course_id ?>" class="course_widget_card">
                                <div class="course_widget_content"><?= $course_name ?></div>
                                <div class="course_widget_data">Total Exam: <?= $num_exam ?></div>
                            </a>
                            <?php 
                        }
                    }?>
                </div>
                <?php 
            }?>
        </div>
    </div>
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
        // order: [[0, 'desc']],
        pageLength: 25,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>

<?php include('../assets/includes/footer.php'); ?>