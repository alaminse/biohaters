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

    // add module
    $alert = '';
    if (isset($_POST['add'])) {
        $module_name      = mysqli_escape_string($db, $_POST['name']);

        $created_date = date('Y-m-d H:i:s', time());

        if (empty($module_name)) {
            $alert = "<p class='warning mb_75'>Required Module Name.....</p>";
        } else {
            $add = "INSERT INTO hc_module (name, course, author, created_date) VALUES ('$module_name', '$course', '$admin_id', '$created_date')";
            $sql_add = mysqli_query($db, $add);
            if ($sql_add) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../module/?course=<?php echo $course; ?>';
                </script>
                <?php 
            }
        }
    }

    // delete module
    if (isset($_POST['delete'])) {
        $module_id = mysqli_escape_string($db, $_POST['delete_id']);

        $delete = "UPDATE hc_module SET is_delete = 1 WHERE id = '$module_id'";
        $sql_delete = mysqli_query($db, $delete);
        if ($sql_delete) {
            ?>
            <script type="text/javascript">
                window.location.href = '../module/?course=<?php echo $course; ?>';
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
                <h5 class="box_title">Add Course Module</h5>

                <?php echo $alert; ?>

                <form action="" method="post" class="single_col_form">
                    <div>
                        <label for="">Module Name*</label>
                        <input type="text" id="" name="name" placeholder="Module Name">
                    </div>

                    <button type="submit" name="add">Add Module</button>
                </form>
            </div>

            <!--========== MANAGE PRODUCT CATEGORY ==========-->
            <div class="mng_category">
                <h5 class="box_title">Manage Course Module</h5>

                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Author</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_module WHERE course = '$course' AND is_delete = 0 ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num == 0) {
                            echo "<tr><td colspan='4' class='text_center'>There are no Course Module</td></tr>";
                        } else {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $module_id        = $row['id'];
                                $module_name      = $row['name'];
                                $module_course    = $row['course'];
                                $module_author    = $row['author'];
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td><?php echo $module_id; ?></td>
                                    
                                    <td><?php echo $module_name; ?></td>

                                    <td><?php $select_module_course = "SELECT * FROM hc_course WHERE id = '$module_course'";
                                    $sql_module_course = mysqli_query($db, $select_module_course);
                                    $num_module_course = mysqli_num_rows($sql_module_course);
                                    if ($num_module_course == 0) {
                                        echo "--";
                                    } else {
                                        $row_module_course = mysqli_fetch_assoc($sql_module_course);
                                        echo $row_module_course['name'];
                                    }?></td>

                                    <td><?php $select_module_author = "SELECT * FROM admin WHERE id = '$module_author'";
                                    $sql_module_author = mysqli_query($db, $select_module_author);
                                    $num_module_author = mysqli_num_rows($sql_module_author);
                                    $row_module_author = mysqli_fetch_assoc($sql_module_author);
                                    echo $row_module_author['name'];?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $module_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                        </div>

                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="delete<?php echo $module_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Module</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $module_name; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <!-- DELETE BUTTON -->
                                                        <form action="" method="post">
                                                            <input type="hidden" name="delete_id" id="" value="<?php echo $module_id; ?>">
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