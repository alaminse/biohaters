<?php include('../assets/includes/dashboard_header.php'); ?>

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
                        $fetch_scoreboard = "SELECT * FROM secret_file WHERE attempt = 1 ORDER BY $day DESC";
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
                            
                            // fetch leaderboard
                            $fetch_scoreboard = "SELECT * FROM secret_file WHERE attempt = 1 ORDER BY $day DESC";
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
                            }?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $question_set_code++ ?></td>
                                <td><?= $my_rank ?><?php if ($my_rank == 1) { echo 'st'; } elseif ($my_rank == 2) { echo 'nd'; } elseif ($my_rank == 3) { echo 'rd'; } else { echo 'th'; }?></td>
                                <td>
                                    <div class="btn_grp">
                                        <a href="<?= $base_url ?>exam-attempt/?exam=<?= $exam_id ?>" class="ep_flex ep_start button btn_sm text_sm">Solve PDF</a>
                                        <a href="<?= $base_url ?>secret-file-score/?set=<?= $i ?>" class="ep_flex ep_start button btn_outline btn_sm text_sm">Scoreboard</a>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include('../assets/includes/dashboard_footer.php'); ?>