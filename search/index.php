<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['search_btn'])) {
    $search = mysqli_escape_string($db, $_GET['search']);

    if (empty($search)) {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>';
        </script>
        <?php 
    }
} else {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>';
    </script>
    <?php 
}?>

<!--=========== COMMON SECTION ===========-->
<section class="common_section hc_section">
    <div class="hc_container text_center">
        <h1 class="common_section_title">Your Search - <?= $search ?></h1>
    </div>
</section>

<!--=========== CHAPTER ===========-->
<section class="chapter_section hc_section">
    <div class="hc_container">
        <?php if (!empty($search)) {
            $search_data = "SELECT * FROM hc_chapter_lecture WHERE tags LIKE '%$search%' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
            $search_sql = mysqli_query($db, $search_data);
            $search_num = mysqli_num_rows($search_sql);

            if ($search_num == 0) {
                echo '<p class="mb_75">There is no lecture found!</p>';
            } else {
                echo '<p class="mb_75">There are ' . $search_num . ' lectures found</p>';
                ?>
                <div class="search_container ep_grid">
                    <?php while ($search_result = mysqli_fetch_assoc($search_sql)) {
                        $chapter        = $search_result['chapter'];
                        $lecture_name   = $search_result['name'];
                        $tags           = $search_result['tags'];
                        $duration       = $search_result['duration'];
                        $video          = $search_result['video'];
                        $is_free        = $search_result['is_free'];

                        $single_tag = explode(',', $tags);

                        // fetch chapter
                        $fetch_chapter = "SELECT * FROM hc_chapter WHERE id = '$chapter'";
                        $sql_fetch_chapter = mysqli_query($db, $fetch_chapter);
                        $row_fetch_chapter = mysqli_fetch_assoc($sql_fetch_chapter);
                        $chapter_name = $row_fetch_chapter['chapter'];
                        ?>
                        <div class="search_card">
                            <?php if ($is_free == 1) {
                                ?>
                                <a href="<?= $base_url ?>free-trial/" class="ep_flex ep_start search_card_lecture">
                                    <div class=""><?= $lecture_name ?></div>
                                    <div class="success">Free</div>
                                </a>
                                <?php 
                            } else {
                                ?>
                                <a href="<?= $base_url ?>single-chapter/?chapter=<?= $chapter ?>" class="ep_flex ep_start search_card_lecture">
                                    <div class=""><?= $lecture_name ?></div>
                                    <div class="danger">Paid</div>
                                </a>
                                <?php 
                            }?>
                            <div class="ep_flex">
                                <div class="search_card_duration">Duration: <?= gmdate('H:i:s', $duration); ?></div>
                                <div class="search_card_chapter"><?= $chapter_name ?></div>
                            </div>
                            <div class="search_card_key"><?php foreach ($single_tag as $tags) {
                                ?>
                                <div class="warning"><?php echo $tags; ?></div>
                                <?php 
                            }?></div>
                        </div>
                        <?php 
                    }?>
                </div>
                <?php 
            }
        }?>
    </div>
</section>

<?php include('../assets/includes/footer.php'); ?>