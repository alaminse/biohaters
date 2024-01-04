<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 3 || $admin_role == 6 || $admin_role == 7 || $admin_role == 8) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php // ADD COURSE 
$alert = '';
if (isset($_POST['add'])) {
    if(isset($_POST['day'])) {
        $selectedDays = $_POST['day'];
        if (!empty($selectedDays)) {
            $formattedDays = implode(", ", $selectedDays);
        }
    }

    $time_start = date("h:i A", strtotime($_POST['time_start']));
    $time_end = date("h:i A", strtotime($_POST['time_end']));

    $times              = $time_start.'-'.$time_end;
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

    $created_date = date('Y-m-d H:i:s', time());

    if (($type == '') || empty($name) || ($price == '') || empty($duration_number) || empty($expired_number) || empty($duration_type) || empty($expired_type) || empty($category) || empty($day) || empty($times) || empty($cover_pic)) {
        $alert = "<p class='warning mb_75'>Required Fields.....</p>";
    } else {
        if ($price < $sale_price) {
            $alert = "<p class='warning mb_75'>Sale price must be lower than Regular price.....</p>";
        } else {
            $array_img = explode('.', $cover_pic);
            $extension_img = end($array_img);

            if ($extension_img == 'jpg' || $extension_img == 'png') {
                if ($cover_pic_size <= 130000) {
                    $random_prev = rand(0, 999999);
                    $random = rand(0, 999999);
                    $random_next = rand(0, 999999);
                    $time = date('Ymdhis');

                    $final_img = "../assets/course_photo/hc_".$random_prev."_".$random."_".$random_next."_".$time."_".$cover_pic;

                    move_uploaded_file($cover_pic_tmp, $final_img);

                    // duration
                    if ($duration_number > 1) {
                        $duration_type = $duration_type."s";
                    }

                    $duration = $duration_number." ".$duration_type;

                    // expired time converted in second
                    if ($expired_number > 1) {
                        $expired_type = $expired_type."s";
                    }

                    // expired text
                    $expired_txt = $expired_number." ".$expired_type;
                    $now_in_second = strtotime($created_date);
                    $inputed_expired = strtotime(date('Y-m-d H:i:s', strtotime("+$expired_txt")));
                    
                    $expired_date = $inputed_expired - $now_in_second;

                    // add post
                    $add = "INSERT INTO hc_course (name, type, category, day_schedule, time_schedule, trailer, status, tags, description, price, sale_price, duration, expired_date, cover_photo, author, created_date) VALUES ('$name', '$type', '$category', '$day', '$times', '$trailer', '$status', '$tags', '$des', '$price', '$sale_price', '$duration', '$expired_date', '$final_img', '$admin_id', '$created_date')";
                    $sql_add = mysqli_query($db, $add);
                    if ($sql_add) {
                        ?>
                        <script type="text/javascript">
                            window.location.href = '../course/';
                        </script>
                        <?php 
                    }
                } else {
                    $alert = '<p class="danger mb_75">Cover Photo should be under 60KB</p>';
                }
            } else {
                $alert = '<p class="danger mb_75">Give PNG or JPG file</p>';
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
            <!--========== ADD COURSE ==========-->
            <div class="add_category">
                <h5 class="box_title">Add Course</h5>

                <?php echo $alert; ?>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Course Name*</label>
                        <input type="text" id="" name="name" placeholder="Course Name">
                    </div>

                    <div>
                        <label for="">Type*</label>
                        <select id="" name="type">
                            <option value="">Choose Type</option>
                            <option value="1">Online</option>
                            <option value="0">Offline</option>
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

                                    echo '<option value="'.$category_id.'">'.$category_name.'</option>';
                                }
                            }?>
                        </select>
                    </div>

                    <div>
                        <label for="">Day Schedule*</label>
                        <select name="day[]" id="js-example-basic-hide-search-multi" multiple="multiple">
                            <?php $select = "SELECT * FROM hc_day WHERE is_delete = 0 ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $day_id     = $row['id'];
                                    $day_name   = $row['day_to_day'];

                                    echo '<option value="'.$day_name.'">'.$day_name.'</option>';
                                }
                            }?>
                        </select>
                    </div>

                    <div class="time-schedule">
                        <label for="">Time Schedule*</label>
                        <div class="time-inputs">
                            <input type="time" name="time_start">
                            <input type="time" name="time_end">
                        </div>
                    </div>


                    <div>
                        <label for="">Status</label>
                        <select id="" name="status">
                            <option value="0">Choose Status</option>
                            <option value="1">Published</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>

                    <div>
                        <label for="">Course Tags</label>
                        <input type="text" id="" name="tags" placeholder="Course Tags">
                    </div>

                    <div>
                        <label for="">Course Price*</label>
                        <input type="text" id="" name="price" placeholder="Course Price">
                    </div>

                    <div>
                        <label for="">Course Sale Price</label>
                        <input type="text" id="" name="sale_price" placeholder="Course Sale Price">
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
                        <label for="">Course Trailer</label>
                        <input type="text" id="" name="trailer" placeholder="Course Trailer">
                    </div>

                    <div>
                        <label for="">Course Cover Photo* (360*200px, max: 60kb)</label>
                        <input type="file" id="" name="cover_pic" class="input_sm">
                    </div>

                    <div class="grid_col_3">
                        <label for="">Course Description</label>
                        <textarea id="" name="des" placeholder="Course Description" rows="4"></textarea>
                    </div>

                    <button type="submit" name="add">Add Course</button>
                </form>
            </div>
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