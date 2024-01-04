<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 3 || $admin_role == 6 || $admin_role == 7 || $admin_role == 8) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php // EDIT COURSE CATEGORY
$alert = '';
if (isset($_POST['edit'])) {
    $category_id      = mysqli_escape_string($db, $_POST['id']);
    $category_name      = mysqli_escape_string($db, $_POST['name']);
    $category_des       = mysqli_escape_string($db, $_POST['des']);
    $category_status    = $_POST['status'];
    $category_parent    = $_POST['parent'];

    if (empty($category_name)) {
        $alert = "<p class='warning mb_75'>Required Category Name.....</p>";
    } else {
        $update = "UPDATE hc_course_category SET name = '$category_name', description = '$category_des', parent = '$category_parent', status = '$category_status' WHERE id = '$category_id'";
        $sql_update = mysqli_query($db, $update);
        if ($sql_update) {
            ?>
            <script type="text/javascript">
                window.location.href = '../course-category/';
            </script>
            <?php 
        }
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
            <?php echo $alert; ?>
            <?php if (isset($_POST['edit_category'])) {
                $edit_id = $_POST['edit_id'];

                $select = "SELECT * FROM hc_course_category WHERE id = '$edit_id'";
                $sql = mysqli_query($db, $select);
                $row = mysqli_fetch_assoc($sql);
                $course_category_id        = $row['id'];
                $course_category_name      = $row['name'];
                $course_category_des       = $row['description'];
                $course_category_parent    = $row['parent'];
                $course_category_status    = $row['status'];
                $course_category_author    = $row['author']; ?>
                <!--========== EDIT COURSE CATEGORY ==========-->
                <div class="add_category">
                    <h5 class="box_title">Update Category</h5>

                    <form action="" method="post" class="single_col_form">
                        <div>
                            <label for="product-cat-name">Category Name*</label>
                            <input type="text" id="product-cat-name" name="name" placeholder="Category Name" value="<?php echo $course_category_name; ?>">
                        </div>

                        <div>
                            <label for="product-cat-des">Category Description</label>
                            <textarea id="product-cat-des" name="des" placeholder="Category Description" rows="4"><?php echo $course_category_des; ?></textarea>
                        </div>

                        <div>
                            <label for="product-cat-status">Status</label>
                            <select id="product-cat-status" name="status">
                                <option value="">Choose Status</option>
                                <option value="1" <?php if ($course_category_status == 1) {echo "selected";} ?>>Published</option>
                                <option value="0" <?php if ($course_category_status == 0) {echo "selected";} ?>>Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="product-cat-parent">Parent Category</label>
                            <select id="product-cat-parent" name="parent">
                                <option value="">Choose Parent Category</option>
                                <?php $select_parent = "SELECT * FROM hc_course_category WHERE is_delete = 0 ORDER BY id DESC";
                                $sql_parent = mysqli_query($db, $select_parent);
                                $num_parent = mysqli_num_rows($sql_parent);
                                if ($num_parent > 0) {
                                    while ($row_parent = mysqli_fetch_assoc($sql_parent)) {
                                        $course_parent_id     = $row_parent['id'];
                                        $course_parent_name   = $row_parent['name'];
                                        ?>
                                        <option value="<?php echo $course_parent_id; ?>" <?php if ($course_category_parent == $course_parent_id) {echo "selected";}?>><?php echo $course_parent_name; ?></option>
                                        <?php 
                                    }
                                }?>
                            </select>
                        </div>
                        
                        <!-- get id -->
                        <input type="hidden" name="id" value="<?php echo $course_category_id; ?>">

                        <button type="submit" name="edit">Update Category</button>
                    </form>
                </div>
                <?php 
            }?>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>