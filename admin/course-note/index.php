<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 3 || $admin_role == 7 || $admin_role == 8) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php if (isset($_GET['course'])) { 
    $course = $_GET['course'];

    // add note
    $alert = '';
    if (isset($_POST['add'])) {
        $chapter_id = $_POST['chapter'];

        $created_date = date('Y-m-d H:i:s', time());

        if ($chapter_id == '') {
            $alert = "<p class='warning mb_75'>Required Chapter.....</p>";
        } else {
            $add = "INSERT INTO hc_module (course, chapter, author, created_date) VALUES ('$course', '$chapter_id', '$admin_id', '$created_date')";
            $sql_add = mysqli_query($db, $add);
            if ($sql_add) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../course-note/?course=<?php echo $course; ?>';
                </script>
                <?php 
            }
        }
    }

    // delete module
    if (isset($_POST['delete'])) {
        $delete_id = mysqli_escape_string($db, $_POST['delete_id']);

        $delete = "UPDATE hc_course_note SET is_delete = 1 WHERE id = '$delete_id'";
        $sql_delete = mysqli_query($db, $delete);
        if ($sql_delete) {
            ?>
            <script type="text/javascript">
                window.location.href = '../course-note/?course=<?php echo $course; ?>';
            </script>
            <?php 
        }
    }?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Course Module</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container ep_grid grid_2">
            <!--========== ADD PRODUCT CATEGORY ==========-->
            <div class="add_category">
                <h5 class="box_title">Add Course Note</h5>

                <?php echo $alert; ?>

                <form action="" method="post" class="single_col_form">
                    <div>
                        <label for="">Chapter Name*</label>
                        <select id="" name="chapter">
                            <option value="">Choose Chapter</option>
                            <?php $select = "SELECT * FROM hc_marked_book_chapter WHERE is_delete = 0 ORDER BY id ASC";
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

                    <button type="submit" name="add">Add Note</button>
                </form>
            </div>

            <!--========== MANAGE PRODUCT CATEGORY ==========-->
            <div class="mng_category">
                <h5 class="box_title">Manage Course Notes</h5>

                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Chapter</th>
                            <th>Course</th>
                            <th>Author</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_course_note WHERE course = '$course' AND is_delete = 0 ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num == 0) {
                            echo "<tr><td colspan='5' class='text_center'>There are no Course Notes</td></tr>";
                        } else {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $note_id        = $row['id'];
                                $note_chapter   = $row['chapter'];
                                $note_course    = $row['course'];
                                $note_author    = $row['author'];
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td><?php echo $module_id; ?></td>
                                    
                                    <td><?php $select_note_chapter = "SELECT * FROM hc_marked_book_chapter WHERE id = '$note_chapter'";
                                    $sql_note_chapter = mysqli_query($db, $select_note_chapter);
                                    $num_note_chapter = mysqli_num_rows($sql_note_chapter);
                                    if ($num_note_chapter == 0) {
                                        echo "--";
                                    } else {
                                        $row_note_chapter = mysqli_fetch_assoc($sql_note_chapter);
                                        echo $row_note_chapter['chapter'];
                                    }?></td>

                                    <td><?php $select_note_course = "SELECT * FROM hc_course WHERE id = '$note_course'";
                                    $sql_note_course = mysqli_query($db, $select_note_course);
                                    $num_note_course = mysqli_num_rows($sql_note_course);
                                    if ($num_note_course == 0) {
                                        echo "--";
                                    } else {
                                        $row_note_course = mysqli_fetch_assoc($sql_note_course);
                                        echo $row_note_course['name'];
                                    }?></td>

                                    <td><?php $select_note_author = "SELECT * FROM admin WHERE id = '$note_author'";
                                    $sql_note_author = mysqli_query($db, $select_note_author);
                                    $num_note_author = mysqli_num_rows($sql_note_author);
                                    $row_note_author = mysqli_fetch_assoc($sql_note_author);
                                    echo $row_note_author['name'];?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $note_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                        </div>

                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="delete<?php echo $note_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Note</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $row_note_chapter['chapter']; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <!-- DELETE BUTTON -->
                                                        <form action="" method="post">
                                                            <input type="hidden" name="delete_id" id="" value="<?php echo $note_id; ?>">
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
</main>
<?php } else { ?><script type="text/javascript">window.location.href = '../course/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>