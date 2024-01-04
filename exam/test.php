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
        <h3 class="hc_page_title">All Exam</h3>
    </div>
</section>

<!--=========== RESOURCE SECTION ===========-->
<section class="hc_section">
    <div class="hc_container mb_1_5">
        <form action="" method="get" class="double_col_form">
            <div>
                <label for="">Filter Exam by Course</label>
                <select name="filter_exam" id="">
                    <option value="">Choose Course</option>
                    <?php if (isset($result['my_courses'])) {
                        foreach ($result['my_courses'] as $key => $my_courses) {
                            // courses id
                            $my_courses_id = $my_courses['item_id'];
        
                            // fetch my course
                            $select_my_course  = "SELECT * FROM hc_course WHERE id = '$my_courses_id' AND type = 1 AND status = 1 AND is_delete = 0";
                            $sql_my_course     = mysqli_query($db, $select_my_course);
                            $num_my_course     = mysqli_num_rows($sql_my_course);
                            if ($num_my_course > 0) {
                                $row_my_course = mysqli_fetch_assoc($sql_my_course);
        
                                // my course id
                                $my_course_id   = $row_my_course['id'];
                                $my_course_name = $row_my_course['name'];
                                ?>
                                <option value="<?= $my_course_id ?>" <?php if ($student_ssc_year == '2024') { echo 'selected'; } ?>><?= $my_course_name ?></option>
                                <?php 
                            }
                        }
                    }?>
                </select>
            </div>

            <button type="submit" name="filter" class="button btn_sm">Filter</button>
        </form>
    </div>
    
    <div class="resource_container hc_container ep_grid">
        <table class="hc_table hc_exam_table">
            <thead>
                <tr>
                    <th>নং</th>
                    <th>কোর্সের নাম</th>
                    <th>পরীক্ষার নাম</th>
                    <th>MCQ</th>
                    <th>CQ</th>
                    <th>প্রকাশের তারিখ</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php $total_course_id = '';
                if (isset($result['my_courses'])) {
                    foreach ($result['my_courses'] as $key => $my_courses) {
                        // courses id
                        $my_courses_id = $my_courses['item_id'];
    
                        $total_course_id = $my_courses_id . ',' . $total_course_id;
                    }
                }
                
                $total_course_id = substr($total_course_id, 0, -1);
                
                $si = 0;           
                // intialize now time
                $now = date('Y-m-d H:i:s', time());
                
                $num_exam = 0;
                
                if (isset($_GET['filter_exam']) && $_GET['filter_exam'] != '') {
                    $filter_id = $_GET['filter_exam'];
                    
                    // check this exam course is my course
                    $course_found = false;
                    foreach ($result['my_courses'] as $my_courses) {
                        if ($my_courses['item_id'] === $filter_id) {
                            $course_found = true;
                            break;
                        }
                    }
                    
                    if ($course_found) {
                        // fetch exam
                        $select_exam = "SELECT * FROM hc_exam WHERE course_id = '$filter_id' AND status = 1 AND is_delete = 0 ORDER BY created_date DESC";
                        $sql_exam = mysqli_query($db, $select_exam);
                        $num_exam = mysqli_num_rows($sql_exam);
                    } else {
                        ?>
                        <script type="text/javascript">
                            window.location.href = '<?= $base_url ?>exam/';
                        </script>
                        <?php 
                    }
                } else {
                    if (isset($result['my_courses'])) {
                        // fetch exam
                        $select_exam = "SELECT * FROM hc_exam WHERE course_id IN ($total_course_id) AND status = 0 AND is_delete = 0 ORDER BY course_id, created_date DESC LIMIT 5";
                        $sql_exam = mysqli_query($db, $select_exam);
                        $num_exam = mysqli_num_rows($sql_exam);
                    }
                }
                if ($num_exam > 0) {
                    while ($row_exam = mysqli_fetch_assoc($sql_exam)) {
                        $exam_id                = $row_exam['id'];
                        $exam_name              = $row_exam['name'];
                        $exam_course_id         = $row_exam['course_id'];
                        $exam_mcq               = $row_exam['mcq'];
                        $exam_total_question    = $row_exam['total_question'];
                        $exam_mark_per_question = $row_exam['mark_per_question'];
                        $exam_cq                = $row_exam['cq'];
                        $exam_mark              = $row_exam['mark'];
                        $exam_mcq_duration      = $row_exam['mcq_duration'];
                        $exam_cq_duration       = $row_exam['cq_duration'];
                        $exam_valid_time        = $row_exam['valid_time'];
                        $exam_date              = $row_exam['created_date'];

                        $exam_date_text = date('d M, Y', strtotime($exam_date));
                        
                        // fetch course
                        $select_course  = "SELECT * FROM hc_course WHERE id = '$exam_course_id' AND type = 1 AND status = 1 AND is_delete = 0";
                        $sql_course     = mysqli_query($db, $select_course);
                        $num_course     = mysqli_num_rows($sql_course);
                        if ($num_course > 0) {
                            $row_course = mysqli_fetch_assoc($sql_course);
                            $course_id   = $row_course['id'];
                            $course_name = $row_course['name'];
                        }
                        
                        $si++;
                        if ($now >= $exam_date) {
                            ?>
                            <tr>
                                <td><?= $si ?></td>

          %