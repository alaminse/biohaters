<?php include('../assets/includes/dashboard_header.php'); ?>

<?php use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/mailer/Exception.php';
require '../assets/mailer/PHPMailer.php';
require '../assets/mailer/SMTP.php';?>

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

<?php if (($student_gen != '') && !empty($student_father_name) && !empty($student_father_phone) && !empty($student_mother_name) && !empty($student_mother_phone) && !empty($student_school) && !empty($student_ssc_year) && !empty($student_ssc_board) && !empty($student_profile)) {
    if ($student_status == '0') {
        $update_verify = "UPDATE hc_student SET status = '1' WHERE id = '$student_id'";
        $sql_verify = mysqli_query($db, $update_verify);
        if ($sql_verify) {
            ?>
            <script type="text/javascript">
                window.location.href = '<?= $base_url ?>dashboard/';
            </script>
            <?php 
        }
    }
}?>

<?php if (isset($_POST['update_courier_info'])) {
    $courier_address    = mysqli_escape_string($db, $_POST['courier_address']);
    $purchase_id        = $_POST['purchase_id'];
    $course_id        = $_POST['course_id'];
    
    $update_date = date('Y-m-d H:i:s', time());
    
    if ($courier_address == '') {
        $courier_alert = '<p class="danger mt_75">আপনার কুরিয়ার ঠিকানা দিন।</p>';
    } else {
        // check updated address
        $select_updated_address  = "SELECT * FROM hc_courier WHERE student_id = '$student_id' AND course_id = '$course_id'";
        $sql_updated_address     = mysqli_query($db, $select_updated_address);
        $num_updated_address     = mysqli_num_rows($sql_updated_address);
        if ($num_updated_address == 0) {
            // insert courier address
            $insert_courier = "INSERT INTO hc_courier (student_id, purchase_id, courier_address, update_date, course_id) VALUES ('$student_id', '$purchase_id', '$courier_address', '$update_date', '$course_id')";
            $sql_courier = mysqli_query($db, $insert_courier);
            if ($sql_courier) {
                echo '<div class="modal_container payment_modal show-modal" id="update-personal-success">
                            <div class="modal_content payment_content">
                                <div class="modal_body">
                                    <div class="payment_icon_success text_center">
                                        <i class="bx bx-check"></i>
                                    </div>
        
                                    <p class="payment_success_subtitle text_center">Update Successfully!</p>
                                </div>
        
                                <div class="">
                                    <a href="https://biohaters.com/dashboard/" class="button no_hover btn_sm m_auto">OK</a>
                                </div>
                            </div>
                        </div>';
            }
        } else {
            echo '<div class="modal_container payment_modal show-modal" id="update-personal-success">
                        <div class="modal_content payment_content">
                            <div class="modal_body">
                                <div class="payment_icon_success text_center">
                                    <i class="bx bx-check"></i>
                                </div>
    
                                <p class="payment_success_subtitle text_center">Update Successfully!</p>
                            </div>
    
                            <div class="">
                                <a href="https://biohaters.com/dashboard/" class="button no_hover btn_sm m_auto">OK</a>
                            </div>
                        </div>
                    </div>';
        }
    }
}

foreach ($result['my_courses'] as $key => $my_courses) {
    // courses id
    $my_courses_id = $my_courses['item_id'];
    
    if (($my_courses_id == 4) || ($my_courses_id == 9) || ($my_courses_id == 5) || ($my_courses_id == 11) || ($my_courses_id == 12)) {
        // if ($my_courses['price'] > 0) {
            // purchase id
            $my_purchase_id = $my_courses['purchase_id'];
            
            // fetch my course
            $select_my_course  = "SELECT * FROM hc_course WHERE id = '$my_courses_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
            $sql_my_course     = mysqli_query($db, $select_my_course);
            $num_my_course     = mysqli_num_rows($sql_my_course);
            if ($num_my_course > 0) {
                $row_my_course = mysqli_fetch_assoc($sql_my_course);

                // my course variable
                $my_course_id   = $row_my_course['id'];
                $my_course_name = $row_my_course['name'];
            }
            
            // check updated address
            $select_updated_address  = "SELECT * FROM hc_courier WHERE student_id = '$student_id' AND course_id = '$my_courses_id'";
            $sql_updated_address     = mysqli_query($db, $select_updated_address);
            $num_updated_address     = mysqli_num_rows($sql_updated_address);
            if ($num_updated_address == 0) {
                ?>
                <!--=========== ALERT SECTION ===========-->
                <section class="profile_alert_section hc_section">
                    <div class="hc_container">
                        <div class="hc_alert hc_alert_success">
                            <h4 class="hc_alert_title">কুরিয়ার ঠিকানা আপডেট নোটিশ</h4>
                            <h6 class="hc_alert_message"><?= $my_course_name ?> কোর্সে ভর্তি শিক্ষার্থীদের গিফট পাঠানো শুরু করা হয়েছে। আপনি আপনার কুরিয়ার ঠিকানা এখানে আপডেট করুন।</h6>
                            <!--<h6 class="hc_alert_message">নোটঃ আপনার এবং আপনার রিডিং পার্টনারের গিফট একসাথে আপনাকে পাঠানো হবে।</h6>-->
                
                            <form action="" method="post" class="single_col_form mt_75">
                                <?= $courier_alert ?>
                                <div>
                                    <label for="">Courier Address</label>
                                    <textarea name="courier_address" id="" rows="2" placeholder="ঢাকা সিটির মধ্যে যাদের বাসা, তাদের বাসার ঠিকানা দিবেন। আর যারা ঢাকা সিটির বাইরে অথবা অন্য জেলায় থাকেন, তাদের কুরিয়ার ঠিকানা দিবেন।"></textarea>
                                </div>
                                
                                <input type="hidden" id="" name="purchase_id" value="<?= $my_purchase_id ?>">
                                
                                <input type="hidden" id="" name="course_id" value="<?= $my_courses_id ?>">
                
                                <button type="submit" name="update_courier_info" class="">Update Courier Address</button>
                            </form>
                        </div>
                    </div>
                </section>
                <?php 
            }
        // }
    }
}?>

<?php if (($student_gen == '') || empty($student_father_name) || empty($student_father_phone) || empty($student_mother_name) || empty($student_mother_phone) || empty($student_school) || empty($student_ssc_year) || empty($student_ssc_board) || empty($student_profile)) {
    $join_second = strtotime($student_join_date);
    $expired_second = $join_second + (20 * 24 * 60 * 60);
    $alert_second = time();
    $expired_date = date('Y-m-d H:i:s', $expired_second);
    $alert_date = date('Y-m-d H:i:s', time());
    ?>
    <!--=========== ALERT SECTION ===========-->
    <section class="profile_alert_section hc_section">
        <div class="hc_container">
            <div class="hc_alert hc_alert_warning">
                <h4 class="hc_alert_title">অ্যাকাউন্ট ভেরিফিকেশন অ্যালার্ট</h4>
                <h6 class="hc_alert_message">আপনাকে বায়োলজির লজিক ও কনসেপ্টের জগতে স্বাগতম। আমরা দেখছি আপনি এখনও আপনার আবশ্যিক তথ্য যেমন ছবি, লিঙ্গ, বাবার নাম এবং ফোন নম্বর, মায়ের নাম এবং ফোন নম্বর, স্কুলের নাম, এস.এস.সি সাল এবং এস.এস.সি বোর্ড গুলো পূরণ করেননি। আপনি যদি আপনার এই অ্যাকাউন্টের বয়স ২০ দিন হওয়ার আগে তথ্যগুলো না দেন। তবে ওয়েবসাইট আপনাকে একজন অপরিচিত আগন্তুক ভেবে আপনার অ্যাকাউন্ট ব্যান করে দিবে। তাই যতো তাড়াতাড়ি সম্ভব আপনি তথ্যগুলো দিয়ে আপনাকে একজন ভেরিফাইড শিক্ষার্থী হিসেবে নিজেকে উপস্থাপন করুন।</h6>
                <p>আপনার আপডেট করার শেষ সময়ঃ <?= $expired_date ?></p>
                <a href="https://biohaters.com/profile-setting/">প্রোফাইল আপডেট করতে এখানে ক্লিক করুন <i class='bx bx-right-arrow-alt'></i></a>
            </div>
        </div>
    </section>
    <section class="profile_alert_section hc_section">
        <div class="hc_container">
            <div class="hc_alert hc_alert_warning" style="background: var(--success-bg-color);">
                <h4 class="hc_alert_title">Eleventh Hour Model Test</h4>
                <h6 class="hc_alert_message"></h6>
                <p>মেডিকেলের প্রিপারেশে শেষ পেরেক হল- মডেল টেস্ট। বেস্ট প্রশ্নে মডেল টেস্ট দিলে অতীতের ত্রুটি- ঘাটতি সব পূরণ হয়ে যায়। ফাইনাল পরীক্ষার জন্য পড়ালেখা গুছিয়ে যায়। বায়োলজি হেটার্স সবসময় তোমাদের জন্য বেস্ট কোয়ালিটি সার্ভ করেছে। এবারো তাই করছি। আমাদের সব আয়োজনের মধ্যে মডেল টেস্ট আয়োজন হয় বৃহৎ পরিসরে। এই আয়োজনে তোমাকে স্বাগতম। তোমার বেস্ট প্রিপারেশন ও পড়ালেখা গুছিয়ে ফেলার দায়িত্ব আমাদের। তোমার কাজ হল- রুটিন দেখে ভালোভাবে প্রিপারেশন নেওয়া। রেগুলার এটেন্ড করা। যেগুলো ভুল করবে, সেগুলোকে আরেকবার দেখে ফেলা। আমাদের ডেডিকেটেড গ্রুপ থাকবে, সেখানে তোমার কনফিউশন পোস্ত করবে। আমরা সেটাকে সলভ করতে মেডিকেল স্টুডেন্টদের বেস্ট টিম রেডি রেখেছি।</p>

                <p>মডেল টেস্ট শুরু ২৩ তারিখ থেকে।</p>

                <p><strong>Note: </strong>&nbsp;</p>

                <ul>
                    <li>হান্টিং এমবিবিএস : Revolution কোর্সের সবার জন্য মডেল টেস্টটি &lsquo;ফ্রি&rsquo;।</li>
                    <li>সিক্রেট ফাইলস যারা নিয়েছ, তাদের জন্য ৫০% ডিস্কাউন্ট থাকবে।</li>
                </ul>

                <p>ফ্রি এবং ডিস্কাউন্ট সুবিধা পেতে অবশ্যই ওয়েবসাইটে আপনার একাউন্ট টি লগিন অবস্থায় রেখে কোর্স টি তে এনরোল করতে হবে। অন্যথায় আপনি এই সুবিধা টি পাবেন না।&nbsp;</p>

                <p><strong><a style="color: red;" href="https://biohaters.com/course-details/?course=15">Course Link</a></strong></p>
            </div>
        </div>
    </section>
    <?php 
}?>

<!--=========== ALERT SECTION ===========-->
<!--<section class="profile_alert_section hc_section">-->
<!--    <div class="hc_container">-->
<!--        <div class="hc_alert hc_alert_danger">-->
<!--            <h4 class="hc_alert_title">লগিন OTP নিয়ে জরুরী নোটিশ</h4>-->
<!--            <h6 class="hc_alert_message">আপনাদের লগিন OTP এখন (আপনাদের ইমেইল এবং মোবাইল নম্বর) দুই মাধ্যমেই পাবেন। তবে আগামী ১০/০৯/২০২৩ ইং তারিখ হতে মোবাইল নম্বরে OTP পাঠানো বন্ধ করে দেওয়া হবে; তখন শুধুমাত্র ইমেইলে OTP পাবেন। তাই এখন থেকে ইমেইলের OTP সংগ্রহ করুন। যারা কোর্সে এনরোল করার সময় ভ্যালিড ইমেইল অ্যাড্রেস দেননি অথবা আপনি যেই ইমেইলটি দিয়ে লগিন করেন সেটি যদি ইমেইল পাওয়ার মতো ভ্যালিড না হয়। তবে শুধুমাত্র তারা বায়োলজি হেটার্স অফিসিয়াল পেইজে যোগাযোগ করে নিজের সঠিক ইমেইল দিবেন।</h6>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
<?php
    
    $ab24 = "SELECT * FROM hc_course WHERE name = 'Academic Biology 2nd year-2024' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
    $sql_my_course     = mysqli_query($db, $ab24);
    $num_my_course     = mysqli_num_rows($sql_my_course);
    if ($num_my_course > 0) {
        $row_my_course = mysqli_fetch_assoc($sql_my_course);

        // my course variable
        $my_course_id   = $row_my_course['id'];
        $my_course_name = $row_my_course['name'];
    }
?>
<!--==== MAIN CONTAINER ====-->
<section class="dashboard_section hc_section">
    <div class="dashboard_container hc_container ep_grid">
        <!--==== MAIN CONTENT ====-->
        <div class="main_content ep_grid">
            <!--==== BANNER ====-->
            <div class="banner">
                <h3 class="banner_title">লজিক ও বেসিক কনসেপ্টের <br>জগতে স্বাগতম</h3>

                <h5 class="banner_subtitle">জটিল বিষয় শেখার সহজ উপায়</h5>

                <div class="banner_icons">
                    <img src="../assets/img/new-element.png" alt="" class="banner_icon_new">
                    <img src="../assets/img/dashboard-elipse.png" alt="" class="banner_icon_1">
                    <img src="../assets/img/dashboard-banner-icon.png" alt="" class="banner_icon_2">
                    <img src="../assets/img/dashboard-banner-icon-2.png" alt="" class="banner_icon_3">
                    <img src="../assets/img/dashboard-elipse.png" alt="" class="banner_icon_4">
                </div>

                <img src="../assets/img/dashboard-banner.png" alt="" class="banner_img">
            </div>

            <!--==== WIDGETS ====-->
            <div class="dashboard_widget_container">
                <a href="<?= $base_url ?>student-course/" class="dashboard_widget">
                    <i class='bx bx-chalkboard'></i>
                    <div>My Courses</div>
                </a>

                <a href="<?= $base_url ?>exam/" class="dashboard_widget">
                    <i class='bx bx-food-menu'></i>
                    <div>Exams</div>
                </a>

                <a href="<?= $base_url ?>student-chapter/" class="dashboard_widget">
                    <i class='bx bx-book-alt'></i>
                    <div>My Chapters</div>
                </a>

                <a href="<?= $base_url ?>notice/" class="dashboard_widget">
                    <i class='bx bx-info-circle' ></i>
                    <div>Notices</div>
                </a>

                <a href="<?= $base_url ?>marked-book-pdf/" class="dashboard_widget">
                    <i class='bx bx-book-content' ></i>
                    <div>Free Honey</div>
                </a>
                
                <a href="<?= $base_url ?>course-note/" class="dashboard_widget">
                    <i class='bx bxs-pencil'></i>
                    <div>Notes</div>
                </a>

                <!--<a href="" class="dashboard_widget">-->
                <!--    <i class='bx bx-link' ></i>-->
                <!--    <div>Important Links</div>-->
                <!--</a>-->
            </div>
        </div>

        <!--==== EXTRA CONTENT ====-->
        <div class="extra_content ep_grid">
            <!--==== NOTICE ====-->
            <div class="hc_card">
                <h4 class="hc_card_title">Latest Updates</h4>
                <h5 class="hc_card_subtitle">Important updates for you</h5>

                <div class="dashboard_list">
                    <?php $total_course_id = '';
                    if (isset($result['my_courses'])) {
                        foreach ($result['my_courses'] as $key => $my_courses) {
                            // courses id
                            $my_courses_id = $my_courses['item_id'];
        
                            $total_course_id = $my_courses_id . ',' . $total_course_id;
                        }
                    }
                    
                    $total_course_id = substr($total_course_id, 0, -1);
                    
                    // intialize now time
                    $now = date('Y-m-d H:i:s', time());
                    
                    $num_exam = 0;
                    
                    if (isset($result['my_courses'])) {
                        // fetch exam
                        $select_exam = "SELECT * FROM hc_exam WHERE course_id IN ($total_course_id) AND status = 1 AND is_delete = 0 ORDER BY created_date DESC LIMIT 5";
                        $sql_exam = mysqli_query($db, $select_exam);
                        $num_exam = mysqli_num_rows($sql_exam);
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

                            $exam_date_text = date('d M, Y | h:i a', strtotime($exam_date));
                            
                            // fetch course
                            $select_course  = "SELECT * FROM hc_course WHERE id = '$exam_course_id' AND type = 1 AND status = 1 AND is_delete = 0";
                            $sql_course     = mysqli_query($db, $select_course);
                            $num_course     = mysqli_num_rows($sql_course);
                            if ($num_course > 0) {
                                $row_course = mysqli_fetch_assoc($sql_course);
                                $course_id   = $row_course['id'];
                                $course_name = $row_course['name'];
                            }
                            
                            // fetch attempt
                            $select_attempt  = "SELECT * FROM hc_exam_attempt WHERE exam = '$exam_id' AND student_id = '$student_id'";
                            $sql_attempt     = mysqli_query($db, $select_attempt);
                            $num_attempt     = mysqli_num_rows($sql_attempt);
                            
                            if (($now >= $exam_date) && ($exam_valid_time >= $now) && ($num_attempt == 0)) {
                                ?>
                                <a href="<?php if ($exam_mcq == 1) { ?><?= $base_url ?>exam-attempt/?exam=<?= $exam_id ?><?php }?>" class="dashboard_list_card">
                                    <div class="dashboard_list_card_header">
                                        <div class="dashboard_list_card_badge dashboard_list_card_badge_exam">
                                            <i class='bx bx-food-menu'></i> Exam
                                        </div>
                                        <i class='bx bxs-chevrons-right'></i>
                                        <div class="dashboard_list_card_course"><?= $course_name ?></div>
                                        <div class="badge_new"><?php if ($exam_valid_time >= $now) { echo 'Live'; }?></div>
                                    </div>

                                    <div class="dashboard_list_card_content">
                                        <div class="dashboard_list_card_title"><?= $exam_name ?></div>
                                        <div class="dashboard_list_card_date"><?= $exam_date_text ?></div>
                                    </div>
                                </a>
                                <?php 
                            } elseif (($exam_valid_time >= $now) && ($num_attempt == 0)) {
                                ?>
                                <div class="dashboard_list_card">
                                    <div class="dashboard_list_card_header">
                                        <div class="dashboard_list_card_badge dashboard_list_card_badge_exam">
                                            <i class='bx bx-food-menu'></i> Exam
                                        </div>
                                        <i class='bx bxs-chevrons-right'></i>
                                        <div class="dashboard_list_card_course"><?= $course_name ?></div>
                                        <div class="badge_new badge_scheduled"><?php echo 'Scheduled'; ?></div>
                                    </div>

                                    <div class="dashboard_list_card_content">
                                        <div class="dashboard_list_card_title"><i class='bx bx-lock'></i> <?= $exam_name ?></div>
                                        <div class="dashboard_list_card_date">Scheduled: <?= $exam_date_text ?></div>
                                    </div>
                                </div>
                                <?php 
                            }
                        }
                    }?>
                    
                    <?php if (isset($result['notice'])) {
                        foreach ($result['notice'] as $key => $notice) {
                            // notice id
                            $notice_id = $notice['id'];
                            $notice_course_id = $notice['for_whom'];
                            $notice_created_date = $notice['created_date'];
                            
                            // joined date convert to text
                            $notice_created_date_text = date('d M, Y | h:i:s a', strtotime($notice_created_date));

                            // fetch course
                            $select_course  = "SELECT * FROM hc_course WHERE id = '$notice_course_id' AND type = 1 AND status = 1 AND is_delete = 0";
                            $sql_course     = mysqli_query($db, $select_course);
                            $num_course     = mysqli_num_rows($sql_course);
                            if ($num_course > 0) {
                                $row_course = mysqli_fetch_assoc($sql_course);
                                $course_id   = $row_course['id'];
                                $course_name = $row_course['name'];
                            }
                            
                            if ($notice_course_id == 0) {
                                $course_name = 'For All';
                            }
    
                            $notification_expired_date = date('Y-m-d H:i:s', strtotime($notice_created_date) + (3 * 24 * 60 * 60));
                            if ($now <= $notification_expired_date) {
                                ?>
                                <!-- notice card -->
                                <a href="<?= $base_url ?>notice-view/?notice=<?= $notice_id ?>" class="dashboard_list_card">
                                    <div class="dashboard_list_card_header">
                                        <div class="dashboard_list_card_badge dashboard_list_card_badge_notice">
                                            <i class='bx bx-info-circle' ></i> Notice
                                        </div>
                                        <i class='bx bxs-chevrons-right'></i>
                                        <div class="dashboard_list_card_course"><?= $course_name ?></div>
                                        <div class="badge_new">New</div>
                                    </div>

                                    <div class="dashboard_list_card_content">
                                        <div class="dashboard_list_card_title"><?= $notice['name'] ?></div>
                                        <div class="dashboard_list_card_date"><?= $notice_created_date_text ?></div>
                                    </div>
                                </a>
                                <?php 
                            }
                        }
                    }?>
                </div>
            </div>
            
            <!--==== COMBINED LIST ====-->
            <div class="hc_card">
                <h4 class="hc_card_title">Combined Result</h4>
                <h5 class="hc_card_subtitle">Course wise combined result list</h5>
                
                <?php if (isset($result['my_courses'])) {
                    ?>
                    <div class="dashboard_list">
                        <?php foreach ($result['my_courses'] as $key => $my_courses) {
                            // courses id
                            $my_courses_id = $my_courses['item_id'];
                
                            // fetch my course
                            $select_my_course  = "SELECT * FROM hc_course WHERE id = '$my_courses_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
                            $sql_my_course     = mysqli_query($db, $select_my_course);
                            $num_my_course     = mysqli_num_rows($sql_my_course);
                            if ($num_my_course > 0) {
                                $row_my_course = mysqli_fetch_assoc($sql_my_course);
                
                                // my course variable
                                $my_course_id              = $row_my_course['id'];
                                $my_course_name            = $row_my_course['name'];
                                ?>
                                <!-- CARD -->
                                <a href="<?= $base_url ?>combine-result-list/?course=<?= $my_course_id ?>" class="dashboard_list_card">
                                    <div class="dashboard_list_card_content">
                                        <div class="dashboard_list_card_title"><?= $my_course_name ?></div>
                                    </div>
                                </a>
                                <?php 
                            }
                        }?>
                    </div>
                    <?php 
                }?>
            </div>
        </div>
    </div>
</section>

<!--=========== CHART JS ===========-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
<?php $chart_label = "";
foreach ($my_progress['exam_data'] as $exam_data) {
    $exam_name = $exam_data['exam_name'];
    $gain_mark = $exam_data['gain_mark'];
    
    $chart_label .= "{ y: '" . $exam_name . "', a: " . $gain_mark . " },";
}

$chart_label = substr($chart_label, 0, -1); ?>

<script>
// Define the data for the chart
var data = [
    <?= $chart_label ?>
];

// Create and render the chart
new Morris.Line({
    element: 'result',
    parseTime: false,
    data: data,
    xkey: 'y',
    ykeys: ['a'],
    labels: ['Marks'],
    barColors: ['#007BFF'],
    behaveLikeLine: true,
    resize: true
});
</script>

<?php include('../assets/includes/dashboard_footer.php'); ?>