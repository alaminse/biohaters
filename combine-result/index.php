<?php include('../assets/includes/dashboard_header.php'); ?>

<?php if (isset($_GET['list'])) {
    $get_list_id = $_GET['list'];
    
    if (empty($get_list_id)) {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>dashboard/';
        </script>
        <?php 
    }?>
    <!--==== MAIN CONTAINER ====-->
    <section class="dashboard_section hc_section">
        <div class="dashboard_container hc_container ep_grid">
            <!--==== MAIN CONTENT ====-->
            <div class="main_content ep_grid">
                <!--==== LEADERBOARD ====-->
                <div class="dashboard_scorecard">
                    <div class="ep_flex mb_75">
                        <h4 class="hc_card_title">Combine Scoreboard</h4>
                    </div>
                    
                    <table class="scoreboard_table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Roll</th>
                            <th>Mark</th>
                            <th>College</th>
                        </tr>
                    </thead>
    
                    <tbody>
                        <?php // fetch list
                        $select_list = "SELECT * FROM hc_combined_list WHERE id = '$get_list_id' AND is_delete = 0 ORDER BY id DESC";
                        $sql_list = mysqli_query($db, $select_list);
                        $num_list = mysqli_num_rows($sql_list);
                        if ($num_list > 0) {
                            while ($row_list = mysqli_fetch_assoc($sql_list)) {
                                $list_id = $row_list['id'];
                                
                                // fetch list data
                                $select_list_data = "SELECT * FROM hc_combined_result WHERE combined_list_id = '$list_id' ORDER BY rank ASC";
                                $sql_list_data = mysqli_query($db, $select_list_data);
                                $num_list_data = mysqli_num_rows($sql_list_data);
                                if ($num_list_data > 0) {
                                    while ($row_list_data = mysqli_fetch_assoc($sql_list_data)) {
                                        $list_data_id       = $row_list_data['id'];
                                        $list_data_rank     = $row_list_data['rank'];
                                        $list_data_name     = $row_list_data['name'];
                                        $list_data_roll     = $row_list_data['roll'];
                                        $list_data_marking  = $row_list_data['marking'];
                                        $list_data_college  = $row_list_data['college'];
                                        ?>
                                        <tr>
                                            <td><?= $list_data_rank ?></td>
                                            <td><?= $list_data_name ?></td>
                                            <td><?= $list_data_roll ?></td>
                                            <td><?= $list_data_marking ?></td>
                                            <td><?= $list_data_college ?></td>
                                        </tr>
                                        <?php 
                                    }
                                }
                            }
                        }?>
                    </tbody>
                </table>
                </div>
    
                <!--==== RESULT GRAPH ====-->
                <div id="result" class="w_100 result_graph"></div>
            </div>
    
            <!--==== EXTRA CONTENT ====-->
            <div class="extra_content ep_grid">
                <!--==== COURSE ====-->
                <div class="hc_card">
                    <div class="ep_flex mb_75">
                        <h4 class="hc_card_title">My Courses</h4>
                        <a href="<?= $base_url ?>student-course/">See All</a>
                    </div>
                    
                    <?php if (isset($result['my_courses'])) {
                        ?>
                        <div class="ep_grid course_list">
                            <?php foreach ($result['my_courses'] as $key => $my_courses) {
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
                                    <div class="dasboard_course_card ep_flex">
                                        <h5 class="dasboard_course_card_title"><?= $my_course_name ?></h5>
                
                                        <a href="<?= $base_url ?>course-view-lecture/?course=<?= $my_course_id ?>"><i class='bx bx-play-circle'></i></a>
                                    </div>
                                    <?php 
                                }
                            }?>
                        </div>
                        <?php 
                    }?>
                </div>
    
                <!--==== CHAPTER ====-->
                <div class="hc_card">
                    <div class="ep_flex mb_75">
                        <h4 class="hc_card_title">My Chapters</h4>
                        <a href="<?= $base_url ?>student-chapter/">See All</a>
                    </div>
                    
                    <?php if (isset($result['my_chapters'])) {
                        ?>
                        <div class="ep_grid chapter_list">
                            <?php foreach ($result['my_chapters'] as $key => $my_chapters) {
                                // chapter id
                                $my_chapters_id = $my_chapters['item_id'];
                    
                                // fetch my chapter
                                $select_my_chapter  = "SELECT * FROM hc_chapter WHERE id = '$my_chapters_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
                                $sql_my_chapter     = mysqli_query($db, $select_my_chapter);
                                $num_my_chapter     = mysqli_num_rows($sql_my_chapter);
                                if ($num_my_chapter > 0) {
                                    $row_my_chapter = mysqli_fetch_assoc($sql_my_chapter);
                    
                                    // my chapter variable
                                    $my_chapter_id             = $row_my_chapter['id'];
                                    $my_chapter_name           = $row_my_chapter['chapter'];
                                    $my_chapter_subject        = $row_my_chapter['subject'];
                                    $my_chapter_price          = $row_my_chapter['price'];
                                    $my_chapter_sale           = $row_my_chapter['sale_price'];
                                    $my_chapter_cover_photo    = $row_my_chapter['cover_photo'];
                                    $my_chapter_status         = $row_my_chapter['status'];
                                    $my_chapter_author         = $row_my_chapter['author'];
                                    $my_chapter_created_date   = $row_my_chapter['created_date'];
                    
                                    // connected with chapter
                                    $select_chapter_lecture = "SELECT COUNT(id) as chapter_lecture FROM hc_chapter_lecture WHERE chapter = '$my_chapter_id' AND is_delete = 0";
                                    $sql_chapter_lecture = mysqli_query($db, $select_chapter_lecture);
                                    $row_chapter_lecture = mysqli_fetch_assoc($sql_chapter_lecture);
                                    $chapter_lecture = $row_chapter_lecture['chapter_lecture'];
                                    ?>
                                    <!-- CHAPTER CARD -->
                                    <div class="dasboard_course_card ep_flex">
                                        <h5 class="dasboard_course_card_title"><?= $my_chapter_name ?></h5>
                
                                        <a href="<?= $base_url ?>chapter-view-lecture/?chapter=<?= $my_chapter_id ?>"><i class='bx bx-play-circle'></i></a>
                                    </div>
                                    <?php 
                                }
                            }?>
                        </div>
                        <?php 
                    }?>
                </div>
                
                <!--==== NOTICE ====-->
                <div class="hc_card">
                    <h4 class="hc_card_title">Notice Board</h4>
                    <h5 class="hc_card_subtitle">Latest Notice Info</h5>
    
                    <div class="notice_list">
                        <?php if (isset($result['notice'])) {
                            $si = 0;
                            foreach ($result['notice'] as $key => $notice) {
                                // notice id
                                $notice_id = $notice['id'];
                                $notice_created_date = $notice['created_date'];
                                
                                // joined date convert to text
                                $notice_created_date_text = date('d M, Y | h:i:s a', strtotime($notice_created_date));
        
                                $si++;
                                if ($notice_id != 1) {
                                    ?>
                                    <!-- notice card -->
                                    <a href="<?= $base_url ?>notice-view/?notice=<?= $notice_id ?>" class="notice_card">
                                        <div class="notice_content">
                                            <i class='bx bx-bell'></i>
                                        </div>
                
                                        <div class="notice_data ep_flex">
                                            <div>
                                                <div class="notice_title"><?= $notice['name'] ?></div>
                                                <div class="notice_date"><?= $notice_created_date_text ?></div>
                                            </div>
                
                                            <i class='bx bx-right-arrow-alt' ></i>
                                        </div>
                                    </a>
                                    <?php 
                                }
                            }
                        }?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php 
} else {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>exam/';
    </script>
    <?php 
}?>

<?php include('../assets/includes/dashboard_footer.php'); ?>