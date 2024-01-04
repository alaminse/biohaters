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

<?php // ADD COURSE CATEGORY 
$alert = '';
if (isset($_POST['add'])) {
    $category_name      = mysqli_escape_string($db, $_POST['name']);
    $category_des       = mysqli_escape_string($db, $_POST['des']);
    $category_status    = $_POST['status'];
    $category_parent    = $_POST['parent'];

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($category_name)) {
        $alert = "<p class='warning mb_75'>Required Category Name.....</p>";
    } else {
        $add = "INSERT INTO hc_course_category (name, description, parent, status, author, created_date) VALUES ('$category_name', '$category_des', '$category_parent', '$category_status', '$admin_id', '$created_date')";
        $sql_add = mysqli_query($db, $add);
        if ($sql_add) {
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
            <!--========== ADD COURSE CATEGORY ==========-->
            <div class="add_category">
                <h5 class="box_title">Add Category</h5>

                <?php echo $alert; ?>

                <form action="" method="post" class="single_col_form">
                    <div>
                        <label for="">Category Name*</label>
                        <input type="text" id="" name="name" placeholder="Category Name">
                    </div>

                    <div>
                        <label for="">Category Description</label>
                        <textarea id="" name="des" placeholder="Category Description" rows="4"></textarea>
                    </div>

                    <div>
                        <label for="">Status</label>
                        <select id="" name="status">
                            <option value="0">Choose Status</option>
                            <option value="1">Published</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>

                    <div>
                        <label for="">Parent Category</label>
                        <select id="" name="parent">
                            <option value="">Choose Parent Category</option>
                            <?php $select = "SELECT * FROM hc_course_category WHERE is_delete = 0 ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $parent_category_id     = $row['id'];
                                    $parent_category_name   = $row['name'];

                                    echo '<option value="'.$parent_category_id.'">'.$parent_category_name.'</option>';
                                }
                            }?>
                        </select>
                    </div>

                    <button type="submit" name="add">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>