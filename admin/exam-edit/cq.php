<?php
// Check if the checkbox status is received via POST
if (isset($_POST['checkboxStatus']) && $_POST['checkboxStatus'] == 1) {
    // Perform any PHP operations or database queries you want to execute here
    // For this example, let's return a simple message
    $response_cq = '<div>
                    <label for="">Mark</label>
                    <input type="text" id="" name="cq_mark" placeholder="CQ Mark">
                </div>

                <div>
                    <label for="">Duration*</label>
                    <div class="ep_grid grid_2">
                        <input type="text" id="" name="cq_duration_number" placeholder="Duration Number">
                        <input type="text" id="" name="cq_duration_type" value="Minute" readonly="">
                    </div>
                </div>';
} else {
    // In case the checkbox status is not set or is unchecked
    $response_cq = "";
}

// Send the response back to the AJAX call
echo $response_cq;
?>