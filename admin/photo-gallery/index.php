<?php include('../assets/includes/header.php'); ?>

<?php // ADD PHOTO 
$alert = '';
if (isset($_POST['add'])) {
    $cover_pic      = $_FILES['cover_pic']['name'];
    $cover_pic_tmp  = $_FILES['cover_pic']['tmp_name'];
    $cover_pic_size  = $_FILES['cover_pic']['size'];

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($cover_pic)) {
        $alert = "<p class='warning mb_75'>Required File.....</p>";
    } else {
        $array_img = explode('.', $cover_pic);
        $extension_img = end($array_img);

        if ($extension_img == 'jpg' || $extension_img == 'png') {
            if ($cover_pic_size <= 270000) {
                $random_prev = rand(0, 999999);
                $random = rand(0, 999999);
                $random_next = rand(0, 999999);
                $time = date('Ymdhis');

                $final_img = "../assets/photo_gallery/hc_".$random_prev."_".$random."_".$random_next."_".$time."_".$cover_pic;

                move_uploaded_file($cover_pic_tmp, $final_img);

                // add post
                $add = "INSERT INTO hc_photo_gallery (cover_photo, created_date) VALUES ('$final_img', '$created_date')";
                $sql_add = mysqli_query($db, $add);
                if ($sql_add) {
                    ?>
                    <script type="text/javascript">
                        window.location.href = '../photo-gallery/';
                    </script>
                    <?php 
                }
            } else {
                $alert = '<p class="danger mb_75">Photo should be under 260KB</p>';
            }
        } else {
            $alert = '<p class="danger mb_75">Give PNG or JPG file</p>';
        }
    }
}

// DELETE PHOTO 
if (isset($_POST['delete'])) {
    $photo_id      = mysqli_escape_string($db, $_POST['delete_id']);

    // delete cover photo
    $cover_photo      = mysqli_escape_string($db, $_POST['delete_cover_photo']);

    unlink($cover_photo);

    $delete = "UPDATE hc_photo_gallery SET is_delete = 1 WHERE id = '$photo_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../photo-gallery/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Photo Gallery</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container mb_75">
            <div class="btn_grp">
                <a href="../docs-gallery/" class="button btn_sm"><i class='bx bxs-file'></i> Docs</a>
                <a href="../video-gallery/" class="button btn_sm"><i class='bx bxs-videos'></i> Video Gallery</a>
            </div>
        </div>
        
        <div class="ep_container ep_grid grid_1_3">
            <!-- upload photo -->
            <div class="ep_grid border_col height_max">
                <h5>Upload New Photo</h5>

                <?php echo $alert; ?>

                <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Photo* (360*200px, max: 60kb)</label>
                        <input type="file" id="" name="cover_pic" class="input_sm">
                    </div>

                    <button type="submit" name="add" class="btn_sm">Add Photo</button>
                </form>
            </div>

            <!-- photo gallery -->
            <div class="ep_grid grid_3">
                <?php $select = "SELECT * FROM hc_photo_gallery WHERE is_delete = 0 ORDER BY id DESC";
                $sql = mysqli_query($db, $select);
                $num = mysqli_num_rows($sql);
                if ($num == 0) {
                    echo "<tr><td colspan='9' class='text_center'>There are no Photo</td></tr>";
                } else {
                    $si = 0;
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $photo_id       = $row['id'];
                        $photo_link     = $row['cover_photo'];
                        $created_date   = $row['created_date'];
                        $si++;

                        $copy_link = "http://localhost/biohaters/admin".substr($photo_link, 2);
                        ?>
                        <div class="gallery_img height_max">
                            <img src="<?php echo $photo_link; ?>" alt="">
                            <div class="gallery_btn ep_flex ep_center">
                                <!-- VIEW LINK  MODAL BUTTON -->
                                <button type="button" class="btn_icon font_1_4" data-bs-toggle="modal" data-bs-target="#link<?php echo $photo_id; ?>"><i class='bx bx-link-external'></i></button>
                                <!-- DELETE MODAL BUTTON -->
                                <button type="button" class="btn_icon font_1_4" data-bs-toggle="modal" data-bs-target="#delete<?php echo $photo_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                            </div>

                            <!-- VIEW LINK MODAL -->
                            <div class="modal fade" id="link<?php echo $photo_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Photo Link View</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <label for="">Photo Link</label>
                                                <textarea rows="4" readonly="" style="width: 100%;"><?php echo $copy_link; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DELETE MODAL -->
                            <div class="modal fade" id="delete<?php echo $photo_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete Photo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Do you want to delete?
                                            <div class="table_img">
                                                <img src="<?php echo $photo_link; ?>" alt="">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                            <form action="" method="post">
                                                <input type="hidden" name="delete_cover_photo" id="" value="<?php echo $photo_link; ?>">
                                                <input type="hidden" name="delete_id" id="" value="<?php echo $photo_id; ?>">
                                                <button type="submit" name="delete" class="button bg_danger text_danger text_semi">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                    }
                }?>
            </div>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>