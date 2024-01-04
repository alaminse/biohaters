<?php include('../assets/includes/dashboard_header.php'); ?>

<?php if (isset($_GET['course'])) {
    $course_id = $_GET['course'];

    $course_found = false;
    foreach ($result['my_courses'] as $my_courses) {
        if ($my_courses['item_id'] === $course_id) {
            $course_found = true;
            break;
        }
    }
    
    // intialize now time
    $now = date('Y-m-d H:i:s', time());
    
    $today = date('Y-m-d', time());

    // Check if $item was found in the array
    if ($course_found) {
        // course
        $select_course  = "SELECT * FROM hc_course WHERE id = '$course_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
        $sql_course     = mysqli_query($db, $select_course);
        $num_course     = mysqli_num_rows($sql_course);
        if ($num_course > 0) {
            while ($row_course = mysqli_fetch_assoc($sql_course)) {
                $course_id              = $row_course['id'];
                $course_name            = $row_course['name'];
                $course_status          = $row_course['status'];
                $course_tags            = $row_course['tags'];
                $course_des             = $row_course['description'];
                $course_cover_photo     = $row_course['cover_photo'];

                $course_cover_photo = substr($course_cover_photo, 2);
            }
        }

        $course_duration = 0;
        ?>
        <!--=========== PAGE TITLE SECTION ===========-->
        <section class="page_section hc_section">
            <div class="hc_container">
                <h3 class="hc_page_title"><?= $course_name ?></h3>
                <?php // fetch course duration
                $select_course_duration = "SELECT SUM(duration) as course_duration FROM hc_course_lecture WHERE course = '$course_id' AND status = 1 AND is_delete = 0";
                $sql_course_duration = mysqli_query($db, $select_course_duration);
                $num_course_duration = mysqli_num_rows($sql_course_duration);
                if ($num_course_duration > 0) {
                    $row_course_duration = mysqli_fetch_assoc($sql_course_duration);
                }
                
                $course_duration = $row_course_duration['course_duration'];
                
                $course_duration = $course_duration / 3600; 
                
                if ($course_duration < 1) {
                    $course_duration_text = 'Less Than 1 Hour';
                } else {
                    $course_duration = ceil($course_duration);
                    $course_duration_text = 'Almost ' . $course_duration . ' Hours';
                }?>
                <h5 class="hc_page_subtitle">Duration: <?= $course_duration_text ?></h5>
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
                        $select_get_lecture = "SELECT * FROM hc_course_lecture WHERE id = '$lecture_id' AND course = '$course_id' AND status = 1 AND is_delete = 0";
                        $sql_get_lecture = mysqli_query($db, $select_get_lecture);
                        $num_get_lecture = mysqli_num_rows($sql_get_lecture);
                        if ($num_get_lecture > 0) {
                            $row_get_lecture = mysqli_fetch_assoc($sql_get_lecture);
                            $get_lecture_id             = $row_get_lecture['id'];
                            $get_lecture_name           = $row_get_lecture['name'];
                            $get_lecture_module         = $row_get_lecture['module'];
                            $get_lecture_tags           = $row_get_lecture['tags'];
                            $get_lecture_server         = $row_get_lecture['server'];
                            $get_lecture_video          = $row_get_lecture['video'];
                            $get_lecture_animation      = $row_get_lecture['animation'];
                            $get_lecture_drawing        = $row_get_lecture['drawing'];
                            $get_lecture_created_date   = $row_get_lecture['created_date'];
                            
                            $get_lecture_created_date_text = date('M d, Y h:i a', strtotime($get_lecture_created_date));
                            
                            $single_tag = explode(',', $get_lecture_tags);
                            
                            $videos = []; // Initialize the $videos array
                            
                            // fetch single module all lecture
                            $select_module_lecture = "SELECT * FROM hc_course_lecture WHERE module = '$get_lecture_module' AND status = 1 AND is_delete = 0 ORDER BY created_date ASC";
                            $sql_module_lecture = mysqli_query($db, $select_module_lecture);
                            $num_module_lecture = mysqli_num_rows($sql_module_lecture);
                            if ($num_module_lecture > 0) {
                                while ($row_module_lecture = mysqli_fetch_assoc($sql_module_lecture)) {
                                    $module_lecture_id = $row_module_lecture['id'];
                                    // Adding lecture IDs to the $videos array
                                    $videos[] = ['id' => $module_lecture_id];
                                }
                            }
                            
                            // Get current video ID (you may obtain this from the URL or session)
                            $currentVideoId = $get_lecture_id;
                            
                            // Find the current video index in the videos array
                            $currentIndex = array_search($currentVideoId, array_column($videos, 'id'));
                            
                            if ($now >= $get_lecture_created_date) {
                                if (isset($_GET['animation']) && $get_lecture_animation != '') {
                                    ?>
                                    <!--== VIDEO ==-->
                                    <!--<div class="mb_75" style="border-radius: .25rem; padding:56.25% 0 0 0; position:relative;"><iframe src="https://player.vimeo.com/video/<?= $get_lecture_animation ?>?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="1025"></iframe>-->
                                    <!--</div>-->
                                    <div class="mb_75" style="position:relative; padding-top:56.25%;"><iframe src="https://iframe.mediadelivery.net/embed/166709/<?= $get_lecture_animation ?>?autoplay=true&loop=false&muted=false&preload=true" loading="lazy" style="border:0;position:absolute;top:0;height:100%;width:100%;" allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" allowfullscreen="true"></iframe></div>
                                    <?php 
                                } elseif (isset($_GET['drawing']) && $get_lecture_drawing != '') {
                                    ?>
                                    <!--== VIDEO ==-->
                                    <!--<div class="mb_75" style="border-radius: .25rem; padding:56.25% 0 0 0; position:relative;"><iframe src="https://player.vimeo.com/video/<?= $get_lecture_drawing ?>?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="1025"></iframe>-->
                                    <!--</div>-->
                                    <div class="mb_75" style="position:relative; padding-top:56.25%;"><iframe src="https://iframe.mediadelivery.net/embed/166709/<?= $get_lecture_drawing ?>?autoplay=true&loop=false&muted=false&preload=true" loading="lazy" style="border:0;position:absolute;top:0;height:100%;width:100%;" allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" allowfullscreen="true"></iframe></div>
                                    <?php 
                                } else {
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
                                    <div class="ep_flex mb_75">
                                        <?php echo '<div>';
                                        // Display navigation buttons
                                        if ($currentIndex > 0) {
                                            echo '<a href="' . $base_url . 'course-view-lecture/?course=' . $course_id . '&lecture=' . $videos[$currentIndex - 1]['id'] . '" class="button btn_sm">Previous</a>';
                                        }
                                        
                                        echo '</div><div>';
                                        
                                        if ($currentIndex < count($videos) - 1) {
                                            echo '<a href="' . $base_url . 'course-view-lecture/?course=' . $course_id . '&lecture=' . $videos[$currentIndex + 1]['id'] . '" class="button btn_sm">Next</a>';
                                        }
                                        echo '</div>';?>
                                    </div>
                                    <!--== TAGS ==-->
                                    <div class="ep_flex ep_center ep_flex_wrap ep_row_gap">
                                        <?php foreach ($single_tag as $lecture_tags) {
                                            ?>
                                            <div class="warning key_tag"><?= $lecture_tags ?></div>
                                            <?php 
                                        }?>
                                    </div>
                                    <?php 
                                }
                            } else {
                                echo    '<div class="hc_alert hc_alert_info">
                                            <h4 class="hc_alert_message">New Video Get on ' . $get_lecture_created_date_text . '</h4>
                                        </div>';
                            }
                        } else {
                            echo    '<div class="hc_alert hc_alert_danger">
                                        <h4 class="hc_alert_message">No Video Found</h4>
                                    </div>';
                        }
                    } else {
                        // fetch default lectures
                        $select_default_lecture = "SELECT * FROM hc_course_lecture WHERE course = '$course_id' AND status = '1' AND DATE(created_date) <= '$today' AND is_delete = '0' ORDER BY DATE(created_date) DESC, created_date ASC LIMIT 1";
                        $sql_default_lecture = mysqli_query($db, $select_default_lecture);
                        $num_default_lecture = mysqli_num_rows($sql_default_lecture);
                        if ($num_default_lecture > 0) {
                            $row_default_lecture = mysqli_fetch_assoc($sql_default_lecture);
                            $default_lecture_id             = $row_default_lecture['id'];
                            $default_lecture_name           = $row_default_lecture['name'];
                            $default_lecture_module         = $row_default_lecture['module'];
                            $default_lecture_tags           = $row_default_lecture['tags'];
                            $default_lecture_server         = $row_default_lecture['server'];
                            $default_lecture_video          = $row_default_lecture['video'];
                            $default_lecture_created_date   = $row_default_lecture['created_date'];
                            
                            $default_lecture_created_date_text = date('M d, Y h:i a', strtotime($default_lecture_created_date));

                            $single_tag = explode(',', $default_lecture_tags);
                            
                            $videos = []; // Initialize the $videos array
                            
                            // fetch single module all lecture
                            $select_module_lecture = "SELECT * FROM hc_course_lecture WHERE module = '$default_lecture_module' AND status = 1 AND is_delete = 0 ORDER BY created_date ASC";
                            $sql_module_lecture = mysqli_query($db, $select_module_lecture);
                            $num_module_lecture = mysqli_num_rows($sql_module_lecture);
                            if ($num_module_lecture > 0) {
                                while ($row_module_lecture = mysqli_fetch_assoc($sql_module_lecture)) {
                                    $module_lecture_id = $row_module_lecture['id'];
                                    // Adding lecture IDs to the $videos array
                                    $videos[] = ['id' => $module_lecture_id];
                                }
                            }
                            
                            // Get current video ID (you may obtain this from the URL or session)
                            $currentVideoId = $default_lecture_id;
                            
                            // Find the current video index in the videos array
                            $currentIndex = array_search($currentVideoId, array_column($videos, 'id'));
                            
                            if ($now >= $default_lecture_created_date) {
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
                                <div class="ep_flex mb_75">
                                    <?php echo '<div>';
                                    // Display navigation buttons
                                    if ($currentIndex > 0) {
                                        echo '<a href="' . $base_url . 'course-view-lecture/?course=' . $course_id . '&lecture=' . $videos[$currentIndex - 1]['id'] . '" class="button btn_sm">Previous</a>';
                                    }
                                    
                                    echo '</div><div>';
                                    
                                    if ($currentIndex < count($videos) - 1) {
                                        echo '<a href="' . $base_url . 'course-view-lecture/?course=' . $course_id . '&lecture=' . $videos[$currentIndex + 1]['id'] . '" class="button btn_sm">Next</a>';
                                    }
                                    echo '</div>';?>
                                </div>
                                <!--== TAGS ==-->
                                <div class="ep_flex ep_center ep_flex_wrap ep_row_gap">
                                    <?php foreach ($single_tag as $lecture_tags) {
                                        ?>
                                        <div class="warning key_tag"><?= $lecture_tags ?></div>
                                        <?php 
                                    }?>
                                </div>
                                <?php 
                            } else {
                                echo    '<div class="hc_alert hc_alert_info">
                                            <h4 class="hc_alert_message">New Video Get on ' . $default_lecture_created_date_text . '</h4>
                                        </div>';
                            }
                        } else {
                            echo    '<div class="hc_alert hc_alert_danger">
                                        <h4 class="hc_alert_message">No Video Found</h4>
                                    </div>';
                        }
                    }?>
                </div>

                <!--====== LECTURE DATA ======-->
                <div class="lecture_data">
                    <?php // course module
                    $select_course_module = "SELECT * FROM hc_module WHERE course = '$course_id' AND is_delete = 0 ORDER BY id DESC";
                    $sql_course_module    = mysqli_query($db, $select_course_module);
                    $num_course_module    = mysqli_num_rows($sql_course_module);
                    if ($num_course_module > 0) {
                        ?>
                        <div class="accordion" id="course-details-accordion">
                            <?php while($row_course_module = mysqli_fetch_assoc($sql_course_module)) {
                                $course_module_id     = $row_course_module['id'];
                                $course_module_name   = $row_course_module['name'];
                                
                                // fetch last update of this module video
                                $select_module_update = "SELECT * FROM hc_course_lecture WHERE course = '$course_id' ORDER BY id DESC LIMIT 1";
                                $sql_module_update = mysqli_query($db, $select_module_update);
                                $num_module_update = mysqli_num_rows($sql_module_update);
                                if ($num_module_update > 0) {
                                    $row_module_update = mysqli_fetch_assoc($sql_module_update);
                                    $last_update_module = $row_module_update['module'];
                                    $last_update = $row_module_update['created_date'];
                                    
                                    $last_update_text = date('d M, Y H:i:s', strtotime($last_update));
                                }?>
                                <div class="accordion-item hc_accordion_item">
                                    <h2 class="accordion-header" id="module-heading-<?= $course_module_id ?>">
                                        <button class="accordion-button w_100" aria-labelledby="module-heading-<?= $course_module_id ?>" type="button" data-bs-toggle="collapse" data-bs-target="#module-<?= $course_module_id ?>" aria-expanded="true" aria-controls="module-<?= $course_module_id ?>">
                                            <span class="<?php if (($default_lecture_module == $course_module_id) || ($get_lecture_module == $course_module_id)) { echo 'success_lecture'; }?>"><?= $course_module_name ?></span>
                                            <span class="ep_flex ep_start">
                                                <div class="module_duratrion">
                                                    <?php // if ($last_update_module == $course_module_id) {
                                                        ?>
                                                        <!--<div class='success w_max'>New Update on <?= $last_update_text ?></div>-->
                                                        <?php 
                                                    // }?>
                                                </div>

                                                <i class='bx bxs-chevron-down'></i>
                                            </span>
                                        </button>
                                    </h2>
                                    
                                    <div id="module-<?= $course_module_id ?>" class="accordion-collapse collapse <?php if (($default_lecture_module == $course_module_id) || ($get_lecture_module == $course_module_id)) { echo 'show'; }?>" aria-labelledby="module-heading-<?= $course_module_id ?>" data-bs-parent="#course-details-accordion">
                                        <div class="accordion-body ep_grid">
                                            <?php $day_count = 0;
                                            
                                            // fetch day
                                            $select_lecture_day = "SELECT DATE(created_date) as lecture_day FROM hc_course_lecture WHERE module = '$course_module_id' AND status = 1 AND is_delete = 0 GROUP BY DATE(created_date) ORDER BY created_date ASC";
                                            $sql_lecture_day = mysqli_query($db, $select_lecture_day);
                                            $num_lecture_day = mysqli_num_rows($sql_lecture_day);
                                            if ($num_lecture_day > 0) {
                                                while ($row_lecture_day = mysqli_fetch_assoc($sql_lecture_day)) {
                                                    $lecture_day = $row_lecture_day['lecture_day'];
                                                    
                                                    $day_count++;
                                                    
                                                    // fetch lectures
                                                    $select_course_lecture = "SELECT * FROM hc_course_lecture WHERE module = '$course_module_id' AND status = 1 AND DATE(created_date) = '$lecture_day' AND is_delete = 0 ORDER BY created_date ASC";
                                                    $sql_course_lecture = mysqli_query($db, $select_course_lecture);
                                                    $num_course_lecture = mysqli_num_rows($sql_course_lecture);
                                                    if ($num_course_lecture > 0) {
                                                        $lecture_count = 0;
                                                        while ($row_course_lecture = mysqli_fetch_assoc($sql_course_lecture)) {
                                                            $course_lecture_id              = $row_course_lecture['id'];
                                                            $course_lecture_name            = $row_course_lecture['name'];
                                                            $course_lecture_duration        = $row_course_lecture['duration'];
                                                            $course_lecture_free            = $row_course_lecture['is_free'];
                                                            $course_lecture_doc             = $row_course_lecture['document'];
                                                            $course_lecture_animation       = $row_course_lecture['animation'];
                                                            $course_lecture_drawing         = $row_course_lecture['drawing'];
                                                            $course_lecture_created_date    = $row_course_lecture['created_date'];
                                                            
                                                            if ($now >= $course_lecture_created_date) {
                                                                $lecture_count++;
                                                                ?>
                                                                <div class="lecture_content_box">
                                                                    <a href="<?= $base_url ?>course-view-lecture/?course=<?= $course_id ?>&lecture=<?= $course_lecture_id ?>" class="course_details_lecture_active">
                                                                        <div class="text_smr payment_success_properties">Day - <?= $day_count ?> <i class='bx bxs-chevrons-right'></i> Lecture - <?= $lecture_count ?> <i class='bx bxs-chevrons-right'></i> <?= gmdate('H:i:s', $course_lecture_duration); ?></div>
                                                                        
                                                                        <div class="ep_flex gap_1_25 text_sm">
                                                                            <div class="ep_flex ep_start <?php if (($default_lecture_id == $course_lecture_id) || ($get_lecture_id == $course_lecture_id)) { echo 'success_lecture'; }?>">
                                                                                <i class='bx bx-play-circle'></i>
                                                                                <?= $course_lecture_name ?>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    
                                                                    <div class="ep_flex ep_start gap_1_25 text_sm">
                                                                        <?php if ($course_lecture_doc != '') {
                                                                            ?>
                                                                            <a href="<?= $course_lecture_doc ?>" class="info key_tag">
                                                                                <i class='bx bxs-file-doc' ></i> Note
                                                                            </a>
                                                                            <?php 
                                                                        }?>
                                                                        
                                                                        <?php if ($course_lecture_animation != '') {
                                                                            ?>
                                                                            <a href="<?= $base_url ?>course-view-lecture/?course=<?= $course_id ?>&lecture=<?= $course_lecture_id ?>&animation" class="danger key_tag">
                                                                                <i class='bx bx-cube'></i> 3D Animation
                                                                            </a>
                                                                            <?php 
                                                                        }?>
                                                                        
                                                                        <?php if ($course_lecture_drawing != '') {
                                                                            ?>
                                                                            <a href="<?= $base_url ?>course-view-lecture/?course=<?= $course_id ?>&lecture=<?= $course_lecture_id ?>&drawing" class="success key_tag">
                                                                                <i class='bx bx-palette' ></i> Drawing
                                                                            </a>
                                                                            <?php 
                                                                        }?>
                                                                    </div>
                                                                </div>
                                                                <?php 
                                                            }
                                                        }
                                                    }
                                                }
                                            }?>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                            }?>
                        </div>
                        <?php 
                    }?>
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