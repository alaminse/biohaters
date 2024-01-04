<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['course'])) { 
    $course = $_GET['course'];

    // get course name
    $select_course  = "SELECT * FROM hc_course WHERE id = '$course' AND is_delete = 0";
    $sql_course     = mysqli_query($db, $select_course);
    $row_course     = mysqli_fetch_assoc($sql_course);
    $course_name    = $row_course['name'];

    // delete lecture
    if (isset($_POST['delete'])) {
        $lecture_id = mysqli_escape_string($db, $_POST['delete_id']);

        $delete = "UPDATE hc_course_lecture SET is_delete = 1 WHERE id = '$lecture_id'";
        $sql_delete = mysqli_query($db, $delete);
        if ($sql_delete) {
            ?>
            <script type="text/javascript">
                window.location.href = '../course-lecture/?course=<?php echo $course; ?>';
            </script>
            <?php 
        }
    }?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Course Lectures</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE PRODUCT CATEGORY ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title"><?= $course_name; ?> - Lectures</h5>
                    <a href="../course-lecture-add/?course=<?php echo $course; ?>" class="button btn_sm">Add Lecture</a>
                </div>

                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Name</th>
                            <th>Module</th>
                            <th>Status</th>
                            <th>Attach</th>
                            <th>3D Video</th>
                            <th>Author</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_course_lecture WHERE course = '$course' AND is_delete = 0 ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num == 0) {
                            echo "<tr><td colspan='6' class='text_center'>There are no Course Lectures</td></tr>";
                        } else {
                            $si = 0;
                            $now = date('Y-m-d H:i:s', time());
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $course_lecture_id              = $row['id'];
                                $course_lecture_name            = $row['name'];
                                $course_lecture_course          = $row['course'];
                                $course_lecture_module          = $row['module'];
                                $course_lecture_status          = $row['status'];
                                $course_lecture_author          = $row['author'];
                                $course_lecture_doc             = $row['document'];
                                $course_lecture_animation       = $row['animation'];
                                $course_lecture_created_date    = $row['created_date'];
                                
                                $course_lecture_scheduled = date('h:i:s a', strtotime($course_lecture_created_date));
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td><?php echo $course_lecture_name; ?></td>

                                    <td><?php $select_course_lecture_module = "SELECT * FROM hc_module WHERE id = '$course_lecture_module'";
                                    $sql_course_lecture_module = mysqli_query($db, $select_course_lecture_module);
                                    $row_course_lecture_module = mysqli_fetch_assoc($sql_course_lecture_module);
                                    echo $row_course_lecture_module['name'];?></td>

                                    <td><?php if ($course_lecture_status == 1) {
                                        if ($now >= $course_lecture_created_date) {
                                            echo '<div class="ep_badge bg_success text_success">Published</div>';
                                        } else {
                                            echo '<div class="ep_badge bg_info text_info">Scheduled : ' . $course_lecture_scheduled . '</div>';
                                        }
                                    } else {
                                        echo '<div class="ep_badge bg_danger text_danger">Draft</div>';
                                    }?></td>

                                    <td><?php if (!empty($course_lecture_doc)) {
                                        echo "<a href='" . $course_lecture_doc . "' target='_blank' class='ep_badge bg_warning text_warning'><i class='bx bxs-file'></i> View</a>";
                                    }?></td>
                                    
                                    <td>
                                        <a href="../course-lecture-animation/?course=<?php echo $course; ?>&lecture=<?php echo $course_lecture_id; ?>" target="_blank" class="ep_badge bg_warning text_warning">ADD</a>
                                    </td>

                                    <td><?php $select_course_lecture_author = "SELECT * FROM admin WHERE id = '$course_lecture_author'";
                                    $sql_course_lecture_author = mysqli_query($db, $select_course_lecture_author);
                                    $num_course_lecture_author = mysqli_num_rows($sql_course_lecture_author);
                                    $row_course_lecture_author = mysqli_fetch_assoc($sql_course_lecture_author);
                                    echo $row_course_lecture_author['name'];?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <!-- EDIT BUTTON -->
                                            <a href="../course-lecture-edit/?edit_id=<?php echo $course_lecture_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>

                                            <!-- LECTURE SHEET BUTTON -->
                                            <a href="../course-lecture-sheet/?course=<?php echo $course; ?>&lecture=<?php echo $course_lecture_id; ?>" class="btn_icon"><i class='bx bxs-file-doc'></i></a>

                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $course_lecture_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                        </div>

                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="delete<?php echo $course_lecture_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Lecture</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $course_lecture_name; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <!-- DELETE BUTTON -->
                                                        <form action="" method="post">
                                                            <input type="hidden" name="delete_id" id="" value="<?php echo $course_lecture_id; ?>">
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