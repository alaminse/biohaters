<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<!-- add blog category -->
<?php $alert = '';
if (isset($_POST['add'])) {
    $category_name      = mysqli_escape_string($db, $_POST['name']);
    $category_des       = mysqli_escape_string($db, $_POST['des']);
    $category_status    = $_POST['status'];
    $category_parent    = $_POST['parent'];

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($category_name)) {
        $alert = "<p class='warning mb_75'>Required Category Name.....</p>";
    } else {
        $add = "INSERT INTO hc_blog_category (name, description, parent, status, author, created_date) VALUES ('$category_name', '$category_des', '$category_parent', '$category_status', '$admin_id', '$created_date')";
        $sql_add = mysqli_query($db, $add);
        if ($sql_add) {
            ?>
            <script type="text/javascript">
                window.location.href = '../blog-category/';
            </script>
            <?php 
        }
    }
}?>

<!-- delete blog category -->
<?php if (isset($_POST['delete'])) {
    $category_id      = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_blog_category SET is_delete = 1 WHERE id = '$category_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../blog-category/';
        </script>
        <?php 
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
            <!--========== ADD PRODUCT CATEGORY ==========-->
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
                            <?php $select = "SELECT * FROM hc_blog_category WHERE is_delete = 0 ORDER BY id DESC";
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

            <!--========== MANAGE PRODUCT CATEGORY ==========-->
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
                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $blog_category_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                        </div>

                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="delete<?php echo $blog_category_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $blog_category_name; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <!-- DELETE BUTTON -->
                                                        <form action="" method="post">
                                                            <input type="hidden" name="delete_id" id="" value="<?php echo $blog_category_id; ?>">
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