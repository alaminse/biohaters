<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['blog'])) {
    $blog_id = $_GET['blog'];

    // fetch blog
    $select_blog  = "SELECT * FROM hc_blog WHERE id = '$blog_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
    $sql_blog     = mysqli_query($db, $select_blog);
    $num_blog     = mysqli_num_rows($sql_blog);
    if ($num_blog > 0) {
        $i = 0;
        while ($row_blog = mysqli_fetch_assoc($sql_blog)) {
            $blog_id            = $row_blog['id'];
            $blog_name          = $row_blog['name'];
            $blog_des           = $row_blog['description'];
            $blog_category      = $row_blog['category'];
            $blog_tags          = $row_blog['tags'];
            $blog_featured      = $row_blog['is_featured'];
            $blog_cover_photo   = $row_blog['cover_photo'];
            $blog_status        = $row_blog['status'];
            $blog_author        = $row_blog['author'];
            $blog_created_date  = $row_blog['created_date'];

            // fetch category
            $select_category = "SELECT * FROM hc_blog_category WHERE id = '$blog_category' AND is_delete = 0 ORDER BY id DESC";
            $sql_category    = mysqli_query($db, $select_category);
            $num_category    = mysqli_num_rows($sql_category);
            if ($num_category > 0) {
                $row_category = mysqli_fetch_assoc($sql_category);
                $category_id              = $row_category['id'];
                $category_name            = $row_category['name'];
                $category_des             = $row_category['description'];
                $category_parent          = $row_category['parent'];
                $category_status          = $row_category['status'];
                $category_author          = $row_category['author'];
                $category_created_date    = $row_category['created_date'];
            }

            // fetch author
            $select_author = "SELECT * FROM admin WHERE id = '$blog_author' AND is_delete = 0 ORDER BY id DESC";
            $sql_author    = mysqli_query($db, $select_author);
            $num_author    = mysqli_num_rows($sql_author);
            if ($num_author > 0) {
                $row_author = mysqli_fetch_assoc($sql_author);
                $author_id              = $row_author['id'];
                $author_name            = $row_author['name'];
                $author_photo           = $row_author['profile'];
            }

            // blog date
            $blog_date = date('d M Y', strtotime($blog_created_date));
        }
    } else {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>all-blog/';
        </script>
        <?php 
    }?>
    <!--=========== COMMON SECTION ===========-->
    <section class="common_section common_blog_section hc_section">
        <div class="hc_container text_center">
            <h1 class="common_section_title"><?= $blog_name ?></h1>
        </div>
    </section>

    <!--=========== BLOG ===========-->
    <section class="blog_details_section hc_section">
        <div class="blog_details_container hc_container ep_grid">
            <!-- BLOG DETAILS -->
            <div class="blog_details_content">
                <?php $blog_cover_photo = substr($blog_cover_photo, 2); ?>
                <img src="<?= $base_url ?>admin<?php echo $blog_cover_photo; ?>" alt="">
            </div>

            <!-- BLOG DATA -->
            <div class="blog_details_data">
                <!-- BLOG TITLE -->
                <div class="blog_details_data_title"><?= $blog_name ?></div>

                <!-- BLOG DESCRIPTION -->
                <div class="blog_details_data_des"><?= $blog_des ?></div>

                <!-- BLOG META -->
                <div class="blog_details_data_meta">
                    <div class="ep_flex ep_start">
                        <div class="blog_details_author_content">
                            <?php $author_photo = substr($author_photo, 2); ?>
                            <img src="<?= $base_url ?>admin<?= $author_photo ?>" alt="">
                        </div>
                        <div class="blog_details_author_data">
                            <div class="blog_details_author_data_title"><?= $author_name ?></div>
                            <div class="blog_details_publish"><?= $blog_date ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--=========== RELATED CHAPTER ===========-->
    <section class="course_section hc_section">
        <div class="hc_container">
            <h4 class="related_course_title">অন্যান্য ব্লগ সমূহ</h4>
        </div>

        <div class="course_container hc_container ep_grid">
            <?php // all blog
            $select_related_blog  = "SELECT * FROM hc_blog WHERE id != '$blog_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC LIMIT 3";
            $sql_related_blog     = mysqli_query($db, $select_related_blog);
            $num_related_blog     = mysqli_num_rows($sql_related_blog);
            if ($num_related_blog > 0) {
                $i = 0;
                while ($row_related_blog = mysqli_fetch_assoc($sql_related_blog)) {
                    $related_blog_id            = $row_related_blog['id'];
                    $related_blog_name          = $row_related_blog['name'];
                    $related_blog_des           = $row_related_blog['description'];
                    $related_blog_category      = $row_related_blog['category'];
                    $related_blog_tags          = $row_related_blog['tags'];
                    $related_blog_featured      = $row_related_blog['is_featured'];
                    $related_blog_cover_photo   = $row_related_blog['cover_photo'];
                    $related_blog_status        = $row_related_blog['status'];
                    $related_blog_author        = $row_related_blog['author'];
                    $related_blog_created_date  = $row_related_blog['created_date'];

                    // blog date
                    $related_blog_date = date('d M Y', strtotime($related_blog_created_date));
                    ?>
                    <!-- BLOG CARD -->
                    <div class="blog_card">
                        <div class="blog_content">
                            <?php $related_blog_cover_photo = substr($related_blog_cover_photo, 2); ?>
                            <img src="<?= $base_url ?>admin<?php echo $related_blog_cover_photo; ?>" alt="">
                        </div>

                        <div class="blog_data">
                            <h1 class="blog_title"><?= $related_blog_name ?></h1>

                            <div class="text_light mb_75">
                                <?php $words = explode(" ", $related_blog_des);
                                $related_blog_des = implode(" ", array_slice($words, 0, 15)); ?>
                                <?= $related_blog_des."..."; ?>
                            </div>

                            <a href="<?= $base_url ?>single-blog/?blog=<?= $related_blog_id ?>" class="button btn_sm no_hover mb_75 bg_secondary">আরও পড়ুন</a>

                            <p class="text_light text_sm">Published on : <?= $related_blog_date ?></p>
                        </div>
                    </div>
                    <?php 
                }
            }?>
        </div>
    </section>
    <?php 
} else {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>all-blog/';
    </script>
    <?php 
}?>

<?php include('../assets/includes/footer.php'); ?>