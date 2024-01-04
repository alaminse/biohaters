<?php // include database
include('../db/db.php');

// Check if the checkbox status is received via POST
if (isset($_POST['selectedOption']) && $_POST['selectedOption'] != '') {
    // Perform any PHP operations or database queries you want to execute here
    $course = $_POST['selectedOption'];

    $select = "SELECT * FROM hc_course WHERE id = '$course' AND type = 0";
    $sql = mysqli_query($db, $select);
    $num = mysqli_num_rows($sql);
    if ($num > 0) {
        while ($row = mysqli_fetch_assoc($sql)) {
            $course_id      = $row['id'];
            $course_name    = $row['name'];
            $course_type    = $row['type'];

            $select_batch = "SELECT * FROM hc_course_batch WHERE course = '$course' AND is_delete = 0 ORDER BY id ASC";
            $sql_batch = mysqli_query($db, $select_batch);
            $num_batch = mysqli_num_rows($sql_batch);
            if ($num_batch > 0) {
                $response = '';
                $response .=    '<div>
                                    <label for="">Course Batch*</label>
                                    <select id="" name="batch" required>
                                        <option value="">Choose Batch</option>';

                while ($row_batch = mysqli_fetch_assoc($sql_batch)) {
                    $batch_id           = $row_batch['id'];
                    $batch_name         = $row_batch['name'];
                    $batch_start_time   = $row_batch['start_time'];
                    $batch_end_time     = $row_batch['end_time'];
                    
                    $batch_start_time = date('h:i a', strtotime($batch_start_time));
                    $batch_end_time = date('h:i a', strtotime($batch_end_time));

                    $response .= '<option value="' . $batch_id . '">' . $batch_name . ' (' . $batch_start_time . '-' . $batch_end_time . ')</option>';
                }

                $response .=    '</select>
                            </div>';

                $response .= '<div>
                                <label for="">Gender*</label>
                                <select id="" name="gender" required>
                                    <option value="">Choose Gender</option>
                                    <option value="1">Male</option>
                                    <option value="0">Female</option>
                                    <option value="2">Others</option>
                                </select>
                            </div>
            
                            <div>
                                <label for="">Guardian Phone*</label>
                                <input type="text" id="" name="guardian_phone" placeholder="Guardian Phone Number" required>
                            </div>
                            
                            <div>
                                <label for="">Next Installment*</label>
                                <input type="date" id="" name="due_date" required>
                            </div>';
            } else {
                // In case the checkbox status is not set or is unchecked
                $response = "";
            }
            
        }
    } else {
        // In case the checkbox status is not set or is unchecked
        $response = "";
    }
    
} else {
    // In case the checkbox status is not set or is unchecked
    $response = "";
}

// Send the response back to the AJAX call
echo $response;
?>