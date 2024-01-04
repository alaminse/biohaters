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

<?php // check free trial 
$select_trial  = "SELECT * FROM hc_free_trial WHERE student_id = '$student_id' AND is_expired = '0'";
$sql_trial     = mysqli_query($db, $select_trial);
$num_trial     = mysqli_num_rows($sql_trial);
if ($num_trial > 0) {
    $row_trial = mysqli_fetch_assoc($sql_trial);
    $trial_id               = $row_trial['id'];
    $trial_student_id       = $row_trial['student_id'];
    $trial_purchase_date    = $row_trial['purchase_date'];
    $trial_expired_date     = $row_trial['expired_date'];
    $trial_expired          = $row_trial['is_expired'];

    $today = date('Y-m-d H:i:s', time());

    if ($today > $trial_expired_date) {
        $update_expired = "UPDATE hc_free_trial SET is_expired = '1' WHERE id = '$trial_id'";
        $sql_expired = mysqli_query($db, $update_expired);
        if ($sql_expired) {
            ?>
            <script type="text/javascript">
                window.location.href = '<?= $base_url ?>my-trial/';
            </script>
            <?php 
        }
    } else {
        ?>
        <!--=========== PAGE TITLE SECTION ===========-->
        <section class="page_section hc_section">
            <div class="hc_container">
                <h3 class="hc_page_title">30 Days Trial</h3>
            </div>
        </section>

        <!--=========== COURSE LECTURE SECTION ===========-->
        <section class="hc_section">
            <div class="lecture_container hc_container ep_grid">
                <!--====== LECTURE VIDEO ======-->
                <div class="lecture_content text_center">
                    <?php if (isset($_GET['course_lecture'])) {
                        $lecture_id = $_GET['course_lecture'];

                        // fetch lectures
                        $select_get_lecture = "SELECT * FROM hc_course_lecture WHERE id = '$lecture_id' AND is_free = 1 AND status = 1 AND is_delete = 0";
                        $sql_get_lecture = mysqli_query($db, $select_get_lecture);
                        $num_get_lecture = mysqli_num_rows($sql_get_lecture);
                        if ($num_get_lecture > 0) {
                            $row_get_lecture = mysqli_fetch_assoc($sql_get_lecture);
                            $get_lecture_id         = $row_get_lecture['id'];
                            $get_lecture_name       = $row_get_lecture['name'];
                            $get_lecture_tags       = $row_get_lecture['tags'];
                            $get_lecture_server     = $row_get_lecture['server'];
                            $get_lecture_video      = $row_get_lecture['video'];
                            
                            $single_tag = explode(',', $get_lecture_tags);

                            if ($get_lecture_server == 'youtube') {
                                ?>
                                <!--== VIDEO ==-->
                                <div class="mb_75" style="border-radius: .25rem;">
                                    <iframe width="100%" height="315" height="100%" src="https://www.youtube.com/embed/<?= $get_lecture_video ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                </div>
                                <?php 
                            } elseif ($get_lecture_server == 'vimeo') {
                                ?>
                                <!--== VIDEO ==-->
                                <!--<div class="mb_75" style="border-radius: .25rem; padding:56.25% 0 0 0; position:relative;"><iframe src="https://player.vimeo.com/video/<?= $get_lecture_video ?>?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="1025"></iframe>-->
                                <!--</div>-->
                                
                                <div class="mb_75" style="position:relative; padding-top:56.25%;"><iframe src="https://iframe.mediadelivery.net/embed/166709/<?= $get_lecture_video ?>?autoplay=true&loop=false&muted=false&preload=true" loading="lazy" style="border:0;position:absolute;top:0;height:100%;width:100%;" allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" allowfullscreen="true"></iframe></div>
                                <?php 
                            }?>
                            
                            <!--== TAGS ==-->
                            <div class="ep_flex ep_center ep_flex_wrap ep_row_gap">
                                <?php foreach ($single_tag as $lecture_tags) {
                                    ?>
                                    <div class="warning"><?= $lecture_tags ?></div>
                                    <?php 
                                }?>
                            </div>
                            <?php 
                        }
                    } elseif (isset($_GET['chapter_lecture'])) {
                        $lecture_id = $_GET['chapter_lecture'];

                        // fetch lectures
                        $select_get_lecture = "SELECT * FROM hc_chapter_lecture WHERE id = '$lecture_id' AND is_free = 1 AND status = 1 AND is_delete = 0";
                        $sql_get_lecture = mysqli_query($db, $select_get_lecture);
                        $num_get_lecture = mysqli_num_rows($sql_get_lecture);
                        if ($num_get_lecture > 0) {
                            $row_get_lecture = mysqli_fetch_assoc($sql_get_lecture);
                            $get_lecture_id         = $row_get_lecture['id'];
                            $get_lecture_name       = $row_get_lecture['name'];
                            $get_lecture_tags       = $row_get_lecture['tags'];
                            $get_lecture_server     = $row_get_lecture['server'];
                            $get_lecture_video      = $row_get_lecture['video'];
                            
                            $single_tag = explode(',', $get_lecture_tags);

                            if ($get_lecture_server == 'youtube') {
                                ?>
                                <!--== VIDEO ==-->
                                <div class="mb_75" style="border-radius: .25rem;">
                                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/<?= $get_lecture_video ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                </div>
                                <?php 
                            } elseif ($get_lecture_server == 'vimeo') {
                                ?>
                                <!--== VIDEO ==-->
                                <div class="mb_75" style="border-radius: .25rem; padding:56.25% 0 0 0; position:relative;"><iframe src="https://player.vimeo.com/video/<?= $get_lecture_video ?>?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="1025"></iframe>
                                </div>
                                <?php 
                            }?>
                            
                            <!--== TAGS ==-->
                            <div class="ep_flex ep_center ep_flex_wrap ep_row_gap">
                                <?php foreach ($single_tag as $lecture_tags) {
                                    ?>
                                    <div class="warning"><?= $lecture_tags ?></div>
                                    <?php 
                                }?>
                            </div>
                            <?php 
                        }
                    } else {
                        ?>
                        <!--== VIDEO ==-->
                        <img src="../assets/img/my-trial.png" alt="" class="my_trial_img">
                        <?php 
                    }?>
                </div>

                <!--====== LECTURE DATA ======-->
                <div class="lecture_data">
                    <div class="chapter_details_content ep_grid">
                        <?php // fetch free lecture with chapter
                        $select_chapter_lecture = "SELECT * FROM hc_chapter_lecture WHERE is_free = '1' AND is_delete = 0";
                        $sql_chapter_lecture = mysqli_query($db, $select_chapter_lecture);
                        $num_chapter_lecture = mysqli_num_rows($sql_chapter_lecture);
                        $si = 0;
                        while ($row_chapter_lecture = mysqli_fetch_assoc($sql_chapter_lecture)) {
                            $si++;
                            $lecture_id         = $row_chapter_lecture['id'];
                            $lecture_name       = $row_chapter_lecture['name'];
                            $lecture_duration   = $row_chapter_lecture['duration'];
                            $lecture_free       = $row_chapter_lecture['is_free'];
                            ?>
                            <a href="<?= $base_url ?>my-trial/?chapter_lecture=<?= $lecture_id ?>" class="course_details_lecture_active">
                                <div class="ep_flex gap_1_25">
                                    <div class="ep_flex ep_start">
                                        <i class='bx bx-play-circle'></i>
                                        <?= $lecture_name ?>
                                    </div>

                                    <div class="">
                                        <?= gmdate('H:i:s', $lecture_duration) ?>
                                    </div>
                                </div>
                            </a>
                            <?php 
                        }

                        // fetch free lecture with course
                        // $select_course_lecture = "SELECT * FROM hc_course_lecture WHERE is_free = '1' AND is_delete = 0";
                        // $sql_course_lecture = mysqli_query($db, $select_course_lecture);
                        // $num_course_lecture = mysqli_num_rows($sql_course_lecture);
                        // while ($row_course_lecture = mysqli_fetch_assoc($sql_course_lecture)) {
                        //     $si++;
                        //     $lecture_id         = $row_course_lecture['id'];
                        //     $lecture_name       = $row_course_lecture['name'];
                        //     $lecture_duration   = $row_course_lecture['duration'];
                        //     $lecture_free       = $row_course_lecture['is_free'];
                            ?>
                            <!--<a href="<?= $base_url ?>my-trial/?course_lecture=<?= $lecture_id ?>" class="course_details_lecture_active">-->
                            <!--    <div class="ep_flex gap_1_25">-->
                            <!--        <div class="ep_flex ep_start">-->
                            <!--            <i class='bx bx-play-circle'></i>-->
                            <!--            <?= $lecture_name ?>-->
                            <!--        </div>-->

                            <!--        <div class="">-->
                            <!--            <?= gmdate('H:i:s', $lecture_duration) ?>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</a>-->
                            <?php 
                        // }?>
                    </div>
                </div>
            </div>
        </section>
        <?php 
    }
} else {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>dashboard/';
    </script>
    <?php 
}?>

<!--=========== VIMEO JS ===========-->
<!--<script src="https://player.vimeo.com/api/player.js"></script>-->

<?php include('../assets/includes/dashboard_footer.php'); ?>