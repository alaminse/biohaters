<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php // EDIT COURSE CATEGORY
$alert = '';
if (isset($_POST['edit'])) {

    if(isset($_POST['day'])) {
        $selectedDays = $_POST['day'];
        if (!empty($selectedDays)) {
            $formattedDays = implode(", ", $selectedDays);
        }
    }

    $time_start = date("h:i A", strtotime($_POST['time_start']));
    $time_end = date("h:i A", strtotime($_POST['time_end']));
    
    $times              = $time_start.'-'.$time_end;
    $course_id          = mysqli_escape_string($db, $_POST['id']);
    $name               = mysqli_escape_string($db, $_POST['name']);
    $tags               = mysqli_escape_string($db, $_POST['tags']);
    $price              = mysqli_escape_string($db, $_POST['price']);
    $sale_price         = mysqli_escape_string($db, $_POST['sale_price']);
    $trailer            = mysqli_escape_string($db, $_POST['trailer']);
    $des                = mysqli_escape_string($db, $_POST['des']);
    $duration_number    = mysqli_escape_string($db, $_POST['duration_number']);
    $expired_number     = mysqli_escape_string($db, $_POST['expired_number']);
    $duration_type      = $_POST['duration_type'];
    $expired_type       = $_POST['expired_type'];
    $type               = $_POST['type'];
    $category           = $_POST['category'];
    $day                = $formattedDays;
    // $times              = $_POST['time'];
    $status             = $_POST['status'];
    $cover_pic          = $_FILES['cover_pic']['name'];
    $cover_pic_tmp      = $_FILES['cover_pic']['tmp_name'];
    $cover_pic_size     = $_FILES['cover_pic']['size'];


    // now time
    $created_date = date('Y-m-d H:i:s', time());

    // cover duration
    $previous_duration = mysqli_escape_string($db, $_POST['course_duration']);

    // cover expired time
    $previous_expired = mysqli_escape_string($db, $_POST['course_expired']);

    // cover unlink path
    $previous_cover_photo = mysqli_escape_string($db, $_POST['course_cover_photo']);

    if (empty($name) || ($price == '') || ($type == '') || empty($category) || empty($day) || empty($times)) {
        $alert = "<p class='warning mb_75'>Required Fields.....</p>";
    } else {
        if ($price < $sale_price) {
            $alert = "<p class='warning mb_75'>Sale price must be lower than Regular price.....</p>";
        } else {
            // duration selection logic
            if (!empty($duration_number) && !empty($duration_type)) {
                // duration
                if ($duration_number > 1) {
                    $duration_type = $duration_type."s";
                }

                $duration = $duration_number." ".$duration_type;
            } else {
                // duration
                $duration = $previous_duration;
            }

            // expired time selection logic
            if (!empty($expired_number) && !empty($expired_type)) {
                // expired time converted in second
                if ($expired_number > 1) {
                    $expired_type = $expired_type."s";
                }

                // expired text
                $expired_txt = $expired_number." ".$expired_type;
                $now_in_second = strtotime($created_date);
                $inputed_expired = strtotime(date('Y-m-d H:i:s', strtotime("+$expired_txt")));

                $expired_date = $inputed_expired - $now_in_second;
            } else {
                $expired_date = $previous_expired;
            }

            // cover photo selection logic
            if (!empty($cover_pic)) {
                $array_img = explode('.', $cover_pic);
                $extension_img = end($array_img);

                if ($extension_img == 'jpg' || $extension_img == 'png') {
                    if ($cover_pic_size <= 60000) {
                        $random_prev = rand(0, 999999);
                        $random = rand(0, 999999);
                        $random_next = rand(0, 999999);
                        $time = date('Ymdhis');

                        $final_img = "../assets/course_photo/hc_".$random_prev."_".$random."_".$random_next."_".$time."_".$cover_pic;

                        move_uploaded_file($cover_pic_tmp, $final_img);

                        // delete previous cover photo
                        unlink($previous_cover_photo);
                    } else {
                        $alert = '<p class="danger mb_75">Cover Pic should be under 60KB</p>';
                        $final_img = $previous_cover_photo;
                    }
                } else {
                    $alert = '<p class="danger mb_75">Give PNG or JPG file</p>';
                    $final_img = $previous_cover_photo;
                }
            } else {
                $final_img = $previous_cover_photo;
            }

            // add post
            $update = "UPDATE hc_course SET name = '$name', type = '$type', category = '$category', day_schedule = '$day', time_schedule = '$times', trailer = '$trailer', status = '$status', tags = '$tags', description = '$des', price = '$price', sale_price = '$sale_price', duration = '$duration', expired_date = '$expired_date', cover_photo = '$final_img' WHERE id = '$course_id'";
            $sql_update = mysqli_query($db, $update);
            if ($sql_update) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../course/';
                </script>
                <?php 
            }
        }
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Course</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== EDIT COURSE ==========-->
            <?php echo $alert; ?>
            <?php if (isset($_POST['edit_course'])) {
                $edit_id = $_POST['edit_id'];

                $select = "SELECT * FROM hc_course WHERE id = '$edit_id'";
                $sql = mysqli_query($db, $select);
                $row = mysqli_fetch_assoc($sql);

                // print_r($row['day_schedule']);

                $course_id              = $row['id'];
                $course_name            = $row['name'];
                $course_des             = $row['description'];
                $course_type            = $row['type'];
                $course_category        = $row['category'];
                $course_day_schedule    = $row['day_schedule'];
                // $course_time_schedule   = $row['time_schedule'];
                $course_status          = $row['status'];
                $course_tags            = $row['tags'];
                $course_price           = $row['price'];
                $course_sale            = $row['sale_price'];
                $course_trailer         = $row['trailer'];
                $course_duration        = $row['duration'];
                $course_expired         = $row['expired_date'];
                $course_cover_photo     = $row['cover_photo'];
                $course_author          = $row['author'];
                
                $time_parts = explode("-", $row['time_schedule']);
                $start_time = trim($time_parts[0]);
                $end_time = trim($time_parts[1]);

                ?>
                <!--========== EDIT BLOG ==========-->
                <div class="add_category">
                    <h5 class="box_title">Edit Course</h5>

                    <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                        <div>
                            <label for="">Course Name*</label>
                            <input type="text" id="" name="name" placeholder="Course Name" value="<?php echo $course_name; ?>">
                        </div>

                        <div>
                            <label for="">Type*</label>
                            <select id="" name="type">
                                <option value="">Choose Type</option>
                                <option value="1" <?php if ($course_type == 1) {echo "selected";} ?>>Online</option>
                                <option value="0" <?php if ($course_type == 0) {echo "selected";} ?>>Offline</option>
                            </select>
                        </div>

                        <div>
                            <label for="">Category*</label>
                            <select id="" name="category">
                                <option value="">Choose Category</option>
                                <?php $select = "SELECT * FROM hc_course_category WHERE is_delete = 0 ORDER BY id DESC";
                                $sql = mysqli_query($db, $select);
                                $num = mysqli_num_rows($sql);
                                if ($num > 0) {
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        $category_id     = $row['id'];
                                        $category_name   = $row['name'];
                                        ?>
                                        <option value="<?php echo $category_id; ?>" <?php if ($course_category == $category_id) {echo "selected";}?>><?php echo $category_name; ?></option>
                                        <?php 
                                    }
                                }?>
                            </select>
                        </div>

                        <div>
                            <label for="">Day Schedule*</label>
                            <select name="day[]" id="js-example-basic-hide-search-multi" multiple="multiple">
                                <?php 
                                $selectedDays = explode(", ", $course_day_schedule);
                                $select = "SELECT * FROM hc_day WHERE is_delete = 0 ORDER BY id DESC";
                                $sql = mysqli_query($db, $select);
                                $num = mysqli_num_rows($sql);
                                if ($num > 0) {
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        $day_id     = $row['id'];
                                        $day_name   = $row['day_to_day'];
                                        $selected = (in_array($day_name, $selectedDays)) ? 'selected' : '';
                                        echo '<option value="' . $day_name . '" ' . $selected . '>' . $day_name . '</option>';
                                    }
                                }?>
                            </select>
                        </div>
                        <div class="time-schedule">
                            <label for="">Time Schedule* </label>
                            <div class="time-inputs">
                                <input type="time" name="time_start" value="<?php echo date('H:i', strtotime($start_time)); ?>">
                                <input type="time" name="time_end" value="<?php echo date('H:i', strtotime($end_time)); ?>">
                            </div>
                        </div>

                        <div>
                            <label for="">Status</label>
                            <select id="" name="status">
                                <option value="0">Choose Status</option>
                                <option value="1" <?php if ($course_status == 1) {echo "selected";} ?>>Published</option>
                                <option value="0" <?php if ($course_status == 0) {echo "selected";} ?>>Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="">Course Tags*</label>
                            <input type="text" id="" name="tags" placeholder="Course Tags" value="<?php echo $course_tags; ?>">
                        </div>

                        <div>
                            <label for="">Course Price*</label>
                            <input type="text" id="" name="price" placeholder="Course Price" value="<?php echo $course_price; ?>">
                        </div>

                        <div>
                            <label for="">Course Sale Price</label>
                            <input type="text" id="" name="sale_price" placeholder="Course Sale Price" value="<?php echo $course_sale; ?>">
                        </div>

                        <div>
                            <label for="">Duration*</label>
                            <div class="ep_grid grid_2">
                                <input type="text" id="" name="duration_number" placeholder="Duration Number">
                                <select id="" name="duration_type">
                                    <option value="">Choose Duration Type</option>
                                    <option value="Day">Day</option>
                                    <option value="Week">Week</option>
                                    <option value="Month">Month</option>
                                    <option value="Year">Year</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="">Expired Time*</label>
                            <div class="ep_grid grid_2">
                                <input type="text" id="" name="expired_number" placeholder="Expired Number">
                                <select id="" name="expired_type">
                                    <option value="">Choose Expired Type</option>
                                    <option value="day">Day</option>
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                    <option value="year">Year</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="">Course Trailer*</label>
                            <input type="text" id="" name="trailer" placeholder="Course Trailer" value="<?php echo $course_trailer; ?>">
                        </div>

                        <div>
                            <label for="">Course Cover Photo* (360*200px, max: 60kb)</label>
                            <input type="file" id="" name="cover_pic" class="input_sm">
                        </div>

                        <div class="grid_col_3">
                            <label for="">Course Description*</label>
                            <textarea id="" name="des" placeholder="Course Description" rows="4"><?php echo $course_des; ?></textarea>
                        </div>

                        <!-- get previous duration -->
                        <input type="hidden" name="course_duration" value="<?php echo $course_duration; ?>">

                        <!-- get previous expired value -->
                        <input type="hidden" name="course_expired" value="<?php echo $course_expired; ?>">

                        <!-- get previous cover photo to unlink easily -->
                        <input type="hidden" name="course_cover_photo" value="<?php echo $course_cover_photo; ?>">
                        
                        <!-- get id -->
                        <input type="hidden" name="id" value="<?php echo $course_id; ?>">

                        <button type="submit" name="edit">Edit Course</button>
                    </form>
                </div>
                <?php 
            }?>
        </div>
    </div>
</main>

<!--========== CKEDITOR JS =============-->
<script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#js-example-basic-hide-search-multi').select2();

        // Disable search field on opening/closing of Select2 dropdown
        $('#js-example-basic-hide-search-multi').on('select2:opening select2:closing', function(event) {
            var $searchfield = $(this).parent().find('.select2-search__field');
            $searchfield.prop('disabled', true);
        });
    });
</script>
<script>
/*========== POST DESCRIPTION CKEDITOR =============*/
    CKEDITOR.replace( 'des' );
</script>


<style>
    .time-inputs {
        display: flex !important;
        align-items: center;
    }

    .time-inputs input {
        width: 50%;
        margin-right: 1em;
    }
</style>

<?php include('../assets/includes/footer.php'); ?>