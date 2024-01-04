<?php include('../assets/includes/header.php'); ?>

<?php // ADD VIDEO
$alert = '';
if (isset($_POST['add'])) {
    $name       = mysqli_escape_string($db, $_POST['name']);
    $video      = $_FILES['video']['name'];
    $video_tmp  = $_FILES['video']['tmp_name'];
    $video_size = $_FILES['video']['size'];

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($name) || empty($video)) {
        $alert = "<p class='warning mb_75'>Required Fields.....</p>";
    } else {
        $array_video = explode('.', $video);
        $extension_video = end($array_video);

        if ($extension_video == 'mp4') {
            if ($video_size <= 400000000) {
                $random_prev = rand(0, 999999);
                $random = rand(0, 999999);
                $random_next = rand(0, 999999);
                $time = date('Ymdhis');

                $final_video = "../assets/video_gallery/hc_".$random_prev."_".$random."_".$random_next."_".$time."_".$video;

                move_uploaded_file($video_tmp, $final_video);

                // add post
                $add = "INSERT INTO hc_video_gallery (name, video, created_date) VALUES ('$name', '$final_video', '$created_date')";
                $sql_add = mysqli_query($db, $add);
                if ($sql_add) {
                    ?>
                    <script type="text/javascript">
                        window.location.href = '../video-gallery/';
                    </script>
                    <?php 
                }
            } else {
                $alert = '<p class="danger mb_75">Video should be under 400MB</p>';
            }
        } else {
            $alert = '<p class="danger mb_75">Give only MP4 file</p>';
        }
    }
}

// DELETE VIDEO 
if (isset($_POST['delete'])) {
    $video_id = mysqli_escape_string($db, $_POST['delete_id']);

    // delete cover video
    $video = mysqli_escape_string($db, $_POST['delete_video']);

    unlink($video);

    $delete = "UPDATE hc_video_gallery SET is_delete = 1 WHERE id = '$video_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../video-gallery/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Video Gallery</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container ep_grid grid_1_3">
            <!-- upload video -->
            <div class="ep_grid border_col height_max">
                <h5>Upload New Video</h5>

                <?php echo $alert; ?>

                <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Name</label>
                        <input type="text" id="" name="name" placeholder="Video Name">
                    </div>

                    <div>
                        <label for="">Video* (max: 400mb)</label>
                        <input type="file" id="" name="video" class="input_sm">
                    </div>

                    <button type="submit" name="add" class="btn_sm">Add Video</button>
                </form>
            </div>

            <!-- video gallery -->
            <div class="ep_grid grid_3">
                <?php $select = "SELECT * FROM hc_video_gallery WHERE is_delete = 0 ORDER BY id DESC";
                $sql = mysqli_query($db, $select);
                $num = mysqli_num_rows($sql);
                if ($num == 0) {
                    echo "<tr><td colspan='9' class='text_center'>There are no Video</td></tr>";
                } else {
                    $si = 0;
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $video_id       = $row['id'];
                        $video_name     = $row['name'];
                        $video_link     = $row['video'];
                        $created_date   = $row['created_date'];
                        $si++;

                        $copy_link = "https://biohaters.com/admin".substr($video_link, 2);
                        ?>
                        <div class="gallery_video height_max">
                            <video src="<?php echo $video_link; ?>" alt="" controls></video>
                            <div class="gallery_video_data"></div>
                            <div class="ep_flex">
                                <p class="text_sm">Connected with <span class="text_semi"><?php // connected with course
                                $select_course_lecture = "SELECT COUNT(id) as course_lecture FROM hc_course_lecture WHERE video = '$copy_link' AND is_delete = 0";
                                $sql_course_lecture = mysqli_query($db, $select_course_lecture);
                                $row_course_lecture = mysqli_fetch_assoc($sql_course_lecture);

                                // connected with chapter
                                $select_chapter_lecture = "SELECT COUNT(id) as chapter_lecture FROM hc_chapter_lecture WHERE video = '$copy_link' AND is_delete = 0";
                                $sql_chapter_lecture = mysqli_query($db, $select_chapter_lecture);
                                $row_chapter_lecture = mysqli_fetch_assoc($sql_chapter_lecture);
                                echo $row_course_lecture['course_lecture'] + $row_chapter_lecture['chapter_lecture']; ?> Lectures</span></p>
                                <div class="btn_grp">
                                    <!-- VIEW LINK  MODAL BUTTON -->
                                    <button type="button" class="btn_icon font_1_4" data-bs-toggle="modal" data-bs-target="#link<?php echo $video_id; ?>"><i class='bx bx-link-external'></i></button>
                                    <!-- DELETE MODAL BUTTON -->
                                    <button type="button" class="btn_icon font_1_4" data-bs-toggle="modal" data-bs-target="#delete<?php echo $video_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                </div>
                            </div>

                            <!-- VIEW LINK MODAL -->
                            <div class="modal fade" id="link<?php echo $video_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Video Link View</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <label for="">Video Link</label>
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
                            <div class="modal fade" id="delete<?php echo $video_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete Video</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Do you want to delete?<span class ="ep_p text_semi bg_danger text_danger"><?php echo $video_name; ?></span>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                            <form action="" method="post">
                                                <input type="hidden" name="delete_video" id="" value="<?php echo $video_link; ?>">
                                                <input type="hidden" name="delete_id" id="" value="<?php echo $video_id; ?>">
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