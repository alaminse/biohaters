<?php include('../assets/includes/header.php'); ?>

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
    $blog_id        = mysqli_escape_string($db, $_POST['id']);
    $blog_name      = mysqli_escape_string($db, $_POST['name']);
    $blog_tags      = mysqli_escape_string($db, $_POST['tags']);
    $blog_des       = mysqli_escape_string($db, $_POST['des']);
    $blog_category  = $_POST['category'];
    $blog_status    = $_POST['status'];
    $cover_pic      = $_FILES['cover_pic']['name'];
    $cover_pic_tmp  = $_FILES['cover_pic']['tmp_name'];
    $cover_pic_size  = $_FILES['cover_pic']['size'];

    // cover unlink path
    $previous_cover_photo = mysqli_escape_string($db, $_POST['blog_cover_photo']);

    if (isset($_POST['featured'])) {
        $blog_featured = 1;
    } else {
        $blog_featured = 0;
    }

    if (empty($blog_name) || empty($blog_category) || empty($blog_tags) || empty($blog_des)) {
        $alert = "<p class='warning mb_75'>Required Fields.....</p>";
    } else {
        if (!empty($cover_pic)) {
            $array_img = explode('.', $cover_pic);
            $extension_img = end($array_img);

            if ($extension_img == 'jpg' || $extension_img == 'png') {
                if ($cover_pic_size <= 120000) {
                    $random_prev = rand(0, 999999);
                    $random = rand(0, 999999);
                    $random_next = rand(0, 999999);
                    $time = date('Ymdhis');

                    $final_img = "../assets/post/hc_".$random_prev."_".$random."_".$random_next."_".$time."_".$cover_pic;

                    move_uploaded_file($cover_pic_tmp, $final_img);

                    if (!empty($previous_cover_photo)) {
                        // delete previous cover photo
                        unlink($previous_cover_photo);
                    }
                } else {
                    $alert = '<p class="danger mb_75">Cover Pic should be under 100KB</p>';
                    $final_img = $previous_cover_photo;
                }
            } else {
                $alert = '<p class="danger mb_75">Give PNG or JPG file</p>';
                $final_img = $previous_cover_photo;
            }
        } else {
            $final_img = $previous_cover_photo;
        }

        // update post
        $update = "UPDATE hc_blog SET name = '$blog_name', description = '$blog_des', category = '$blog_category', tags = '$blog_tags', is_featured = '$blog_featured', cover_photo = '$final_img', status = '$blog_status' WHERE id = '$blog_id'";
        $sql_update = mysqli_query($db, $update);
        if ($sql_update) {
            ?>
            <script type="text/javascript">
                window.location.href = '../blog/';
            </script>
            <?php 
        }
    }
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
            <?php echo $alert; ?>
            <?php if (isset($_POST['edit_blog'])) {
                $edit_id = $_POST['edit_id'];

                $select = "SELECT * FROM hc_blog WHERE id = '$edit_id'";
                $sql = mysqli_query($db, $select);
                $row = mysqli_fetch_assoc($sql);
                $blog_id            = $row['id'];
                $blog_name          = $row['name'];
                $blog_des           = $row['description'];
                $blog_category      = $row['category'];
                $blog_tags          = $row['tags'];
                $blog_featured      = $row['is_featured'];
                $blog_cover_photo   = $row['cover_photo'];
                $blog_status        = $row['status'];
                $blog_author        = $row['author']; ?>
                <!--========== EDIT BLOG ==========-->
                <div class="add_category">
                    <h5 class="box_title">Update Blog</h5>

                    <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                        <div>
                            <label for="">Title*</label>
                            <input type="text" id="" name="name" placeholder="Title" value="<?php echo $blog_name; ?>">
                        </div>

                        <div>
                            <label for="">Category*</label>
                            <select id="" name="category">
                                <option value="">Choose Category</option>
                                <?php $select = "SELECT * FROM hc_blog_category WHERE is_delete = 0 ORDER BY id DESC";
                                $sql = mysqli_query($db, $select);
                                $num = mysqli_num_rows($sql);
                                if ($num > 0) {
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        $category_id     = $row['id'];
                                        $category_name   = $row['name'];
                                        ?>
                                        <option value="<?php echo $category_id; ?>" <?php if ($blog_category == $category_id) {echo "selected";}?>><?php echo $category_name; ?></option>
                                        <?php 
                                    }
                                }?>
                            </select>
                        </div>

                        <div>
                            <label for="">Tags*</label>
                            <input type="text" id="" name="tags" placeholder=", is the separator" value="<?php echo $blog_tags; ?>">
                        </div>

                        <div>
                            <label for="">Cover Photo* (995*560px, max: 100kb)</label>
                            <input type="file" id="" name="cover_pic" class="input_sm">
                        </div>

                        <div>
                            <label for="">Status</label>
                            <select id="" name="status">
                                <option value="0">Choose Status</option>
                                <option value="1" <?php if ($blog_status == 1) {echo "selected";} ?>>Published</option>
                                <option value="0" <?php if ($blog_status == 0) {echo "selected";} ?>>Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="featured">Featured</label>
                            <label for="featured" class="checkbox_label">
                                <input type="checkbox" class="checkbox" name="featured" id="featured" <?php if ($blog_featured == 1) {echo "checked";}?>>
                                <span class="checked"></span>
                                Yes
                            </label>
                        </div>

                        <div class="grid_col_3">
                            <label for="">Description*</label>
                            <textarea id="" name="des" placeholder="Description" rows="6"><?php echo $blog_des; ?></textarea>
                        </div>

                        <!-- get previous cover photo to unlink easily -->
                        <input type="hidden" name="blog_cover_photo" value="<?php echo $blog_cover_photo; ?>">
                        
                        <!-- get id -->
                        <input type="hidden" name="id" value="<?php echo $blog_id; ?>">

                        <button type="submit" name="edit">Update Blog</button>
                    </form>
                </div>
                <?php 
            }?>
        </div>
    </div>
</main>

<!--========== CKEDITOR JS =============-->
<script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>

<script>
/*========== POST DESCRIPTION CKEDITOR =============*/
CKEDITOR.replace( 'des' );
</script>

<?php include('../assets/includes/footer.php'); ?>