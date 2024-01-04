<?php include('../assets/includes/header.php'); ?>

<main>
    <?php
       $ids = [1, 2, 3];
       $item_ids = implode(',', array_map('intval', $ids));
       $select_report = "SELECT * FROM hc_purchase_details WHERE item_id IN ($item_ids)";       
       $sql_report = mysqli_query($db, $select_report);

        if ($sql_report) {
            $i = 0;
            mysqli_autocommit($db, false);
            $error = false; 

            while ($row_report = mysqli_fetch_assoc($sql_report)) {
                if (in_array($row_report['item_id'], [1, 2, 3])) {
                    $student_id = mysqli_real_escape_string($db, $row_report['student_id']);

                    // Check if the combination of student_id and item_id exists in hc_purchase_details
                    $select_query = "SELECT * FROM hc_purchase_details WHERE student_id = '$student_id' AND item_id = 15";
                    $result = mysqli_query($db, $select_query);

                    // Check if there are any matching records
                    if (mysqli_num_rows($result) > 0) {
                        echo "Entry for student_id: $student_id and item_id: 15 already exists. Skipping INSERT.";
                    } else {
                        $insert_purchase = "INSERT INTO `hc_purchase`(`student_id`, `purchase_item`, `status`, `payment_status`, `method`, `subtotal`, `charge`, `total_amount`, `discount_by`, `insert_by`, `purchase_date`, `expired_date`, `is_expired`) VALUES ('$student_id', 1, 1, 1,'Free', 0, 0, 0, 'Razib H Sarkar', 'Self','2023-12-23 11:45:30','2024-02-23 11:45:30', 0)";
                        $sql_add = mysqli_query($db, $insert_purchase);

                        if ($sql_add) {
                            $inserted_item_id = mysqli_insert_id($db);

                            $insert_purchase_details = "INSERT INTO `hc_purchase_details`(`purchase_id`, `purchase_item`, `item_id`, `price`, `paid_amount`, `student_id`, `payment_time`) VALUES ('$inserted_item_id', 1, 15, 0, 0, '$student_id','2023-12-23 12:40:56')";
                            $purchase_details_add = mysqli_query($db, $insert_purchase_details);

                            if (!$purchase_details_add) {
                                $error = true;
                                break; // Exit loop if an error occurs
                            }
                        } else {
                            $error = true;
                            break; // Exit loop if an error occurs
                        }
                    }
                }
            }

            if ($error) {
                echo "Error occurred. Rolling back changes.";
                mysqli_rollback($db);
            } else {
                echo "Transaction successful. Committing changes.";
                mysqli_commit($db);
            }
            
            // Re-enable autocommit
            mysqli_autocommit($db, true);
        }
    ?>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Migration</h4>
        </div>
    </div>
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE BLOG ==========-->
            <div class="mng_category">
                <h5 class="box_title">Migration For <span style="color: red"></span></h5>
                <form method="POST" action="" class="form-row">
                    <button type="submit">Migration</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>