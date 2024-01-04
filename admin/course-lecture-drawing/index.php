<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php if (isset($_GET['course'])) { 
    $course_id = $_GET['course'];

    // if course id is not valid, redirect to another page
    if ($course_id == '') { ?><script type="text/javascript">window.location.href = '../course/';</script><?php }

    if (isset($_GET['lecture'])) { 
        $lecture_id = $_GET['lecture'];
    
        // if lecture id is not valid, redirect to another page
        if ($lecture_id == '') { ?><script type="text/javascript">window.location.href = '../course/';</script><?php }
    } else {
        ?>
        <script type="text/javascript">window.location.href = '../course/';</script>
        <?php 
    }

    // get course lecture
    $select_lecture = "SELECT * FROM hc_course_lecture WHERE id = '$lecture_id' AND is_delete = 0";
    $sql_lecture    = mysqli_query($db, $select_lecture);
    $row_lecture    = mysqli_fetch_assoc($sql_lecture);
    $lecture_id     = $row_lecture['id'];
    $lecture_name   = $row_lecture['name'];
    $lecture_drawing  = $row_lecture['drawing'];

    // add lecture sheet
    $alert = '';
    if (isset($_POST['add'])) {
        $drawing  = mysqli_escape_string($db, $_POST['drawing']);
        $lecture_id = mysqli_escape_string($db, $_POST['id']);

        // update lecture
        $update = "UPDATE hc_course_lecture SET drawing = '$drawing' WHERE id = $lecture_id";
        $sql_update = mysqli_query($db, $update);
        if ($sql_update) {
            ?>
            <script type="text/javascript">
                window.location.href = '../course-lecture/?course=<?php echo $course_id; ?>';
            </script>
            <?php 
        }
    }?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Course Lecture Drawing</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== ADD COURSE CATEGORY ==========-->
            <div class="add_category">
                <div class="ep_flex">
                    <h5 class="box_title"><?= $lecture_name ?> - Drawing</h5>
                
                    <div class="btn_grp">
                        <a href="../course-lecture-animation/?course=<?php echo $course_id; ?>&lecture=<?php echo $lecture_id; ?>" class="button btn_sm btn_trp">3D Animation</a>
                        <a href="../course-lecture-drawing/?course=<?php echo $course_id; ?>&lecture=<?php echo $lecture_id; ?>" class="button btn_sm btn_trp btn_active">Drawing</a>
                    </div>
                </div>

                <?php echo $alert; ?>

                <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Drawing Link*</label>
                        <textarea id="" name="drawing" placeholder="Drawing Link" rows="3"><?= $lecture_drawing ?></textarea>
                    </div>

                    <!-- get id -->
                    <input type="hidden" name="id" value="<?php echo $lecture_id; ?>">

                    <button type="submit" name="add">Add Drawing</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php } else { ?><script type="text/javascript">window.location.href = '../course/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>