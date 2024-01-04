<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['chapter'])) {
    $chapter_id = $_GET['chapter'];

    // fetch chapter
    $select_chapter  = "SELECT * FROM hc_chapter WHERE id = '$chapter_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
    $sql_chapter     = mysqli_query($db, $select_chapter);
    $num_chapter     = mysqli_num_rows($sql_chapter);
    if ($num_chapter > 0) {
        $i = 0;
        while ($row_chapter = mysqli_fetch_assoc($sql_chapter)) {
            $chapter_id             = $row_chapter['id'];
            $chapter_name           = $row_chapter['chapter'];
            $chapter_subject        = $row_chapter['subject'];
            $chapter_price          = $row_chapter['price'];
            $chapter_sale           = $row_chapter['sale_price'];
            $chapter_cover_photo    = $row_chapter['cover_photo'];
            $chapter_status         = $row_chapter['status'];
            $chapter_author         = $row_chapter['author'];
            $chapter_created_date   = $row_chapter['created_date'];

            // connected with chapter
            $select_chapter_lecture = "SELECT COUNT(id) as chapter_lecture FROM hc_chapter_lecture WHERE chapter = '$chapter_id' AND is_delete = 0";
            $sql_chapter_lecture = mysqli_query($db, $select_chapter_lecture);
            $row_chapter_lecture = mysqli_fetch_assoc($sql_chapter_lecture);
            $chapter_lecture = $row_chapter_lecture['chapter_lecture'];

            // fetch subject
            $select_subject  = "SELECT * FROM hc_subject WHERE id = '$chapter_subject' AND is_delete = 0";
            $sql_subject     = mysqli_query($db, $select_subject);
            $num_subject     = mysqli_num_rows($sql_subject);
            if ($num_subject > 0) {
                $row_subject = mysqli_fetch_assoc($sql_subject);
            }

            // exact price
            if ($chapter_sale > 0) {
                $exact_price = $chapter_sale;
            } else {
                $exact_price = $chapter_price;
            }

            // bkash charge
            $bkash_charge = floor($exact_price * 0); // 0.015
            
            // $discount = floor($exact_price * 0.2);
            
            $discount = 0;
            
            $subtotal = $exact_price - $discount;

            // grant total
            $grant_total = $exact_price + $bkash_charge - $discount;
        }
    } else {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>all-chapter/';
        </script>
        <?php 
    }?>
    <!--=========== COMMON SECTION ===========-->
    <section class="common_section hc_section">
        <div class="hc_container text_center">
            <h1 class="common_section_title"><?= $chapter_name ?></h1>
        </div>
    </section>

    <!--=========== CHAPTER PURCHASE FORM ===========-->
    <form action="<?= $base_url ?>purchase/" method="post">
        <!--=========== CHAPTER ===========-->
        <section class="course_details_section hc_section">
            <div class="chapter_details_container hc_container ep_grid">
                <!-- CHAPTER DETAILS -->
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
                            if ($chapter_lecture_free == 1) {
                                ?>
                                <a href="<?= $base_url ?>free-trial/" class="course_details_lecture_active">
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
                            } else {
                                ?>
                                <div class="ep_flex gap_1_25">
                                    <div class="ep_flex ep_start text_light">
                                        <i class='bx bx-lock' ></i>
                                        <?= $chapter_lecture_name ?>
                                    </div>

                                    <div class="text_light">
                                        <?= gmdate('H:i:s', $chapter_lecture_duration); ?>
                                    </div>
                                </div>
                                <?php 
                            }
                        }
                    }?>
                </div>

                <!-- CHECKOUT CARD -->
                <div class="single_chapter_card">
                    <h4 class="single_chapter_card_title">পেমেন্ট ফর্ম</h4>

                    <div class="payment_details">
                        <div class="ep_flex">
                            <p class="text_semi">চ্যাপ্টার ফীঃ </p>
                            <p>৳<?= $exact_price ?></p>
                        </div>

                        <div class="ep_flex">
                            <p class="text_semi">বিকাশ চার্জঃ </p>
                            <p>৳<?= $bkash_charge ?></p>
                        </div>
                        
                        <!--<div class="ep_flex payment_details_discount">-->
                        <!--    <p class="text_semi">Festival Disount (20%): </p>-->
                        <!--    <p>- ৳<?= $discount ?></p>-->
                        <!--</div>-->

                        <div class="ep_flex grant_total">
                            <p class="text_semi">মোট ফীঃ </p>
                            <p>৳<?= $grant_total ?></p>
                        </div>
                    </div>

                    <div class="single_col_form">
                        <?php if ($login_validity == 1) {
                            ?>
                            <div class="ep_flex">
                                <label for="">নাম</label>
                                <p><?= $student_name ?></p>
                            </div>

                            <div class="ep_flex">
                                <label for="">ইমেইল</label>
                                <p><?= $student_email ?></p>
                            </div>

                            <div class="ep_flex">
                                <label for="">ফোন নম্বর</label>
                                <p><?= $student_phone ?></p>
                            </div>
                            
                            <input type="hidden" id="" name="student_name" value="<?= $student_name ?>">
                            <input type="hidden" id="" name="student_email" value="<?= $student_email ?>">
                            <input type="hidden" id="" name="student_phone" value="<?= $student_phone ?>">
                            <?php 
                        } else {
                            ?>
                            <div>
                                <label for="checkout-name">আপনার নাম *</label>
                                <input type="text" id="checkout-name" name="name" placeholder="আপনার নাম">
                            </div>

                            <div>
                                <label for="checkout-email">আপনার ইমেইল *</label>
                                <input type="text" id="checkout-email" name="email" placeholder="আপনার ইমেইল">
                            </div>

                            <div>
                                <label for="checkout-phone">আপনার ফোন নম্বর *</label>
                                <input type="text" id="checkout-phone" name="phone" minlength="11" maxlength="11" placeholder="আপনার ফোন নম্বর">
                            </div>
                            <?php 
                        }?>
                        
                        <!-- ARRAY INPUT -->
                        <input type="hidden" name="item_id[]" id="" value="<?= $chapter_id ?>">
                        <input type="hidden" name="price[]" id="" value="<?= $subtotal ?>">
                        <input type="hidden" name="purchase_item" id="" value="2">

                        <!-- TRANSACTION DETAILS INPUT -->
                        <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
                        <input type="hidden" name="bkash_charge" value="<?= $bkash_charge ?>">
                        <input type="hidden" name="grant_total" value="<?= $grant_total ?>">

                        <button type="submit" name="checkout" class="w_100 mt_75">পেমেন্ট করুন</button>
                    </div>
                </div>
            </div>
        </section>
    </form>

    <!--=========== RELATED CHAPTER ===========-->
    <section class="course_section hc_section">
        <div class="hc_container">
            <h4 class="related_course_title">আমাদের অন্যান্য অধ্যায় সমূহ</h4>
        </div>

        <div class="course_container hc_container ep_grid">
            <?php // all chapter
            $select_related_chapter  = "SELECT * FROM hc_chapter WHERE subject = '$chapter_subject' AND id != '$chapter_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC LIMIT 3";
            $sql_related_chapter     = mysqli_query($db, $select_related_chapter);
            $num_related_chapter     = mysqli_num_rows($sql_related_chapter);
            if ($num_related_chapter > 0) {
                $i = 0;
                while ($row_related_chapter = mysqli_fetch_assoc($sql_related_chapter)) {
                    $related_chapter_id             = $row_related_chapter['id'];
                    $related_chapter_name           = $row_related_chapter['chapter'];
                    $related_chapter_subject        = $row_related_chapter['subject'];
                    $related_chapter_price          = $row_related_chapter['price'];
                    $related_chapter_sale           = $row_related_chapter['sale_price'];
                    $related_chapter_cover_photo    = $row_related_chapter['cover_photo'];
                    $related_chapter_status         = $row_related_chapter['status'];
                    $related_chapter_author         = $row_related_chapter['author'];
                    $related_chapter_created_date   = $row_related_chapter['created_date'];

                    // connected with chapter
                    $select_chapter_lecture = "SELECT COUNT(id) as chapter_lecture FROM hc_chapter_lecture WHERE chapter = '$related_chapter_id' AND is_delete = 0";
                    $sql_chapter_lecture = mysqli_query($db, $select_chapter_lecture);
                    $row_chapter_lecture = mysqli_fetch_assoc($sql_chapter_lecture);
                    $chapter_lecture = $row_chapter_lecture['chapter_lecture'];

                    // fetch subject
                    $select_subject  = "SELECT * FROM hc_subject WHERE id = '$related_chapter_subject' AND is_delete = 0";
                    $sql_subject     = mysqli_query($db, $select_subject);
                    $num_subject     = mysqli_num_rows($sql_subject);
                    if ($num_subject > 0) {
                        $row_subject = mysqli_fetch_assoc($sql_subject);
                    }?>
                    <!-- CHAPTER CARD -->
                    <div class="chapter_card">
                        <div class="chapter_content">
                            <?php $related_chapter_cover_photo = substr($related_chapter_cover_photo, 2); ?>
                            <img src="<?= $base_url ?>admin<?php echo $related_chapter_cover_photo; ?>" alt="">
                        </div>

                        <div class="chapter_data">
                            <h1 class="chapter_title"><?php echo $related_chapter_name; ?></h1>

                            <div class="ep_flex mb_75">
                                <div class="ep_flex ep_start text_light">
                                    <i class='bx bx-message-square-detail'></i>
                                    <?= $chapter_lecture ?> টি লেসন্স
                                </div>

                                <div class="success"><?php if ($row_subject['subject']  == 'Botany') {
                                    echo 'উদ্ভিদবিজ্ঞান';
                                } elseif ($row_subject['subject']  == 'Zoology') {
                                    echo 'প্রাণিবিজ্ঞান';
                                }?></div>
                            </div>

                            <div class="ep_flex ep_end mb_75 mt_75">
                                <?php if ($related_chapter_sale > 0) {
                                    ?>
                                    <span class="text_strike text_light text_sm">৳<?= $related_chapter_price ?></span>
                                    <span class="text_lg">৳<?= $related_chapter_sale ?></span>
                                    <?php 
                                } else {
                                    ?>
                                    <span class="text_lg">৳<?= $related_chapter_price ?></span>
                                    <?php 
                                }?>
                            </div>

                            <a href="<?= $base_url ?>single-chapter/?chapter=<?= $related_chapter_id ?>" class="button w_100 no_hover mt_75">অধ্যায়ভিত্তিক এনরোল করুন</a>
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
        window.location.href = '<?= $base_url ?>all-chapter/';
    </script>
    <?php 
}?>

<?php include('../assets/includes/footer.php'); ?>