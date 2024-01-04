<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php if (isset($_GET['edit_id'])) { 
    $edit_id = $_GET['edit_id'];

    // if edit id is not valid, redirect to another page
    if (empty($edit_id)) { ?><script type="text/javascript">window.location.href = '../chapter/';</script><?php }

    // get chapter lecture
    $select_lecture  = "SELECT * FROM hc_chapter_lecture WHERE id = '$edit_id' AND is_delete = 0";
    $sql_lecture     = mysqli_query($db, $select_lecture);
    $row_lecture     = mysqli_fetch_assoc($sql_lecture);
    $lecture_id         = $row_lecture['id'];
    $lecture_chapter     = $row_lecture['chapter'];
    $lecture_name       = $row_lecture['name'];
    $lecture_module     = $row_lecture['module'];
    $lecture_tags       = $row_lecture['tags'];
    $lecture_duration   = $row_lecture['duration'];
    $lecture_server     = $row_lecture['server'];
    $lecture_video      = $row_lecture['video'];
    $lecture_free       = $row_lecture['is_free'];
    $lecture_status     = $row_lecture['status'];
    
    // get chapter name
    $select_chapter  = "SELECT * FROM hc_chapter WHERE id = '$lecture_chapter' AND is_delete = 0";
    $sql_chapter     = mysqli_query($db, $select_chapter);
    $row_chapter     = mysqli_fetch_assoc($sql_chapter);
    $chapter_name    = $row_chapter['chapter'];

    // edit lecture
    $alert = '';
    if (isset($_POST['edit'])) {
        $chapter_lecture_name    = mysqli_escape_string($db, $_POST['name']);
        $chapter_lecture_tags    = mysqli_escape_string($db, $_POST['tags']);
        $chapter_lecture_video   = mysqli_escape_string($db, $_POST['video']);
        $chapter_lecture_hour    = mysqli_escape_string($db, $_POST['hour']);
        $chapter_lecture_minute  = mysqli_escape_string($db, $_POST['minute']);
        $chapter_lecture_second  = mysqli_escape_string($db, $_POST['second']);
        $chapter_lecture_status  = $_POST['status'];
        $chapter_lecture_server  = $_POST['server'];

        if (isset($_POST['free'])) {
            $video_free = 1;
        } else {
            $video_free = 0;
        }

        // get id and previous duration
        $chapter_lecture_id    = mysqli_escape_string($db, $_POST['id']);
        $previous_duration    = mysqli_escape_string($db, $_POST['previous_duration']);

        if (empty($chapter_lecture_name) || empty($chapter_lecture_video) || empty($chapter_lecture_server)) {
            $alert = "<p class='warning mb_75'>Required Fields.....</p>";
        } else {
            if (empty($chapter_lecture_hour) && empty($chapter_lecture_minute) && empty($chapter_lecture_second)) {
                $chapter_lecture_duration = $previous_duration;
            } else {
                $chapter_lecture_duration = ($chapter_lecture_hour * 3600) + ($chapter_lecture_minute * 60) + $chapter_lecture_second;
            }

            // update lecture
            $update = "UPDATE hc_chapter_lecture SET name = '$chapter_lecture_name', tags = '$chapter_lecture_tags', duration = '$chapter_lecture_duration', server = '$chapter_lecture_server', video = '$chapter_lecture_video', is_free = '$video_free', status = '$chapter_lecture_status' WHERE id = $chapter_lecture_id";
            $sql_update = mysqli_query($db, $update);
            if ($sql_update) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../chapter-lecture/?chapter=<?php echo $lecture_chapter; ?>';
                </script>
                <?php 
            }
        }
    }?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Chapter Lectures</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== ADD Chapter CATEGORY ==========-->
            <div class="add_category">
                <h5 class="box_title">Edit Chapter Lectures</h5>

                <?php echo $alert; ?>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div class="grid_col_2">
                        <label for="">Title*</label>
                        <input type="text" id="" name="name" placeholder="Title" value="<?php echo $lecture_name; ?>">
                    </div>

                    <div>
                        <label for="">Status</label>
                        <select id="" name="status">
                            <option value="">Choose Status</option>
                            <option value="1" <?php if ($lecture_status == 1) {echo "selected";} ?>>Published</option>
                            <option value="0" <?php if ($lecture_status == 0) {echo "selected";} ?>>Draft</option>
                        </select>
                    </div>

                    <div>
                        <label for="">Tags*</label>
                        <textarea id="" name="tags" placeholder=", is the separator" rows="4"><?php echo $lecture_tags; ?></textarea>
                    </div>

                    <div>
                        <label for="">Duration*</label>
                        <div class="ep_grid">
                            <div>
                                <input type="text" id="" name="hour" placeholder="Hours">
                            </div>
                            <div>
                                <input type="text" id="" name="minute" placeholder="Minutes">
                            </div>
                            <div>
                                <input type="text" id="" name="second" placeholder="Seconds">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="">Video*</label>
                        <textarea id="" name="video" placeholder="Video Link" rows="4"><?php echo $lecture_video; ?></textarea>
                    </div>

                    <div>
                        <label for="">Server*</label>
                        <select id="" name="server">
                            <option value="">Choose Server</option>
                            <option value="vimeo" <?php if ($lecture_server == 'vimeo') {echo "selected";} ?>>Vimeo</option>
                            <option value="youtube" <?php if ($lecture_server == 'youtube') {echo "selected";} ?>>Youtube</option>
                        </select>
                    </div>

                    <div class="grid_col_3 mb_75">
                        <label for="free">Free?</label>
                        <label for="free" class="checkbox_label">
                            <input type="checkbox" class="checkbox" name="free" id="free" <?php if ($lecture_free == 1) {echo "checked";}?>>
                            <span class="checked"></span>
                            Yes
                        </label>
                    </div>

                    <!-- get duration -->
                    <input type="hidden" name="previous_duration" value="<?php echo $lecture_duration; ?>">

                    <!-- get id -->
                    <input type="hidden" name="id" value="<?php echo $lecture_id; ?>">

                    <button type="submit" name="edit">Edit Lecture</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php } else { ?><script type="text/javascript">window.location.href = '../chapter/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>