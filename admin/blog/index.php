<?php include('../assets/includes/header.php'); ?>

<!-- DELETE BLOG -->
<?php if (isset($_POST['delete'])) {
    $blog_id      = mysqli_escape_string($db, $_POST['delete_id']);

    // delete cover photo
    $blog_cover_photo      = mysqli_escape_string($db, $_POST['delete_cover_photo']);

    unlink($blog_cover_photo);

    $delete = "UPDATE hc_blog SET is_delete = 1 WHERE id = '$blog_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../blog/';
        </script>
        <?php 
    }
}?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Blog</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE BLOG ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Manage Blog</h5>
                    <div class="btn_grp">
                        <a href="../blog-category/" class="button btn_sm">Blog Category</a>
                        <a href="../blog-add/" class="button btn_sm">Add Blog</a>
                    </div>
                </div>

                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Cover Pic</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Featured</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th>Published On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_blog WHERE is_delete = 0 ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num == 0) {
                            echo "<tr><td colspan='9' class='text_center'>There are no blog</td></tr>";
                        } else {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $blog_id            = $row['id'];
                                $blog_name          = $row['name'];
                                $blog_category      = $row['category'];
                                $blog_featured      = $row['is_featured'];
                                $blog_cover_photo   = $row['cover_photo'];
                                $blog_status        = $row['status'];
                                $blog_author        = $row['author'];
                                $blog_created_date  = $row['created_date'];
                                $si++;

                                $blog_created_date = date('d M Y', strtotime($blog_created_date));
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>

                                    <td>
                                        <div class="table_img">
                                            <img src="<?php echo $blog_cover_photo; ?>" alt="">
                                        </div>
                                    </td>
                                    
                                    <td><?php echo $blog_name; ?></td>

                                    <td><?php $select_blog_category = "SELECT * FROM hc_blog_category WHERE id = '$blog_category'";
                                    $sql_blog_category = mysqli_query($db, $select_blog_category);
                                    $num_blog_category = mysqli_num_rows($sql_blog_category);
                                    if ($num_blog_category == 0) {
                                        echo "--";
                                    } else {
                                        $row_blog_category = mysqli_fetch_assoc($sql_blog_category);
                                        echo $row_blog_category['name'];
                                    }?></td>

                                    <td><?php if ($blog_featured == 1) {
                                        echo '<div class="ep_badge bg_success text_success">Yes</div>';
                                    } else {
                                        echo '<div class="ep_badge bg_danger text_danger">No</div>';
                                    }?></td>

                                    <td><?php if ($blog_status == 1) {
                                        echo '<div class="ep_badge bg_success text_success">Published</div>';
                                    } else {
                                        echo '<div class="ep_badge bg_danger text_danger">Draft</div>';
                                    }?></td>

                                    <td><?php $select_blog_author = "SELECT * FROM admin WHERE id = '$blog_author'";
                                    $sql_blog_author = mysqli_query($db, $select_blog_author);
                                    $num_blog_author = mysqli_num_rows($sql_blog_author);
                                    $row_blog_author = mysqli_fetch_assoc($sql_blog_author);
                                    echo $row_blog_author['name'];?></td>

                                    <td><?php echo $blog_created_date; ?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <!-- EDIT BUTTON -->
                                            <form action="../blog-edit/" method="post">
                                                <input type="hidden" name="edit_id" id="" value="<?php echo $blog_id; ?>">
                                                <button type="submit" name="edit_blog" class="btn_icon"><i class="bx bxs-edit"></i></button>
                                            </form>
                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $blog_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                        </div>

                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="delete<?php echo $blog_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $blog_name; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <form action="" method="post">
                                                            <input type="hidden" name="delete_cover_photo" id="" value="<?php echo $blog_cover_photo; ?>">
                                                            <input type="hidden" name="delete_id" id="" value="<?php echo $blog_id; ?>">
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