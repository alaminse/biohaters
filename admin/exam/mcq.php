<?php
// Check if the checkbox status is received via POST
if (isset($_POST['checkboxStatus']) && $_POST['checkboxStatus'] == 1) {
    // Perform any PHP operations or database queries you want to execute here
    // For this example, let's return a simple message
    $response_mcq = '<div>
                    <label for="">Total Question</label>
                    <input type="text" id="" name="total_question" placeholder="Total Question">
                </div>

                <div>
                    <label for="">Mark Per Question</label>
                    <input type="text" id="" name="mark_per_question" placeholder="Mark Per Question">
                </div>

                <div>
                    <label for="">Negative Marking Question</label>
                    <input type="text" id="" name="negative_marking" placeholder="Negative Marking Question">
                </div>

                <div>
                    <label for="">Duration*</label>
                    <div class="ep_grid grid_2">
                        <input type="text" id="" name="mcq_duration_number" placeholder="Duration Number">
                        <input type="text" id="" name="mcq_duration_type" value="Minute" readonly="">
                    </div>
                </div>';
} else {
    // In case the checkbox status is not set or is unchecked
    $response_mcq = "";
}

// Send the response back to the AJAX call
echo $response_mcq;
?>