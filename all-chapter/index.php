<?php include('../assets/includes/header.php'); ?>

<!--=========== COMMON SECTION ===========-->
<section class="common_section hc_section">
    <div class="hc_container text_center">
        <h1 class="common_section_title">জীববিজ্ঞান সকল অধ্যায় সমূহ</h1>
    </div>
</section>

<!--=========== CHAPTER ===========-->
<section class="chapter_section hc_section">
    <div class="chapter_container hc_container ep_grid">
        <?php // chapter
        foreach ($result['all_chapter'] as $key => $all_chapter) {
            // chapter id
            $chapter_id = $all_chapter['id'];

            // connected with chapter
            $select_chapter_lecture = "SELECT COUNT(id) as chapter_lecture FROM hc_chapter_lecture WHERE chapter = '$chapter_id' AND is_delete = 0";
            $sql_chapter_lecture = mysqli_query($db, $select_chapter_lecture);
            $row_chapter_lecture = mysqli_fetch_assoc($sql_chapter_lecture);
            $chapter_lecture = $row_chapter_lecture['chapter_lecture'];
            ?>
            <!-- CHAPTER CARD -->
            <div class="chapter_card">
                <div class="chapter_content">
                    <?php $all_chapter_cover_photo = substr($all_chapter['cover_photo'], 2); ?>
                    <img src="<?= $base_url ?>admin<?php echo $all_chapter_cover_photo; ?>" alt="">
                </div>

                <div class="chapter_data">
                    <h1 class="chapter_title"><?php echo $all_chapter['name']; ?></h1>

                    <div class="ep_flex mb_75 chapter_widget">
                        <div class="ep_flex ep_start text_light">
                            <i class='bx bx-message-square-detail'></i>
                            <?= $chapter_lecture ?> টি লেসন্স
                        </div>
                        <?php // fetch subject
                        $subject = $all_chapter['subject'];
                        $select_subject  = "SELECT * FROM hc_subject WHERE id = '$subject' AND is_delete = 0";
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

                    <div class="ep_flex ep_end mb_75 mt_75 chapter_price">
                        <?php if ($all_chapter['sale_price'] > 0) {
                            ?>
                            <span class="text_strike text_light text_sm chapter_strike_price">৳<?= $all_chapter['price'] ?></span>
                            <span class="text_lg chapter_reg_price">৳<?= $all_chapter['sale_price'] ?></span>
                            <?php 
                        } else {
                            ?>
                            <span class="text_lg chapter_reg_price">৳<?= $all_chapter['sale_price'] ?></span>
                            <?php 
                        }?>
                    </div>

                    <a href="<?= $base_url ?>single-chapter/?chapter=<?= $chapter_id ?>" class="button w_100 no_hover mt_75 chapter_btn">এনরোল করুন</a>
                </div>
            </div>
            <?php 
        }?>
    </div>
</section>

<?php include('../assets/includes/footer.php'); ?>