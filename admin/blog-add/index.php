<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php // ADD BLOG 
$alert = '';
if (isset($_POST['add'])) {
    $blog_name      = mysqli_escape_string($db, $_POST['name']);
    $blog_tags      = mysqli_escape_string($db, $_POST['tags']);
    $blog_des       = mysqli_escape_string($db, $_POST['des']);
    $blog_category  = $_POST['category'];
    $blog_status    = $_POST['status'];
    $cover_pic      = $_FILES['cover_pic']['name'];
    $cover_pic_tmp  = $_FILES['cover_pic']['tmp_name'];
    $cover_pic_size  = $_FILES['cover_pic']['size'];

    if (isset($_POST['featured'])) {
        $blog_featured = 1;
    } else {
        $blog_featured = 0;
    }

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($blog_name) || empty($blog_category) || empty($blog_tags) || empty($cover_pic) || empty($blog_des)) {
        $alert = "<p class='warning mb_75'>Required Fields.....</p>";
    } else {
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

                // add post
                $add = "INSERT INTO hc_blog (name, description, category, tags, is_featured, cover_photo, status, author, created_date) VALUES ('$blog_name', '$blog_des', '$blog_category', '$blog_tags', '$blog_featured', '$final_img', '$blog_status', '$admin_id', '$created_date')";
                $sql_add = mysqli_query($db, $add);
                if ($sql_add) {
                    ?>
                    <script type="text/javascript">
                        window.location.href = '../blog/';
                    </script>
                    <?php 
                }
            } else {
                $alert = '<p class="danger mb_75">Cover Pic should be under 100KB</p>';
            }
        } else {
            $alert = '<p class="danger mb_75">Give PNG or JPG file</p>';
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
            <!--========== ADD COURSE CATEGORY ==========-->
            <div class="add_category">
                <h5 class="box_title">Add Blog</h5>

                <?php echo $alert; ?>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Title*</label>
                        <input type="text" id="" name="name" placeholder="Title">
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

                                    echo '<option value="'.$category_id.'">'.$category_name.'</option>';
                                }
                            }?>
                        </select>
                    </div>

                    <div>
                        <label for="">Tags*</label>
                        <input type="text" id="" name="tags" placeholder=", is the separator">
                    </div>

                    <div>
                        <label for="">Cover Photo* (995*560px, max: 100kb)</label>
                        <input type="file" id="" name="cover_pic" class="input_sm">
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
                        <label for="featured">Featured</label>
                        <label for="featured" class="checkbox_label">
                            <input type="checkbox" class="checkbox" name="featured" id="featured">
                            <span class="checked"></span>
                            Yes
                        </label>
                    </div>

                    <div class="grid_col_3">
                        <label for="">Description*</label>
                        <textarea id="" name="des" placeholder="Description" rows="6"></textarea>
                    </div>

                    <button type="submit" name="add">Add Post</button>
                </form>
            </div>
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