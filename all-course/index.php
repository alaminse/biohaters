<?php include('../assets/includes/header.php'); ?>

<!--=========== COMMON SECTION ===========-->
<section class="common_section hc_section">
    <div class="hc_container text_center">
        <h1 class="common_section_title">সকল কোর্স সমূহ</h1>
    </div>
</section>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['course_category'])) {
        $selectedCategoryId = $_POST['course_category'];

        if($selectedCategoryId == 'Academic & Medical')
        {
            $result['all_course'] = $result['all_course'];
        } else {
            $select_course = "SELECT * FROM hc_course WHERE category = '$selectedCategoryId' AND is_delete = 0";
            $sql_course = mysqli_query($db, $select_course);

            $result['all_course'] = array(); // Initialize an empty array to store multiple rows

            if ($sql_course) {
                while ($row = mysqli_fetch_assoc($sql_course)) {
                    $result['all_course'][] = $row; // Append each row to the result array
                }
            } else {
                // Handle the case where the query fails
                $result['all_course'] = [];
            }
        }
    }
}
?>

<!--=========== COURSE ===========-->
<section class="course_section hc_section">
    <div class="all_course_container hc_container ep_grid">
        <!-- filter -->
        <div class="filter_container">
            <!-- HTML form -->
            <form action="" method="post" class="filter_form">
                <!-- Your radio button code -->
                <div class="radio_grid">
                    <?php foreach ($result['all_course_category'] as $all_course_category) { ?>
                        <label for="filter-<?= $all_course_category['id'] ?>" class="checkbox_label">
                            <input type="radio" class="checkbox" name="course_category" id="filter-<?= $all_course_category['id'] ?>" value="<?= $all_course_category['id'] ?>">
                            <?php
                            if ($all_course_category['name'] == 'Medical') {
                                echo 'মেডিকেল';
                            } elseif ($all_course_category['name'] == 'Academic') {
                                echo 'একাডেমিক';
                            } elseif ($all_course_category['name'] == 'Academic & Medical') {
                                echo 'মেডিকেল এবং একাডেমিক';
                            }
                            ?>
                        </label>
                    <?php } ?>
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>

        <!-- course grid -->
        <div class="course_grid_container ep_grid">
            <?php // course
            foreach ($result['all_course'] as $key => $all_course) {
                // course id
                $course_id = $all_course['id'];

                // connected with course
                $select_course_lecture = "SELECT COUNT(id) as course_lecture FROM hc_course_lecture WHERE course = '$course_id' AND is_delete = 0";
                $sql_course_lecture = mysqli_query($db, $select_course_lecture);
                $row_course_lecture = mysqli_fetch_assoc($sql_course_lecture);
                $course_lecture = $row_course_lecture['course_lecture'];
                ?>
                <!-- COURSE CARD -->
                <div class="course_card">
                    <div class="course_content">
                        <?php $all_course_cover_photo = substr($all_course['cover_photo'], 2); ?>
                        <img src="<?= $base_url ?>admin<?php echo $all_course_cover_photo; ?>" alt="">
                    </div>

                    <div class="course_data">
                        <h1 class="course_title"><?= $all_course['name'] ?></h1>

                        <div class="ep_flex mb_75 course_widget">
                            <div class="ep_flex ep_start text_light">
                                <i class='bx bx-time-five'></i>
                                <?= $all_course['time_schedule'] ?>
                            </div>

                            <div class="ep_flex ep_start text_light">
                                <i class='bx bx-message-square-detail'></i>
                                <?= $course_lecture ?> টি লেসন্স
                            </div>
                        </div>

                        <div class="ep_flex mb_75 mt_75 course_price">
                            <?php $course_category = $all_course['category'];
                            $select_course_category = "SELECT * FROM hc_course_category WHERE id = '$course_category' AND is_delete = 0";
                            $sql_course_category    = mysqli_query($db, $select_course_category);
                            $num_course_category    = mysqli_num_rows($sql_course_category);
                            if ($num_course_category > 0) {
                                $row_course_category = mysqli_fetch_assoc($sql_course_category);
                                $course_category_id     = $row_course_category['id'];
                                $course_category_name   = $row_course_category['name'];
                                ?>
                                <div class="success course_widget"><?php if ($course_category_name  == 'Medical') {
                                    echo 'মেডিকেল';
                                } elseif ($course_category_name  == 'Academic') {
                                    echo 'একাডেমিক';
                                } elseif ($course_category_name  == 'Academic & Medical') {
                                    echo 'মেডিকেল এবং একাডেমিক';
                                }?></div>
                                <?php 
                            }?>

                            <div class="ep_flex ep_start">
                                <?php if ($all_course['sale_price'] > 0) {
                                    ?>
                                        <span class="text_strike text_light text_sm course_strike_price">৳<?= $all_course['price'] ?></span>
                                    <?php
                                    
                                    $full_course = [1, 2, 3];
                                    $courseIds = implode("','", $full_course);
                                    $is_eleventh_h = [];
                                    if ($login_validity == 1 && $course_id == 15) {
                                        // Query to retrieve course enrollments
                                        // $query = "SELECT * FROM hc_purchase_details WHERE purchase_item = 1 AND student_id = $student_id AND item_id = '$full_course'";
                                        // $result = mysqli_query($db, $query);
                                        $query = "SELECT * FROM hc_purchase_details WHERE purchase_item = 1 AND student_id = $student_id AND item_id IN ('$courseIds')";
                                        $result = mysqli_query($db, $query);
                                    
                                        if ($result) {
                                            $num_rows = mysqli_num_rows($result);
                                            if ($num_rows > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $all_course['sale_price'] = 0;
                                                }
                                            } else {
                                                $query = "SELECT * FROM hc_secret_file_entry WHERE student_id = $student_id AND status = 1 AND is_expired = 0";
                                                $result = mysqli_query($db, $query);
                                                if ($result) {
                                                    $num_rows = mysqli_num_rows($result);
                                                    
                                                    if ($num_rows > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $is_secrate_file = 'exist';
                                                            $all_course['sale_price'] = floor($all_course['sale_price']/2);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        
                                    }
                                    ?>
                                        <span class="text_lg course_reg_price">৳<?= $all_course['sale_price'] ?></span>
                                    <?php 
                                } else {
                                    ?>
                                    <span class="text_lg course_reg_price">৳<?= $all_course['price'] ?></span>
                                    <?php 
                                }?>
                            </div>
                        </div>

                        <a href="<?= $base_url ?>course-details/?course=<?= $course_id ?>" class="button w_100 no_hover mt_75 course_btn">এনরোল করুন</a>
                    </div>
                </div>
                <?php 
            }?>
        </div>
    </div>
</section>
<!-- <script>
        // JavaScript
        const radioButtons = document.querySelectorAll('.checkbox');

        // Add event listeners to radio buttons
        radioButtons.forEach(button => {
            button.addEventListener('change', (event) => {
                // When a radio button is selected, submit the form
                event.target.closest('form').submit();
            });
        });
    </script> -->
<?php include('../assets/includes/footer.php'); ?>

