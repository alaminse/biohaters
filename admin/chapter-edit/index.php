<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php // chapter edit
$alert = '';
if (isset($_POST['edit'])) {
    $chapter_id         = mysqli_escape_string($db, $_POST['id']);
    $chapter_name       = mysqli_escape_string($db, $_POST['chapter']);
    $chapter_price      = mysqli_escape_string($db, $_POST['price']);
    $chapter_sale_price = mysqli_escape_string($db, $_POST['sale_price']);
    $chapter_status     = $_POST['status'];
    $chapter_subject    = $_POST['subject'];
    $cover_pic          = $_FILES['cover_pic']['name'];
    $cover_pic_tmp      = $_FILES['cover_pic']['tmp_name'];
    $cover_pic_size     = $_FILES['cover_pic']['size'];

    // cover unlink path
    $previous_cover_photo = mysqli_escape_string($db, $_POST['chapter_cover_photo']);

    if (empty($chapter_name) || empty($chapter_subject) || ($chapter_price == '')) {
        $alert = "<p class='warning mb_75'>Required Fields.....</p>";
    } else {
        if ($chapter_price <= $chapter_sale_price) {
            $alert = "<p class='warning mb_75'>Sale price must be lower than Regular price.....</p>";
        } else {
            if (!empty($cover_pic)) {
                $array_img = explode('.', $cover_pic);
                $extension_img = end($array_img);
    
                if ($extension_img == 'jpg' || $extension_img == 'png') {
                    if ($cover_pic_size <= 60000) {
                        $random_prev = rand(0, 999999);
                        $random = rand(0, 999999);
                        $random_next = rand(0, 999999);
                        $time = date('Ymdhis');
    
                        $final_img = "../assets/chapter/hc_".$random_prev."_".$random."_".$random_next."_".$time."_".$cover_pic;
    
                        move_uploaded_file($cover_pic_tmp, $final_img);
    
                        // delete previous cover photo
                        unlink($previous_cover_photo);
                    } else {
                        $alert = '<p class="danger mb_75">Cover Pic should be under 60KB</p>';
                        $final_img = $previous_cover_photo;
                    }
                } else {
                    $alert = '<p class="danger mb_75">Give PNG or JPG file</p>';
                    $final_img = $previous_cover_photo;
                }
            } else {
                $final_img = $previous_cover_photo;
            }

            // update chapter
            $update = "UPDATE hc_chapter SET chapter = '$chapter_name', subject = '$chapter_subject', price = '$chapter_price', sale_price = '$chapter_sale_price', cover_photo = '$final_img', status = '$chapter_status' WHERE id = '$chapter_id'";
            $sql_update = mysqli_query($db, $update);
            if ($sql_update) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../chapter/';
                </script>
                <?php 
            }
        }
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Chapter</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <?php echo $alert; ?>
            <?php if (isset($_POST['edit_chapter'])) {
                $edit_id = $_POST['edit_id'];

                $select = "SELECT * FROM hc_chapter WHERE id = '$edit_id'";
                $sql = mysqli_query($db, $select);
                $row = mysqli_fetch_assoc($sql);
                $chapter_id             = $row['id'];
                $chapter_name           = $row['chapter'];
                $chapter_subject        = $row['subject'];
                $chapter_price          = $row['price'];
                $chapter_sale           = $row['sale_price'];
                $chapter_cover_photo    = $row['cover_photo'];
                $chapter_status         = $row['status'];
                $chapter_author         = $row['author']; ?>
                <!--========== EDIT Chapter ==========-->
                <div class="add_category">
                    <h5 class="box_title">Update Chapter</h5>

                    <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                        <div>
                            <label for="">Chapter*</label>
                            <input type="text" id="" name="chapter" placeholder="Chapter Name" value="<?php echo $chapter_name; ?>">
                        </div>

                        <div>
                            <label for="">Price*</label>
                            <input type="text" id="" name="price" placeholder="Regular Price" value="<?php echo $chapter_price; ?>">
                        </div>

                        <div>
                            <label for="">Sale Price</label>
                            <input type="text" id="" name="sale_price" placeholder="Sale Price" value="<?php echo $chapter_sale; ?>">
                        </div>

                        <div>
                            <label for="">Cover Photo* (360*200px, max: 60kb)</label>
                            <input type="file" id="" name="cover_pic" class="input_sm">
                        </div>

                        <div>
                            <label for="">Status</label>
                            <select id="" name="status">
                                <option value="">Choose Status</option>
                                <option value="1" <?php if ($chapter_status == 1) {echo "selected";} ?>>Published</option>
                                <option value="0" <?php if ($chapter_status == 0) {echo "selected";} ?>>Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="">Subject*</label>
                            <select id="" name="subject">
                                <option value="">Choose Subject</option>
                                <?php $select_subject = "SELECT * FROM hc_subject WHERE is_delete = 0 ORDER BY id DESC";
                                $sql_subject = mysqli_query($db, $select_subject);
                                $num_subject = mysqli_num_rows($sql_subject);
                                if ($num_subject > 0) {
                                    while ($row_subject = mysqli_fetch_assoc($sql_subject)) {
                                        $subject_id     = $row_subject['id'];
                                        $subject_name   = $row_subject['subject'];
                                        ?>
                                        <option value="<?php echo $subject_id; ?>" <?php if ($chapter_subject == $subject_id) {echo "selected";}?>><?php echo $subject_name; ?></option>
                                        <?php 
                                    }
                                }?>
                            </select>
                        </div>

                        <!-- get previous cover photo to unlink easily -->
                        <input type="hidden" name="chapter_cover_photo" value="<?php echo $chapter_cover_photo; ?>">
                        
                        <!-- get id -->
                        <input type="hidden" name="id" value="<?php echo $chapter_id; ?>">

                        <button type="submit" name="edit">Update Chapter</button>
                    </form>
                </div>
                <?php 
            }?>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>