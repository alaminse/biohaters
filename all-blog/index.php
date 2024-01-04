<?php include('../assets/includes/header.php'); ?>

<!--=========== COMMON SECTION ===========-->
<section class="common_section hc_section">
    <div class="hc_container text_center">
        <h1 class="common_section_title">সকল ব্লগ সমূহ</h1>
    </div>
</section>

<!--=========== BLOG ===========-->
<section class="blog_section hc_section">
    <div class="blog_container hc_container ep_grid">
        <?php // blog
        foreach ($result['all_blog'] as $key => $all_blog) {
            // blog id
            $blog_id = $all_blog['id'];

            // blog date
            $blog_date = date('d M Y', strtotime($all_blog['created_date']));
            ?>
            <!-- BLOG CARD -->
            <div class="blog_card">
                <div class="blog_content">
                    <?php $all_blog_cover_photo = substr($all_blog['cover_photo'], 2); ?>
                    <img src="<?= $base_url ?>admin<?php echo $all_blog_cover_photo; ?>" alt="">
                </div>

                <div class="blog_data">
                    <h1 class="blog_title"><?= $all_blog['name'] ?></h1>

                    <div class="text_light mb_75">
                        <?php $words = explode(" ", $all_blog['description']);
                        $all_blog_des = implode(" ", array_slice($words, 0, 15)); ?>
                        <?= $all_blog_des."..."; ?>
                    </div>

                    <a href="<?= $base_url ?>single-blog/?blog=<?= $blog_id ?>" class="button btn_sm no_hover mb_75 bg_secondary">আরও পড়ুন</a>

                    <p class="text_light text_sm">Published on : <?= $blog_date ?></p>
                </div>
            </div>
            <?php 
        }?>
    </div>
</section>

<?php include('../assets/includes/footer.php'); ?>