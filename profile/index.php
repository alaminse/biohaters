<?php include('../assets/includes/dashboard_header.php'); ?>

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
                ?>
                <script type="text/javascript">
                    window.location.href = '<?= $base_url ?>profile/';
                </script>
                <?php 
            }
        } else {
            $profile_img_alert = '<p class="danger text_center mt_75">Give JPG or PNG or JPEG file</p>';
        }
    }
}?>

<!--=========== PAGE TITLE SECTION ===========-->
<section class="page_section hc_section">
    <div class="ep_flex hc_container">
        <h3 class="hc_page_title">Profile</h3>

        <a href="<?= $base_url ?>profile-setting/" class="button">Edit Profile</a>
    </div>
</section>

<!--=========== PROFILE VIEW SECTION ===========-->
<section class="hc_section">
    <div class="profile_view_container hc_container ep_grid">
        <!--===== PROFILE VIEW CARD =====-->
        <div>
            <!--===== PROFILE VIEW IMAGE =====-->
            <div class="profile_view_img">
                <?php if (empty($student_profile)) {
                    ?><img src="../assets/img/student.png" alt="" class="hc_dropdown_btn"><?php 
                } else {
                    ?><img src="<?= $student_profile_img ?>" alt="" class="hc_dropdown_btn"><?php 
                }?>
            </div>

            <!--===== PROFILE VIEW PERSONAL DATA =====-->
            <div class="profile_view_card_data">
                <div class="profile_view_card_data_title"><?= $student_name ?></div>

                <div class="profile_view_card_data_subtitle">Roll: <?= $student_roll ?></div>

                <?php if ($student_status == '1') {
                    echo "<div class='success w_max'><i class='bx bxs-badge-check'></i>Verified</div>";
                }?>

                <div class="ep_flex mb_75">
                    <div class="profile_view_card_data_properties">Email</div>
                    <div class="profile_view_card_data_value"><?= $student_email ?></div>
                </div>

                <div class="ep_flex mb_75">
                    <div class="profile_view_card_data_properties">Phone</div>
                    <div class="profile_view_card_data_value"><?= $student_phone ?></div>
                </div>

                <div class="ep_flex mb_75">
                    <div class="profile_view_card_data_properties">Gender</div>
                    <div class="profile_view_card_data_value"><?= $student_gen_text ?></div>
                </div>

                <div class="ep_flex mb_75">
                    <div class="profile_view_card_data_properties">Birth Day</div>
                    <div class="profile_view_card_data_value"><?= $student_birth_date_text ?></div>
                </div>

                <div class="ep_flex mb_75">
                    <div class="profile_view_card_data_properties">Joined Date</div>
                    <div class="profile_view_card_data_value"><?= $student_join_date_text ?></div>
                </div>
            </div>

            <!--===== PROFILE VIEW IMAGE EDIT FORM =====-->
            <form action="" method="post" class="ep_grid ep_center" enctype="multipart/form-data">
                <h4 class="text_center">Profile Image Edit</h4>

                <div class="input_grp">
                    <input type="file" name="profile_img" id="" class="input_sm trasparent">
                </div>

                <div class="input_grp">
                    <button type="submit" name="update_profile_img" class="m_auto btn_sm">Save</button>
                </div>
            </form>

            <?= $profile_img_alert ?>
        </div>

        <!--===== PROFILE VIEW Deatils =====-->
        <div class="profile_view_details">
            <h4 class="form_title">Contact Address</h3>
            <p class="form_subtitle">Your contact address details that shared on your profile</p>

            <div class="profile_view_details_grid mb_1_5">
                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">Present Address</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_present_address)) {
                        echo 'N/A';
                    } else {
                        echo $student_present_address;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">Permanent Address</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_permanent_address)) {
                        echo 'N/A';
                    } else {
                        echo $student_permanent_address;
                    }?></div>
                </div>
            </div>

            <h4 class="form_title">Guardian Information</h3>
            <p class="form_subtitle">Your guardian information that shared on your profile</p>

            <div class="profile_view_details_grid mb_1_5">
                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">Father's Name</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_father_name)) {
                        echo 'N/A';
                    } else {
                        echo $student_father_name;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">Father's Phone No.</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_father_phone)) {
                        echo 'N/A';
                    } else {
                        echo $student_father_phone;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">Father's Occupation</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_father_occupation)) {
                        echo 'N/A';
                    } else {
                        echo $student_father_occupation;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">Mother's Name</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_mother_name)) {
                        echo 'N/A';
                    } else {
                        echo $student_mother_name;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">Mother's Phone No.</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_mother_phone)) {
                        echo 'N/A';
                    } else {
                        echo $student_mother_phone;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">Mother's Occupation</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_mother_occupation)) {
                        echo 'N/A';
                    } else {
                        echo $student_mother_occupation;
                    }?></div>
                </div>
            </div>

            <h4 class="form_title">Academic Information</h3>
            <p class="form_subtitle">Your academic information that shared on your profile</p>

            <div class="profile_view_details_grid">
                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">School Name</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_school)) {
                        echo 'N/A';
                    } else {
                        echo $student_school;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">SSC Session</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_ssc_year)) {
                        echo 'N/A';
                    } else {
                        echo $student_ssc_year;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">SSC Board</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_ssc_board)) {
                        echo 'N/A';
                    } else {
                        echo $student_ssc_board;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">SSC Roll</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_ssc_roll)) {
                        echo 'N/A';
                    } else {
                        echo $student_ssc_roll;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">SSC Result</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_ssc_result)) {
                        echo 'N/A';
                    } else {
                        echo $student_ssc_result;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">College Name</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_college)) {
                        echo 'N/A';
                    } else {
                        echo $student_college;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">HSC Session</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_hsc_year)) {
                        echo 'N/A';
                    } else {
                        echo $student_hsc_year;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">HSC Board</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_hsc_board)) {
                        echo 'N/A';
                    } else {
                        echo $student_hsc_board;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">HSC Roll</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_hsc_roll)) {
                        echo 'N/A';
                    } else {
                        echo $student_hsc_roll;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">HSC Result</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_hsc_result)) {
                        echo 'N/A';
                    } else {
                        echo $student_hsc_result;
                    }?></div>
                </div>

                <div class="profile_view_details_set mb_75">
                    <div class="profile_view_card_data_properties">Board Registration No.</div>
                    <div class="profile_view_card_data_value"><?php if (empty($student_registration)) {
                        echo 'N/A';
                    } else {
                        echo $student_registration;
                    }?></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('../assets/includes/dashboard_footer.php'); ?>