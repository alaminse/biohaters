<?php include('../assets/includes/header.php'); ?>

<main>
    <style>
        .active {
            color: #FBC91B;
        }
    </style>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Gifts</h4>
        </div>
    </div>
    <?php
        if(isset($_POST['submit_update'])) {
            $gift_id = $_POST['id'];
            $gift_courier_address = $_POST['gift_courier_address'];
            
            if(!$gift_courier_address == '')
            {
                $update_verify = "UPDATE hc_courier SET courier_address = '$gift_courier_address' WHERE id = '$gift_id'";
                $sql_verify = mysqli_query($db, $update_verify);
                if ($sql_verify) {
                    ?>
                    <div style="background-color: green; color: white; text-align: center;">
                        <?php echo "Address updated successfully!"; ?>
                    </div>
                    <?php
                } else {
                    ?>
                    <div style="background-color: red; color: white; text-align: center;">
                        <?php echo "Error updating address: "; ?>
                    </div>
                    <?php
                }
            }
        }
    ?>
    <!-- <?php if (isset($_GET['print'])) {
        ?>
        <div class="ep_section">
            <div class="ep_container">
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Print Label</h5>
                    </div>
                </div>

                <form action="../print-gift-label/" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">First Serial?*</label>
                        <input type="text" id="" name="qty_first" placeholder="First Serial" required>
                    </div>
                    
                    <div>
                        <label for="">Amount?*</label>
                        <input type="text" id="" name="qty_last" placeholder="Amount" required>
                    </div>

                    <button type="submit" name="print" class="grid_col_3">Print</button>
                </form>
            </div>
        </div>
        <?php 
    }?> -->

    <?php
        $name = 'HSC 2025 : First Semester';
        if (isset($_GET['name'])) {
            $name = $_GET['name'];
        }
    ?>
    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE BLOG ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Manage Courier</h5>
                    <div class="btn_grp">
                        <a href="?name=HSC 2025 : First Semester" class="button btn_sm <?php echo ($name == 'HSC 2025 : First Semester') ? 'active' : ''; ?>">HSC 2025 : First Semester</a>
                        <a href="?name=HSC 2024 : First Semester" class="button btn_sm <?php echo ($name == 'HSC 2024 : First Semester') ? 'active' : ''; ?>">HSC 2024 : First Semester</a>
                        <a href="?name=HSC 2025 : Second Semester" class="button btn_sm <?php echo ($name == 'HSC 2025 : Second Semester') ? 'active' : ''; ?>">HSC 2025 : Second Semester</a>
                        <a href="?name=HSC 2024 : Second Semester" class="button btn_sm <?php echo ($name == 'HSC 2024 : Second Semester') ? 'active' : ''; ?>">HSC 2024 : Second Semester</a>
                    </div>
                    <div class="btn_grp">
                        <a href="#" id="printLabelBtn" class="button btn_sm">Print Label</a>
                        <!-- <a href="../gift/?print" class="button btn_sm">Print Label</a> -->
                    </div>
                </div>

                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>SI</th>
                            <th>Invoice No.</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <!-- <th>Set</th> -->
                            <th>Course</th>
                            <th>Published On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                        $select = "SELECT c.id AS courier_id, c.student_id, c.purchase_id, c.courier_address, c.update_date AS courier_update_date, c.is_delivered,
                                s.name AS student_name, s.phone AS student_phone, s.roll AS student_roll,
                                pd.item_id AS purchase_item_id,
                                cd.name AS course_name
                                FROM hc_courier c
                                LEFT JOIN hc_student s ON c.student_id = s.id
                                LEFT JOIN hc_purchase_details pd ON c.purchase_id = pd.purchase_id
                                LEFT JOIN hc_course cd ON pd.item_id = cd.id WHERE cd.name = '$name'
                                ORDER BY c.is_delivered ASC";
    
                        $sql = mysqli_query($db, $select);

                        if (!$sql || mysqli_num_rows($sql) === 0) {
                            echo "<tr><td colspan='9' class='text_center'>There are no items</td></tr>";
                        } else {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $si++;
                                $gift_id = $row['courier_id'];
                                $gift_purchase_id = $row['purchase_id'];
                                $gift_courier_address = $row['courier_address'];
                                $gift_update_date = date('d M Y', strtotime($row['courier_update_date']));
                                $gift_status = $row['is_delivered'];
                                $student_data_name = $row['student_name'];
                                $student_data_phone = $row['student_phone'];
                                $student_data_roll = $row['student_roll'];
                                $course_name = $row['course_name'];
                            ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="purchase_id[]" value="<?php echo $gift_purchase_id; ?>">
                                    </td>
                                    <td><?php echo $si; ?></td>
                                    <td><?php echo $gift_purchase_id; ?></td>
                                    <td><?php echo $student_data_name; ?></td>
                                    <td><?php echo $student_data_phone; ?></td>
                                    <td><?php if ($gift_status == 1) {
                                        echo '<div class="success">Send to Courier</div>';
                                    } ?></td>
                                    <td><?php echo $course_name; ?></td>
                                    <td><?php echo $gift_update_date; ?></td>
                                </tr>
                                <tr>
                                <form action="" method="post">
                                    <td colspan="8">
                                        <input type="hidden" name="id" value="<?php echo $gift_id; ?>">
                                        <input type="text" style="width: 60rem !important;" name="gift_courier_address" value="<?php echo $gift_courier_address; ?>">
                                    </td>
                                    <td>
                                        <button type="submit" name="submit_update">
                                            <i class="bx bxs-edit"></i>
                                        </button>
                                    </td>
                                </form>
                                </tr>
                            <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>
<script>
    document.getElementById('printLabelBtn').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('input[name="purchase_id[]"]:checked');
        var purchaseIds = [];

        checkboxes.forEach(function(checkbox) {
            purchaseIds.push(checkbox.value);
        });

        // Create a form dynamically
        var form = document.createElement('form');
        form.setAttribute('method', 'post');
        form.setAttribute('action', '../print-gift-label/'); // Replace with your PHP page URL

        // Create a hidden input field to store the purchaseIds
        var input = document.createElement('input');
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', 'selected_purchase_ids');
        input.setAttribute('value', purchaseIds.join(',')); // Convert array to comma-separated string

        // Append the input to the form
        form.appendChild(input);

        // Append the form to the document body and submit
        document.body.appendChild(form);
        form.submit();
});
</script>

<?php include('../assets/includes/footer.php'); ?>