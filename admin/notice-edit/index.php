<?php include('../assets/includes/header.php'); ?>

<?php // EDIT COURSE CATEGORY
$alert = '';
if (isset($_POST['edit'])) {
    $notice_id          = mysqli_escape_string($db, $_POST['id']);
    $name               = mysqli_escape_string($db, $_POST['name']);
    $des                = mysqli_escape_string($db, $_POST['des']);
    $status             = $_POST['status'];
    $for_whom           = $_POST['for_whom'];
    $attachment         = $_FILES['attachment']['name'];
    $attachment_tmp     = $_FILES['attachment']['tmp_name'];
    $attachment_size    = $_FILES['attachment']['size'];

    // cover unlink path
    $notice_attachment = mysqli_escape_string($db, $_POST['notice_attachment']);
    
    $created_date = date('Y-m-d H:i:s', time());

    if (empty($name) || empty($des)) {
        $alert = "<p class='warning mb_75'>Required Fields.....</p>";
    } else {
        // cover photo selection logic
        if (!empty($attachment)) {
            if ($attachment_size <= 20000000) {
                $random_prev = rand(0, 999999);
                $random = rand(0, 999999);
                $random_next = rand(0, 999999);
                $time = date('Ymdhis');

                $final_attachment = "../assets/notice/hc_".$random_prev."_".$random."_".$random_next."_".$time."_".$attachment;

                move_uploaded_file($attachment_tmp, $final_attachment);
                
                if (!empty($notice_attachment)) {
                    // delete previous cover photo
                    unlink($notice_attachment);
                }
            } else {
                $alert = '<p class="danger mb_75">Attachment should be under 20MB</p>';
                $final_attachment = $notice_attachment;
            }
        } else {
            $final_attachment = $notice_attachment;
        }

        // add post
        $update = "UPDATE hc_notice SET name = '$name', description = '$des', for_whom = '$for_whom', attachment = '$final_attachment', status = '$status', created_date = '$created_date' WHERE id = '$notice_id'";
        $sql_update = mysqli_query($db, $update);
        if ($sql_update) {
            ?>
            <script type="text/javascript">
                window.location.href = '../notice/';
            </script>
            <?php 
        }
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Notice</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== EDIT NOTICE ==========-->
            <?php echo $alert; ?>
            <?php if (isset($_GET['notice'])) {
                $edit_id = $_GET['notice'];

                $select = "SELECT * FROM hc_notice WHERE id = '$edit_id'";
                $sql = mysqli_query($db, $select);
                $row = mysqli_fetch_assoc($sql);
                $notice_id              = $row['id'];
                $notice_name            = $row['name'];
                $notice_description     = $row['description'];
                $notice_for_whom        = $row['for_whom'];
                $notice_attachment      = $row['attachment'];
                $notice_status          = $row['status'];
                $notice_author          = $row['author'];
                $notice_created_date    = $row['created_date']; ?>
                <!--========== EDIT NOTICE ==========-->
                <div class="add_category">
                    <h5 class="box_title">Edit Notice</h5>

                    <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                        <div>
                            <label for="">Notice Title*</label>
                            <input type="text" id="" name="name" placeholder="Notice Title" value="<?= $notice_name ?>">
                        </div>

                        <div>
                            <label for="">Status</label>
                            <select id="" name="status">
                                <option value="0">Choose Status</option>
                                <option value="1" <?php if ($notice_status == '1') { echo 'selected'; }?>>Published</option>
                                <option value="0" <?php if ($notice_status == '0') { echo 'selected'; }?>>Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="">For Whom</label>
                            <select id="" name="for_whom">
                                <option value="0" <?php if ($notice_for_whom == '0') { echo 'selected'; }?>>For All</option>
                                <?php $select = "SELECT * FROM hc_course WHERE is_delete = 0 ORDER BY id DESC";
                                $sql = mysqli_query($db, $select);
                                $num = mysqli_num_rows($sql);
                                if ($num > 0) {
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        $course_id     = $row['id'];
                                        $course_name   = $row['name'];
                                        ?>
                                        <option value="<?php echo $course_id; ?>" <?php if ($course_id == $notice_for_whom) { echo 'selected'; }?>><?php echo $course_name; ?></option>
                                        <?php 
                                    }
                                }?>
                            </select>
                        </div>

                        <div>
                            <label for="">Attachment</label>
                            <input type="file" id="" name="attachment" class="input_sm">
                        </div>

                        <div class="grid_col_3">
                            <label for="">Notice Description*</label>
                            <textarea id="" name="des" placeholder="Course Description" rows="4"><?= $notice_description ?></textarea>
                        </div>

                        <!-- get previous attachment to unlink easily -->
                        <input type="hidden" name="notice_attachment" value="<?php echo $notice_attachment; ?>">
                        
                        <!-- get id -->
                        <input type="hidden" name="id" value="<?php echo $notice_id; ?>">

                        <button type="submit" name="edit">Edit Notice</button>
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