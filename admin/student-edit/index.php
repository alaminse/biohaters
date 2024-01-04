<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['student'])) {
    $edit_id = $_GET['student'];

    if (empty($edit_id)) {
        ?>
        <script type="text/javascript">
            window.location.href = '../student/';
        </script>
        <?php 
    }

    $select = "SELECT * FROM hc_student WHERE id = '$edit_id'";
    $sql = mysqli_query($db, $select);
    $row = mysqli_fetch_assoc($sql);
    $student_id                 = $row['id'];
    $student_name               = $row['name'];
    $student_email              = $row['email'];
    $student_phone              = $row['phone'];
    $student_gender             = $row['gender'];
    $student_birth_date         = $row['birth_date'];
    $student_father_name        = $row['father_name'];
    $student_father_phone       = $row['father_phone'];
    $student_father_occupation  = $row['father_occupation'];
    $student_mother_name        = $row['mother_name'];
    $student_mother_phone       = $row['mother_phone'];
    $student_mother_occupation  = $row['mother_occupation'];
    $student_present_address    = $row['present_address'];
    $student_permanent_address  = $row['permanent_address'];
    $student_school             = $row['school'];
    $student_ssc_year           = $row['ssc_year'];
    $student_ssc_result         = $row['ssc_result'];
    $student_ssc_board          = $row['ssc_board'];
    $student_ssc_roll           = $row['ssc_roll'];
    $student_college            = $row['college'];
    $student_hsc_year           = $row['hsc_year'];
    $student_hsc_result         = $row['hsc_result'];
    $student_hsc_board          = $row['hsc_board'];
    $student_hsc_roll           = $row['hsc_roll'];
    $student_registration       = $row['registration'];
    $student_profile            = $row['profile'];

    // EDIT STUDENT
    $alert = '';
    if (isset($_POST['edit'])) {
        $student_id         = mysqli_escape_string($db, $_POST['id']);
        $name               = mysqli_escape_string($db, $_POST['name']);
        $email              = mysqli_escape_string($db, $_POST['email']);
        $phone              = mysqli_escape_string($db, $_POST['phone']);
        $father_name        = mysqli_escape_string($db, $_POST['father_name']);
        $father_phone       = mysqli_escape_string($db, $_POST['father_phone']);
        $mother_name        = mysqli_escape_string($db, $_POST['mother_name']);
        $mother_phone       = mysqli_escape_string($db, $_POST['mother_phone']);
        $school             = mysqli_escape_string($db, $_POST['school']);
        $gender             = $_POST['gender'];
        $ssc_year           = $_POST['ssc_year'];
        $ssc_board          = $_POST['ssc_board'];
        $profile_pic        = $_FILES['profile']['name'];
        $profile_pic_tmp    = $_FILES['profile']['tmp_name'];

        // cover unlink path
        $previous_profile_photo = mysqli_escape_string($db, $_POST['student_profile']);

        if (!empty($profile_pic)) {
            $array_img = explode('.', $profile_pic);
            $extension_img = end($array_img);

            if ($extension_img == 'jpg' || $extension_img == 'jpeg') {
                $random_prev = rand(0, 999999);
                $random = rand(0, 999999);
                $random_next = rand(0, 999999);
                $time = date('Ymdhis');

                $final_img = "../assets/student_profile/hc_".$random_prev."_".$random."_".$random_next."_".$time."_".$profile_pic;

                move_uploaded_file($profile_pic_tmp, $final_img);

                if (!empty($previous_profile_photo)) {
                    // delete previous cover photo
                    unlink($previous_profile_photo);
                }
            } else {
                $alert = '<p class="danger mb_75">Give JPG or JPEG file</p>';
                $final_img = $previous_profile_photo;
            }
        } else {
            $final_img = $previous_profile_photo;
        }

        // update student
        $update = "UPDATE hc_student SET name = '$name', email = '$email', phone = '$phone', gender = '$gender', father_name = '$father_name', father_phone = '$father_phone', mother_name = '$mother_name', mother_phone = '$mother_phone', school = '$school', ssc_year = '$ssc_year', ssc_board = '$ssc_board', profile = '$final_img' WHERE id = '$student_id'";
        $sql_update = mysqli_query($db, $update);
        if ($sql_update) {
            // check otp row 
            $select_otp_row = "SELECT * FROM hc_login_otp WHERE student_id = '$edit_id'";
            $sql_otp_row = mysqli_query($db, $select_otp_row);
            $num_otp_row = mysqli_num_rows($sql_otp_row);
            if ($num_otp_row > 0) {
                $update_otp_row = "UPDATE hc_login_otp SET email = '$email', phone = '$phone', otp_count = 1 WHERE student_id = '$edit_id'";
                mysqli_query($db, $update_otp_row);
            }?>
            <script type="text/javascript">
                window.location.href = '../student/';
            </script>
            <?php 
        }
    }?>

    <main>
        <!-- page title -->
        <div class="ep_section">
            <div class="ep_container">
                <h4 class="welcome_admin_title">Student</h4>
            </div>
        </div>

        <!-- welcome message -->
        <div class="ep_section">
            <div class="ep_container">
                <?php echo $alert; ?>
                <!--========== EDIT STUDENT ==========-->
                <div class="add_category">
                    <h5 class="box_title">Update Student</h5>

                    <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                        <div>
                            <label for="">Name*</label>
                            <input type="text" id="" name="name" placeholder="Name" value="<?= $student_name ?>">
                        </div>

                        <div>
                            <label for="">Email*</label>
                            <input type="email" id="" name="email" placeholder="Email Address" value="<?= $student_email ?>">
                        </div>

                        <div>
                            <label for="">Phone*</label>
                            <input type="text" id="" name="phone" placeholder="Phone Number" value="<?= $student_phone ?>">
                        </div>

                        <div>
                            <label for="">Gender*</label>
                            <select id="" name="gender">
                                <option value="">Gender</option>
                                <option value="1" <?php if ($student_gender == '1') {echo "selected";} ?>>Male</option>
                                <option value="0" <?php if ($student_gender == '0') {echo "selected";} ?>>Female</option>
                                <option value="2" <?php if ($student_gender == '2') {echo "selected";} ?>>Others</option>
                            </select>
                        </div>

                        <div>
                            <label for="">Father's Name*</label>
                            <input type="text" id="" name="father_name" placeholder="Father's Name" value="<?= $student_father_name ?>">
                        </div>

                        <div>
                            <label for="">Father's Phone*</label>
                            <input type="text" id="" name="father_phone" placeholder="Father's Phone" value="<?= $student_father_phone ?>">
                        </div>

                        <div>
                            <label for="">Mother's Name*</label>
                            <input type="text" id="" name="mother_name" placeholder="Mother's Name" value="<?= $student_mother_name ?>">
                        </div>

                        <div>
                            <label for="">Mother's Phone*</label>
                            <input type="text" id="" name="mother_phone" placeholder="Mother's Phone" value="<?= $student_mother_phone ?>">
                        </div>

                        <div>
                            <label for="">School*</label>
                            <input type="text" id="" name="school" placeholder="School Name" value="<?= $student_school ?>">
                        </div>

                        <div>
                            <label for="">SSC Session Year*</label>
                            <select id="" name="ssc_year">
                                <option value="0">SSC Year</option>
                                <option value="2025" <?php if ($student_ssc_year == '2025') {echo "selected";} ?>>2025</option>
                                <option value="2024" <?php if ($student_ssc_year == '2024') {echo "selected";} ?>>2024</option>
                                <option value="2023" <?php if ($student_ssc_year == '2023') {echo "selected";} ?>>2023</option>
                                <option value="2022" <?php if ($student_ssc_year == '2022') {echo "selected";} ?>>2022</option>
                                <option value="2021" <?php if ($student_ssc_year == '2021') {echo "selected";} ?>>2021</option>
                                <option value="2020" <?php if ($student_ssc_year == '2020') {echo "selected";} ?>>2020</option>
                            </select>
                        </div>

                        <div>
                            <label for="">SSC Board *</label>
                            <select name="ssc_board" id="">
                                <option value="">Choose SSC Board</option>
                                <option value="Barisal" <?php if ($student_ssc_board == 'Barisal') { echo 'selected'; } ?>>Barisal</option>
                                <option value="Chattogram" <?php if ($student_ssc_board == 'Chattogram') { echo 'selected'; } ?>>Chattogram</option>
                                <option value="Comilla" <?php if ($student_ssc_board == 'Comilla') { echo 'selected'; } ?>>Comilla</option>
                                <option value="Dhaka" <?php if ($student_ssc_board == 'Dhaka') { echo 'selected'; } ?>>Dhaka</option>
                                <option value="Dinajpur" <?php if ($student_ssc_board == 'Dinajpur') { echo 'selected'; } ?>>Dinajpur</option>
                                <option value="Jessore" <?php if ($student_ssc_board == 'Jessore') { echo 'selected'; } ?>>Jessore</option>
                                <option value="Mymensingh" <?php if ($student_ssc_board == 'Mymensingh') { echo 'selected'; } ?>>Mymensingh</option>
                                <option value="Rajshahi" <?php if ($student_ssc_board == 'Rajshahi') { echo 'selected'; } ?>>Rajshahi</option>
                                <option value="Sylhet" <?php if ($student_ssc_board == 'Sylhet') { echo 'selected'; } ?>>Sylhet</option>
                                <option value="Madrasah" <?php if ($student_ssc_board == 'Madrasah') { echo 'selected'; } ?>>Madrasah</option>
                                <option value="Technical" <?php if ($student_ssc_board == 'Technical') { echo 'selected'; } ?>>Technical</option>
                            </select>
                        </div>

                        <div>
                            <label for="">Profile Photo</label>
                            <input type="file" id="" name="profile" class="input_sm">
                        </div>

                        <!-- get previous cover photo to unlink easily -->
                        <input type="hidden" name="student_profile" value="<?php echo $student_profile; ?>">
                        
                        <!-- get id -->
                        <input type="hidden" name="id" value="<?php echo $student_id; ?>">

                        <button type="submit" name="edit">Update Student</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php 
} else {
    ?>
    <script type="text/javascript">
        window.location.href = '../student/';
    </script>
    <?php 
}?>

<!--========== CKEDITOR JS =============-->
<script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>

<script>
/*========== POST DESCRIPTION CKEDITOR =============*/
CKEDITOR.replace( 'des' );
</script>

<?php include('../assets/includes/footer.php'); ?>