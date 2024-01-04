<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['course'])) {
    $course_id = $_GET['course'];
    // total price variable
    $total_price = 0;
    $is_eleventh_h_price = null;
    $is_secrate_file = null;

    // fetch courses
    $select_course  = "SELECT * FROM hc_course WHERE id = '$course_id' AND id != '1' AND type = 1 AND status = 1 AND is_delete = 0 ORDER BY id DESC";
    $sql_course     = mysqli_query($db, $select_course);
    $num_course     = mysqli_num_rows($sql_course);
    if ($num_course > 0) {
        $i = 0;
        while ($row_course = mysqli_fetch_assoc($sql_course)) {
            $course_id              = $row_course['id'];
            $course_name            = $row_course['name'];
            $course_type            = $row_course['type'];
            $course_category        = $row_course['category'];
            $course_day_schedule    = $row_course['day_schedule'];
            $course_time_schedule   = $row_course['time_schedule'];
            $course_trailer         = $row_course['trailer'];
            $course_status          = $row_course['status'];
            $course_tags            = $row_course['tags'];
            $course_des             = $row_course['description'];
            $course_price           = $row_course['price'];
            $course_sale            = $row_course['sale_price'];
            $course_duration        = $row_course['duration'];
            $course_expired_date    = $row_course['expired_date'];
            $course_cover_photo     = $row_course['cover_photo'];
            $course_author          = $row_course['author'];
            $course_created_date    = $row_course['created_date'];

            if ($course_sale > 0) {
                $exact_price = $course_sale;
            } else {
                $exact_price = $course_price;
            }

            // connected with course
            $select_course_lecture = "SELECT COUNT(id) as course_lecture FROM hc_course_lecture WHERE course = '$course_id' AND is_delete = 0";
            $sql_course_lecture = mysqli_query($db, $select_course_lecture);
            $row_course_lecture = mysqli_fetch_assoc($sql_course_lecture);
            $course_lecture = $row_course_lecture['course_lecture'];

            // course category
            $select_course_category = "SELECT * FROM hc_course_category WHERE id = '$course_category' AND is_delete = 0";
            $sql_course_category    = mysqli_query($db, $select_course_category);
            $num_course_category    = mysqli_num_rows($sql_course_category);
            if ($num_course_category > 0) {
                $row_course_category = mysqli_fetch_assoc($sql_course_category);
                $course_category_id     = $row_course_category['id'];
                $course_category_name   = $row_course_category['name'];
            }
            
            // course enroll
            $select_course_enroll = "SELECT * FROM hc_purchase_details WHERE purchase_item = 1 AND item_id = '$course_id'";
            $sql_course_enroll    = mysqli_query($db, $select_course_enroll);
            $num_course_enroll    = mysqli_num_rows($sql_course_enroll);

            $full_course = [1, 2, 3];
            $courseIds = implode("','", $full_course);
            $is_eleventh_h = [];
            if ($login_validity == 1 && $course_id == 15) {
                // Query to retrieve course enrollments
                $query = "SELECT * FROM hc_purchase_details WHERE purchase_item = 1 AND student_id = $student_id AND item_id IN ('$courseIds')";
                $result = mysqli_query($db, $query);
                if ($result) {
                    $num_rows = mysqli_num_rows($result);
                    
                    if ($num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $is_eleventh_h_price = 'exist';
                            $exact_price = 0;
                        }
                    } else {
                        $query = "SELECT * FROM hc_secret_file_entry WHERE student_id = $student_id AND status = 1 AND is_expired = 0";
                        $result = mysqli_query($db, $query);
                        
                        if ($result) {
                            $num_rows = mysqli_num_rows($result);
                            
                            if ($num_rows > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $is_secrate_file = 'exist';
                                    $exact_price = floor($exact_price/2);
                                }
                            }
                        }
                    }
                }
            }
            $total_price += $exact_price;
            
        }
    }
    ?>
    <!--=========== COMMON SECTION ===========-->
    <section class="common_section hc_section">
        <div class="hc_container course_details_common_container">
            <div>
                <h1 class="common_section_title"><?= $course_name ?></h1>

                <div class="ep_flex ep_start gap_1_25 mt_75">
                    <div class="ep_flex ep_start text_light">
                        ক্যাটাগরিঃ <?php if ($course_category_name  == 'Medical') {
                            echo 'মেডিকেল';
                        } elseif ($course_category_name  == 'Academic') {
                            echo 'একাডেমিক';
                        } elseif ($course_category_name  == 'Academic & Medical') {
                            echo 'মেডিকেল এবং একাডেমিক';
                        }?>
                    </div>
                    
                    <div class="ep_flex ep_start text_light">
                        কোর্স করছেনঃ 
                        <?= $num_course_enroll * 3 ?> জন
                    </div>
                </div>

                <div class="ep_flex ep_start gap_1_25 mt_75">
                    <div class="ep_flex ep_start text_light">
                        <i class='bx bx-time-five'></i>
                        <?= $course_time_schedule ?>
                    </div>

                    <div class="ep_flex ep_start text_light">
                        <i class='bx bx-message-square-detail'></i>
                        <?= $course_lecture ?> টি লেসন্স
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--=========== COURSE ===========-->
    <section class="course_details_section hc_section">
        <div class="course_details_container hc_container ep_grid">
            <!-- COURSE DETAILS -->
            <div class="course_details_content ep_grid">
                <?php // course module
                $select_course_module = "SELECT * FROM hc_module WHERE course = '$course_id' AND is_delete = 0 ORDER BY id ASC";
                $sql_course_module    = mysqli_query($db, $select_course_module);
                $num_course_module    = mysqli_num_rows($sql_course_module);
                if ($num_course_module > 0) {
                    ?>
                    <div class="accordion" id="course-details-accordion">
                        <?php while($row_course_module = mysqli_fetch_assoc($sql_course_module)) {
                            $course_module_id     = $row_course_module['id'];
                            $course_module_name   = $row_course_module['name'];

                            // fetch module duration
                            $select_module_duration = "SELECT SUM(duration) as total_module_duration FROM hc_course_lecture WHERE module = '$course_module_id' AND status = 1 AND is_delete = 0";
                            $sql_module_duration = mysqli_query($db, $select_module_duration);
                            $num_module_duration = mysqli_num_rows($sql_module_duration);
                            if ($num_module_duration > 0) {
                                $row_module_duration = mysqli_fetch_assoc($sql_module_duration);
                            }
                            ?>
                            <div class="accordion-item hc_accordion_item">
                                <h2 class="accordion-header" id="module-heading-<?= $course_module_id ?>">
                                    <button class="accordion-button w_100 " type="button" data-bs-toggle="collapse" data-bs-target="#module-<?= $course_module_id ?>" aria-expanded="true" aria-controls="module-<?= $course_module_id ?>">
                                        <?= $course_module_name ?>
                                        <span class="ep_flex ep_start">
                                            <div class="module_duratrion">
                                                <?php if ($row_module_duration['total_module_duration'] > 0) {
                                                    echo gmdate('H:i:s', $row_module_duration['total_module_duration']);
                                                }?>
                                            </div>

                                            <i class='bx bxs-chevron-down'></i>
                                        </span>
                                    </button>
                                </h2>
                                <div id="module-<?= $course_module_id ?>" class="accordion-collapse collapse" aria-labelledby="module-heading-<?= $course_module_id ?>" data-bs-parent="#course-details-accordion">
                                    <div class="accordion-body ep_grid">
                                        <?php // fetch lectures
                                        $select_course_lecture = "SELECT * FROM hc_course_lecture WHERE module = '$course_module_id' AND status = 1 AND is_delete = 0 ORDER BY id ASC";
                                        $sql_course_lecture = mysqli_query($db, $select_course_lecture);
                                        $num_course_lecture = mysqli_num_rows($sql_course_lecture);
                                        if ($num_course_lecture > 0) {
                                            while ($row_course_lecture = mysqli_fetch_assoc($sql_course_lecture)) {
                                                $course_lecture_id = $row_course_lecture['id'];
                                                $course_lecture_name = $row_course_lecture['name'];
                                                $course_lecture_duration = $row_course_lecture['duration'];
                                                $course_lecture_free = $row_course_lecture['is_free'];
                                                if ($course_lecture_free == 1) {
                                                    ?>
                                                    <a href="<?= $base_url ?>free-trial/" class="course_details_lecture_active">
                                                        <div class="ep_flex gap_1_25">
                                                            <div class="ep_flex ep_start">
                                                                <i class='bx bx-play-circle'></i>
                                                                <?= $course_lecture_name ?>
                                                            </div>

                                                            <div class="">
                                                                <?= gmdate('H:i:s', $course_lecture_duration); ?>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <?php 
                                                } else {
                                                    ?>
                                                    <div class="ep_flex gap_1_25">
                                                        <div class="ep_flex ep_start text_light">
                                                            <i class='bx bx-lock' ></i>
                                                            <?= $course_lecture_name ?>
                                                        </div>

                                                        <div class="text_light">
                                                            <?= gmdate('H:i:s', $course_lecture_duration); ?>
                                                        </div>
                                                    </div>
                                                    <?php 
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

                <div>
                    <h3 class="course_details_title mt_75">কোর্স সম্পর্কে বিস্তারিত</h3>
                    <?= $course_des ?>
                </div>
            </div>

            <!-- COURSE CARD -->
            <div class="course_card single_course_card">
                <div class="course_content">
                    <?php $all_course_cover_photo = substr($course_cover_photo, 2); ?>
                    <img src="<?= $base_url ?>admin<?php echo $all_course_cover_photo; ?>" />
                    <!--<iframe width="100%" height="196" src="https://www.youtube.com/embed/<?= $course_trailer ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>-->
                </div>

                <div class="course_data single_course_data">
                    <div class="ep_flex ep_start grid_align_end">
                        <?php if ($course_sale > 0) {
                            if($is_eleventh_h_price === 'exist'){
                                ?>
                                <h3 class="">৳ <?= $exact_price ?></h3>
                                <p class="text_strike text_light sale_price">৳ <?= $course_price ?></p>
                                <?php 
                             } elseif($is_secrate_file === 'exist'){
                                ?>
                                    <h3 class="">৳ <?= $exact_price ?></h3>
                                    <p class="text_strike text_light sale_price">৳ <?= $course_price ?></p>
                                <?php 
                             } else {
                            ?>
                                <h3 class="">৳<?= $course_sale ?></h3>
                                <p class="text_strike text_light sale_price">৳<?= $course_price ?></p>
                            <?php }
                        } else {
                            ?>
                            <h3 class="">৳<?= $course_price ?></h3>
                            <?php 
                        }?>
                    </div>

                    <a class="button w_100 no_hover mt_75" data-modal-target="#course-details-<?= $course_id ?>">এনরোল করুন</a>

                    <div class="modal_container" id="course-details-<?= $course_id ?>">
                        <div class="modal_content">
                            <div class="modal_close button_hover close_modal" title="Close" data-close-button><i class="bx bx-x"></i></div>

                            <div class="modal_body">
                                <!-- CHECKOUT CARD -->
                                <?php // bkash charge
                                $bkash_charge = floor($total_price * 0); // 0.015

                                // grant total
                                $grant_total = $total_price + $bkash_charge;?>
                                <!-- COURSE PURCHASE FORM -->
                                <form action="<?php if ($grant_total == 0) { echo 'https://biohaters.com/purchase-free/'; } else { echo 'https://biohaters.com/purchase/'; }?>" method="post">
                                    <h4 class="single_chapter_card_title">পেমেন্ট ফর্ম</h4>

                                    <div class="payment_details">
                                        <div class="ep_flex">
                                            <p class="text_semi">কোর্স ফীঃ </p>
                                            <p>৳<?= $total_price ?></p>
                                        </div>

                                        <div class="ep_flex">
                                            <p class="text_semi">বিকাশ চার্জঃ </p>
                                            <p>৳<?= $bkash_charge ?></p>
                                        </div>

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

                                        <!-- COURSE DETAILS INPUT -->
                                        <input type="hidden" name="item_id[]" id="" value="<?= $course_id ?>">
                                        <input type="hidden" name="price[]" id="" value="<?= $exact_price ?>">
                                        <input type="hidden" name="purchase_item" id="" value="1">

                                        <!-- TRANSACTION DETAILS INPUT -->
                                        <input type="hidden" name="subtotal" value="<?= $total_price ?>">
                                        <input type="hidden" name="bkash_charge" value="<?= $bkash_charge ?>">
                                        <input type="hidden" name="grant_total" value="<?= $grant_total ?>">

                                        <button type="submit" name="checkout" class="w_100 mt_75">পেমেন্ট করুন</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--=========== RELATED COURSE ===========-->
    <section class="course_section hc_section">
        <div class="hc_container">
            <h4 class="related_course_title">আমাদের অন্যান্য কোর্স সমূহ</h4>
        </div>

        <div class="course_container hc_container ep_grid">
            <?php // all courses
            $select_related_course  = "SELECT * FROM hc_course WHERE category = '$course_category' AND id != '$course_id' AND id != '1' AND type = 1 AND status = 1 AND is_delete = 0 ORDER BY id DESC LIMIT 3";
            $sql_related_course     = mysqli_query($db, $select_related_course);
            $num_related_course     = mysqli_num_rows($sql_related_course);
            if ($num_related_course > 0) {
                $i = 0;
                while ($row_related_course = mysqli_fetch_assoc($sql_related_course)) {
                    $related_course_id              = $row_related_course['id'];
                    $related_course_name            = $row_related_course['name'];
                    $related_course_type            = $row_related_course['type'];
                    $related_course_category        = $row_related_course['category'];
                    $related_course_day_schedule    = $row_related_course['day_schedule'];
                    $related_course_time_schedule   = $row_related_course['time_schedule'];
                    $related_course_trailer         = $row_related_course['trailer'];
                    $related_course_status          = $row_related_course['status'];
                    $related_course_tags            = $row_related_course['tags'];
                    $related_course_des             = $row_related_course['description'];
                    $related_course_price           = $row_related_course['price'];
                    $related_course_sale            = $row_related_course['sale_price'];
                    $related_course_duration        = $row_related_course['duration'];
                    $related_course_expired_date    = $row_related_course['expired_date'];
                    $related_course_cover_photo     = $row_related_course['cover_photo'];
                    $related_course_author          = $row_related_course['author'];
                    $related_course_created_date    = $row_related_course['created_date'];

                    // connected with course
                    $select_course_lecture = "SELECT COUNT(id) as course_lecture FROM hc_course_lecture WHERE course = '$related_course_id' AND is_delete = 0";
                    $sql_course_lecture = mysqli_query($db, $select_course_lecture);
                    $row_course_lecture = mysqli_fetch_assoc($sql_course_lecture);
                    $course_lecture = $row_course_lecture['course_lecture'];
                    ?>
                    <!-- COURSE CARD -->
                    <div class="course_card">
                        <div class="course_content">
                            <?php $related_course_cover_photo = substr($related_course_cover_photo, 2); ?>
                            <img src="<?= $base_url ?>admin<?php echo $related_course_cover_photo; ?>" alt="">
                        </div>

                        <div class="course_data">
                            <h1 class="course_title"><?= $related_course_name ?></h1>

                            <div class="ep_flex mb_75">
                                <div class="ep_flex ep_start text_light">
                                    <i class='bx bx-time-five'></i>
                                    <?= $related_course_time_schedule ?>
                                </div>

                                <div class="ep_flex ep_start text_light">
                                    <i class='bx bx-message-square-detail'></i>
                                    <?= $course_lecture ?> টি লেসন্স
                                </div>
                            </div>

                            <div class="ep_flex mb_75 mt_75">
                                <?php $select_course_category = "SELECT * FROM hc_course_category WHERE id = '$related_course_category' AND is_delete = 0";
                                $sql_course_category    = mysqli_query($db, $select_course_category);
                                $num_course_category    = mysqli_num_rows($sql_course_category);
                                if ($num_course_category > 0) {
                                    $row_course_category = mysqli_fetch_assoc($sql_course_category);
                                    $course_category_id     = $row_course_category['id'];
                                    $course_category_name   = $row_course_category['name'];
                                    ?>
                                    <div class="success"><?php if ($course_category_name  == 'Medical') {
                                        echo 'মেডিকেল';
                                    } elseif ($course_category_name  == 'Academic') {
                                        echo 'একাডেমিক';
                                    } elseif ($course_category_name  == 'Academic & Medical') {
                                        echo 'মেডিকেল এবং একাডেমিক';
                                    }?></div>
                                    <?php 
                                }?>

                                <div class="ep_flex ep_start">
                                    <?php if ($related_course_sale > 0) {
                                        ?>
                                        <span class="text_strike text_light text_sm">৳<?= $related_course_price ?></span>
                                        <span class="text_lg">৳<?= $related_course_sale ?></span>
                                        <?php 
                                    } else {
                                        ?>
                                        <span class="text_lg">৳<?= $related_course_price ?></span>
                                        <?php 
                                    }?>
                                </div>
                            </div>

                            <a href="<?= $base_url ?>course-details/?course=<?= $related_course_id ?>" class="button w_100 no_hover mt_75">কোর্সে এনরোল করুন</a>
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
        window.location.href = '<?= $base_url ?>all-course/';
    </script>
    <?php 
}?>

<?php include('../assets/includes/footer.php'); ?>