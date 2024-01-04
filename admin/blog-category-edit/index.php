<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<!-- edit blog category -->
<?php $alert = '';
if (isset($_POST['edit'])) {
    $category_id      = mysqli_escape_string($db, $_POST['id']);
    $category_name      = mysqli_escape_string($db, $_POST['name']);
    $category_des       = mysqli_escape_string($db, $_POST['des']);
    $category_status    = $_POST['status'];
    $category_parent    = $_POST['parent'];

    if (empty($category_name)) {
        $alert = "<p class='warning mb_75'>Required Category Name.....</p>";
    } else {
        $update = "UPDATE hc_blog_category SET name = '$category_name', description = '$category_des', parent = '$category_parent', status = '$category_status' WHERE id = '$category_id'";
        $sql_update = mysqli_query($db, $update);
        if ($sql_update) {
            ?>
            <script type="text/javascript">
                window.location.href = '../blog-category/';
            </script>
            <?php 
        }
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Blog Category</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container ep_grid grid_2">
            <!--========== UPDATE CATEGORY ==========-->
            <?php echo $alert; ?>
            <?php if (isset($_POST['edit_category'])) {
                $edit_id = $_POST['edit_id'];

                $select = "SELECT * FROM hc_blog_category WHERE id = '$edit_id'";
                $sql = mysqli_query($db, $select);
                $row = mysqli_fetch_assoc($sql);
                $blog_category_id        = $row['id'];
                $blog_category_name      = $row['name'];
                $blog_category_des       = $row['description'];
                $blog_category_parent    = $row['parent'];
                $blog_category_status    = $row['status'];
                $blog_category_author    = $row['author']; ?>
                <!--========== UPDATE BLOG CATEGORY ==========-->
                <div class="add_category">
                    <h5 class="box_title">Update Category</h5>

                    <form action="" method="post" class="single_col_form">
                        <div>
                            <label for="">Category Name*</label>
                            <input type="text" id="" name="name" placeholder="Category Name" value="<?php echo $blog_category_name; ?>">
                        </div>

                        <div>
                            <label for="">Category Description</label>
                            <textarea id="" name="des" placeholder="Category Description" rows="4"><?php echo $blog_category_des; ?></textarea>
                        </div>

                        <div>
                            <label for="">Status</label>
                            <select id="" name="status">
                                <option value="">Choose Status</option>
                                <option value="1" <?php if ($blog_category_status == 1) {echo "selected";} ?>>Published</option>
                                <option value="0" <?php if ($blog_category_status == 0) {echo "selected";} ?>>Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="">Parent Category</label>
                            <select id="" name="parent">
                                <option value="">Choose Parent Category</option>
                                <?php $select_parent = "SELECT * FROM hc_blog_category WHERE is_delete = 0 ORDER BY id DESC";
                                $sql_parent = mysqli_query($db, $select_parent);
                                $num_parent = mysqli_num_rows($sql_parent);
                                if ($num_parent > 0) {
                                    while ($row_parent = mysqli_fetch_assoc($sql_parent)) {
                                        $blog_parent_id     = $row_parent['id'];
                                        $blog_parent_name   = $row_parent['name'];
                                        ?>
                                        <option value="<?php echo $blog_parent_id; ?>" <?php if ($blog_category_parent == $blog_parent_id) {echo "selected";}?>><?php echo $blog_parent_name; ?></option>
                                        <?php 
                                    }
                                }?>
                            </select>
                        </div>
                        
                        <!-- get id -->
                        <input type="hidden" name="id" value="<?php echo $blog_category_id; ?>">

                        <button type="submit" name="edit">Update Category</button>
                    </form>
                </div>
                <?php 
            }?>

            <!--========== MANAGE CATEGORY ==========-->
            <div class="mng_category">
                <h5 class="box_title">Manage Category</h5>

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
                        <?php $select = "SELECT * FROM hc_blog_category WHERE is_delete = 0 ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num == 0) {
                            echo "<tr><td colspan='6' class='text_center'>There are no category</td></tr>";
                        } else {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $blog_category_id        = $row['id'];
                                $blog_category_name      = $row['name'];
                                $blog_category_parent    = $row['parent'];
                                $blog_category_status    = $row['status'];
                                $blog_category_author    = $row['author'];
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td><?php echo $blog_category_name; ?></td>

                                    <td><?php $select_blog_category_parent = "SELECT * FROM hc_blog_category WHERE id = '$blog_category_parent'";
                                    $sql_blog_category_parent = mysqli_query($db, $select_blog_category_parent);
                                    $num_blog_category_parent = mysqli_num_rows($sql_blog_category_parent);
                                    if ($num_blog_category_parent == 0) {
                                        echo "--";
                                    } else {
                                        $row_blog_category_parent = mysqli_fetch_assoc($sql_blog_category_parent);
                                        echo $row_blog_category_parent['name'];
                                    }?></td>

                                    <td><?php if ($blog_category_status == 1) {
                                        echo '<div class="ep_badge bg_success text_success">Published</div>';
                                    } else {
                                        echo '<div class="ep_badge bg_danger text_danger">Draft</div>';
                                    }?></td>

                                    <td><?php $select_blog_category_author = "SELECT * FROM admin WHERE id = '$blog_category_author'";
                                    $sql_blog_category_author = mysqli_query($db, $select_blog_category_author);
                                    $num_blog_category_author = mysqli_num_rows($sql_blog_category_author);
                                    $row_blog_category_author = mysqli_fetch_assoc($sql_blog_category_author);
                                    echo $row_blog_category_author['name'];?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <!-- EDIT BUTTON -->
                                            <form action="../blog-category-edit/" method="post">
                                                <input type="hidden" name="edit_id" id="" value="<?php echo $blog_category_id; ?>">
                                                <button type="submit" name="edit_category" class="btn_icon"><i class="bx bxs-edit"></i></button>
                                            </form>
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