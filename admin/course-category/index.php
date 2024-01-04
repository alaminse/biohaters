<?php include('../assets/includes/header.php'); ?>

<!-- DELETE COURSE CATEGORY -->
<?php if (isset($_POST['delete'])) {
    $category_id      = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_course_category SET is_delete = 1 WHERE id = '$category_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../course-category/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Course Category</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE COURSE CATEGORY ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Manage Category</h5>
                    <a href="../course-category-add/" class="button btn_sm">Add Category</a>
                </div>

                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_course_category WHERE is_delete = 0 ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num == 0) {
                            echo "<tr><td colspan='6' class='text_center'>There are no category</td></tr>";
                        } else {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $course_category_id        = $row['id'];
                                $course_category_name      = $row['name'];
                                $course_category_parent    = $row['parent'];
                                $course_category_status    = $row['status'];
                                $course_category_author    = $row['author'];
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td><?php echo $course_category_name; ?></td>

                                    <td><?php $select_course_category_parent = "SELECT * FROM hc_course_category WHERE id = '$course_category_parent'";
                                    $sql_course_category_parent = mysqli_query($db, $select_course_category_parent);
                                    $num_course_category_parent = mysqli_num_rows($sql_course_category_parent);
                                    if ($num_course_category_parent == 0) {
                                        echo "--";
                                    } else {
                                        $row_course_category_parent = mysqli_fetch_assoc($sql_course_category_parent);
                                        echo $row_course_category_parent['name'];
                                    }?></td>

                                    <td><?php if ($course_category_status == 1) {
                                        echo '<div class="ep_badge bg_success text_success">Published</div>';
                                    } else {
                                        echo '<div class="ep_badge bg_danger text_danger">Draft</div>';
                                    }?></td>

                                    <td><?php $select_course_category_author = "SELECT * FROM admin WHERE id = '$course_category_author'";
                                    $sql_course_category_author = mysqli_query($db, $select_course_category_author);
                                    $num_course_category_author = mysqli_num_rows($sql_course_category_author);
                                    $row_course_category_author = mysqli_fetch_assoc($sql_course_category_author);
                                    echo $row_course_category_author['name'];?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <!-- EDIT BUTTON -->
                                            <form action="../course-category-edit/" method="post">
                                                <input type="hidden" name="edit_id" id="" value="<?php echo $course_category_id; ?>">
                                                <button type="submit" name="edit_category" class="btn_icon"><i class="bx bxs-edit"></i></button>
                                            </form>
                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $course_category_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                        </div>

                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="delete<?php echo $course_category_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $course_category_name; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <form action="" method="post">
                                                            <input type="hidden" name="delete_id" id="" value="<?php echo $course_category_id; ?>">
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

<?php include('../assets/includes/footer.php'); ?>