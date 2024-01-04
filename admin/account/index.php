<?php include('../assets/includes/header.php'); ?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Account</h4>
        </div>
    </div>
    
    <?php if ($admin_role == 0 || $admin_role == 1) {
        ?>
        <div class="ep_section">
            <div class="main_account_container ep_container ep_grid grid_2">
                <div class="main_account_card">
                    <div class="main_account_card_content">
                        <img src="../assets/img/logo.png" alt="">
                    </div>
    
                    <div class="main_account_card_data">
                        <div class="main_account_card_data_title">Biology Haters</div>
                        <div class="main_account_card_data_subtitle">Razib H Sarkar (Owner)</div>
                    </div>
                </div>
    
                <?php // initialize array
                $my_account_query = array(
                    'balance' => 0,
                    'incoming' => 0,
                    'outgoing' => 0,
                    'last_update' => 0,
                );
                
                // fetch balance
                $select_account_query = "SELECT * FROM hc_account ORDER BY id ASC";
                $sql_account_query = mysqli_query($db, $select_account_query);
                $num_account_query = mysqli_num_rows($sql_account_query);
                if ($num_account_query > 0) {
                    $balance = 0;
                    $earnings = 0;
                    $expenses = 0;
                    while ($row_account_query = mysqli_fetch_assoc($sql_account_query)) {
                        $type           = $row_account_query['type'];
                        $amount         = $row_account_query['amount'];
                        $last_update    = $row_account_query['insert_time'];
    
                        $last_update = date('d M, Y || h:i a', strtotime($last_update));
    
                        $balance += $amount;
    
                        if ($type == 'Earn') {
                            $earnings += $amount;
                        } elseif ($type == 'Expense') {
                            $expenses += $amount;
                        }
                    }
    
                    $balance = $earnings - $expenses;
    
                    $my_account_query['balance']        = $balance;
                    $my_account_query['incoming']       = $earnings;
                    $my_account_query['outgoing']       = $expenses;
                    $my_account_query['last_update']    = $last_update;
                }?>
    
                <div class="balance_card">
                    <div class="balance_card_content">
                        <div class="balance_card_content_title">Total Balance</div>
                        <div class="balance_card_content_data">
                            ৳ <?= $my_account_query['balance'] ?>.00
                        </div>
    
                        <div class="balance_card_content_footer">
                            Last update on <br><?= $my_account_query['last_update'] ?>
                        </div>
                    </div>
    
                    <div class="balance_card_data">
                        <div class="balance_card_data_income">
                            <div class="balance_tag"><i class='bx bx-right-top-arrow-circle' ></i> Incoming</div>
                            <div class="balance">৳ <?= $my_account_query['incoming'] ?>.00</div>
                        </div>
    
                        <div class="balance_card_data_expense">
                            <div class="balance_tag"><i class='bx bx-right-down-arrow-circle'></i> Outgoing</div>
                            <div class="balance">৳ <?= $my_account_query['outgoing'] ?>.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
    }?>
    
    <?php if ($admin_role == 3 || $admin_role == 6 || $admin_role == 7 || $admin_role == 8 || $admin_role == 9) {
        ?>
        <script type="text/javascript">
            window.location.href = '../dashboard/';
        </script>
        <?php 
    }?>
    
    <?php if ($admin_role == 0 || $admin_role == 1 || $admin_role == 2 || $admin_role == 4) {
        ?>
        <div class="ep_section">
            <div class="main_account_container ep_container">
                <form action="../balance-sheet-print/" method="post" class="ep_flex ep_end mt_75">
                    <input type="date" name="report_date" id="">
                    <button type="submit" name="get_report" class="button">Get Report</button>
                </form>
            </div>
        </div>
        <?php 
    }?>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="account_widget ep_container">
            <div class="account_widget_card account_widget_card_cash">
                <img src="../assets/img/cash.png" alt="" class="account_widget_img">
                <p class="account_widget_card_title">Cash</p>
                <?php // initialize today
                $today = date('Y-m-d', time());
                // fetch Cash balance
                $select_cash = "SELECT *, SUM(amount) as balance FROM hc_account WHERE method = 'Cash' AND DATE(insert_time) = '$today' GROUP BY type";
                $sql_cash = mysqli_query($db, $select_cash);
                $num_cash = mysqli_num_rows($sql_cash);
                if ($num_cash == 0) {
                    $cash_balance = 0;
                } else {
                    $earnings = 0;
                    $expenses = 0;
                    while ($row_cash = mysqli_fetch_assoc($sql_cash)) {
                        $type       = $row_cash['type'];
                        $balance    = $row_cash['balance'];

                        if ($type == 'Earn') {
                            $earnings += $balance;
                        } elseif ($type == 'Expense') {
                            $expenses += $balance;
                        }
                    }

                    $cash_balance = $earnings - $expenses;
                }?>
                <?= $cash_balance ?>/-
            </div>

            <div class="account_widget_card account_widget_card_merchant">
                <img src="../assets/img/bkash.png" alt="" class="account_widget_img">
                <p class="account_widget_card_title">Merchant</p>
                <?php // fetch Merchant balance
                $select_merchant = "SELECT *, SUM(amount) as balance FROM hc_account WHERE method = 'Merchant' AND DATE(insert_time) = '$today' GROUP BY type";
                $sql_merchant = mysqli_query($db, $select_merchant);
                $num_merchant = mysqli_num_rows($sql_merchant);
                if ($num_merchant == 0) {
                    $merchant_balance = 0;
                } else {
                    $earnings = 0;
                    $expenses = 0;
                    while ($row_merchant = mysqli_fetch_assoc($sql_merchant)) {
                        $type       = $row_merchant['type'];
                        $balance    = $row_merchant['balance'];

                        if ($type == 'Earn') {
                            $earnings += $balance;
                        } elseif ($type == 'Expense') {
                            $expenses += $balance;
                        }
                    }

                    $merchant_balance = $earnings - $expenses;
                }?>
                <?= $merchant_balance ?>/-
            </div>

            <div class="account_widget_card account_widget_card_bkash">
                <img src="../assets/img/bkash.png" alt="" class="account_widget_img">
                <p class="account_widget_card_title">Bkash</p>
                <?php // fetch Bkash balance
                $select_bkash = "SELECT *, SUM(amount) as balance FROM hc_account WHERE method = 'Bkash' GROUP BY type";
                $sql_bkash = mysqli_query($db, $select_bkash);
                $num_bkash = mysqli_num_rows($sql_bkash);
                if ($num_bkash == 0) {
                    $bkash_balance = 0;
                } else {
                    $earnings = 0;
                    $expenses = 0;
                    while ($row_bkash = mysqli_fetch_assoc($sql_bkash)) {
                        $type       = $row_bkash['type'];
                        $balance    = $row_bkash['balance'];

                        if ($type == 'Earn') {
                            $earnings += $balance;
                        } elseif ($type == 'Expense') {
                            $expenses += $balance;
                        }
                    }

                    $bkash_balance = $earnings - $expenses;
                }?>
                <?= $bkash_balance ?>/-
            </div>

            <div class="account_widget_card account_widget_card_nagad">
                <img src="../assets/img/nagad.png" alt="" class="account_widget_img">
                <p class="account_widget_card_title">Nagad</p>
                <?php // fetch Nagad balance
                $select_nagad = "SELECT *, SUM(amount) as balance FROM hc_account WHERE method = 'Nagad' GROUP BY type";
                $sql_nagad = mysqli_query($db, $select_nagad);
                $num_nagad = mysqli_num_rows($sql_nagad);
                if ($num_nagad == 0) {
                    $nagad_balance = 0;
                } else {
                    $earnings = 0;
                    $expenses = 0;
                    while ($row_nagad = mysqli_fetch_assoc($sql_nagad)) {
                        $type       = $row_nagad['type'];
                        $balance    = $row_nagad['balance'];

                        if ($type == 'Earn') {
                            $earnings += $balance;
                        } elseif ($type == 'Expense') {
                            $expenses += $balance;
                        }
                    }

                    $nagad_balance = $earnings - $expenses;
                }?>
                <?= $nagad_balance ?>/-
            </div>

            <div class="account_widget_card account_widget_card_rocket">
                <img src="../assets/img/rocket.png" alt="" class="account_widget_img">
                <p class="account_widget_card_title">Rocket</p>
                <?php // fetch Rocket balance
                $select_rocket = "SELECT *, SUM(amount) as balance FROM hc_account WHERE method = 'Rocket' GROUP BY type";
                $sql_rocket = mysqli_query($db, $select_rocket);
                $num_rocket = mysqli_num_rows($sql_rocket);
                if ($num_rocket == 0) {
                    $rocket_balance = 0;
                } else {
                    $earnings = 0;
                    $expenses = 0;
                    while ($row_rocket = mysqli_fetch_assoc($sql_rocket)) {
                        $type       = $row_rocket['type'];
                        $balance    = $row_rocket['balance'];

                        if ($type == 'Earn') {
                            $earnings += $balance;
                        } elseif ($type == 'Expense') {
                            $expenses += $balance;
                        }
                    }

                    $rocket_balance = $earnings - $expenses;
                }?>
                <?= $rocket_balance ?>/-
            </div>
        </div>
    </div>
    
    <?php if (isset($_POST['add_earn'])) {
        foreach ($_POST["purchase_id"] as $index => $purchase_id) {
            $purchase_id    = $purchase_id;
            $purpose        = $_POST['purpose'][$index];
            $method         = $_POST['method'][$index];
            $trx_id         = $_POST['trx_id'][$index];
            $amount         = $_POST['amount'][$index];
            $issued_by      = $_POST['issued_by'][$index];
            $issued_date    = $_POST['issued_date'][$index];

            do {
                // generate token
                $tokenLength = 10; // Length of the token in bytes
    
                $randomBytes = random_bytes($tokenLength);
                $token = base64_encode($randomBytes);
    
                // Replace '/' character with '-'
                $token = str_replace('/', '_', $token);
                $token = str_replace('+', '$', $token);
                $token = str_replace('%', '_', $token);
                $token = str_replace('^', '$', $token);
                $token = str_replace('@', '_', $token);
                $token = str_replace('!', '$', $token);
                $token = str_replace('&', '_', $token);
                $token = str_replace('(', '$', $token);
                $token = str_replace(')', '_', $token);
                $token = str_replace('=', '$', $token);
                $token = str_replace(' ', '_', $token);
            
                // check token
                $check_token = "SELECT * FROM hc_account WHERE token = '$token'";
                $sql_check_token = mysqli_query($db, $check_token);
                $num_check_token = mysqli_num_rows($sql_check_token);
            } while ($num_check_token != 0);

            // type initializing
            $type = 'Earn';

            $insert_time = date('Y-m-d H:i:s', time());

            // insert to account
            $insert = "INSERT INTO hc_account (purchase_id, token, type, purpose, method, trx_id, amount, issued_by, issued_date, author, insert_time) VALUES ('$purchase_id', '$token', '$type', '$purpose', '$method', '$trx_id', '$amount', '$issued_by', '$issued_date', '$admin_id', '$insert_time')";
            $sql = mysqli_query($db, $insert);
        }?>
        <script type="text/javascript">
            window.location.href = '../account/';
        </script>
        <?php 
    }
    
    if (isset($_POST['add_external'])) {
        $purpose        = mysqli_escape_string($db, $_POST['purpose']);
        $trx_id         = mysqli_escape_string($db, $_POST['trx_id']);
        $amount         = mysqli_escape_string($db, $_POST['amount']);
        $method         = $_POST['method'];
        $issued_by      = $admin_name;
        $issued_date    = date('Y-m-d H:i:s', time());

        do {
            // generate token
            $tokenLength = 10; // Length of the token in bytes

            $randomBytes = random_bytes($tokenLength);
            $token = base64_encode($randomBytes);

            // Replace '/' character with '-'
            $token = str_replace('/', '_', $token);
            $token = str_replace('+', '$', $token);
            $token = str_replace('%', '_', $token);
            $token = str_replace('^', '$', $token);
            $token = str_replace('@', '_', $token);
            $token = str_replace('!', '$', $token);
            $token = str_replace('&', '_', $token);
            $token = str_replace('(', '$', $token);
            $token = str_replace(')', '_', $token);
            $token = str_replace('=', '$', $token);
            $token = str_replace(' ', '_', $token);
        
            // check token
            $check_token = "SELECT * FROM hc_account WHERE token = '$token'";
            $sql_check_token = mysqli_query($db, $check_token);
            $num_check_token = mysqli_num_rows($sql_check_token);
        } while ($num_check_token != 0);

        // type initializing
        $type = 'Earn';

        $insert_time = date('Y-m-d H:i:s', time());

        // insert to account
        $insert = "INSERT INTO hc_account (token, type, purpose, method, trx_id, amount, issued_by, issued_date, author, insert_time) VALUES ('$token', '$type', '$purpose', '$method', '$trx_id', '$amount', '$issued_by', '$issued_date', '$admin_id', '$insert_time')";
        $sql = mysqli_query($db, $insert);
        if ($sql) {
            ?>
            <script type="text/javascript">
                window.location.href = '../account/?external_earn';
            </script>
            <?php 
        }
    }

    if (isset($_POST['add_expense'])) {
        $purpose        = mysqli_escape_string($db, $_POST['purpose']);
        $trx_id         = mysqli_escape_string($db, $_POST['trx_id']);
        $amount         = mysqli_escape_string($db, $_POST['amount']);
        $method         = $_POST['method'];
        $issued_by      = $admin_name;
        $issued_date    = date('Y-m-d H:i:s', time());

        do {
            // generate token
            $tokenLength = 10; // Length of the token in bytes

            $randomBytes = random_bytes($tokenLength);
            $token = base64_encode($randomBytes);

            // Replace '/' character with '-'
            $token = str_replace('/', '_', $token);
            $token = str_replace('+', '$', $token);
            $token = str_replace('%', '_', $token);
            $token = str_replace('^', '$', $token);
            $token = str_replace('@', '_', $token);
            $token = str_replace('!', '$', $token);
            $token = str_replace('&', '_', $token);
            $token = str_replace('(', '$', $token);
            $token = str_replace(')', '_', $token);
            $token = str_replace('=', '$', $token);
            $token = str_replace(' ', '_', $token);
        
            // check token
            $check_token = "SELECT * FROM hc_account WHERE token = '$token'";
            $sql_check_token = mysqli_query($db, $check_token);
            $num_check_token = mysqli_num_rows($sql_check_token);
        } while ($num_check_token != 0);

        // type initializing
        $type = 'Expense';

        $insert_time = date('Y-m-d H:i:s', time());

        // insert to account
        $insert = "INSERT INTO hc_account (token, type, purpose, method, trx_id, amount, issued_by, issued_date, author, insert_time) VALUES ('$token', '$type', '$purpose', '$method', '$trx_id', '$amount', '$issued_by', '$issued_date', '$admin_id', '$insert_time')";
        $sql = mysqli_query($db, $insert);
        if ($sql) {
            ?>
            <script type="text/javascript">
                window.location.href = '../account/?expense';
            </script>
            <?php 
        }
    }?>

    <div class="ep_section">
        <div class="ep_container ep_grid">
            <div class="account_btn_grp">
                <a href="../account/" class="account_button account_button_earn w_100 btn_trp <?php if (!isset($_GET['external_earn']) && !isset($_GET['expense']) && !isset($_GET['report'])) { echo 'active'; }?>">
                    <img src="../assets/img/earn.png" alt="">
                    Earning
                </a>

                <a href="../account/?external_earn" class="account_button account_button_external btn_trp w_100 <?php if (isset($_GET['external_earn'])) { echo 'active'; }?>">
                    <img src="../assets/img/cards.png" alt="">
                    External Earning
                </a>

                <a href="../account/?expense" class="account_button account_button_expense btn_trp w_100 <?php if (isset($_GET['expense'])) { echo 'active'; }?>">
                    <img src="../assets/img/spending-money.png" alt="">
                    Expense
                </a>

                <a href="../account/?report" class="account_button account_button_report btn_trp w_100 <?php if (isset($_GET['report'])) { echo 'active'; }?>">
                    <img src="../assets/img/balance-sheet.png" alt="">
                    Report
                </a>
            </div>
        </div>
    </div>

    <div class="ep_section">
        <div class="ep_container ep_grid">
            <?php if (isset($_GET['external_earn'])) {
                ?>
                <h5 class="box_title">External Earnings</h5>

                <form action="" method="post" class="double_col_form">
                    <div>
                        <label for="">Earning Purpose*</label>
                        <input type="text" name="purpose" id="" placeholder="Earning Purpose" required>
                    </div>

                    <div>
                        <label for="">Method*</label>
                        <select id="" name="method" required>
                            <option value="">Choose Method</option>
                            <option value="Cash">Cash</option>
                            <option value="Bkash">Bkash</option>
                            <option value="Nagad">Nagad</option>
                            <option value="Rocket">Rocket</option>
                            <option value="Merchant">Merchant</option>
                        </select>
                    </div>

                    <div>
                        <label for="">Transaction ID</label>
                        <input type="text" id="" name="trx_id" placeholder="Transaction ID">
                    </div>

                    <div>
                        <label for="">Amount*</label>
                        <input type="text" id="" name="amount" placeholder="Amount" required>
                    </div>

                    <div class="grid_col_3">
                        <button type="submit" name="add_external" class="button ">Add to Account</button>
                    </div>
                </form>
                <?php 
            } elseif (isset($_GET['expense'])) {
                ?>
                <h5 class="box_title">All Expenses</h5>

                <form action="" method="post" class="double_col_form">
                    <div>
                        <label for="">Expense Purpose*</label>
                        <input type="text" name="purpose" id="" placeholder="Expense Purpose" required>
                    </div>

                    <div>
                        <label for="">Method*</label>
                        <select id="" name="method" required>
                            <option value="">Choose Method</option>
                            <option value="Cash">Cash</option>
                            <option value="Bkash">Bkash</option>
                            <option value="Nagad">Nagad</option>
                            <option value="Rocket">Rocket</option>
                            <option value="Merchant">Merchant</option>
                        </select>
                    </div>

                    <div>
                        <label for="">Transaction ID</label>
                        <input type="text" id="" name="trx_id" placeholder="Transaction ID">
                    </div>

                    <div>
                        <label for="">Amount*</label>
                        <input type="text" id="" name="amount" placeholder="Amount" required>
                    </div>

                    <div class="grid_col_3">
                        <button type="submit" name="add_expense" class="button ">Add to Account</button>
                    </div>
                </form>
                <?php 
            } elseif (isset($_GET['report'])) {
                // initialize today
                $today = date('Y-m-d', time());
                ?>
                <div class="account_report">
                    <h5 class="box_title">Todays Report</h5>
                
                    <div class="account_report_container">
                        <div>
                            <table class="account_table">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th>Begining</th>
                                        <th>New</th>
                                        <th>Expense</th>
                                        <th>Ending</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php // fetch methods
                                    $select_methods = "SELECT * FROM hc_accounts ORDER BY id ASC";
                                    $sql_methods = mysqli_query($db, $select_methods);
                                    $num_methods = mysqli_num_rows($sql_methods);
                                    
                                    $report = array();
        
                                    $total_new_balance = 0;
                                    $total_ending_balance = 0;
                                    if ($num_methods > 0) {
                                        while ($row_methods = mysqli_fetch_assoc($sql_methods)) {
                                            $method = $row_methods['name'];
                                    
                                            $report_entry = array(
                                                'method' => $method,
                                                'begining' => 0, // Initialize the beginning balance
                                                'new' => 0, // Initialize the new balance
                                                'expense' => 0, // Initialize the expense balance
                                                'ending' => 0, // Initialize the ending balance
                                            );
        
                                            $earnings_beginning = 0;
                                            $expenses_beginning = 0;
                                            $earnings = 0;
                                            $expenses = 0;
                                    
                                            // account balance initialization
                                            $select_report = "SELECT *, SUM(amount) as balance FROM hc_account WHERE method = '$method' AND DATE(insert_time) != '$today' GROUP BY type";
                                            $sql_report = mysqli_query($db, $select_report);
                                            $num_report = mysqli_num_rows($sql_report);
                                    
                                            if ($num_report == 0) {
                                                $beginning_balance = 0;
                                            } else {                      
                                                while ($row_report = mysqli_fetch_assoc($sql_report)) {
                                                    $type = $row_report['type'];
                                                    $balance = $row_report['balance'];
                                    
                                                    if ($type == 'Earn') {
                                                        $earnings_beginning += $balance;
                                                    } elseif ($type == 'Expense') {
                                                        $expenses_beginning += $balance;
                                                    }
                                                }
                                    
                                                $beginning_balance = $earnings_beginning - $expenses_beginning;
                                                
                                                if ($method == 'Merchant') {
                                                    $beginning_balance = 0;
                                                }
                                    
                                                $report_entry['begining'] = $beginning_balance;
                                            }
        
                                            // account balance query
                                            $select_balance_query = "SELECT *, SUM(amount) as balance FROM hc_account WHERE method = '$method' AND DATE(insert_time) = '$today' GROUP BY type";
                                            $sql_balance_query = mysqli_query($db, $select_balance_query);
                                            $num_balance_query = mysqli_num_rows($sql_balance_query);
                                            if ($num_balance_query == 0) {
                                                $new_balance = 0;
                                                $new_balance = $beginning_balance + $earnings - $expenses;
        
                                                $report_entry['new'] = $earnings;
                                                $report_entry['expense'] = $expenses;
                                                $report_entry['ending'] = $new_balance;
                                            } else {
                                                while ($row_balance_query = mysqli_fetch_assoc($sql_balance_query)) {
                                                    $type       = $row_balance_query['type'];
                                                    $balance    = $row_balance_query['balance'];
        
                                                    if ($type == 'Earn') {
                                                        $earnings += $balance;
                                                    } elseif ($type == 'Expense') {
                                                        $expenses += $balance;
                                                    }
                                                }
        
                                                $new_balance = $beginning_balance + $earnings - $expenses;
                                                
                                                if ($method == 'Merchant') {
                                                    $new_balance = 0;
                                                }
        
                                                $report_entry['new'] = $earnings;
                                                $report_entry['expense'] = $expenses;
                                                $report_entry['ending'] = $new_balance;
                                            }
        
                                            $total_new_balance += $earnings;
                                            $total_ending_balance += $new_balance;
                                    
                                            $report['report'][] = $report_entry;
                                        }
                                    }
        
                                    // fetch Cash balance
                                    $select_cash = "SELECT *, SUM(amount) as balance FROM hc_account WHERE method = 'Cash' AND DATE(insert_time) = '$today' GROUP BY type";
                                    $sql_cash = mysqli_query($db, $select_cash);
                                    $num_cash = mysqli_num_rows($sql_cash);
                                    $method = 'Cash';
                                    $report_entry = array(
                                        'method' => $method,
                                        'begining' => 0, // Initialize the beginning balance
                                        'new' => 0, // Initialize the new balance
                                        'expense' => 0, // Initialize the expense balance
                                        'ending' => 0, // Initialize the ending balance
                                    );
                                    if ($num_cash == 0) {
                                        $cash_balance = 0;
                                    } else {
                                        $beginning_balance = 0;
                                        $earnings = 0;
                                        $expenses = 0;
                                        while ($row_cash = mysqli_fetch_assoc($sql_cash)) {
                                            $type       = $row_cash['type'];
                                            $balance    = $row_cash['balance'];
                
                                            if ($type == 'Earn') {
                                                $earnings += $balance;
                                            } elseif ($type == 'Expense') {
                                                $expenses += $balance;
                                            }
                                        }
                
                                        $new_balance = $earnings - $expenses;
        
                                        $total_new_balance += $earnings;
                                        $total_ending_balance += $new_balance;
                                        
                                        $report_entry['new'] = $earnings;
                                        $report_entry['expense'] = $expenses;
                                        $report_entry['ending'] = $new_balance;
                                    }
        
                                    $report['report'][] = $report_entry;
        
                                    $report['total_new_balance'] = $total_new_balance;
                                    $report['total_ending_balance'] = $total_ending_balance;
                                    
                                    foreach ($report['report'] as $report_entry) {
                                        ?>
                                        <tr>
                                            <td><?= $report_entry['method'] ?></td>
                                            <td><?= $report_entry['begining'] ?>/- BDT</td>
                                            <td><?= $report_entry['new'] ?>/- BDT</td>
                                            <td><?= $report_entry['expense'] ?>/- BDT</td>
                                            <td><?= $report_entry['ending'] ?>/- BDT</td>
                                        </tr>
                                        <?php 
                                    }?>
        
                                    <tr>
                                        <td colspan="2" class="text_right text_semi">Total New Balance:</td>
                                        <td class="text_semi"><?= $report['total_new_balance'] ?>/- BDT</td>
                                        <td class="text_right text_semi">Total Ending Balance:</td>
                                        <td class="text_semi"><?= $report['total_ending_balance'] ?>/- BDT</td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <div class="btn_grp">
                                <a href="../balance-sheet-print/" target="_blank" class="button btn_sm">Print</a>
                                <a href="../balance-sheet-print/" target="_blank" class="button btn_sm">Send to Admin</a>
                            </div>
                        </div>
                        
                        <?php $report_details = array();
                        // fetch earning details
                        $select_earning_details = "SELECT *, COUNT(id) as person FROM hc_account WHERE type = 'Earn' AND DATE(insert_time) = '$today' GROUP BY purpose";
                        $sql_earning_details = mysqli_query($db, $select_earning_details);
                        $num_earning_details = mysqli_num_rows($sql_earning_details);
                        if ($num_earning_details > 0) {
                            $total_person = 0;
                            while ($row_earning_details = mysqli_fetch_assoc($sql_earning_details)) {
                                $purpose = $row_earning_details['purpose'];
                                $person = $row_earning_details['person'];
                                
                                $total_person += $person;
    
                                $earning_details = array(
                                    'purpose'   => $purpose,
                                    'person'    => $person, 
                                );
    
                                $report_details['earning'][] = $earning_details;
                            }
                        }
    
                        // fetch expense details
                        $select_expense_details = "SELECT * FROM hc_account WHERE type = 'Expense' AND DATE(insert_time) = '$today'";
                        $sql_expense_details = mysqli_query($db, $select_expense_details);
                        $num_expense_details = mysqli_num_rows($sql_expense_details);
                        if ($num_expense_details > 0) {
                            while ($row_expense_details = mysqli_fetch_assoc($sql_expense_details)) {
                                $purpose    = $row_expense_details['purpose'];
                                $method     = $row_expense_details['method'];
                                $trx_id     = $row_expense_details['trx_id'];
                                $amount     = $row_expense_details['amount'];
    
                                $expense_details = array(
                                    'purpose'   => $purpose,
                                    'method'    => $method, 
                                    'trx_id'    => $trx_id, 
                                    'amount'    => $amount, 
                                );
    
                                $report_details['expense'][] = $expense_details;
                            }
                        }?>
                        <div class="account_details_card">
                            <h4 class="account_details">Details</h4>
    
                            <?php if (isset($report_details['earning'])) {
                                echo '<h4 class="account_details_title">Earning Details</h4>';
                                echo '<div>';
                                foreach ($report_details['earning'] as $earning_details) {
                                    ?>
                                    <div class="ep_flex">
                                        <div><?= $earning_details['purpose'] ?></div>
                                        <div><?= $earning_details['person'] ?></div>
                                    </div>
                                    <?php 
                                }
                                echo '</div>';
                            }?>
                            
                            <?php if ($total_person > 0) {
                                ?>
                                <div>
                                    <div class="ep_flex">
                                        <div>Total Admit</div>
                                        <div><?= $total_person ?></div>
                                    </div>
                                </div>
                                <?php 
                            }?>
    
                            <?php if (isset($report_details['expense'])) {
                                echo '<h4 class="account_details_title">Expense Details</h4>';
                                echo '<div>';
                                foreach ($report_details['expense'] as $expense_details) {
                                    ?>
                                    <div class="ep_flex">
                                        <div><?= $expense_details['purpose'] ?></div>
                                        <div><?= $expense_details['amount'] ?> (<?= $expense_details['method'] ?>)</div>
                                    </div>
                                    <?php 
                                }
                                echo '</div>';
                            }?>
                        </div>
                    </div>
                </div>
                <?php 
            } else {
                ?>
                <div class="account_report">
                    <h5 class="box_title">Website Earnings</h5>
    
                    <form action="" method="post">
                        <table class="account_table">
                            <thead>
                                <tr>
                                    <th>SI</th>
                                    <th>Reference</th>
                                    <th>Method</th>
                                    <th>TRX ID</th>
                                    <th>Amount</th>
                                    <th>Issued by</th>
                                    <th>Issued date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php // fetch done purchase id
                                $select_purchase = "SELECT * FROM hc_account WHERE purchase_id != ''";
                                $sql_purchase = mysqli_query($db, $select_purchase);
                                $num_purchase = mysqli_num_rows($sql_purchase);
                                if ($num_purchase == 0) {
                                    $purchase_array = '0';
                                } else {
                                    $purchase_array = '';
                                    while ($row_purchase = mysqli_fetch_assoc($sql_purchase)) {
                                        $purchase_id = $row_purchase['purchase_id'];
                                        $purchase_array = $purchase_id . ',' . $purchase_array;
                                    }
    
                                    $purchase_array = substr($purchase_array, 0, -1);
                                }
                                
                                // fetch transaction
                                $select_transaction = "SELECT * FROM hc_transaction WHERE reference NOT IN ($purchase_array) AND method != 'Free' AND issued_date >= '2023-08-31 20:02:02' ORDER BY issued_date DESC";
                                $sql_transaction = mysqli_query($db, $select_transaction);
                                $num_transaction = mysqli_num_rows($sql_transaction);
                                if ($num_transaction > 0) {
                                    $si = 0;
                                    $total_transaction = 0;
                                    while ($row_transaction = mysqli_fetch_assoc($sql_transaction)) {
                                        $purchase_id    = $row_transaction['reference'];
                                        $method         = $row_transaction['method'];
                                        $amount         = $row_transaction['amount'];
                                        $trx_id         = $row_transaction['trx_id'];
                                        $issued_by      = $row_transaction['issued_by'];
                                        $issued_date    = $row_transaction['issued_date'];
                                        
                                        $total_transaction += $amount;
    
                                        // fetch purchased details
                                        $select_purchased_details = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$purchase_id' GROUP BY item_id";
                                        $sql_purchased_details = mysqli_query($db, $select_purchased_details);
                                        $num_purchased_details = mysqli_num_rows($sql_purchased_details);
                                        if ($num_purchased_details > 0) {
                                            $items_name = '';
                                            while ($row_purchased_details = mysqli_fetch_assoc($sql_purchased_details)) {
                                                $purchase_item  = $row_purchased_details['purchase_item'];
                                                $item_id        = $row_purchased_details['item_id'];
    
                                                // FETCH ITEM NAME
                                                if ($purchase_item == 1) {
                                                    $select_item = "SELECT * FROM hc_course WHERE id = '$item_id'";
                                                    $sql_item = mysqli_query($db, $select_item);
                                                    $num_item = mysqli_num_rows($sql_item);
                                                    if ($num_item > 0) {
                                                        while ($row_item = mysqli_fetch_assoc($sql_item)) {
                                                            $item_name  = $row_item['name'];
                                                        }
                                                    }
                                                } elseif ($purchase_item == 2) {
                                                    $select_item = "SELECT * FROM hc_chapter WHERE id = '$item_id'";
                                                    $sql_item = mysqli_query($db, $select_item);
                                                    $num_item = mysqli_num_rows($sql_item);
                                                    if ($num_item > 0) {
                                                        while ($row_item = mysqli_fetch_assoc($sql_item)) {
                                                            $item_name  = $row_item['chapter'];
                                                        }
                                                    }
                                                }
    
                                                $items_name = $item_name . ', ' . $items_name;
                                            }
                                        }
    
                                        $items_name = substr($items_name, 0, -2);
    
                                        $si++;
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $si ?>
                                                <input type="hidden" name="purchase_id[]" id="" value="<?= $purchase_id ?>">
                                                <input type="hidden" name="purpose[]" id="" value="<?= $items_name ?>">
                                                <input type="hidden" name="method[]" id="" value="<?= $method ?>">
                                                <input type="hidden" name="trx_id[]" id="" value="<?= $trx_id ?>">
                                                <input type="hidden" name="amount[]" id="" value="<?= $amount ?>">
                                                <input type="hidden" name="issued_by[]" id="" value="<?= $issued_by ?>">
                                                <input type="hidden" name="issued_date[]" id="" value="<?= $issued_date ?>">
                                            </td>
                                            <td>
                                                <?= $items_name ?>
                                            </td>
                                            <td>
                                                <?= $method ?>
                                            </td>
                                            <td>
                                                <?= $trx_id ?>
                                            </td>
                                            <td>
                                                <?= $amount ?>
                                            </td>
                                            <td>
                                                <?= $issued_by ?>
                                            </td>
                                            <td>
                                                <?= $issued_date ?>
                                            </td>
                                        </tr>
                                        <?php 
                                    }?>
                                    <tr>
                                        <td colspan="4" class="text_right text_semi">
                                            Total Transaction:
                                        </td>
                                        <td class="text_semi">
                                            <?= $total_transaction ?>/- BDT
                                        </td>
                                        <td colspan="2">
                                        </td>
                                    </tr>
                                    <?php 
                                }?>
                            </tbody>
                        </table>
    
                        <div class="ep_flex ep_end mt_75">
                            <button type="submit" name="add_earn" class="button btn_sm">Add to Account</button>
                        </div>
                    </form>
                </div>
                <?php 
            }?>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>