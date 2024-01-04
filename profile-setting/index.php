<?php include('../assets/includes/dashboard_header.php'); ?>

<?php if (($student_gen == '') || empty($student_father_name) || empty($student_father_phone) || empty($student_mother_name) || empty($student_mother_phone) || empty($student_school) || empty($student_ssc_year) || empty($student_ssc_board) || empty($student_profile)) {
    $join_second = strtotime($student_join_date);
    $expired_second = $join_second + (20 * 24 * 60 * 60);
    $alert_second = time();
    $expired_date = date('Y-m-d H:i:s', $expired_second);
    $alert_date = date('Y-m-d H:i:s', time());
    if ($alert_second > $expired_second) {
        ?>
        <section class="profile_alert_section hc_section">
            <div class="hc_container">
                <div class="hc_alert hc_alert_danger">
                    <h4 class="hc_alert_title">Verification Alert!</h4>
                    <h6 class="hc_alert_message">আপনাকে ওয়েবসাইট সন্দেহভাজন হিসেবে ডিটেক্ট করেছে। আপনার তথ্য ভেরিফাই এর জন্য ফর্ম দেখাচ্ছে এই জন্য। আপনি * মার্ক করা আপনার তথ্যগুলো আপডেট করে নিজেকে ভেরিফাই করুন।</h6>
                </div>
            </div>
        </section>
        <?php 
    }
}?>

<!-- edit profile picture -->
<?php $profile_img_alert = '';
if (isset($_POST['update_profile_img'])) {
    $profile = $_FILES['profile_img']['name'];
    $tmp_profile = $_FILES['profile_img']['tmp_name'];

    if (empty($profile)) {
        $profile_img_alert = '<p class="danger text_center mt_75">Choose a file</p>';
    } else {
        $array_img = explode('.', $profile);
        $extension_img = end($array_img);

        if ($extension_img == 'jpg' || $extension_img == 'jpeg' || $extension_img == 'png') {
            $random = rand(0, 999999);
            $roll = $student_roll;
            $up_date = date('Ymdhis');

            $final_img = "../assets/student_profile/hc_".$random."_".$roll."_".$up_date."_".$profile;

            // upload directory
            $upload_directory = "../admin/assets/student_profile/hc_".$random."_".$roll."_".$up_date."_".$profile;

            move_uploaded_file($tmp_profile, $upload_directory);

            // delete previous picture
            if (!empty($student_profile)) {
                $unlink_directory = "../admin" . substr($student_profile, 2);
                unlink($unlink_directory);
            }

            $update = "UPDATE hc_student SET profile = '$final_img' WHERE id = $student_id";
            $sql = mysqli_query($db, $update);
            if ($sql) {
                $profile_img_alert = '<div class="modal_container payment_modal show-modal" id="update-personal-success">
                                            <div class="modal_content payment_content">
                                                <div class="modal_body">
                                                    <div class="payment_icon_success text_center">
                                                        <i class="bx bx-check"></i>
                                                    </div>
    
                                                    <p class="payment_success_subtitle text_center">Update Successfully!</p>
                                                </div>
    
                                                <div class="">
                                                    <button class="button no_hover btn_sm m_auto" data-close-button>OK</button>
                                                </div>
                                            </div>
                                        </div>';
            }
        } else {
            $profile_img_alert = '<p class="danger text_center mt_75">Give JPG or PNG or JPEG file</p>';
        }
    }
}?>

<?php // update personal info
$alert_personal_info = '';
if (isset($_POST['update_personal_info'])) {
    $name               = mysqli_escape_string($db, $_POST['name']);
    $present_address    = mysqli_escape_string($db, $_POST['present_address']);
    $permanent_address  = mysqli_escape_string($db, $_POST['permanent_address']);
    $birth              = $_POST['birth'];
    $gen                = $_POST['gen'];

    if (empty($name) || empty($gen)) {
        $alert_personal_info = "<p class='danger mb_75'>নাম এবং লিঙ্গ পূরণ করা বাধ্যতামূলক.....</p>";
    } else {
        if ($gen == '1') {
            $gen = 1;
        } elseif ($gen == '2') {
            $gen = 0;
        } elseif ($gen == '3') {
            $gen = 3;
        }
        
        if ($birth == '') {
            $update = "UPDATE hc_student SET name = '$name', gender = '$gen', present_address = '$present_address', permanent_address = '$permanent_address' WHERE id = '$student_id'";
        } else {
            $update = "UPDATE hc_student SET name = '$name', gender = '$gen', birth_date = '$birth', present_address = '$present_address', permanent_address = '$permanent_address' WHERE id = '$student_id'";
        }
        
        $sql_update = mysqli_query($db, $update);
        if ($sql_update) {
            $alert_personal_info = '<div class="modal_container payment_modal show-modal" id="update-personal-success">
                                        <div class="modal_content payment_content">
                                            <div class="modal_body">
                                                <div class="payment_icon_success text_center">
                                                    <i class="bx bx-check"></i>
                                                </div>

                                                <p class="payment_success_subtitle text_center">Update Successfully!</p>
                                            </div>

                                            <div class="">
                                                <button class="button no_hover btn_sm m_auto" data-close-button>OK</button>
                                            </div>
                                        </div>
                                    </div>';
        }
    }
}

// update guardian info
$alert_guardian_info = '';
if (isset($_POST['update_parent_info'])) {
    $father_name        = mysqli_escape_string($db, $_POST['father_name']);
    $father_phone       = mysqli_escape_string($db, $_POST['father_phone']);
    $father_occupation  = mysqli_escape_string($db, $_POST['father_occupation']);
    $mother_name        = mysqli_escape_string($db, $_POST['mother_name']);
    $mother_phone       = mysqli_escape_string($db, $_POST['mother_phone']);
    $mother_occupation  = mysqli_escape_string($db, $_POST['mother_occupation']);

    if (empty($father_name) || empty($father_phone) || empty($mother_name) || empty($mother_phone)) {
        $alert_guardian_info = "<p class='danger mb_75'>বাবার নাম এবং ফোন নম্বর, মায়ের নাম এবং ফোন নম্বর পূরণ করা বাধ্যতামূলক.....</p>";
    } else {
        $father_phone = str_replace('+880', '0', $father_phone);
        $mother_phone = str_replace('+880', '0', $mother_phone);
        
        if ((!preg_match("/^([0-9]{11})$/", $father_phone)) || (!preg_match("/^([0-9]{11})$/", $mother_phone))) {
            ?>
            <div class="modal_container payment_modal show-modal" id="">
                <div class="modal_content payment_content">
                    <div class="modal_body">
                        <div class="payment_icon_error text_center">
                            <i class='bx bxs-error'></i>
                        </div>
    
                        <p class="payment_success_subtitle text_center">Phone Number is Invalid!</p>
                    </div>
    
                    <div class="">
                        <a href="https://biohaters.com/profile-setting/" class="button no_hover btn_sm m_auto">Go Back</a>
                    </div>
                </div>
            </div>
            <?php 
        } else {
            if (($student_phone == $father_phone) && ($student_phone == $mother_phone)) {
                ?>
                <div class="modal_container payment_modal show-modal" id="">
                    <div class="modal_content payment_content">
                        <div class="modal_body">
                            <div class="payment_icon_error text_center">
                                <i class='bx bxs-error'></i>
                            </div>
        
                            <p class="payment_success_subtitle text_center">Your phone, Your gaurdian's phones will not same</p>
                        </div>
        
                        <div class="">
                            <a href="https://biohaters.com/profile-setting/" class="button no_hover btn_sm m_auto">Go Back</a>
                        </div>
                    </div>
                </div>
                <?php 
            } else {
                $update = "UPDATE hc_student SET father_name = '$father_name', father_phone = '$father_phone', father_occupation = '$father_occupation', mother_name = '$mother_name', mother_phone = '$mother_phone', mother_occupation = '$mother_occupation' WHERE id = '$student_id'";
                $sql_update = mysqli_query($db, $update);
                if ($sql_update) {
                    $alert_guardian_info = '<div class="modal_container payment_modal show-modal" id="update-personal-success">
                                                <div class="modal_content payment_content">
                                                    <div class="modal_body">
                                                        <div class="payment_icon_success text_center">
                                                            <i class="bx bx-check"></i>
                                                        </div>
        
                                                        <p class="payment_success_subtitle text_center">Update Successfully!</p>
                                                    </div>
        
                                                    <div class="">
                                                        <button class="button no_hover btn_sm m_auto" data-close-button>OK</button>
                                                    </div>
                                                </div>
                                            </div>';
                }
            }
        }
    }
}

// update academic info
$alert_academic_info = '';
if (isset($_POST['update_academic_info'])) {
    $school         = mysqli_escape_string($db, $_POST['school']);
    $ssc_roll       = mysqli_escape_string($db, $_POST['ssc_roll']);
    $ssc_result     = mysqli_escape_string($db, $_POST['ssc_result']);
    $college        = mysqli_escape_string($db, $_POST['college']);
    $hsc_roll       = mysqli_escape_string($db, $_POST['hsc_roll']);
    $hsc_result     = mysqli_escape_string($db, $_POST['hsc_result']);
    $registration   = mysqli_escape_string($db, $_POST['registration']);
    $ssc_year       = $_POST['ssc_year'];
    $ssc_board      = $_POST['ssc_board'];
    $hsc_year       = $_POST['hsc_year'];
    $hsc_board      = $_POST['hsc_board'];

    if (empty($school) || empty($ssc_year) || empty($ssc_board)) {
        $alert_academic_info = "<p class='danger mb_75'>স্কুলের নাম, এস.এস.সি সাল এবং এস.এস.সি বোর্ড পূরণ করা বাধ্যতামূলক.....</p>";
    } else {
        $update = "UPDATE hc_student SET school = '$school', ssc_year = '$ssc_year', ssc_result = '$ssc_result', ssc_board = '$ssc_board', ssc_roll = '$ssc_roll', college = '$college', hsc_year = '$hsc_year', hsc_result = '$hsc_result', hsc_board = '$hsc_board', hsc_roll = '$hsc_roll', registration = '$registration' WHERE id = '$student_id'";
        $sql_update = mysqli_query($db, $update);
        if ($sql_update) {
            $alert_academic_info = '<div class="modal_container payment_modal show-modal" id="update-personal-success">
                                        <div class="modal_content payment_content">
                                            <div class="modal_body">
                                                <div class="payment_icon_success text_center">
                                                    <i class="bx bx-check"></i>
                                                </div>

                                                <p class="payment_success_subtitle text_center">Update Successfully!</p>
                                            </div>

                                            <div class="">
                                                <button class="button no_hover btn_sm m_auto" data-close-button>OK</button>
                                            </div>
                                        </div>
                                    </div>';
        }
    }
}?>

<!--=========== PAGE TITLE SECTION ===========-->
<section class="page_section hc_section">
    <div class="ep_flex hc_container">
        <h3 class="hc_page_title">Profile</h3>

        <a href="<?= $base_url ?>profile/" class="button">View Profile</a>
    </div>
</section>

<!--=========== PROFILE FORM SECTION ===========-->
<section class="hc_section">
    <div class="hc_container">
        <h4 class="form_title">Profile Picture</h4>
        <p class="form_subtitle">Add your picture on your profile</p>

        <!--===== PROFILE VIEW IMAGE EDIT FORM =====-->
        <form action="" method="post" class="ep_grid ep_center" enctype="multipart/form-data">
            <h4 class="text_center">Profile Image*</h4>

            <div class="input_grp">
                <input type="file" name="profile_img" id="" class="input_sm trasparent">
            </div>

            <div class="input_grp">
                <button type="submit" name="update_profile_img" class="m_auto btn_sm">Save</button>
            </div>
        </form>

        <?= $profile_img_alert ?>
    </div>
</section>

<!--=========== PROFILE FORM SECTION ===========-->
<section class="hc_section">
    <div class="hc_container">
        <h4 class="form_title">Personal Information</h4>
        <p class="form_subtitle">Add information about yourself to share on your profile</p>

        <?= $alert_personal_info ?>

        <form action="" method="post" class="double_col_form">
            <div class="profile_setting_input">
                <label for="">Name *</label>
                <input type="text" id="" name="name" placeholder="Your Certificate Name" value="<?= $student_name ?>">
            </div>

            <div>
                <label for="">Gender *</label>
                <select name="gen" id="">
                    <option value="">Choose Gender</option>
                    <option value="1" <?php if ($student_gen == '1') { echo 'selected'; } ?>>Male</option>
                    <option value="2" <?php if ($student_gen == '0') { echo 'selected'; } ?>>Female</option>
                    <option value="3" <?php if ($student_gen == '2') { echo 'selected'; } ?>>Others</option>
                </select>
            </div>

            <div>
                <label for="">Date of Birth</label>
                <input type="date" id="" name="birth" value="<?= $student_birth_date ?>">
            </div>

            <div>
                <label for="">Present Address</label>
                <textarea name="present_address" id="" rows="5" placeholder="Example: Address, Police station name, District"><?= $student_present_address ?></textarea>
            </div>

            <div>
                <label for="">Permanent Address</label>
                <textarea name="permanent_address" id="" rows="5" placeholder="Example: Village Name, Post office name, Police station name, District"><?= $student_permanent_address ?></textarea>
            </div>

            <button type="submit" name="update_personal_info" class="profile_setting_button">Update Personal Info</button>
        </form>
    </div>
</section>

<!--=========== PROFILE FORM SECTION ===========-->
<section class="hc_section">
    <div class="hc_container">
        <h4 class="form_title">Guardian Information</h4>
        <p class="form_subtitle">Add information about your Guardian to share on your profile</p>

        <?= $alert_guardian_info ?>

        <form action="" method="post" class="double_col_form">
            <?php if (empty($student_father_name) || empty($student_father_phone)) {
                ?>
                <div>
                    <label for="">Father's Name *</label>
                    <input type="text" id="" name="father_name" placeholder="Father's Name">
                </div>

                <div>
                    <label for="">Father's Phone Number *</label>
                    <input type="text" id="" name="father_phone" placeholder="Father's Phone Number">
                </div>
                <?php 
            } else {
                ?>
                <input type="hidden" id="" name="father_name" placeholder="Father's Name" value="<?= $student_father_name ?>">
                <input type="hidden" id="" name="father_phone" minlength="11" maxlength="11" placeholder="Father's Phone Number" value="<?= $student_father_phone ?>">
                <?php 
            }?>

            <div>
                <label for="">Father's Occupation</label>
                <input type="text" id="" name="father_occupation" placeholder="Father's Occupation" value="<?= $student_father_occupation ?>">
            </div>

            <?php if (empty($student_mother_name) || empty($student_mother_phone)) {
                ?>
                <div>
                    <label for="">Mother's Name *</label>
                    <input type="text" id="" name="mother_name" placeholder="Mother's Name">
                </div>

                <div>
                    <label for="">Mother's Phone Number *</label>
                    <input type="text" id="" name="mother_phone" minlength="11" maxlength="11" placeholder="Mother's Phone Number">
                </div>
                <?php 
            } else {
                ?>
                <input type="hidden" id="" name="mother_name" placeholder="Mother's Name" value="<?= $student_mother_name ?>">
                <input type="hidden" id="" name="mother_phone" placeholder="Mother's Phone Number" value="<?= $student_mother_phone ?>">
                <?php 
            }?>

            <div>
                <label for="">Mother's Occupation</label>
                <input type="text" id="" name="mother_occupation" placeholder="Mother's Occupation" value="<?= $student_mother_occupation ?>">
            </div>

            <button type="submit" name="update_parent_info" class="profile_setting_button">Update Guardian Info</button>
        </form>
    </div>
</section>

<!--=========== PROFILE FORM SECTION ===========-->
<section class="hc_section">
    <div class="hc_container">
        <h4 class="form_title">Academic Information</h4>
        <p class="form_subtitle">Add information about your Academic to share on your profile</p>

        <?= $alert_academic_info ?>

        <form action="" method="post" class="double_col_form">
            <div>
                <label for="">School Institute *</label>
                <input type="text" id="" name="school" placeholder="School Institute Name" value="<?= $student_school ?>">
            </div>

            <div>
                <label for="">SSC Session *</label>
                <select name="ssc_year" id="">
                    <option value="">Choose SSC Session</option>
                    <option value="2024" <?php if ($student_ssc_year == '2024') { echo 'selected'; } ?>>2024</option>
                    <option value="2023" <?php if ($student_ssc_year == '2023') { echo 'selected'; } ?>>2023</option>
                    <option value="2022" <?php if ($student_ssc_year == '2022') { echo 'selected'; } ?>>2022</option>
                    <option value="2021" <?php if ($student_ssc_year == '2021') { echo 'selected'; } ?>>2021</option>
                    <option value="2020" <?php if ($student_ssc_year == '2020') { echo 'selected'; } ?>>2020</option>
                    <option value="2019" <?php if ($student_ssc_year == '2019') { echo 'selected'; } ?>>2019</option>
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
                <label for="">SSC Roll</label>
                <input type="text" id="" name="ssc_roll" placeholder="SSC Roll" value="<?= $student_ssc_roll ?>">
            </div>

            <div>
                <label for="">SSC GPA</label>
                <input type="text" id="" name="ssc_result" placeholder="SSC GPA Example: 4.65" value="<?= $student_ssc_result ?>">
            </div>

            <div></div>

            <div>
                <label for="">HSC College</label>
                <input type="text" id="" name="college" placeholder="HSC College Name" value="<?= $student_college ?>">
            </div>

            <div>
                <label for="">HSC Session</label>
                <select name="hsc_year" id="">
                    <option value="">Choose HSC Session</option>
                    <option value="2026" <?php if ($student_hsc_year == '2026') { echo 'selected'; } ?>>2026</option>
                    <option value="2025" <?php if ($student_hsc_year == '2025') { echo 'selected'; } ?>>2025</option>
                    <option value="2024" <?php if ($student_hsc_year == '2024') { echo 'selected'; } ?>>2024</option>
                    <option value="2023" <?php if ($student_hsc_year == '2023') { echo 'selected'; } ?>>2023</option>
                    <option value="2022" <?php if ($student_hsc_year == '2022') { echo 'selected'; } ?>>2022</option>
                    <option value="2021" <?php if ($student_hsc_year == '2021') { echo 'selected'; } ?>>2021</option>
                </select>
            </div>

            <div>
                <label for="">HSC Board</label>
                <select name="hsc_board" id="">
                    <option value="">Choose HSC Board</option>
                    <option value="Barisal" <?php if ($student_hsc_board == 'Barisal') { echo 'selected'; } ?>>Barisal</option>
                    <option value="Chattogram" <?php if ($student_hsc_board == 'Chattogram') { echo 'selected'; } ?>>Chattogram</option>
                    <option value="Comilla" <?php if ($student_hsc_board == 'Comilla') { echo 'selected'; } ?>>Comilla</option>
                    <option value="Dhaka" <?php if ($student_hsc_board == 'Dhaka') { echo 'selected'; } ?>>Dhaka</option>
                    <option value="Dinajpur" <?php if ($student_hsc_board == 'Dinajpur') { echo 'selected'; } ?>>Dinajpur</option>
                    <option value="Jessore" <?php if ($student_hsc_board == 'Jessore') { echo 'selected'; } ?>>Jessore</option>
                    <option value="Mymensingh" <?php if ($student_hsc_board == 'Mymensingh') { echo 'selected'; } ?>>Mymensingh</option>
                    <option value="Rajshahi" <?php if ($student_hsc_board == 'Rajshahi') { echo 'selected'; } ?>>Rajshahi</option>
                    <option value="Sylhet" <?php if ($student_hsc_board == 'Sylhet') { echo 'selected'; } ?>>Sylhet</option>
                    <option value="Madrasah" <?php if ($student_hsc_board == 'Madrasah') { echo 'selected'; } ?>>Madrasah</option>
                    <option value="Technical" <?php if ($student_hsc_board == 'Technical') { echo 'selected'; } ?>>Technical</option>
                </select>
            </div>

            <div>
                <label for="">HSC Roll</label>
                <input type="text" id="" name="hsc_roll" placeholder="HSC Roll" value="<?= $student_hsc_roll ?>">
            </div>

            <div>
                <label for="">HSC GPA</label>
                <input type="text" id="" name="hsc_result" placeholder="HSC GPA Example: 4.00" value="<?= $student_hsc_result ?>">
            </div>

            <div></div>

            <div>
                <label for="">Board Registration No.</label>
                <input type="text" id="" name="registration" placeholder="Board Registration No." value="<?= $student_registration ?>">
            </div>

            <button type="submit" name="update_academic_info" class="profile_setting_button">Update Academic Info</button>
        </form>
    </div>
</section>

<?php include('../assets/includes/dashboard_footer.php'); ?>