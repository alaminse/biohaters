<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_POST['publish'])) {
    $combined_title = mysqli_escape_string($db, $_POST['combined_title']);
    $course_id      = mysqli_escape_string($db, $_POST['course_id']);
    $exam_array     = mysqli_escape_string($db, $_POST['exam_array']);
    $scheduled      = $_POST['scheduled'];
    
    // insert list
    $insert_combined_list = "INSERT INTO hc_combined_list (name, course, exams, author, created_date) VALUES ('$combined_title', '$course_id', '$exam_array', '$admin_id', '$scheduled')";
    $sql_combined_list = mysqli_query($db, $insert_combined_list);
    
    // get combined list id if added
    $combined_list_id = mysqli_insert_id($db);
    
    foreach ($_POST["roll"] as $index => $roll) {
        $roll       = mysqli_escape_string($db, $roll);
        $name       = mysqli_escape_string($db, $_POST['name'][$index]);
        $rank       = mysqli_escape_string($db, $_POST['rank'][$index]);
        $marking    = mysqli_escape_string($db, $_POST['marking'][$index]);
        $college    = mysqli_escape_string($db, $_POST['college'][$index]);
        
        // insert result
        $insert_result = "INSERT INTO hc_combined_result (combined_list_id, rank, name, roll, marking, college, author, insert_date) VALUES ('$combined_list_id', '$rank', '$name', '$roll', '$marking', '$college', '$admin_id', '$scheduled')";
        $sql_result = mysqli_query($db, $insert_result);
    }?>
    <script type="text/javascript">
        window.location.href = '../result-combine/';
    </script>
    <?php 
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Combine List</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <?php $exam_array = '';
            if (isset($_GET['select_exam'])) {
                foreach($_GET['select_exam'] as $exam_id) {
                    $exam_array = $exam_id . ',' . $exam_array;
                }
                
                $exam_array = substr($exam_array, 0, -1);
                
                $course_id = $_GET['course'];
            } else {
                $course_id = $_GET['course'];
                
                // select course exam
                $select_exam = "SELECT * FROM hc_exam WHERE course_id = '$course_id' AND mcq = 1 AND status = 1 AND is_delete = 0";
                $sql_exam = mysqli_query($db, $select_exam);
                $num_exam = mysqli_num_rows($sql_exam);
                if ($num_exam > 0) {
                    while ($row_exam = mysqli_fetch_assoc($sql_exam)) {
                        $exam_id = $row_exam['id'];
                        
                        $exam_array = $exam_id . ',' . $exam_array;
                    }
                    
                    $exam_array = substr($exam_array, 0, -1);
                }
            }
            
            $select = "SELECT *, SUM(score) as total_mark FROM hc_exam_attempt WHERE exam IN ($exam_array) AND submission_status = 'In Time' GROUP BY student_id";
            $sql = mysqli_query($db, $select);
            $num = mysqli_num_rows($sql);
            if ($num > 0) {
                $combine_data = array();
                while ($row = mysqli_fetch_assoc($sql)) {
                    $student_id = $row['student_id'];
                    $total_mark = $row['total_mark'];
                    
                    // fetch student info
                    $select_student_info = "SELECT * FROM hc_student WHERE id = '$student_id'";
                    $sql_student_info = mysqli_query($db, $select_student_info);
                    $num_student_info = mysqli_num_rows($sql_student_info);
                    if ($num_student_info > 0) {
                        while ($row_student_info = mysqli_fetch_assoc($sql_student_info)) {
                            $student_name       = $row_student_info['name'];
                            $student_roll       = $row_student_info['roll'];
                            $student_college    = $row_student_info['college'];
                        }
                    }
                    
                    // $total_mark = 0;
                    // $correct    = 0;
                    // $wrong      = 0;
                    
                    // $single_exam = explode(',', $exam_array);
                    // foreach ($single_exam as $exam_id) {
                    //     // exam valid time
                    //     $fetch_valid_time = "SELECT id, valid_time FROM hc_exam WHERE id = '$exam_id'";
                    //     $sql_valid_time = mysqli_query($db, $fetch_valid_time);
                    //     $num_valid_time = mysqli_num_rows($sql_valid_time);
                    //     if ($num_valid_time > 0) {
                    //         $row_valid_time = mysqli_fetch_assoc($sql_valid_time);
                    //         $valid_time = $row_valid_time['valid_time'];
                            
                    //         // valid attempt
                    //         $fetch_valid_attempt = "SELECT * FROM hc_exam_attempt WHERE exam = '$exam_id' AND student_id = '$student_id' AND attempt_date <= '$valid_time'";
                    //         $sql_valid_attempt = mysqli_query($db, $fetch_valid_attempt);
                    //         $num_valid_attempt = mysqli_num_rows($sql_valid_attempt);
                    //         if ($num_valid_attempt > 0) {
                    //             // fetch student attempt
                    //             $fetch_attempt_answer = "SELECT * FROM hc_attempt_answer WHERE student_id = '$student_id' AND exam = '$exam_id' AND submitted_option != '0'";
                    //             $sql_attempt_answer = mysqli_query($db, $fetch_attempt_answer);
                    //             $num_attempt_answer = mysqli_num_rows($sql_attempt_answer);
                    //             if ($num_attempt_answer > 0) {
                    //                 while ($row_attempt_answer = mysqli_fetch_assoc($sql_attempt_answer)) {
                    //                     $question = $row_attempt_answer['question'];
                    //                     $submitted_option = $row_attempt_answer['submitted_option'];
                                        
                    //                     // check correct or wrong
                    //                     $fetch_check_answer = "SELECT * FROM hc_question_option WHERE id = '$submitted_option' AND question = '$question' AND is_correct = 1";
                    //                     $sql_check_answer = mysqli_query($db, $fetch_check_answer);
                    //                     $num_check_answer = mysqli_num_rows($sql_check_answer);
                    //                     if ($num_check_answer > 0) {
                    //                         $correct++;
                    //                     } else {
                    //                         $wrong++;
                    //                     }
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }
                    
                    // $total_mark = ($correct * 1) - ($wrong * 0.25);
                    
                    if ($total_mark > 0) {
                        $combine_data['student_data'][] = array(
                            'student_id'    => $student_id,
                            'name'          => $student_name,
                            'roll'          => $student_roll,
                            'college'       => $student_college,
                            'total_mark'    => $total_mark,
                        );
                    }
                }
            }?>
            
            <form action="" method="post" class="ep_grid mb_75">
                <div class="double_col_form">
                    <div>
                        <label>Combined List Title*</label>
                        <input type="text" name="combined_title" placeholder="Write a combined title">
                    </div>
                    
                    <div>
                        <label>Result Publish Schedule*</label>
                        <input type="datetime-local" id="" name="scheduled">
                    </div>
                </div>
                
                <input type="hidden" name="course_id" value="<?= $course_id ?>">
                <input type="hidden" name="exam_array" value="<?= $exam_array ?>">
                
                <button type="submit" name="publish">Publish</button>
                
                <table class="ep_table" id="datatable">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Roll</th>
                            <th>Total Mark Obtained</th>
                            <th>College</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($combine_data['student_data'])) {
                            // Sort the student data based on gain marks in descending order
                            usort($combine_data['student_data'], function ($a, $b) {
                                return ($b['total_mark'] <=> $a['total_mark']);
                            });
    
                            // Initialize rank counter and previous gain marks
                            $rank = 1;
    
                            // Loop through the sorted student data and display the data
                            foreach ($combine_data['student_data'] as $key => $student_data) {
                                $student_id         = $student_data['student_id'];
                                $student_name       = $student_data['name'];
                                $student_roll       = $student_data['roll'];
                                $student_college    = $student_data['college'];
                                $gain_mark          = $student_data['total_mark'];
    
                                // Check for ties in ranks
                                if ($key > 0 && $student_data['total_mark'] !== $combine_data['student_data'][$key - 1]['total_mark']) {
                                    // If the current gain marks are different from the previous student, update the rank
                                    $rank++;
                                }?>
                                <tr>
                                    <td>
                                        <?= $rank ?>
                                        <input type="hidden" name="rank[]" value="<?= $rank ?>">
                                        <input type="hidden" name="name[]" value="<?= $student_name ?>">
                                        <input type="hidden" name="roll[]" value="<?= $student_roll ?>">
                                        <input type="hidden" name="marking[]" value="<?= $gain_mark ?>">
                                        <input type="hidden" name="college[]" value="<?= $student_college ?>">
                                    </td>
                                    <td><?= $student_name ?></td>
                                    <td><?= $student_roll ?></td>
                                    <td><?= $gain_mark ?></td>
                                    <td><?= $student_college ?></td>
                                </tr>
                                <?php 
                            }
                        }?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</main>

<!--=========== DATATABLE ===========-->
<!--<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>-->

<script>
/*========= DATATABLE CUSTOM =========*/
// $(document).ready( function () {
//     $('#datatable').DataTable( {
//         dom: 'Bfrtip',
//         // order: [[0, 'desc']],
//         pageLength: 25,
//         buttons: [
//             'copy', 'csv', 'excel', 'pdf', 'print'
//         ]
//     } );
// } );
</script>

<?php include('../assets/includes/footer.php'); ?>