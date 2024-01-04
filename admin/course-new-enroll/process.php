<?php
// Check if the checkbox status is received via POST
if (isset($_POST['checkboxStatus']) && $_POST['checkboxStatus'] == 1) {
    // Perform any PHP operations or database queries you want to execute here
    // For this example, let's return a simple message
    $response = '<div>
                    <label for="">Discount By</label>
                    <input type="text" id="" name="discount_by" placeholder="Discount By">
                </div>

                <div>
                    <label for="">Discount Reason</label>
                    <input type="text" id="" name="discount_reason" placeholder="Discount Reason">
                </div>

                <div>
                    <label for="">Discount Amount</label>
                    <input type="text" id="" name="discount_amount" placeholder="Discount Amount">
                </div>';
} else {
    // In case the checkbox status is not set or is unchecked
    $response = "";
}

// Send the response back to the AJAX call
echo $response;
?>