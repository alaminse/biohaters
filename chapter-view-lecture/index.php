<?php include('../assets/includes/dashboard_header.php'); ?>

<?php if (isset($_GET['chapter'])) {
    $chapter_id = $_GET['chapter'];

    $chapter_found = false;
    foreach ($result['my_chapters'] as $my_chapters) {
        if ($my_chapters['item_id'] === $chapter_id) {
            $chapter_found = true;
            break;
        }
    }

    // Check if $item was found in the array
    if ($chapter_found) {
        // chapter
        $select_chapter  = "SELECT * FROM hc_chapter WHERE id = '$chapter_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
        $sql_chapter     = mysqli_query($db, $select_chapter);
        $num_chapter     = mysqli_num_rows($sql_chapter);
        if ($num_chapter > 0) {
            while ($row_chapter = mysqli_fetch_assoc($sql_chapter)) {
                $chapter_id              = $row_chapter['id'];
                $chapter_name            = $row_chapter['chapter'];
                $chapter_status          = $row_chapter['status'];
                $chapter_cover_photo     = $row_chapter['cover_photo'];

                $chapter_cover_photo = substr($chapter_cover_photo, 2);
            }
        }?>
        <!--=========== PAGE TITLE SECTION ===========-->
        <section class="page_section hc_section">
            <div class="hc_container">
                <h3 class="hc_page_title"><?= $chapter_name ?></h3>

                <?php // fetch lectures
                $select_chapter_lecture = "SELECT SUM(duration) as total_chapter_duration FROM hc_chapter_lecture WHERE chapter = '$chapter_id' AND  status = 1 AND is_delete = 0";
                $sql_chapter_lecture = mysqli_query($db, $select_chapter_lecture);
                $num_chapter_lecture = mysqli_num_rows($sql_chapter_lecture);
                if ($num_chapter_lecture > 0) {
                    while ($row_chapter_lecture = mysqli_fetch_assoc($sql_chapter_lecture)) {
                        $chapter_duration   = $row_chapter_lecture['total_chapter_duration'];
                    }
                }?>
                <h5 class="hc_page_subtitle">Duration: <?= gmdate('H:i:s', $chapter_duration) ?> Hours</h5>
            </div>
        </section>

        <!--=========== COURSE LECTURE SECTION ===========-->
        <section class="hc_section">
            <div class="lecture_container hc_container ep_grid">
                <!--====== LECTURE VIDEO ======-->
                <div class="lecture_content">
                    <?php if (isset($_GET['lecture'])) {
                        $lecture_id = $_GET['lecture'];

                        // fetch lectures
                        $select_get_lecture = "SELECT * FROM hc_chapter_lecture WHERE id = '$lecture_id' AND chapter = '$chapter_id' AND status = 1 AND is_delete = 0";
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
                    } else {
                        // fetch default lectures
                        $select_default_lecture = "SELECT * FROM hc_chapter_lecture WHERE chapter = '$chapter_id' AND status = '1' AND is_delete = '0' LIMIT 1";
                        $sql_default_lecture = mysqli_query($db, $select_default_lecture);
                        $num_default_lecture = mysqli_num_rows($sql_default_lecture);
                        if ($num_default_lecture > 0) {
                            $row_default_lecture = mysqli_fetch_assoc($sql_default_lecture);
                            $default_lecture_id         = $row_default_lecture['id'];
                            $default_lecture_name       = $row_default_lecture['name'];
                            $default_lecture_tags       = $row_default_lecture['tags'];
                            $default_lecture_server     = $row_default_lecture['server'];
                            $default_lecture_video      = $row_default_lecture['video'];

                            $single_tag = explode(',', $default_lecture_tags);
                            
                            if ($default_lecture_server == 'youtube') {
                                ?>
                                <!--== VIDEO ==-->
                                <div class="mb_75" style="border-radius: .25rem;">
                                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/<?= $default_lecture_video ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                </div>
                                <?php 
                            } elseif ($default_lecture_server == 'vimeo') {
                                ?>
                                <!--== VIDEO ==-->
                                <!--<div class="mb_75" style="border-radius: .25rem; padding:56.25% 0 0 0; position:relative;"><iframe src="https://player.vimeo.com/video/<?= $default_lecture_video ?>?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="1025"></iframe>-->
                                <!--</div>-->
                                
                                <div class="mb_75" style="position:relative; padding-top:56.25%;"><iframe src="https://iframe.mediadelivery.net/embed/166709/<?= $default_lecture_video ?>?autoplay=true&loop=false&muted=false&preload=true" loading="lazy" style="border:0;position:absolute;top:0;height:100%;width:100%;" allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" allowfullscreen="true"></iframe></div>
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
                    }?>
                </div>

                <!--====== LECTURE DATA ======-->
                <div class="lecture_data">
                    <div class="chapter_details_content ep_grid">
                        <?php // fetch lectures
                        $select_chapter_lecture = "SELECT * FROM hc_chapter_lecture WHERE chapter = '$chapter_id' AND  status = 1 AND is_delete = 0";
                        $sql_chapter_lecture = mysqli_query($db, $select_chapter_lecture);
                        $num_chapter_lecture = mysqli_num_rows($sql_chapter_lecture);
                        if ($num_chapter_lecture > 0) {
                            while ($row_chapter_lecture = mysqli_fetch_assoc($sql_chapter_lecture)) {
                                $chapter_lecture_id         = $row_chapter_lecture['id'];
                                $chapter_lecture_name       = $row_chapter_lecture['name'];
                                $chapter_lecture_duration   = $row_chapter_lecture['duration'];
                                $chapter_lecture_free       = $row_chapter_lecture['is_free'];
                                ?>
                                <a href="<?= $base_url ?>chapter-view-lecture/?chapter=<?= $chapter_id ?>&lecture=<?= $chapter_lecture_id ?>" class="course_details_lecture_active">
                                    <div class="ep_flex gap_1_25">
                                        <div class="ep_flex ep_start">
                                            <i class='bx bx-play-circle'></i>
                                            <?= $chapter_lecture_name ?>
                                        </div>

                                        <div class="">
                                            <?= gmdate('H:i:s', $chapter_lecture_duration); ?>
                                        </div>
                                    </div>
                                </a>
                                <?php 
                            }
                        }?>
                    </div>
                </div>
            </div>
        </section>
        <?php 
    } else {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>student-course/';
        </script>
        <?php 
    }
} else {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>student-course/';
    </script>
    <?php 
}?>

<!--=========== VIMEO JS ===========-->
<!--<script src="https://player.vimeo.com/api/player.js"></script>-->

<?php include('../assets/includes/dashboard_footer.php'); ?>