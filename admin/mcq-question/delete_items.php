<?php
// Include your database connection or any necessary configurations
include('../db/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_ids'])) {
    // Sanitize and prepare the IDs for SQL
    $delete_ids = $_POST['delete_ids'];
    $sanitized_ids = implode(',', array_map('intval', $delete_ids));

    // Perform the deletion query
    $delete = "UPDATE hc_exam_question SET is_delete = 1 WHERE id IN ($sanitized_ids)";
    $sql_delete = mysqli_query($db, $delete);

    if ($sql_delete) {
        // Handle any other success operations if needed
        echo "Items deleted successfully!";
        exit();
    } else {
        echo "Error deleting items: " . mysqli_error($db);
        // Handle deletion failure
    }  
} else {
    echo "No items selected for deletion.";
    // Handle case where no IDs are received
}
?>
