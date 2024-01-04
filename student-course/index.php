<?php include('../assets/includes/dashboard_header.php'); ?>

<?php if (($student_gen == '') || empty($student_father_name) || empty($student_father_phone) || empty($student_mother_name) || empty($student_mother_phone) || empty($student_school) || empty($student_ssc_year) || empty($student_ssc_board) || empty($student_profile)) {
    $join_second = strtotime($student_join_date);
    $expired_second = $join_second + (20 * 24 * 60 * 60);
    $alert_second = time();
    $expired_date = date('Y-m-d H:i:s', $expired_second);
    $alert_date = date('Y-m-d H:i:s', time());
    if ($alert_second > $expired_second) {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>profile-setting/';
        </script>
        <?php 
    }
}?>

<!--=========== PAGE TITLE SECTION ===========-->
<section class="page_section hc_section">
    <div class="ep_flex hc_container">
        <h3 class="hc_page_title">My Courses</h3>
    </div>
</section>

<!--=========== PURCHASE HISTORY SECTION ===========-->
<section class="hc_section">
    <div class="course_container hc_container ep_grid">
        <?php if (isset($result['my_courses'])) {
            foreach ($result['my_courses'] as $key => $my_courses) {
                // courses id
                $my_courses_id = $my_courses['item_id'];
    
                // fetch my course
                $select_my_course  = "SELECT * FROM hc_course WHERE id = '$my_courses_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
                $sql_my_course     = mysqli_query($db, $select_my_course);
                $num_my_course     = mysqli_num_rows($sql_my_course);
                if ($num_my_course > 0) {
                    $row_my_course = mysqli_fetch_assoc($sql_my_course);
    
                    // my course variable
                    $my_course_id              = $row_my_course['id'];
                    $my_course_name            = $row_my_course['name'];
                    $my_course_type            = $row_my_course['type'];
                    $my_course_category        = $row_my_course['category'];
                    $my_course_day_schedule    = $row_my_course['day_schedule'];
                    $my_course_time_schedule   = $row_my_course['time_schedule'];
                    $my_course_trailer         = $row_my_course['trailer'];
                    $my_course_status          = $row_my_course['status'];
                    $my_course_tags            = $row_my_course['tags'];
                    $my_course_des             = $row_my_course['description'];
                    $my_course_price           = $row_my_course['price'];
                    $my_course_sale            = $row_my_course['sale_price'];
                    $my_course_duration        = $row_my_course['duration'];
                    $my_course_expired_date    = $row_my_course['expired_date'];
                    $my_course_cover_photo     = $row_my_course['cover_photo'];
                    $my_course_author          = $row_my_course['author'];
                    $my_course_created_date    = $row_my_course['created_date'];
    
                    // connected with course
                    $select_course_lecture = "SELECT COUNT(id) as course_lecture FROM hc_course_lecture WHERE course = '$my_course_id' AND is_delete = 0";
                    $sql_course_lecture = mysqli_query($db, $select_course_lecture);
                    $row_course_lecture = mysqli_fetch_assoc($sql_course_lecture);
                    $course_lecture = $row_course_lecture['course_lecture'];
                    ?>
                    <!-- COURSE CARD -->
                    <div class="course_card">
                        <div class="course_content">
                            <?php $my_course_cover_photo = substr($my_course_cover_photo, 2); ?>
                            <img src="<?= $base_url ?>admin<?= $my_course_cover_photo ?>" alt="">
                        </div>
    
                        <div class="course_data">
                            <h1 class="course_title"><?= $my_course_name ?></h1>
    
                            <div class="ep_flex mb_75">
                                <div class="ep_flex ep_start text_light">
                                    <i class='bx bx-time-five'></i>
                                    <?= $my_course_time_schedule ?>
                                </div>
    
                                <div class="ep_flex ep_start text_light">
                                    <i class='bx bx-message-square-detail'></i>
                                    <?= $course_lecture ?> টি লেসন্স
                                </div>
                            </div>
    
                            <div class="ep_flex ep_end mb_75 mt_75">
                                <?php $select_course_category = "SELECT * FROM hc_course_category WHERE id = '$my_course_category' AND is_delete = 0";
                                $sql_course_category    = mysqli_query($db, $select_course_category);
                                $num_course_category    = mysqli_num_rows($sql_course_category);
                                if ($num_course_category > 0) {
                                    $row_course_category = mysqli_fetch_assoc($sql_course_category);
                                    $course_category_id     = $row_course_category['id'];
                                    $course_category_name   = $row_course_category['name'];
                                    ?>
                                    <div class="success"><?php if ($course_category_name  == 'Medical') {
                                        echo 'মেডিকেল';
                                    } elseif ($course_category_name  == 'Academic') {
                                        echo 'একাডেমিক';
                                    } elseif ($course_category_name  == 'Academic & Medical') {
                                        echo 'মেডিকেল এবং একাডেমিক';
                                    }?></div>
                                    <?php 
                                }?>
                            </div>
    
                            <?php
                            if($my_course_id == 15)
                                { ?>
                                <div class="row">
                                    <a style="width: 45%; margin-left: .7rem;" href="<?= $base_url ?>course-view-lecture/?course=<?= $my_course_id ?>" class="button no_hover mt_75">Continue</a>
                                    <a target="_blank" style="width: 45%; margin-left: 1rem; background-color: #2563EB" href="https://www.facebook.com/groups/1079715346368645/?ref=share_group_link" class="button no_hover mt_75">FB Group</a>
                                </div>
                            <?php } else { ?>
                                <a href="<?= $base_url ?>course-view-lecture/?course=<?= $my_course_id ?>" class="button w_100 no_hover mt_75">Continue</a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php 
                }
            }
        }?>
    </div>
</section>

<?php include('../assets/includes/dashboard_footer.php'); ?>