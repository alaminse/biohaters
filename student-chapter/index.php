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
        <h3 class="hc_page_title">My Chapters</h3>
    </div>
</section>

<!--=========== PURCHASE HISTORY SECTION ===========-->
<section class="hc_section">
    <div class="chapter_container hc_container ep_grid">
        <?php if (isset($result['my_chapters'])) {
            foreach ($result['my_chapters'] as $key => $my_chapters) {
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
                    <div class="chapter_card">
                        <div class="chapter_content">
                            <?php $my_chapter_cover_photo = substr($my_chapter_cover_photo, 2); ?>
                            <img src="<?= $base_url ?>admin<?= $my_chapter_cover_photo ?>" alt="">
                        </div>
    
                        <div class="chapter_data">
                            <h1 class="chapter_title"><?= $my_chapter_name ?></h1>
    
                            <div class="ep_flex mb_75 mt_75">
                                <div class="ep_flex ep_start text_light">
                                    <i class='bx bx-message-square-detail'></i>
                                    <?= $chapter_lecture ?> টি লেসন্স
                                </div>
    
                                <?php // fetch subject
                                $select_subject  = "SELECT * FROM hc_subject WHERE id = '$my_chapter_subject' AND is_delete = 0";
                                $sql_subject     = mysqli_query($db, $select_subject);
                                $num_subject     = mysqli_num_rows($sql_subject);
                                if ($num_subject > 0) {
                                    $row_subject = mysqli_fetch_assoc($sql_subject);
                                    if ($row_subject['subject']  == 'Botany') {
                                        echo ' <div class="success">উদ্ভিদবিজ্ঞান</div>';
                                    } elseif ($row_subject['subject']  == 'Zoology') {
                                        echo ' <div class="danger">প্রাণিবিজ্ঞান</div>';
                                    }
                                }?>
                            </div>
    
                            <a href="<?= $base_url ?>chapter-view-lecture/?chapter=<?= $my_chapter_id ?>" class="button w_100 no_hover mt_75">Continue</a>
                        </div>
                    </div>
                    <?php 
                }
            }
        }?>
    </div>
</section>

<?php include('../assets/includes/dashboard_footer.php'); ?>