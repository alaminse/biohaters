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
    $lecture_sheet  = $row_lecture['document'];

    // add lecture sheet
    $alert = '';
    if (isset($_POST['add'])) {
        $doc        = mysqli_escape_string($db, $_POST['doc']);
        $lecture_id = mysqli_escape_string($db, $_POST['id']);

        $document_date = date('Y-m-d H:i:s', time());

        // update lecture
        $update = "UPDATE hc_course_lecture SET document = '$doc', document_date = '$document_date' WHERE id = $lecture_id";
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
            <h4 class="welcome_admin_title">Course Lecture Sheet</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== ADD COURSE CATEGORY ==========-->
            <div class="add_category">
                <h5 class="box_title"><?= $lecture_name ?> - Lecture Sheet</h5>

                <?php echo $alert; ?>

                <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Lecture Sheet Link*</label>
                        <textarea id="" name="doc" placeholder="Lecture Sheet Link" rows="4"><?= $lecture_sheet ?></textarea>
                    </div>

                    <!-- get id -->
                    <input type="hidden" name="id" value="<?php echo $lecture_id; ?>">

                    <button type="submit" name="add">Add Lecture Sheet</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php } else { ?><script type="text/javascript">window.location.href = '../course/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>