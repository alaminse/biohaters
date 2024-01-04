<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['exam'])) {
    $exam = $_GET['exam'];

    if (empty($exam)) {
        ?>
        <script type="text/javascript">
            window.location.href = '../exam/';
        </script>
        <?php 
    }

    // get exam name
    $select_exam  = "SELECT * FROM hc_exam WHERE id = '$exam' AND is_delete = 0";
    $sql_exam     = mysqli_query($db, $select_exam);
    $row_exam     = mysqli_fetch_assoc($sql_exam);

    $exam_id                = $row_exam['id'];
    $exam_name              = $row_exam['name'];
    $exam_course            = $row_exam['course_id'];
    $exam_cq_mark    = $row_exam['mark'];
    ?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">CQ Questions</h4>
        </div>
    </div>
    
    <!-- NOTICE LIST -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE NOTICE ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Submit List - <?= $exam_name ?></h5>
                </div>
                
                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Name</th>
                            <th>Roll</th>
                            <th>Phone</th>
                            <th>Submitted Copy</th>
                            <th>Submitted Time</th>
                            <th>Mark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_cq_attempt WHERE exam = '$exam_id' AND submission_status = 'In Time' ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num == 0) {
                            echo "<tr><td colspan='7' class='text_center'>There are no submitted copy</td></tr>";
                        } else {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $submitted_copy_id          = $row['id'];
                                $submitted_copy_student_id  = $row['student_id'];
                                $submitted_copy_exam        = $row['exam'];
                                $submitted_copy_pdf         = $row['submitted_pdf'];
                                $submitted_copy_submit_time = $row['submit_time'];
                                
                                $submitted_copy_submit_time = date('d-m-Y, h:i a', strtotime($submitted_copy_submit_time));
                                
                                // fetch student data
                                $select_student_data = "SELECT * FROM hc_student WHERE id = '$submitted_copy_student_id'";
                                $sql_student_data = mysqli_query($db, $select_student_data);
                                $num_student_data = mysqli_num_rows($sql_student_data);
                                if ($num_student_data > 0) {
                                    while ($row_student_data = mysqli_fetch_assoc($sql_student_data)) {
                                        $student_data_name  = $row_student_data['name'];
                                        $student_data_phone = $row_student_data['phone'];
                                        $student_data_roll  = $row_student_data['roll'];
                                    }
                                }
                                
                                // fetch cq mark
                                $select_cq_mark = "SELECT * FROM hc_cq_marking WHERE student_id = '$submitted_copy_student_id' AND exam = '$exam_id'";
                                $sql_cq_mark = mysqli_query($db, $select_cq_mark);
                                $num_cq_mark = mysqli_num_rows($sql_cq_mark);
                                if ($num_cq_mark > 0) {
                                    $cq_mark = 0;
                                    while ($row_cq_mark = mysqli_fetch_assoc($sql_cq_mark)) {
                                        $cq_marking  = $row_cq_mark['marking'];
                                        
                                        $cq_mark += $cq_marking;
                                    }
                                } else {
                                    $cq_mark = '--';
                                }
                                
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td><?php echo $student_data_name; ?></td>
                                    
                                    <td><?php echo $student_data_roll; ?></td>
                                    
                                    <td><?php echo $student_data_phone; ?></td>
                                    
                                    <td><a href="<?php echo $submitted_copy_pdf; ?>" download class="ep_badge bg_info text_info"><i class='bx bxs-download'></i> Download</a></td>
                                    
                                    <td><?php echo $submitted_copy_submit_time; ?></td>
                                    
                                    <td><?php echo $cq_mark . ' / ' . $exam_cq_mark?></td>
                                    
                                    <td><a href="../cq-marking/?exam=<?php echo $exam_id; ?>&student_id=<?php echo $submitted_copy_student_id; ?>" target="_blank" class="btn_icon"><i class="bx bxs-message-square-check"></i></a></td>
                                </tr>
                                <?php 
                            }
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php } else { ?><script type="text/javascript">window.location.href = '../exam/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>