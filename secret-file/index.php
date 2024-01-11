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

<?php // check secret file 
$select_secret  = "SELECT * FROM hc_secret_file_entry WHERE student_id = '$student_id' AND is_expired = '0'";
$sql_secret     = mysqli_query($db, $select_secret);
$num_secret     = mysqli_num_rows($sql_secret);
if ($num_secret == 0) {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>dashboard/';
    </script>
    <?php 
}?>

<!--=========== PAGE TITLE SECTION ===========-->
<section class="page_section hc_section">
    <div class="ep_flex hc_container">
        <h3 class="hc_page_title">Secret File</h3>

        <a href="<?= $base_url ?>secret-file/?add" class="button">Add Mark</a>
    </div>
</section>

<?php if (isset($_POST['update_attempt'])) {
    $day = $_POST['day'];
    $attempt = $_POST['attempt'];
    $marks = mysqli_escape_string($db, $_POST['marks']);

    $update_attempt = "UPDATE secret_file SET $day = '$marks' WHERE student_id = '$student_id' AND attempt = '$attempt'";
    $sql_update_attempt = mysqli_query($db, $update_attempt);

    if ($sql_update_attempt) {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>secret-file/';
        </script>
        <?php 
    }
}?>

<?php if (isset($_GET['add'])) {
    ?>
    <!--=========== PURCHASE HISTORY SECTION ===========-->
    <section class="hc_section">
        <div class="hc_container">
            <h4 class="form_title">Update Your Marks</h3>
            <p class="form_subtitle">Add your mark of secret file attempt to compare yourself with your friends and topper</p>

            <form action="" method="post" class="double_col_form">
                <div>
                    <label for="">Day *</label>
                    <select name="day" id="" required>
                        <option value="">Choose Day</option>
                        <?php $question_set_code = 1020323001;
                        for ($i=1; $i < 112 ; $i++) { 
                            ?>
                            <option value="day<?php echo $i; ?>">Day <?php echo $i; ?> [Set Code: <?php echo $question_set_code++; ?>]</option>
                            <?php 
                        }?>
                    </select>
                </div>

                <div>
                    <label for="">Attempt *</label>
                    <select name="attempt" id="" required>
                        <option value="">Choose Attempt</option>
                        <option value="1">Attempt 1</option>
                        <option value="2">Attempt 2</option>
                        <option value="3">Attempt 3</option>
                    </select>
                </div>

                <div class="profile_setting_input">
                    <label for="">Marks *</label>
                    <input type="text" id="" name="marks" placeholder="Your Marks" required>
                </div>

                <button type="submit" name="update_attempt" class="profile_setting_button">Update Your Marks</button>
            </form>
        </div>
    </section>
    <?php 
}?>

<!--=========== ALERT SECTION ===========-->
<section class="profile_alert_section hc_section">
    <div class="hc_container">
        <div class="hc_alert hc_alert_danger">
            <h4 class="hc_alert_title">Secret Files: War Edition - ৬৪ নাম্বার সেট</h4>
            <h6 class="hc_alert_message">যারা ৬৪ নাম্বার সেটটি ভুল পেয়েছেন তারা এখান থেকে ডাউনলোড করে নিতে পারবেন-</h6>
            <a href="http://localhost/biohaters/admin/assets/doc_gallery/hc_640909_353109_115758_20231029010821_1020323064.pdf" target="_blank">Download Now</a>
        </div>
    </div>
</section>

<section class="profile_alert_section hc_section">
    <div class="hc_container">
        <div class="hc_alert hc_alert_info">
            <h4 class="hc_alert_title">Telegram Link</h4>
            <h6 class="hc_alert_message">
                হ্যালো, এভ্রিওয়ান!<br>
                সিক্রেট ফাইলস এর যেকোনো সমস্যার মোকাবিলা হবে খুব সহজে, খুব দ্রুত। এই উদ্দেশ্যে একটি টেলিগ্রাম গ্রুপ খোলা হয়েছে। সেখানে তোমরা সিক্রেট ফাইলস বিষয়ক যাবতীয় সমস্যার কথা জানাতে পারবে। আমরা সমাধান করবো।
                তাই দেরী না করে যুক্ত হয়ে যাও টেলিগ্রাম গ্রুপে।
            </h6>
            <a href="https://t.me/+wtd7CBEIxWQ2YzVl" target="_blank">টেলিগ্রাম গ্রুপে জয়েন করতে এখানে ক্লিক করো...</a><br>
            <h6 class="hc_alert_message">
                সিক্রেট ফাইলসের সলভটিকে আমরা নিখুঁত করার সর্বোচ্চ চেষ্টা করেছি, তবে তাও কিছু অনাকাঙ্ক্ষিত ভুল রয়ে গিয়েছে। এখন আমরা ভুল দেখা মাত্রই সলভ আপডেট করছি। অনেকটা নিঁখুত হয়েছে ইতোমধ্যে। তাই এক্ষেত্রে অনুরোধ থাকবে আপনি যখন সলভ করবেন তখন যে ফাইল ওয়েবসাইটে পাবেন সেখান থেকে সলভ করবেন। এতে আপনি আপডেটেড সলভ পাবেন। ভোগান্তি কমবে।
<br>গুড লাক।
            </h6>
        </div>
    </div>
</section>

<!--=========== PURCHASE HISTORY SECTION ===========-->
<section class="hc_section">
    <div class="graph_container hc_container ep_grid">
        <div class="ep_flex ep_start mb_75" style="gap: 2rem;">
            <h4 class="hc_card_title hc_alert hc_alert_success" style="padding: 0.5rem 1rem;">Progress Graph - <?php if (isset($_GET['attempt'])) {
                if ($_GET['attempt'] == 2) {
                    echo '2nd Attempt';
                } elseif ($_GET['attempt'] == 3) {
                    echo '3rd Attempt';
                }
            } else {
                echo '1st Attempt';
            }?></h4>
            
            <div class="hc_dropdown hc_alert hc_alert_danger" style="padding: 0.5rem 1rem;">
                <div class="hc_dropdown_wrapper btn_icon switch_toggle">
                    <!--<i class='bx bx-dots-horizontal-rounded hc_dropdown_btn hc_dropdown_icon'></i>-->
                    <div class="hc_dropdown_btn hc_dropdown_icon">Switch <i class='bx bx-repost hc_dropdown_btn'></i></div>
                </div>
                
                <div class="hc_dropdown_list profile_dropdown_list">
                    <!-- ATTEMPT NAME -->
                    <a href="<?= $base_url ?>secret-file/">1st Attempt</a>
                    <a href="<?= $base_url ?>secret-file/?attempt=2">2nd Attempt</a>
                    <a href="<?= $base_url ?>secret-file/?attempt=3">3rd Attempt</a>
                </div>
            </div>
        </div>
        
        <?php if (isset($_GET['attempt'])) {
            $attempt = $_GET['attempt'];
        } else {
            $attempt = 1;
        }?>

        <div id="myChart" class="graph"></div>
    </div>
</section>

<section class="hc_section">
    <div class="hc_container ep_grid secret_file_grid">
        <div class="secret_file_scoreboard">
            <div class="ep_flex mb_75">
                <h4 class="hc_card_title">Scoreboard - <?php $set = 1020323000;
                if (isset($_GET['set'])) {
                    $set += $_GET['set'];
                } else {
                    $set += 1;
                }
                
                echo $set; ?></h4>
            </div>
            
            <div class="">
                <table class="scoreboard_table mb_75">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Roll</th>
                            <th>Mark</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (isset($_GET['set'])) {
                            $day = 'day' . $_GET['set'];
                        } else {
                            $day = 'day1';
                        }
                        
                        // fetch leaderboard
                        $fetch_scoreboard = "SELECT * FROM secret_file WHERE attempt = $attempt ORDER BY $day DESC LIMIT 15";
                        $sql_fetch_scoreboard = mysqli_query($db, $fetch_scoreboard);
                        $num_fetch_scoreboard = mysqli_num_rows($sql_fetch_scoreboard);
                        if ($num_fetch_scoreboard > 0) {
                            $si = 0; // Initialize $si
                            $prev_mark = null; // Initialize $prev_mark
                            while ($row_fetch_scoreboard = mysqli_fetch_assoc($sql_fetch_scoreboard)) {
                                $secret_student_id  = $row_fetch_scoreboard['student_id'];
                                $mark               = $row_fetch_scoreboard[$day];
                                
                                // Check if the current mark is different from the previous mark
                                if ($mark !== $prev_mark) {
                                    $si++; // Increment $si only when the mark is different
                                }
                                
                                // Update the previous mark
                                $prev_mark = $mark;
                                
                                // fetch student data
                                $fetch_student_data = "SELECT * FROM hc_student WHERE id = '$secret_student_id'";
                                $sql_student_data = mysqli_query($db, $fetch_student_data);
                                $num_student_data = mysqli_num_rows($sql_student_data);
                                if ($num_student_data > 0) {
                                    while ($row_student_data = mysqli_fetch_assoc($sql_student_data)) {
                                        $secret_student_name        = $row_student_data['name'];
                                        $secret_student_roll        = $row_student_data['roll'];
                                        $secret_student_college     = $row_student_data['college'];
                                    }
                                }?>
                                <tr>
                                    <td><?= $si ?></td>
                                    <td><?= $secret_student_name ?></td>
                                    <td><?= $secret_student_roll ?></td>
                                    <td><?= $mark ?></td>
                                </tr>
                                <?php 
                            }
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="secret_file_scoreboard">
            <div class="ep_flex mb_75">
                <h4 class="hc_card_title">Set Table</h4>
            </div>
            
            <div class="">
                <table class="scoreboard_table w_100 mb_75">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Set Code</th>
                            <th>My Position</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $question_set_code = 1020323001;
                        $set_code = array();
                        for ($i=1; $i < 112 ; $i++) {
                            $day = 'day' . $i;
                            
                            // fetch set details
                            $fetch_setdetails = "SELECT * FROM secret_file_pattern WHERE setcode = $question_set_code";
                            $sql_fetch_setdetails = mysqli_query($db, $fetch_setdetails);
                            $num_fetch_setdetails = mysqli_num_rows($sql_fetch_setdetails);
                            if ($num_fetch_setdetails > 0) {
                                while ($row_fetch_setdetails = mysqli_fetch_assoc($sql_fetch_setdetails)) {
                                    $set_subject    = $row_fetch_setdetails['subject'];
                                    $set_des        = $row_fetch_setdetails['description'];
                                }
                            }
                            
                            // fetch leaderboard
                            $fetch_scoreboard = "SELECT * FROM secret_file WHERE attempt = $attempt ORDER BY $day DESC";
                            $sql_fetch_scoreboard = mysqli_query($db, $fetch_scoreboard);
                            $num_fetch_scoreboard = mysqli_num_rows($sql_fetch_scoreboard);
                            if ($num_fetch_scoreboard > 0) {
                                $si = 0; // Initialize $si
                                $prev_mark = null; // Initialize $prev_mark
                                while ($row_fetch_scoreboard = mysqli_fetch_assoc($sql_fetch_scoreboard)) {
                                    $secret_student_id  = $row_fetch_scoreboard['student_id'];
                                    $mark               = $row_fetch_scoreboard[$day];
                                    
                                    // Check if the current mark is different from the previous mark
                                    if ($mark !== $prev_mark) {
                                        $si++; // Increment $si only when the mark is different
                                    }
                                    
                                    // Update the previous mark
                                    $prev_mark = $mark;
                                    
                                    if ($secret_student_id == $student_id) {
                                        $my_rank = $si;
                                    }
                                }
                            }
                            
                            // check solve
                            $select_solve = "SELECT * FROM secret_file_solve WHERE file_set = '$day'";
                            $sql_solve = mysqli_query($db, $select_solve);
                            $num_solve = mysqli_num_rows($sql_solve);
                            if ($num_solve > 0) {
                                while ($row_solve = mysqli_fetch_assoc($sql_solve)) {
                                    $solve_day  = $row_solve['file_set'];
                                    $solve_link = $row_solve['solve_link'];
                                }
                            }?>
                            <tr>
                                <td><?= $i ?></td>
                                <td class="text_left">
                                    <?= $question_set_code ?><br>
                                    <span class="text_smr"><?= $set_subject ?> || <?= $set_des ?></span>
                                </td>
                                <td><?= $my_rank ?><?php if ($my_rank == 1) { echo 'st'; } elseif ($my_rank == 2) { echo 'nd'; } elseif ($my_rank == 3) { echo 'rd'; } else { echo 'th'; }?></td>
                                <td>
                                    <div class="btn_grp">
                                        <?php if ($num_solve > 0) {
                                            ?>
                                            <a href="<?= $solve_link ?>" class="ep_flex ep_start button btn_sm text_sm">Solve</a>
                                            <?php 
                                        }?>
                                        <a href="<?= $base_url ?>secret-file-score/?set=<?= $i ?>" class="ep_flex ep_start button btn_outline btn_sm text_sm">Scoreboard</a>
                                    </div>
                                </td>
                            </tr>
                            <?php $question_set_code++;
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php // initialize label
$chart_label = "";


if ($attempt == 1) {
    // fetch topper
    $fetch_topper = "SELECT * FROM secret_file WHERE attempt = $attempt";
    $sql_fetch_topper = mysqli_query($db, $fetch_topper);
    $num_fetch_topper = mysqli_num_rows($sql_fetch_topper);
    if ($num_fetch_topper > 0) {
        $score = array();
        while ($row_fetch_topper = mysqli_fetch_assoc($sql_fetch_topper)) {
            $secret_student = $row_fetch_topper['student_id'];
            $total_gain = 0;
            for ($i = 1; $i < 112; $i++) {
                $mark = $row_fetch_topper['day' . $i];
                if ($mark == null) {
                    $mark = 0;
                }
                $total_gain += $mark;
            }
            
            // Create an array for each student and their total gain
            $student_data = array(
                'student_id' => $secret_student,
                'total_gain' => $total_gain,
            );
        
            // Add student data to the $score array
            $score[] = $student_data;
        }
    }
    
    // Sort the $score array by 'total_gain' in descending order
    usort($score, function ($a, $b) {
        return $b['total_gain'] - $a['total_gain'];
    });
    
    // Take the highest total_gain data (the first element after sorting)
    if (!empty($score)) {
        $highest_total_gain = $score[0];
        // You can access the student_id and total_gain of the highest scorer like this:
        $highest_student_id = $highest_total_gain['student_id'];
    } else {
        $highest_student_id = $student_id;
    }
    
    // fetch self attempt details
    $fetch_self_attempt = "SELECT * FROM secret_file WHERE student_id = '$student_id' AND attempt = '$attempt'";
    $sql_self_attempt = mysqli_query($db, $fetch_self_attempt);
    $num_self_attempt = mysqli_num_rows($sql_self_attempt);
    if ($num_self_attempt > 0) {
        while ($row_self_attempt = mysqli_fetch_assoc($sql_self_attempt)) {
            $fetch_topper_attempt = "SELECT * FROM secret_file WHERE student_id = '$highest_student_id' AND attempt = '$attempt'";
            $sql_topper_attempt = mysqli_query($db, $fetch_topper_attempt);
            $num_topper_attempt = mysqli_num_rows($sql_topper_attempt);
            if ($num_topper_attempt > 0) {
                $row_topper_attempt = mysqli_fetch_assoc($sql_topper_attempt);
            }
            for ($i = 1; $i < 112; $i++) {
                $topper_mark = $row_topper_attempt['day' . $i];
                $mark = $row_self_attempt['day' . $i];
                if ($topper_mark == null) {
                    $topper_mark = 0;
                }
                if ($mark == null) {
                    $mark = 0;
                }
                $chart_label .= "{ day: 'Set" . $i . "', a: " . $topper_mark . ", b: " . $mark . " },";
            }
        }
    }
} else {
    // fetch self attempt details
    $fetch_self_attempt = "SELECT * FROM secret_file WHERE student_id = '$student_id' AND attempt = '$attempt'";
    $sql_self_attempt = mysqli_query($db, $fetch_self_attempt);
    $num_self_attempt = mysqli_num_rows($sql_self_attempt);
    if ($num_self_attempt > 0) {
        while ($row_self_attempt = mysqli_fetch_assoc($sql_self_attempt)) {
            for ($i = 1; $i < 112; $i++) {
                $mark = $row_self_attempt['day' . $i];
                if ($mark == null) {
                    $mark = 0;
                }
                $chart_label .= "{ day: 'Set" . $i . "', a: " . $mark . " },";
            }
        }
    }
}

$chart_label = substr($chart_label, 0 , -1);
if ($attempt == 1) {
    $ykeys = "['a', 'b']";
    $labels = "['Topper', 'Self']";
    $linecolors = "['#6CB71D', '#FDC894']";
} else {
    $ykeys = "['a']";
    $labels = "['Self']";
    $linecolors = "['#6CB71D']";
}?>

<script>
// Define the data for the chart
var data = [
    <?= $chart_label ?>
];

// Create and render the chart
new Morris.Line({
    element: 'myChart',
    parseTime: false,
    data: data,
    xkey: 'day',
    ykeys: <?= $ykeys ?>,
    labels: <?= $labels ?>, // Label for each line
    lineColors: <?= $linecolors ?>, // Colors for the lines
    behaveLikeLine: true,
    resize: true
});
</script>

<?php include('../assets/includes/dashboard_footer.php'); ?>