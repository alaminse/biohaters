<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php if (isset($_GET['chapter'])) { 
    $chapter = $_GET['chapter'];

    // get chapter name
    $select_chapter  = "SELECT * FROM hc_chapter WHERE id = '$chapter' AND is_delete = 0";
    $sql_chapter     = mysqli_query($db, $select_chapter);
    $row_chapter     = mysqli_fetch_assoc($sql_chapter);
    $chapter_name    = $row_chapter['chapter'];

    // add lecture
    $alert = '';
    if (isset($_POST['add'])) {
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

        $created_date = date('Y-m-d H:i:s', time());

        if (empty($chapter_lecture_name) || empty($chapter_lecture_video) || empty($chapter_lecture_server) || ($chapter_lecture_hour == '') || ($chapter_lecture_minute == '') || ($chapter_lecture_second == '')) {
            $alert = "<p class='warning mb_75'>Required Fields.....</p>";
        } else {
            $chapter_lecture_duration = ($chapter_lecture_hour * 3600) + ($chapter_lecture_minute * 60) + $chapter_lecture_second;

            // add lecture
            $add = "INSERT INTO hc_chapter_lecture (chapter, name, tags, duration, server, video, is_free, status, author, created_date) VALUES ('$chapter', '$chapter_lecture_name', '$chapter_lecture_tags', '$chapter_lecture_duration', '$chapter_lecture_server', '$chapter_lecture_video', '$video_free', '$chapter_lecture_status', '$admin_id', '$created_date')";
            $sql_add = mysqli_query($db, $add);
            if ($sql_add) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../chapter-lecture/?chapter=<?php echo $chapter; ?>';
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
                <h5 class="box_title">Add Chapter Lectures</h5>

                <?php echo $alert; ?>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div class="grid_col_2">
                        <label for="">Title*</label>
                        <input type="text" id="" name="name" placeholder="Title">
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
                        <label for="">Tags*</label>
                        <textarea id="" name="tags" placeholder=", is the separator" rows="4"></textarea>
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
                        <textarea id="" name="video" placeholder="Video Link" rows="4"></textarea>
                    </div>

                    <div>
                        <label for="">Server*</label>
                        <select id="" name="server">
                            <option value="">Choose Server</option>
                            <option value="vimeo">Vimeo</option>
                            <option value="youtube">Youtube</option>
                        </select>
                    </div>

                    <div class="grid_col_3 mb_75">
                        <label for="free">Free?</label>
                        <label for="free" class="checkbox_label">
                            <input type="checkbox" class="checkbox" name="free" id="free">
                            <span class="checked"></span>
                            Yes
                        </label>
                    </div>

                    <button type="submit" name="add">Add Lecture</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php } else { ?><script type="text/javascript">window.location.href = '../chapter/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>