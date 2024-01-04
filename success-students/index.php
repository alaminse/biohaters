<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['session']) && $_GET['session'] != '') {
    $session = $_GET['session'];
    
    $session_bn = $session;
    
    $session_bn = str_replace('0', '০', $session_bn);
    $session_bn = str_replace('1', '১', $session_bn);
    $session_bn = str_replace('2', '২', $session_bn);
    $session_bn = str_replace('3', '৩', $session_bn);
    $session_bn = str_replace('4', '৪', $session_bn);
    $session_bn = str_replace('5', '৫', $session_bn);
    $session_bn = str_replace('6', '৬', $session_bn);
    $session_bn = str_replace('7', '৭', $session_bn);
    $session_bn = str_replace('8', '৮', $session_bn);
    $session_bn = str_replace('9', '৯', $session_bn);
    ?>
    <!--=========== COMMON SECTION ===========-->
    <section class="common_section hc_section">
        <div class="hc_container text_center">
            <h1 class="common_section_title">সফল শিক্ষার্থী - <?= $session_bn ?></h1>
        </div>
    </section>
    
    <!--=========== BLOG ===========-->
    <section class="success_students_section hc_section">
        <div class="success_students_container hc_container">
            <?php // select success students
            $select = "SELECT * FROM hc_success_student WHERE session = '$session' AND is_delete = 0 ORDER BY college ASC";
            $sql = mysqli_query($db, $select);
            $num = mysqli_num_rows($sql);
            if ($num > 0) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    $name       = $row['name'];
                    $merit      = $row['merit'];
                    $college    = $row['college'];
                    $session    = $row['session'];
                    $photo      = $row['photo'];
                    $review     = $row['review'];
                    
                    // select college name
                    $select_college = "SELECT * FROM hc_medical_college WHERE id = '$college'";
                    $sql_college = mysqli_query($db, $select_college);
                    $num_college = mysqli_num_rows($sql_college);
                    if ($num_college > 0) {
                        $row_college = mysqli_fetch_assoc($sql_college);
                        $college_name       = $row_college['name'];
                        $college_shortcode  = $row_college['shortcode'];
                    }
                    
                    if ($photo != '') {
                        // detect path of student profile
                        $photo_img = substr($photo, 2);
                        $photo_img = $base_url . 'admin' . $photo_img;
                    } else {
                        $photo_img = $base_url . 'assets/img/student.png';
                    }?>
                    <div class="success_students_card">
                        <div class="success_students_icon"><i class='bx bxs-quote-left'></i></div>
                        
                        <div class="success_students_name"><?= $name ?></div>
                        
                        <div class="success_students_meta">Merit Position - <?= $merit ?> | Session -<?= $session ?></div>
                        
                        <div class="success_students_college"><?= $college_name ?></div>
                        
                        <?php if ($review != '') {
                            ?>
                            <div class="success_students_review"><?= $review ?></div>
                            <?php 
                        }?>
                        
                        <div class="success_students_photo">
                            <img src="<?= $photo_img ?>" alt="">
                        </div>
                    </div>
                    <?php 
                }
            }?>
        </div>
    </section>
    <?php 
}?>

<?php include('../assets/includes/footer.php'); ?>