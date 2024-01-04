<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 3 || $admin_role == 7 || $admin_role == 8) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php if (isset($_GET['course'])) { 
    $course = $_GET['course'];

    // get course name
    $select_course  = "SELECT * FROM hc_course WHERE id = '$course' AND is_delete = 0";
    $sql_course     = mysqli_query($db, $select_course);
    $row_course     = mysqli_fetch_assoc($sql_course);
    $course_name    = $row_course['name'];

    // add lecture
    $alert = '';
    if (isset($_POST['add'])) {
        $course_lecture_name    = mysqli_escape_string($db, $_POST['name']);
        $course_lecture_tags    = mysqli_escape_string($db, $_POST['tags']);
        $course_lecture_video   = mysqli_escape_string($db, $_POST['video']);
        $course_lecture_hour    = mysqli_escape_string($db, $_POST['hour']);
        $course_lecture_minute  = mysqli_escape_string($db, $_POST['minute']);
        $course_lecture_second  = mysqli_escape_string($db, $_POST['second']);
        $course_lecture_module  = $_POST['module'];
        $course_lecture_status  = $_POST['status'];
        $course_lecture_server  = $_POST['server'];
        
        // scheduled time
        $course_lecture_scheduled  = $_POST['scheduled'];

        if (isset($_POST['free'])) {
            $video_free = 1;
        } else {
            $video_free = 0;
        }
        
        // $created_date = date('Y-m-d H:i:s', time());

        if (empty($course_lecture_name) || empty($course_lecture_video) || empty($course_lecture_module) || empty($course_lecture_server) || empty($course_lecture_scheduled) || ($course_lecture_hour == '') || ($course_lecture_minute == '') || ($course_lecture_second == '')) {
            $alert = "<p class='warning mb_75'>Required Fields.....</p>";
        } else {
            $course_lecture_duration = ($course_lecture_hour * 3600) + ($course_lecture_minute * 60) + $course_lecture_second;

            // add lecture
            $add = "INSERT INTO hc_course_lecture (course, module, name, tags, duration, server, video, is_free, status, author, created_date) VALUES ('$course', '$course_lecture_module', '$course_lecture_name', '$course_lecture_tags', '$course_lecture_duration', '$course_lecture_server', '$course_lecture_video', '$video_free', '$course_lecture_status', '$admin_id', '$course_lecture_scheduled')";
            $sql_add = mysqli_query($db, $add);
            if ($sql_add) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../course-lecture/?course=<?php echo $course; ?>';
                </script>
                <?php 
            }
        }
    }?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Course Lectures</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== ADD COURSE CATEGORY ==========-->
            <div class="add_category">
                <h5 class="box_title">Add Course Lectures - <?= $course_name ?></h5>

                <?php echo $alert; ?>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Title*</label>
                        <input type="text" id="" name="name" placeholder="Title">
                    </div>

                    <div>
                        <label for="">Module*</label>
                        <select id="" name="module">
                            <option value="">Choose Module</option>
                            <?php $select = "SELECT * FROM hc_module WHERE course = '$course' AND is_delete = 0 ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $module_id     = $row['id'];
                                    $module_name   = $row['name'];

                                    echo '<option value="'.$module_id.'">'.$module_name.'</option>';
                                }
                            }?>
                        </select>
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
                    
                    <div>
                        <label for="">Scheduled Time*</label>
                        <input type="datetime-local" id="" name="scheduled">
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
<?php } else { ?><script type="text/javascript">window.location.href = '../course/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>