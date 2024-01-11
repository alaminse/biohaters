<?php // php extension file redirecting to folder
function current_url()
{
    $url = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $valid_url = str_replace("&", "&amp;", $url);

    return $valid_url;
}

$current_url = current_url();

$array_url = explode('/', $current_url);
$extension_url = end($array_url);

if ($extension_url == 'index.php') {
    $redirect_url = substr($current_url, 0, -9); ?>
    <script type="text/javascript">
        window.location.href = '<?php echo $redirect_url; ?>';
    </script>
    <?php 
}

if ($current_url == 'http://localhost/biohaters/') {
    ?>
    <script type="text/javascript">
        window.location.href = 'http://localhost/biohaters/';
    </script>
    <?php 
}

// Set the character set for the output
header('Content-Type: text/html; charset=utf-8');

$base_url = 'http://localhost/biohaters/';

// include database
include('admin/db/db.php');

// maintenance redirect

// session start
session_start();

// set local time zone
date_default_timezone_set('Asia/Dhaka');

// checking cookie & redirect to valid folder
if (isset($_COOKIE['student_id'])) {
    $student_id = $_COOKIE['student_id'];
    
    // Get IP address
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    // Get device information
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $deviceType = "Unknown";
    $deviceName = "Unknown";

    // Define an array of device types and their corresponding keywords
    $deviceTypes = array(
        'Mobile' => array('Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone'),
        'Tablet' => array('Tablet', 'iPad', 'Android'),
        'Desktop' => array('Windows', 'Macintosh', 'Linux', 'Ubuntu')
    );

    // Loop through the device types and check if the user agent contains any of the keywords
    foreach ($deviceTypes as $type => $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($userAgent, $keyword) !== false) {
                $deviceType = $type;
                break 2; // Break out of both loops once a match is found
            }
        }
    }

    // Get device name (if available)
    if (preg_match('/\((.*?)\)/', $userAgent, $matches)) {
        $deviceName = $matches[1];
    }

    // match login validity
    $match_login_validity = "SELECT * FROM hc_login_otp WHERE student_id = '$student_id' AND device_type = '$deviceType' AND device_name = '$deviceName'";
    $sql_login_validity = mysqli_query($db, $match_login_validity);
    $num_login_validity = mysqli_num_rows($sql_login_validity);
    if ($num_login_validity > 0) {
        $login_validity = 1;
    } else {
        $login_validity = 0;
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>logout/';
        </script>
        <?php 
    }
} elseif ((!isset($_COOKIE['student_id'])) || $_COOKIE['student_id'] == '') {
    $login_validity = 0;
}

if ($login_validity == 1) {
    // include purchase variable
    include('assets/includes/purchase_variable.php');
}

// include common variable
include('assets/includes/variable.php');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biology Haters</title>
    <!--=========== BOOTSTRAP CSS ===========-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!--=========== JQUERY ===========-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!--=========== BOOTSTRAP JS ===========-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!--=============== BOX ICONS ===============-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <!--=========== SOLAIMANLIPI FONT ===========-->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">

    <!--=========== FAV ICON ===========-->
    <link rel="shortcut icon" type="image/png" href="assets/img/logo.png">

    <!--=========== SWIPER CSS ===========-->
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">

    <!--=========== STYLE CSS ===========-->
    <link rel="stylesheet" href="assets/css/style.css">

    <!--=========== LOGIN CSS ===========-->
    <link rel="stylesheet" href="assets/css/home.css">
    <!-- <link rel="stylesheet" href="assets/css/home_two.css"> -->
     <link rel="stylesheet" href="assets/css/slide.css"> 
     <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '935171864616569');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=935171864616569&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
</head>

<body>

    <!--=========== HEADER ===========-->
    <header>
        <div class="hc_container header">
            <!--==== TOGGLE BUTTON ====-->
            <div class="nav_toggle" id="nav-toggle">
                <i class='bx bx-menu'></i> <span>Menu</span>
            </div>

            <!--==== LOGO ====-->
            <div class="nav_logo">
                <a href="<?= $base_url ?>">
                    <img src="<?= $base_url ?>assets/img/logo.png" alt="">
                </a>
            </div>

            <nav class="nav_menu" id="nav-menu">
                <!--==== MENU ====-->
                <div class="position_relative nav_menu_upper">
                    <!--== NAV CLOSE ==-->
                    <i class='bx bx-x nav_close' id="nav-close"></i>

                    <!-- MOBILE LOGO -->
                    <a href="<?= $base_url ?>" class="nav_menu_logo">
                        <img src="<?= $base_url ?>assets/img/logo.png" alt="">
                    </a>

                    <!-- MENU LIST -->
                    <ul>
                        <a href="<?= $base_url ?>" class="nav_link">
                            <li>
                                হোম
                            </li>
                        </a>

                        <a href="<?= $base_url ?>all-course/" class="nav_link">
                            <li>
                                কোর্স
                            </li>
                        </a>

                        <a href="<?= $base_url ?>all-chapter/" class="nav_link">
                            <li>
                                চ্যাপ্টার
                            </li>
                        </a>

                        <a href="<?= $base_url ?>notice/" class="nav_link">
                            <li>
                                নোটিশ
                            </li>
                        </a>
                    </ul>
                </div>

                <div class="menu_btn_grp">
                    <?php if ($login_validity == 1) {
                    ?>
                        <a href="<?= $base_url ?>dashboard/" class="button btn_outline hearder_btn">ড্যাশবোর্ড</a>
                        <a href="<?= $base_url ?>logout/" class="button no_hover hearder_btn">লগ-আউট</a>
                    <?php
                    } else {
                    ?>
                        <!-- <a href="" class="button btn_outline hearder_btn">টিফিন বুক সিরিজ</a> -->
                        <a href="<?= $base_url ?>login/" class="button no_hover hearder_btn">লগ-ইন করুন</a>
                    <?php
                    } ?>
                </div>
            </nav>
            
            <div class="ep_flex ep_end mobile_view_only">
                <?php if ($login_validity == 1) {
                    ?>
                    <a href="<?= $base_url ?>dashboard/" class="button btn_outline hearder_btn_sm">ড্যাশবোর্ড</a>
                    <?php 
                } else {
                    ?>
                    <a href="<?= $base_url ?>login/" class="button no_hover hearder_btn_sm">লগ-ইন করুন</a>
                    <?php 
                }?>
            </div>
        </div>
    </header>

    <!--=========== MAIN ===========-->
    <main>
        <!--=========== WIDGETS ===========-->
        <section class="home_widget_section hc_section">
            <div class="home_widget_container hc_container ep_grid">
                <!-- WIDGETS CARD -->
                <a href="<?= $base_url ?>free-honey/" class="home_widget_card">
                    <div class="home_widget_card_content">
                        <img src="assets/icon/mark-book.png" alt="">
                    </div>
    
                    <div class="home_widget_card_data">
                        <div class="home_widget_card_badges">
                            <div class="home_widget_card_badge home_widget_card_badge_free">Free</div>
                            <div class="home_widget_card_badge home_widget_card_badge_live">Live</div>
                        </div>
                        <p class="home_widget_card_des">দাগানো বই ও সলভ ক্লাস</p>
                    </div>
                </a>
    
                <!-- WIDGETS CARD -->
                <a href="<?= $base_url ?>secret-file-token/" class="home_widget_card">
                    <div class="home_widget_card_content">
                        <img src="assets/icon/token-entry.png" alt="">
                    </div>
    
                    <div class="home_widget_card_data">
                        <div class="home_widget_card_badges">
                            <div class="home_widget_card_badge home_widget_card_badge_free">Free</div>
                            <div class="home_widget_card_badge home_widget_card_badge_live">Live</div>
                        </div>
                        <p class="home_widget_card_des">সিক্রেট ফাইলস - টোকেন এন্ট্রি</p>
                    </div>
                </a>
    
                <!-- WIDGETS CARD -->
                <a href="https://elpandorapub.com/sf.php" class="home_widget_card">
                    <div class="home_widget_card_content">
                        <img src="assets/icon/tiffin.png" alt="">
                    </div>
    
                    <div class="home_widget_card_data">
                        <p class="home_widget_card_des">সিক্রেট ফাইলস সংগ্রহ</p>
                    </div>
                </a>
    
                <!-- WIDGETS CARD -->
                <a href="<?= $base_url ?>course-details/?course=10" class="home_widget_card">
                    <div class="home_widget_card_content">
                        <img src="assets/icon/syllabus.png" alt="">
                    </div>
    
                    <div class="home_widget_card_data">
                        <div class="home_widget_card_badges">
                            <div class="home_widget_card_badge home_widget_card_badge_free">Free</div>
                            <div class="home_widget_card_badge home_widget_card_badge_live">Live</div>
                        </div>
                        <p class="home_widget_card_des">জিকে-ইংলিশ ক্র্যাশ ডেইলি</p>
                    </div>
                </a>
    
                <!-- WIDGETS CARD -->
                <!-- <a href="/free-honey/" class="home_widget_card">
                    <div class="home_widget_card_content">
                        <img src="assets/icon/medical.png" alt="">
                    </div>
    
                    <div class="home_widget_card_data">
                        মেডিকেল কোর্স
                    </div>
                </a> -->
    
                <!-- WIDGETS CARD -->
                <!-- <a href="/free-honey/" class="home_widget_card">
                    <div class="home_widget_card_content">
                        <img src="assets/icon/chapter.png" alt="">
                    </div>
    
                    <div class="home_widget_card_data">
                        পছন্দের অধ্যায়
                    </div>
                </a> -->
    
                <!-- WIDGETS CARD -->
                <a href="<?= $base_url ?>course-details/?course=13" class="home_widget_card">
                    <div class="home_widget_card_content">
                        <img src="assets/icon/quiz.png" alt="">
                    </div>
    
                    <div class="home_widget_card_data">
                        <div class="home_widget_card_badges">
                            <div class="home_widget_card_badge home_widget_card_badge_free">Free</div>
                            <div class="home_widget_card_badge home_widget_card_badge_live">Live</div>
                        </div>
                        <p class="home_widget_card_des">কুইজ কমান্ডো</p>
                    </div>
                </a>
    
                <!-- WIDGETS CARD -->
                <a href="<?= $base_url ?>free-trial/" class="home_widget_card">
                    <div class="home_widget_card_content">
                        <img src="assets/icon/free-trial.png" alt="">
                    </div>
    
                    <div class="home_widget_card_data">
                        <div class="home_widget_card_badges">
                            <div class="home_widget_card_badge home_widget_card_badge_free">Free</div>
                        </div>
                        <p class="home_widget_card_des">ফ্রি ট্রায়াল</p>
                    </div>
                </a>
            </div>
        </section>
        <section class="banner_section hc_section">
            <div class="t2_banner_container hc_container ep_grid">
                <div class="t2_banner_content_img">
                    <a href="http://localhost/biohaters/course-details/?course=15">
                        <img src="assets/img/home_banner.png" alt="">
                    </a>
                    <!--<img src="assets/img/banner_logo.png" alt="">-->
                </div>

                <div class="t2_banner_data">
                    <div class="t2_banner_content">
                        <h1 class="banner_title">বাংলাদেশের সবচেয়ে সমৃদ্ধ বায়োলজি</h1>
                        <h4 class="t2_banner_subtitle">লজিক ও বেসিক কনসেপ্টের জগতে স্বাগতম</h4>
                    </div>
                    <form action="search/" method="get" class="t2_banner_form banner_form">
                        <!-- <i class='bx bx-search'></i> -->
                        <input type="text" name="search" id="" placeholder="কোন বিষয়ে জানতে চান?">
                        <button type="submit" name="search_btn" class="no_hover"><span class="">খুঁজে দেখুন</span> <i class='bx bx-search'></i></button>
                    </form>
                </div>
            </div>
        </section>
        <!--=========== ACCORDIONS ===========-->
        <section class="home_course_tabs_section hc_section">
            <div class="home_course_tabs_container hc_container ep_grid">
                <div class="accordion" id="home-course-tabs-accordion">
                    <!-- accordion -->
                    <div class="accordion-item home_accordion_item">
                        <h2 class="accordion-header" id="medical-courses-header">
                            <button class="accordion-button w_100 " type="button" data-bs-toggle="collapse" data-bs-target="#medical-courses" aria-expanded="true" aria-controls="medical-courses">
                                <div class="home_course_tabs_content">
                                    <img src="assets/icon/medicals.png" alt="">
                                </div>
    
                                <div class="home_course_tabs_data">
                                    মেডিকেল কোর্স
                                </div>
                            </button>
                        </h2>
                        
                        <div id="medical-courses" class="accordion-collapse collapse" aria-labelledby="medical-courses-header" data-bs-parent="#home-course-tabs-accordion">
                            <div class="accordion-body">
                                <?php $select_medical_course  = "SELECT * FROM hc_course WHERE id != 1 AND type = 1 AND category = 2 AND status = 1 AND is_delete = 0 ORDER BY created_date DESC LIMIT 4";
                                $sql_medical_course     = mysqli_query($db, $select_medical_course);
                                $num_medical_course     = mysqli_num_rows($sql_medical_course);
                                if ($num_medical_course > 0) {
                                    while ($row_medical_course = mysqli_fetch_assoc($sql_medical_course)) {
                                        $medical_course_id      = $row_medical_course['id'];
                                        $medical_course_name    = $row_medical_course['name'];
                                        ?>
                                        <a href="<?= $base_url ?>course-details/?course=<?= $medical_course_id ?>" class="home_accordion_active">
                                            <i class='bx bxs-chevrons-right'></i>
                                            <?= $medical_course_name ?>
                                        </a>
                                        <?php 
                                    }
                                }?>
    
                                <a href="<?= $base_url ?>all-course/" class="home_accordion_active">
                                    View All
                                    <i class='bx bx-right-arrow-alt' ></i>
                                </a>
                            </div>
                        </div>
                    </div>
    
                    <!-- accordion -->
                    <div class="accordion-item home_accordion_item">
                        <h2 class="accordion-header" id="academic-courses-header">
                            <button class="accordion-button w_100 " type="button" data-bs-toggle="collapse" data-bs-target="#academic-courses" aria-expanded="true" aria-controls="academic-courses">
                                <div class="home_course_tabs_content">
                                    <img src="assets/icon/academics.png" alt="">
                                </div>
    
                                <div class="home_course_tabs_data">
                                    একাডেমিক কোর্স
                                </div>
                            </button>
                        </h2>
                        
                        <div id="academic-courses" class="accordion-collapse collapse" aria-labelledby="academic-courses-header" data-bs-parent="#home-course-tabs-accordion">
                            <div class="accordion-body">
                                <?php $select_academic_course  = "SELECT * FROM hc_course WHERE id != 1 AND type = 1 AND category = 1 AND status = 1 AND is_delete = 0 ORDER BY created_date DESC LIMIT 4";
                                $sql_academic_course     = mysqli_query($db, $select_academic_course);
                                $num_academic_course     = mysqli_num_rows($sql_academic_course);
                                if ($num_academic_course > 0) {
                                    while ($row_academic_course = mysqli_fetch_assoc($sql_academic_course)) {
                                        $academic_course_id      = $row_academic_course['id'];
                                        $academic_course_name    = $row_academic_course['name'];
                                        ?>
                                        <a href="<?= $base_url ?>course-details/?course=<?= $academic_course_id ?>" class="home_accordion_active">
                                            <i class='bx bxs-chevrons-right'></i>
                                            <?= $academic_course_name ?>
                                        </a>
                                        <?php 
                                    }
                                }?>
    
                                <a href="<?= $base_url ?>all-course/" class="home_accordion_active">
                                    View All
                                    <i class='bx bx-right-arrow-alt' ></i>
                                </a>
                            </div>
                        </div>
                    </div>
    
                    <!-- accordion -->
                    <div class="accordion-item home_accordion_item">
                        <h2 class="accordion-header" id="chapter-courses-header">
                            <button class="accordion-button w_100 " type="button" data-bs-toggle="collapse" data-bs-target="#chapter-courses" aria-expanded="true" aria-controls="chapter-courses">
                                <div class="home_course_tabs_content">
                                    <img src="assets/icon/chapters.png" alt="">
                                </div>
    
                                <div class="home_course_tabs_data">
                                    অধ্যায় সমূহ
                                </div>
                            </button>
                        </h2>
                        
                        <div id="chapter-courses" class="accordion-collapse collapse" aria-labelledby="chapter-courses-header" data-bs-parent="#home-course-tabs-accordion">
                            <div class="accordion-body">
                                <a href="<?= $base_url ?>full-syllabus/" class="home_accordion_active">
                                    <i class='bx bxs-chevrons-right'></i>
                                    ফুল সিলেবাস
                                </a>
    
                                <a href="<?= $base_url ?>chapter/" class="home_accordion_active">
                                    <i class='bx bxs-chevrons-right'></i>
                                    পছন্দের অধ্যায়
                                </a>
    
                                <a href="<?= $base_url ?>botany-part/" class="home_accordion_active">
                                    <i class='bx bxs-chevrons-right'></i>
                                    প্রাণীবিজ্ঞান/উদ্ভিদবিজ্ঞান
                                </a>
    
                                <a href="<?= $base_url ?>all-chapter/" class="home_accordion_active">
                                    View All
                                    <i class='bx bx-right-arrow-alt' ></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--=========== CHAPTER ===========-->
        <section class="testimonial_section chapter_section hc_section">
            <div class="hc_container text_center">
                <h1 class="section_title">অধ্যায়ভিত্তিক সংগ্রহ করুন</h1>
            </div>
            <div class="testimonial_slide hc_container x_hidden">
                <div class="swiper-wrapper">
                    <?php // chapter
                    $i = 0;
                    foreach ($result['all_chapter'] as $key => $all_chapter) {
                        $i++;
                        // chapter id
                        $chapter_id = $all_chapter['id'];
                        // connected with chapter
                        $select_chapter_lecture = "SELECT COUNT(id) as chapter_lecture FROM hc_chapter_lecture WHERE chapter = '$chapter_id' AND is_delete = 0";
                        $sql_chapter_lecture = mysqli_query($db, $select_chapter_lecture);
                        $row_chapter_lecture = mysqli_fetch_assoc($sql_chapter_lecture);
                        $chapter_lecture = $row_chapter_lecture['chapter_lecture'];
                        if ($i < 10) {
                    ?>
                            <!-- TESTIMONIAL CARD -->
                            <div class="course_card swiper-slide">
                                <div class="chapter_content" style="text-align: center;">
                                     <?php $all_chapter_cover_photo = substr($all_chapter['cover_photo'], 2); ?>
                                <img class="text-center" src="<?= $base_url ?>admin<?php echo $all_chapter_cover_photo; ?>" alt="">
                                </div>

                                <div class="chapter_data">
                                    <h1 class="course_title" style="min-height: 80px;"><?php echo $all_chapter['name']; ?></h1>

                                    <div class="ep_flex mb_75">
                                        <div class="ep_flex ep_start text_light">
                                            <i class='bx bx-message-square-detail'></i>
                                            <?= $chapter_lecture ?> টি লেসন্স
                                        </div>
                                        <?php // fetch subject
                                        $subject = $all_chapter['subject'];
                                        $select_subject  = "SELECT * FROM hc_subject WHERE id = '$subject' AND is_delete = 0";
                                        $sql_subject     = mysqli_query($db, $select_subject);
                                        $num_subject     = mysqli_num_rows($sql_subject);
                                        if ($num_subject > 0) {
                                            $row_subject = mysqli_fetch_assoc($sql_subject);
                                            if ($row_subject['subject']  == 'Botany') {
                                                echo ' <div class="success">উদ্ভিদবিজ্ঞান</div>';
                                            } elseif ($row_subject['subject']  == 'Zoology') {
                                                echo ' <div class="danger">প্রাণীবিজ্ঞান</div>';
                                            }
                                        } ?>
                                    </div>


                                    <div class="ep_flex ep_end mb_75 mt_75">
                                        <?php if ($all_chapter['sale_price'] > 0) {
                                        ?>
                                            <span class="text_strike text_light text_sm">৳<?= $all_chapter['price'] ?></span>
                                            <span class="text_lg">৳<?= $all_chapter['sale_price'] ?></span>
                                        <?php
                                        } else {
                                        ?>
                                            <span class="text_lg">৳<?= $all_chapter['sale_price'] ?></span>
                                        <?php
                                        } ?>
                                    </div>

                                    <a href="<?= $base_url ?>single-chapter/?chapter=<?= $chapter_id ?>" class="button w_100 no_hover mt_75">এনরোল করুন</a>
                                </div>
                            </div> <!-- Loop End -->

                    <?php
                        }
                    } ?>
                </div>

                <div class="testimonial_pagination text_center mt_1_5"></div>
            </div>
            <div class="hc_container all_view_btn mt_1_5">
                <a href="<?= $base_url ?>all-chapter/" class="button btn_sm no_hover">সকল অধ্যায় <i class='bx bx-right-arrow-alt'></i></a>
            </div>
        </section>

        <!-- Join Course -->
        <section class="testimonial_section chapter_section hc_section">
            <div class="hc_container text_center">
                <h1 class="section_title">কোর্সে জয়েন করুন</h1>
            </div>

            <div class="testimonial_slide hc_container x_hidden">
                <div class="swiper-wrapper">

                    <?php // course
                    $i = 0;
                    foreach ($result['all_course'] as $key => $all_course) {
                        $i++;

                        // course id
                        $course_id = $all_course['id'];

                        // connected with course
                        $select_course_lecture = "SELECT COUNT(id) as course_lecture FROM hc_course_lecture WHERE course = '$course_id' AND is_delete = 0";
                        $sql_course_lecture = mysqli_query($db, $select_course_lecture);
                        $row_course_lecture = mysqli_fetch_assoc($sql_course_lecture);
                        $course_lecture = $row_course_lecture['course_lecture'];
                        if ($i < 5) {
                    ?>
                            <!-- TESTIMONIAL CARD -->
                            <div class="course_card swiper-slide">
                                <div class="chapter_content" style="text-align: center;">
                                    <?php $all_course_cover_photo = substr($all_course['cover_photo'], 2); ?>
                                     <img  src="<?= $base_url ?>admin<?php echo $all_course_cover_photo; ?>" alt="">
                                </div>

                                <div class="chapter_data">
                                    <h1 class="course_title" style="min-height: 80px;"><?= $all_course['name'] ?></h1>

                                    <div class="ep_flex mb_75">
                                        <div class="ep_flex ep_start text_light">
                                            <i class='bx bx-time-five'></i>
                                            <?= $all_course['time_schedule'] ?>
                                        </div>

                                        <div class="ep_flex ep_start text_light">
                                            <i class='bx bx-message-square-detail'></i>
                                            <?= $course_lecture ?> টি লেসন্স
                                        </div>
                                    </div>

                                    <div class="ep_flex mb_75 mt_75">
                                        <?php $course_category = $all_course['category'];
                                        $select_course_category = "SELECT * FROM hc_course_category WHERE id = '$course_category' AND is_delete = 0";
                                        $sql_course_category    = mysqli_query($db, $select_course_category);
                                        $num_course_category    = mysqli_num_rows($sql_course_category);
                                        if ($num_course_category > 0) {
                                            $row_course_category = mysqli_fetch_assoc($sql_course_category);
                                            $course_category_id     = $row_course_category['id'];
                                            $course_category_name   = $row_course_category['name'];
                                        ?>
                                            <div class="success"><?php if ($course_category_name == 'Medical') {
                                                                        echo 'মেডিকেল';
                                                                    } elseif ($course_category_name == 'Academic') {
                                                                        echo 'একাডেমিক';
                                                                    } elseif ($course_category_name == 'Academic & Medical') {
                                                                        echo 'মেডিকেল এবং একাডেমিক';
                                                                    } ?></div>
                                        <?php
                                        } ?>

                                        <div class="ep_flex ep_start">
                                            <?php if ($all_course['sale_price'] > 0) {
                                            ?>
                                                <span class="text_strike text_light text_sm">৳<?= $all_course['price'] ?></span>
                                                <span class="text_lg">৳<?= $all_course['sale_price'] ?></span>
                                            <?php
                                            } else {
                                            ?>
                                                <span class="text_lg">৳<?= $all_course['price'] ?></span>
                                            <?php
                                            } ?>
                                        </div>
                                    </div>

                                    <a href="<?= $base_url ?>course-details/?course=<?= $course_id ?>" class="button w_100 no_hover mt_75">এনরোল করুন</a>

                                </div>
                            </div> <!-- Loop End -->

                    <?php
                        }
                    } ?>
                </div>

                <div class="testimonial_pagination text_center mt_1_5"></div>
            </div>
            <div class="hc_container all_view_btn mt_1_5">
                <a href="<?= $base_url ?>all-course/" class="button btn_sm no_hover">সকল কোর্স <i class='bx bx-right-arrow-alt'></i></a>
            </div>
        </section>

        <section class="testimonial_section hc_section">
        <div class="hc_container text_center">
            <h1 class="section_title">আমাদের সম্পর্কে শিক্ষার্থীদের কিছু কথা</h1>
        </div>

        <div class="testimonial_container swiper-container hc_container">
            <div class="swiper-wrapper">
                <?php // testimonial
                if (isset($result['testimonial'])) {
                    foreach ($result['testimonial'] as $key => $testimonial) {
                        // testimonial id
                        $testimonial_id = $testimonial['id'];
    
                        // detect path of testimonial photo
                        $testimonial_photo_tmp = substr($testimonial['photo'], 2);
                        $testimonial_photo_img = $base_url . 'admin' . $testimonial_photo_tmp;
    
                        // detect testimonial college
                        $testimonial_college = $testimonial['college'];
                        $select_testimonial_college = "SELECT * FROM hc_medical_college WHERE id = '$testimonial_college'";
                        $sql_testimonial_college    = mysqli_query($db, $select_testimonial_college);
                        $num_testimonial_college    = mysqli_num_rows($sql_testimonial_college);
                        if ($num_testimonial_college > 0) {
                            $row_testimonial_college = mysqli_fetch_assoc($sql_testimonial_college);
                            $testimonial_college_id     = $row_testimonial_college['id'];
                            $testimonial_college_name   = $row_testimonial_college['name'];
                        }
                        ?>
                        <!-- TESTIMONIAL CARD -->
                        <div class="testimonial_card swiper-slide">
                            <div class="testimonial_content">
                                <img src="<?= $testimonial_photo_img ?>" alt="">
                                <i class='bx bxs-quote-right'></i>
                            </div>
    
                            <div class="testimonial_data">
                                <h1 class="testimonial_title"><?= $testimonial['name'] ?></h1>
                                <p class="mb_75"><?= $testimonial_college_name ?></p>
                                <p class="testimonial_des"><?= $testimonial['review'] ?></p>
                            </div>
                        </div>
                        <?php 
                    }
                }?>
            </div>

            <div class="testimonial_pagination text_center mt_1_5"></div>
        </div>
    </section>
        <!--=========== SUCCESS STUTENT ===========-->
        <section class="success_section hc_section">
            <div class="hc_container text_center">
                <h1 class="section_title">মেডিকেলে চান্সপ্রাপ্ত স্টুডেন্ট</h1>
            </div>

            <div class="success_container hc_container ep_grid">
                <div class="success_container_2 ep_grid">
                    <!-- SUCCESS STUTENT CARD -->
                    <div class="success_card counter_item">
                        <div class="success_subtitle">মেডিকেলে স্টুডেন্ট ২০২২</div>
                        <h1 class="success_title"> <span class="counter scrollup" id="scroll-up" data-number="370" data-speed="200"></span> +স্টুডেন্ট </h1>
                    </div>

                    <!-- SUCCESS STUTENT CARD -->
                    <div class="success_card">
                        <div class="success_subtitle">মেডিকেলে স্টুডেন্ট ২০২১</div>
                        <h1 class="success_title"> <span class="counter scrollup" id="scroll-up" data-number="226" data-speed="200"></span> +স্টুডেন্ট </h1>
                    </div>
                </div>

                <!-- SUCCESS STUTENT CARD -->
                <div class="success_card">
                    <div class="success_subtitle">মেডিকেলে স্টুডেন্ট ২০২০</div>
                    <h1 class="success_title"> <span class="counter scrollup" id="scroll-up" data-number="55" data-speed="200"></span> +স্টুডেন্ট </h1>
                </div>
            </div>

        </section>


        <section class="success_section hc_section">
            <div class="hc_container text_center">
                <h1 class="section_title">মেডিকেলে চান্সপ্রাপ্ত স্টুডেন্ট তালিকা</h1>
            </div>
            <div class="hc_container text_center">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">স্টুডেন্ট</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#exTab1" aria-controls="exTab1" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="exTab1">
                            <ul class="navbar-nav nav nav-pills">
                                <li class="nav-item">
                                    <a href="#1a" id="one_slide" data-toggle="tab" class="nav-link" aria-current="page">২০২২</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#2a" id="two_slide" data-toggle="tab" class="nav-link">২০২১</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#3a" id="there_slide" data-toggle="tab" class="nav-link">২০২০</a>
                                </li>
                            </ul>
                            <br>
                        </div>
                    </div>
                </nav>
                <div class="tab-content clearfix">
                    <div class="tab-pane active" id="1a">
                        <section class="chapter_section hc_section">
                            <div class="student_slide hc_container x_hidden swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events" style="cursor: grab;">
                                <div class="swiper-wrapper" style="transition-duration: 0ms; transform: translate3d(-1666.67px, 0px, 0px);" id="swiper-wrapper-5df4436361263785" aria-live="off">
                                    <!-- TESTIMONIAL CARD --> <!-- Loop Start -->
                                    <?php // select success students
                                    $select = "SELECT * FROM hc_success_student WHERE session = '2022' AND is_delete = 0 ORDER BY college ASC";
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
                                            } ?>
                                            <div class="course_card swiper-slide">
                                                <div class="single-project">
                                                    <div class="wrapper" style="padding: 30px;">
                                                        <div class="img-area mt-6">
                                                            <div class="inner-area">
                                                                <img src="<?= $photo_img ?>">
                                                            </div>
                                                        </div>
                                                        <div class="name">
                                                            <h5><?= $name ?></h5>
                                                        </div>
                                                        <div class="about mt-2" style="text-align: center"><?= $college_name ?></div>

                                                        <div class="about mt-1" style="text-align: center">Merit Position - <?= $merit ?> | Session -<?= $session ?></div>
                                                    </div>
                                                </div>
                                            </div> <!-- Loop End -->
                                    <?php
                                        }
                                    } ?>
                                </div>
                                <!-- <div class="student_pagination text_center mt_1_5 swiper-pagination-bullets"><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span><span class="swiper-pagination-bullet"></span></div> -->
                                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                            </div>
                            <div class="hc_container all_view_btn mt_1_5">
                                <a href="<?= $base_url ?>success-students/?session=<?= $result['total_success'][1]['session'] ?>" class="button btn_sm no_hover">সকল ছাত্র <i class="bx bx-right-arrow-alt"></i></a>
                            </div>
                        </section>
                    </div>

                    <div class="tab-pane active" id="2a">
                        <section class="chapter_section hc_section">
                            <div class="student_slide hc_container x_hidden swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events" style="cursor: grab;">
                                <div class="swiper-wrapper" style="transition-duration: 0ms; transform: translate3d(-1666.67px, 0px, 0px);" id="swiper-wrapper-5df4436361263785" aria-live="off">
                                    <!-- TESTIMONIAL CARD --> <!-- Loop Start -->
                                    <?php // select success students
                                    $select = "SELECT * FROM hc_success_student WHERE session = '2021' AND is_delete = 0 ORDER BY college ASC";
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
                                            } ?>
                                            <div class="course_card swiper-slide">
                                                <div class="single-project">
                                                    <div class="wrapper">
                                                        <div class="img-area">
                                                            <div class="inner-area">
                                                                <img src="<?= $photo_img ?>">
                                                            </div>
                                                        </div>
                                                        <div class="name"><?= $name ?></div>
                                                        <div class="about" style="text-align: center">Dhaka Medical College, Dhaka</div>
                                                        <div class="social-icons">
                                                            <a href="#" class="fb"><i class="fa fa-facebook-f"></i></a>
                                                            <a href="#" class="insta"><i class="fa fa-instagram"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- Loop End -->
                                    <?php
                                        }
                                    } ?>
                                </div>
                                <!-- <div class="student_pagination text_center mt_1_5 swiper-pagination-bullets"><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span><span class="swiper-pagination-bullet"></span></div> -->
                                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                            </div>
                            <div class="hc_container all_view_btn mt_1_5">
                                <a href="<?= $base_url ?>success-students/?session=<?= $result['total_success'][2]['session'] ?>" class="button btn_sm no_hover">সকল ছাত্র <i class="bx bx-right-arrow-alt"></i></a>
                            </div>
                        </section>
                    </div>

                    <div class="tab-pane active" id="3a">
                        <section class="chapter_section hc_section">
                            <div class="student_slide hc_container x_hidden swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events" style="cursor: grab;">
                                <div class="swiper-wrapper" style="transition-duration: 0ms; transform: translate3d(-1666.67px, 0px, 0px);" id="swiper-wrapper-5df4436361263785" aria-live="off">
                                    <!-- TESTIMONIAL CARD --> <!-- Loop Start -->
                                    <?php // select success students
                                    $select = "SELECT * FROM hc_success_student WHERE session = '2020' AND is_delete = 0 ORDER BY college ASC";
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
                                            } ?>
                                            <div class="course_card swiper-slide">
                                                <div class="single-project">
                                                    <div class="wrapper">
                                                        <div class="img-area">
                                                            <div class="inner-area">
                                                                <img src="<?= $photo_img ?>">
                                                            </div>
                                                        </div>
                                                        <div class="name"><?= $name ?></div>
                                                        <div class="about" style="text-align: center">Dhaka Medical College, Dhaka</div>
                                                        <div class="social-icons">
                                                            <a href="#" class="fb"><i class="fa fa-facebook-f"></i></a>
                                                            <a href="#" class="insta"><i class="fa fa-instagram"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- Loop End -->
                                    <?php
                                        }
                                    } ?>
                                </div>
                                <!-- <div class="student_pagination text_center mt_1_5 swiper-pagination-bullets"><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span><span class="swiper-pagination-bullet"></span></div> -->
                                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                            </div>
                            <div class="hc_container all_view_btn mt_1_5">
                                <a href="<?= $base_url ?>success-students/?session=<?= $result['total_success'][3]['session'] ?>" class="button btn_sm no_hover">সকল ছাত্র <i class="bx bx-right-arrow-alt"></i></a>
                            </div>
                        </section>
                    </div>


                    <div class="tab-pane" id="4a">
                        <h3>We use css to change the background color of the content to be equal to the tab</h3>
                    </div>
                </div>
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
            <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        </section>
        <!--=========== BLOG ===========-->
        <section class="testimonial_section blog_section hc_section">
            <div class="hc_container text_center">
                <h1 class="section_title">ব্লগ পড়ুন</h1>
            </div>
            <section class="blog_section hc_section">
                <div class="blog_container hc_container ep_grid">
                    <?php // blog
                    $i = 0;
                    foreach ($result['all_blog'] as $key => $all_blog) {
                        $i++;
                        // blog id
                        $blog_id = $all_blog['id'];
                        // blog date
                        $blog_date = date('d M Y', strtotime($all_blog['created_date']));
                        if ($i < 7) {
                        ?>
                        <!-- BLOG CARD -->
                        <div class="blog__slid">
                            <div class="blog_content">
                                    <?php $all_blog_cover_photo = substr($all_blog['cover_photo'], 2); ?>
                            <img src="<?= $base_url ?>admin<?php echo $all_blog_cover_photo; ?>" alt="">
                            </div>

                            <div class="blog_data">
                                <h1 class="blog_title"><?= $all_blog['name'] ?></h1>
                                <a href="<?= $base_url ?>single-blog/?blog=<?= $blog_id ?>" class="button btn_sm no_hover mb_75 bg_secondary">আরও পড়ুন</a>
                            </div>
                        </div>
                        <?php 
                        }
                    }?>
                </div>
            </section>
            <!-- added value -->

            <div class="hc_container all_view_btn mt_1_5">
                <a href="http://localhost/biohaters/all-blog/" class="button btn_sm no_hover">সকল ব্লগ <i class="bx bx-right-arrow-alt"></i></a>
            </div>
        </section>

        <!--=========== INFORMATION ===========-->
         <section class="info_section hc_section position_relative">
        <!--=========== SECTION ABSOLUTE ELEMENT ===========-->
        <img src="assets/img/foot-frame-top.png" alt="" class="foot_frame_top">
        <img src="assets/img/frame-down-left.png" alt="" class="foot_frame_left">
        <img src="assets/img/frame-down-right.png" alt="" class="foot_frame_right">

        <div class="info_container hc_container ep_grid">
            <!-- INFORMATION CARD -->
            <div class="info_card text_center position_relative">
                <div class="button no_hover icon_lg m_auto info_btn"><i class='bx bx-envelope-open' ></i> info@biohaters.com</div>
                <div class="button no_hover icon_lg m_auto info_btn"><i class='bx bx-phone-call'></i> 01713983345</div>
                <a href="https://goo.gl/maps/WTykNjCugekZFqzg8" target="_blank" class="button no_hover icon_lg m_auto info_btn"><i class='bx bx-map' ></i> Location</a>
            </div>
        </div>
    </section>
    </main>

    <!--=========== FOOTER ===========-->
   <footer class="hc_section">
    <!--=========== UPPER FOOTER ===========-->
    <div class="upper_footer hc_container">
        <!-- foot logo -->
        <div class="foot_logo">
            <img src="assets/img/logo.png" alt="">
        </div>

        <!-- need page list menu -->
        <ul class="need_page_list">
            <li>
                <a href="">About Us</a>
            </li>

            <li>
                <!--<a href="">Privacy Policy</a>-->
                <a href="<?= $base_url ?>free-trial/">Free Trial</a>
            </li>

            <li>
                <a href="">Refund Policy</a>
            </li>

            <li>
                <a href="">Terms and Conditions</a>
            </li>

            <li>
                <a href="<?= $base_url ?>all-course/">Courses</a>
            </li>

            <li>
                <a href="<?= $base_url ?>all-blog/">Blogs</a>
            </li>

            <li>
                <a href="<?= $base_url ?>notice/">Notices</a>
            </li>
        </ul>

        <!-- social menu -->
        <ul class="social_list">
            <li>
                <a href="https://www.facebook.com/groups/BiologyHatersGroup/"><i class='bx bxl-facebook-square'></i></a>
            </li>

            <li>
                <a href="https://www.youtube.com/@BiologyHaters"><i class='bx bxl-youtube' ></i></a>
            </li>

            <li>
                <a href="https://www.facebook.com/BiologyHatersOfficial/"><i class='bx bxl-messenger' ></i></a>
            </li>

            <li>
                <a href=""><i class='bx bxl-whatsapp' ></i></a>
            </li>
        </ul>
    </div>

    <!--=========== DOWN FOOTER ===========-->
    <div class="down_footer">
        <div class="hc_container text_center">
            © <?= date('Y', time()) ?> Biology Haters, All rights reserved, Developed By <a href="https://www.facebook.com/hasan.mehedi.940">Mehedi Hasan</a>
        </div>
    </div>
</footer>

    <!--=========== SWIPER JS ===========-->
    <script src="assets/js/swiper-bundle.min.js"></script>

    <!-- added value -->
    <script>
        var swiper = new Swiper('.testimonial_swiper', {
            slidesPerView: 3,
            direction: getDirection(),
            on: {
                resize: function () {
                swiper.changeDirection(getDirection());
                },
            },
        });

        function getDirection() {
            var windowWidth = window.innerWidth;
            var direction = window.innerWidth <= 760 ? 'vertical' : 'horizontal';

            return direction;
        }
    </script>



    <!--=========== CUSTOM JS ===========-->
    <script>
        function scrollUp() {
            const scrollUp = document.getElementById('scroll-up');
            // When the scroll is higher than 200 viewport height, add the show-scroll class to the a tag with the scroll-top class
            if (this.scrollY >= 200) scrollUp.classList.add('show-scroll');
            else scrollUp.classList.remove('show-scroll')
        }
        window.addEventListener('scroll', scrollUp)

        /*========= MENU SIDEBAR =========*/
        const navMenu = document.getElementById('nav-menu'),
            navToggle = document.getElementById('nav-toggle'),
            navClose = document.getElementById('nav-close')

        /*========= MENU SHOW =========*/
        if (navToggle) {
            navToggle.addEventListener('click', () => {
                navMenu.classList.add('show-menu')
            })
        }

        /*========= MENU HIDE =========*/
        if (navClose) {
            navClose.addEventListener('click', () => {
                navMenu.classList.remove('show-menu')
            })
        }

        /*========= REMOVE MENU MOBILE =========*/
        const navLink = document.querySelectorAll('.nav_link')

        function linkAction() {
            navMenu.classList.remove('show-menu')
        }

        navLink.forEach(n => n.addEventListener('click', linkAction))

        /*========== CHANGE THE TAB =============*/
        const currentLocation = location.href;
        const menuItem = document.querySelectorAll('.nav_menu ul a');
        const menuLength = menuItem.length
        for (let i = 0; i < menuLength; i++) {
            if (menuItem[i].href === currentLocation) {
                menuItem[i].classList.remove('active-link');
                menuItem[i].classList.add('active-link');
            }
        }

        /*=============== SWIPER TESTIMONIAL ==================*/
        var swiper = new Swiper(".testimonial_container", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "1",
            autoplay: true,
            loop: true,
            spaceBetween: 32,
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: false,
            },
            breakpoints: {
                768: {
                    slidesPerView: "auto",
                    spaceBetween: 40,
                },
                1024: {
                    slidesPerView: "auto",
                    spaceBetween: 50,
                },
            },
            pagination: {
                el: ".testimonial_pagination",
            },
        });


        // var swiperTwo = new Swiper(".testimonial_slide", {
        //     effect: "Fade",
        //     grabCursor: true,
        //     centeredSlides: true,
        //     slidesPerView: "1",
        //     autoplay: true,
        //     loop: true,
        //     spaceBetween: 16,
        //     coverflowEffect: {
        //         rotate: 50,
        //         stretch: 0,
        //         depth: 100,
        //         modifier: 10,
        //         slideShadows: false,
        //     },
        //     breakpoints: {
        //         768: {
        //             slidesPerView: "2",
        //             spaceBetween: 24,
        //         },
        //         1024: {
        //             slidesPerView: "3",
        //             spaceBetween: 24,
        //         },
        //     },
        //     pagination: {
        //         el: ".testimonial_pagination",
        //     },
        // });
        
        var swiperTwo = new Swiper(".testimonial_slide", {
            effect: "Fade",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "1",
            autoplay: true,
            loop: true,
            spaceBetween: 16,
            breakpoints: {
                768: {
                    slidesPerView: "2",
                    spaceBetween: 24,
                },
                1024: {
                    slidesPerView: "3",
                    spaceBetween: 24,
                },
            }
        });

        var studentSlide = new Swiper(".student_slide", {
            effect: "Fade",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "1",
            autoplay: true,
            loop: true,
            spaceBetween: 1,
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 10,
                slideShadows: false,
            },
            breakpoints: {
                768: {
                    slidesPerView: "2",
                    spaceBetween: 50,
                },
                1024: {
                    slidesPerView: "3",
                    spaceBetween: 50,
                },
            },
            pagination: {
                el: ".student_pagination",
            },
        });


          $(window).scroll(function () {

            if ($(document).scrollTop() > 2800 && $(document).scrollTop() < 3200) {


                let counter = document.querySelectorAll(".counter")
                let arr = Array.from(counter)
                arr.map((item) => {
                    let count = 0

                    function CounterUp() {
                        count++
                        item.innerHTML = count
                        if (count == item.dataset.number) {
                            clearInterval(stop);
                        }
                    }
                    let stop = setInterval(
                        function () {
                            CounterUp();
                        }, 1800 / item.dataset.speed
                    );
                })

            } else {


            }
        });
        
        $(document).ready(function () {
            $('#2a').hide();
            $('#3a').hide();
        });

        $(document).on('click', '#two_slide', function () {
            $('#2a').show();
            $('#1a').hide();
            $('#3a').hide();
        });
        $(document).on('click', '#one_slide', function () {
            $('#2a').hide();
            $('#1a').show();
            $('#3a').hide();
        });
        $(document).on('click', '#there_slide', function () {
            $('#1a').hide();
            $('#2a').hide();
            $('#3a').show();
        });

    $(document).keypress('u', function (e) {
        if (e.ctrlKey) {
            return false;
        } else {
            return true;
        }
    });
               
    document.addEventListener('contextmenu', event => event.preventDefault()); 
    
    </script>
</body>

</html>