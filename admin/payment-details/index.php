<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php if (isset($_GET['payment']) && $_GET['payment'] != '') { 
    $purchase_id = $_GET['payment'];
    ?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Payment Details</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container ep_grid">
            <!--========== MANAGE PRODUCT CATEGORY ==========-->
            <div class="">
                <?php $select = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$purchase_id' GROUP BY purchase_id";
                $sql = mysqli_query($db, $select);
                $num = mysqli_num_rows($sql);
                if ($num > 0) {
                    $si = 0;
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $student_id = $row['student_id'];
                        
                        // fetch student data
                        $select_student = "SELECT * FROM hc_student WHERE id = '$student_id'";
                        $sql_student = mysqli_query($db, $select_student);
                        $num_student = mysqli_num_rows($sql_student);
                        if ($num_student > 0) {
                            while ($row_student = mysqli_fetch_assoc($sql_student)) {
                                $name       = $row_student['name'];
                                $roll       = $row_student['roll'];
                                $phone      = $row_student['phone'];
                                $email      = $row_student['email'];
                                $profile    = $row_student['profile'];
                                
                                if (empty($profile)) {
                                    $profile = '../assets/img/admin.png';
                                }?>
                                <div class="payment_user_card ep_flex ep_start">
                                    <div class="payment_user_content">
                                        <img src="<?= $profile ?>" alt="">
                                    </div>
                                    
                                    <div class="payment_user_data">
                                        <h5><?= $name ?></h5>
                                        <p>Roll: <?= $roll ?></p>
                                        <p>Email: <?= $email ?></p>
                                        <p>Phone: <?= $phone ?></p>
                                    </div>
                                </div>
                                <?php 
                            }
                        }
                        
                        // fetch purchased items
                        $select_items = "SELECT *, SUM(paid_amount) as paid FROM hc_purchase_details WHERE purchase_id = '$purchase_id' GROUP BY item_id";
                        $sql_items = mysqli_query($db, $select_items);
                        $num_items = mysqli_num_rows($sql_items);
                        if ($num_items > 0) {
                            $si = 0;
                            $total_chapter_price = 0;
                            $total_chapter_paid = 0;
                            $total_course_price = 0;
                            $total_course_paid = 0;
                            ?>
                            <div class="ep_grid grid_2_1 payment_details_table account_report_container">
                                <div>
                                    <table class="account_table">
                                        <thead>
                                            <tr>
                                                <th>SI</th>
                                                <th>Course Name</th>
                                                <th>Price</th>
                                                <th>Paid</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row_items = mysqli_fetch_assoc($sql_items)) {
                                                $purchase_item  = $row_items['purchase_item'];
                                                $item_id        = $row_items['item_id'];
                                                
                                                if ($purchase_item == 2) {
                                                    $price          = $row_items['price'];
                                                    $paid_amount    = $row_items['paid'];
                                                    
                                                    $total_chapter_price += $price;
                                                    $total_chapter_paid += $paid_amount;
                                                }
                                                
                                                if ($purchase_item == 1) {
                                                    $price          = $row_items['price'];
                                                    $paid_amount    = $row_items['paid'];
                                                    
                                                    $total_course_price = $price;
                                                    $total_course_paid += $paid_amount;
                                                }
                                                
                                                $si++;
                                                
                                                if ($purchase_item == 1) {
                                                    // fetch item details
                                                    $select_item_details = "SELECT * FROM hc_course WHERE id = '$item_id'";
                                                    $sql_item_details = mysqli_query($db, $select_item_details);
                                                    $num_item_details = mysqli_num_rows($sql_item_details);
                                                    if ($num_item_details > 0) {
                                                        while ($row_item_details = mysqli_fetch_assoc($sql_item_details)) {
                                                            $item_name  = $row_item_details['name'];
                                                            $item_type  = $row_item_details['type'];
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <?= $si ?>
                                                                </td>
                                                                <td>
                                                                    <?= $item_name ?>
                                                                </td>
                                                                <td>
                                                                    <?= $total_course_price ?>/-
                                                                </td>
                                                                <td>
                                                                    <?= $total_course_paid ?>/-
                                                                </td>
                                                            </tr>
                                                            <?php 
                                                        }
                                                    }
                                                } elseif ($purchase_item == 2) {
                                                    // fetch item details
                                                    $select_item_details = "SELECT * FROM hc_chapter WHERE id = '$item_id'";
                                                    $sql_item_details = mysqli_query($db, $select_item_details);
                                                    $num_item_details = mysqli_num_rows($sql_item_details);
                                                    if ($num_item_details > 0) {
                                                        while ($row_item_details = mysqli_fetch_assoc($sql_item_details)) {
                                                            $item_name  = $row_item_details['chapter'];
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <?= $si ?>
                                                                </td>
                                                                <td>
                                                                    <?= $item_name ?>
                                                                </td>
                                                                <td>
                                                                    <?= $price ?>/-
                                                                </td>
                                                                <td>
                                                                    <?= $paid_amount ?>/-
                                                                </td>
                                                            </tr>
                                                            <?php 
                                                        }
                                                    }
                                                }
                                            }?>
                                        </tbody>
                                    </table>
                                    
                                    <?php if ($purchase_item == 1) {
                                        // fetch installment
                                        $select_installment = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$purchase_id' ORDER BY payment_time ASC";
                                        $sql_installment = mysqli_query($db, $select_installment);
                                        $num_installment = mysqli_num_rows($sql_installment);
                                        if ($num_installment > 0) {
                                            $si = 0;
                                            echo '<h4 class="box_title">Installment Table</h4>';
                                            echo '<div class="ep_grid grid_2_1 account_report_container">
                                                    <table class="account_table">
                                                        <thead>
                                                            <tr>
                                                                <th>SI</th>
                                                                <th>Installment</th>
                                                                <th>Paid</th>
                                                                <th>Payment Time</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>';
                                            while ($row_installment = mysqli_fetch_assoc($sql_installment)) {
                                                $paid           = $row_installment['paid_amount'];
                                                $payment_time   = $row_installment['payment_time'];
                                                $si++;
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?= $si ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($si == 1) {
                                                            echo '1st Installment';
                                                        } elseif ($si == 2) {
                                                            echo '2nd Installment';
                                                        } elseif ($si == 3) {
                                                            echo '3rd Installment';
                                                        } elseif ($si == 4) {
                                                            echo '4th Installment';
                                                        } elseif ($si == 5) {
                                                            echo '5th Installment';
                                                        }?>
                                                    </td>
                                                    <td>
                                                        <?= $paid ?>/-
                                                    </td>
                                                    <td>
                                                        <?= $payment_time ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            } 
                                            
                                            echo '      </tbody>
                                                    </table>
                                                </div>';
                                        }
                                    }?>
                                </div>
                                
                                <?php if ($purchase_item == 2) {
                                    ?>
                                    <div class="account_details_card">
                                        <h4 class="account_details">Payment Summary</h4>
                                        
                                        <div>
                                            <div class="ep_flex">
                                                <div>Subtotal</div>
                                                <div><?= $total_chapter_price ?>/-BDT</div>
                                            </div>
                                            
                                            <div class="ep_flex">
                                                <div>Paid</div>
                                                <div><?= $total_chapter_paid ?>/-BDT</div>
                                            </div>
                                        </div>
                                        
                                        <h4 class="account_details_title">Payment Status</h4>
                                    
                                        <div>
                                            <div class="ep_flex ep_center">
                                                <h5>Complete</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                }?>
                                
                                <?php if ($purchase_item == 1) {
                                    ?>
                                    <div class="account_details_card">
                                        <h4 class="account_details">Payment Summary</h4>
                                        
                                        <div>
                                            <div class="ep_flex">
                                                <div>Subtotal</div>
                                                <div><?= $total_course_price ?>/-BDT</div>
                                            </div>
                                            
                                            <div class="ep_flex">
                                                <div>Paid</div>
                                                <div><?= $total_course_paid ?>/-BDT</div>
                                            </div>
                                        </div>
                                        
                                        <h4 class="account_details_title">Payment Status</h4>
                                        
                                        <?php if (($total_course_price - $total_course_paid) == 0) {
                                            ?>
                                            <div>
                                                <div class="ep_flex ep_center">
                                                    <h5>Complete</h5>
                                                </div>
                                            </div>
                                            <?php 
                                        } else {
                                            ?>
                                            <div>
                                                <div class="ep_flex">
                                                    <div>Due Amount</div>
                                                    <div><?= $total_course_price - $total_course_paid ?>/-BDT</div>
                                                </div>
                                            </div>
                                            <?php 
                                        }?>
                                    </div>
                                    <?php 
                                }?>
                            </div>
                            <?php 
                        }
                        
                        $alert = '';
                        if (isset($_POST['add_due'])) {
                            $method         = $_POST['method'];
                            $purchase_id    = $_POST['purchase_id'];
                            $purchase_item  = $_POST['purchase_item'];
                            $item_id        = $_POST['item_id'];
                            $paid_amount    = $_POST['paid_amount'];
                            $payment_number = '';
                            $trx_id         = '';
                            
                            $payment_time = date('Y-m-d H:i:s', time());
                            
                            if (empty($method) || empty($purchase_id) || empty($purchase_item) || empty($item_id) || $paid_amount == '') {
                                $alert = "<p class='warning mb_75'>Required Fields.....</p>";
                            } else {
                                if ($method != 'Cash') {
                                    $payment_number = $_POST['payment_number'];
                                    $trx_id         = $_POST['trx_id'];
                                    
                                    if (empty($payment_number) || empty($trx_id)) {
                                        $alert = "<p class='warning mb_75'>Transaction Details Required.....</p>";
                                    } else {
                                        // insert to payment details
                                        $insert = "INSERT INTO hc_purchase_details (purchase_id, purchase_item, item_id, price, paid_amount, student_id, payment_time) VALUES ('$purchase_id', '$purchase_item', '$item_id', '$price', '$paid_amount', '$student_id', '$payment_time')";
                                        $sql_insert = mysqli_query($db, $insert);
                                        
                                        // insert to transaction
                                        $insert_transaction = "INSERT INTO hc_transaction (reference, method, payment_number, trx_id, amount, transaction_type, status, issued_by, issued_date) VALUES ('$purchase_id', '$method', '$payment_number', '$trx_id', '$paid_amount', '1', '1', '$admin_name', '$payment_time')";
                                        $sql_insert_transaction = mysqli_query($db, $insert_transaction);
                                    }
                                } else {
                                    // insert to payment details
                                    $insert = "INSERT INTO hc_purchase_details (purchase_id, purchase_item, item_id, price, paid_amount, student_id, payment_time) VALUES ('$purchase_id', '$purchase_item', '$item_id', '$price', '$paid_amount', '$student_id', '$payment_time')";
                                    $sql_insert = mysqli_query($db, $insert);
                                    
                                    // insert to transaction
                                    $insert_transaction = "INSERT INTO hc_transaction (reference, method, payment_number, trx_id, amount, transaction_type, status, issued_by, issued_date) VALUES ('$purchase_id', '$method', '$payment_number', '$trx_id', '$paid_amount', '1', '1', '$admin_name', '$payment_time')";
                                    $sql_insert_transaction = mysqli_query($db, $insert_transaction);
                                }
                            }
                            
                            if ($sql_insert && $sql_insert_transaction) {
                                ?>
                                <script type="text/javascript">
                                    window.location.href = '../payment-details/?payment=<?= $purchase_id ?>';
                                </script>
                                <?php 
                            }
                        }
                        
                        if (isset($_POST['update_due_date'])) {
                            $due_date       = $_POST['due_date'];
                            $purchase_id    = $_POST['purchase_id'];
                            
                            // update due date
                            $update_due_date = "UPDATE hc_due SET due_date = '$due_date' WHERE purchase_id = '$purchase_id'";
                            mysqli_query($db, $update_due_date);
                            
                            if ($update_due_date) {
                                ?>
                                <script type="text/javascript">
                                    window.location.href = '../payment-details/?payment=<?= $purchase_id ?>';
                                </script>
                                <?php 
                            }
                        }
                        
                        if (isset($_POST['add_discount'])) {
                            $discount_by        = $_POST['discount_by'];
                            $discount_reason    = $_POST['discount_reason'];
                            $discount_amount    = $_POST['discount_amount'];
                            $purchase_id        = $_POST['purchase_id'];
                            
                            // update discount
                            $update_discount = "UPDATE hc_purchase SET discount_by = '$discount_by', discount_reason = '$discount_reason', discount_amount = '$discount_amount' WHERE id = '$purchase_id'";
                            mysqli_query($db, $update_discount);
                            
                            // fetch payment details
                            $fetch_details = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$purchase_id'";
                            $sql_details = mysqli_query($db, $fetch_details);
                            $num_details = mysqli_num_rows($sql_details);
                            if ($num_details > 0) {
                                while ($row_details = mysqli_fetch_assoc($sql_details)) {
                                    $details_id = $row_details['id'];
                                    $price = $row_details['price'];
                                    
                                    $new_price = $price - $discount_amount;
                                    
                                    // update discount
                                    $update_discount_details = "UPDATE hc_purchase_details SET price = '$new_price' WHERE id = '$details_id'";
                                    mysqli_query($db, $update_discount_details);
                                }
                            }?>
                            <script type="text/javascript">
                                window.location.href = '../payment-details/?payment=<?= $purchase_id ?>';
                            </script>
                            <?php 
                        }
                        
                        if (($total_course_price - $total_course_paid) > 0) {
                            ?>
                            <div class="ep_grid grid_3">
                                <div>
                                    <h5 class="mb_75">Due Payment</h5>
                                    
                                    <?= $alert ?>
                            
                                    <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                                        <div>
                                            <label for="">Payment Method*</label>
                                            <select id="" name="method">
                                                <option value="">Choose Category</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Bkash">Bkash</option>
                                                <option value="Nagad">Nagad</option>
                                                <option value="Rocket">Rocket</option>
                                                <option value="Merchant">Merchant</option>
                                            </select>
                                        </div>
                    
                                        <div>
                                            <label for="">Payment Number</label>
                                            <input type="text" id="" name="payment_number" placeholder="Payment Number">
                                        </div>
                    
                                        <div>
                                            <label for="">Transaction ID</label>
                                            <input type="text" id="" name="trx_id" placeholder="Transaction ID">
                                        </div>
                    
                                        <div>
                                            <label for="">Paid Amount*</label>
                                            <input type="text" id="" name="paid_amount" placeholder="Paid Amount">
                                        </div>
                                        
                                        <input type="hidden" id="" name="purchase_id" value="<?= $purchase_id ?>">
                                        <input type="hidden" id="" name="purchase_item" value="<?= $purchase_item ?>">
                                        <input type="hidden" id="" name="item_id" value="<?= $item_id ?>">
                    
                                        <button type="submit" name="add_due">Add Payment</button>
                                    </form>
                                </div>
                                
                                <div>
                                    <?php if ($item_type == 0) {
                                        ?>
                                        <h5 class="mb_75">Due Date</h5>
                            
                                        <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                                            <div>
                                                <label for="">Due Date*</label>
                                                <input type="date" id="" name="due_date" required>
                                            </div>
                                            
                                            <input type="hidden" id="" name="purchase_id" value="<?= $purchase_id ?>">
                        
                                            <button type="submit" name="update_due_date">Update Due Date</button>
                                        </form>
                                        <?php 
                                    }?>
                                </div>
                                
                                <div>
                                    <h5 class="mb_75">Give Discount</h5>
                            
                                    <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                                        <div>
                                            <label for="">Discount By</label>
                                            <input type="text" id="" name="discount_by" placeholder="Discount By" required>
                                        </div>
                        
                                        <div>
                                            <label for="">Discount Reason</label>
                                            <input type="text" id="" name="discount_reason" placeholder="Discount Reason" required>
                                        </div>
                        
                                        <div>
                                            <label for="">Discount Amount</label>
                                            <input type="text" id="" name="discount_amount" placeholder="Discount Amount" required>
                                        </div>
                                        
                                        <input type="hidden" id="" name="purchase_id" value="<?= $purchase_id ?>">
                    
                                        <button type="submit" name="add_discount">Add Discount</button>
                                    </form>
                                </div>
                            </div>
                            <?php 
                        }
                    }
                }?>
            </div>
        </div>
    </div>
</main>
<?php } else { ?><script type="text/javascript">window.location.href = '../payment-list/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>