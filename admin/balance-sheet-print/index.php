<?php include('../db/db.php');

// include common variable
include('../assets/includes/variable.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!--=========== GOOGLE FONT ===========-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Montserrat+Alternates:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!--=========== BOOTSTRAP CSS ===========-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <!--=========== JQUERY ===========-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!--=========== BOOTSTRAP JS ===========-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <!--=========== FAV ICON ===========-->
    <link rel="shortcut icon" type="image/png" href="../assets/img/logo.png">

    <!--=========== KALPURUSH FONT ===========-->
    <!-- <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet"> -->

    <!--=========== SOLAIMANLIPI FONT ===========-->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    <!--=========== STYLE CSS ===========-->
    <style type="text/css">
        * {margin: 0; padding: 0;}
        
        body {
            max-width: 800px;
            margin: 0 auto;
            overflow-x: hidden;
            position: relative;
            font-family: 'Montserrat', sans-sherif;
            font-weight: normal;
            font-style: normal;
        }
        
        h1 {
            text-align: center;
        }
        
        .ep_flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .box_title {
            margin-bottom: .5rem;
            text-align: center;
            font-size: 1.25rem;
        }
        
        .account_report {
            background: #e5e7eb57;
            padding: 2.5rem;
            border-radius: .75rem;
        }
        
        .account_report_container {
            display: grid;
            column-gap: 1.75rem;
        }
        
        .account_table {
            width: 100%;
            height: max-content;
            margin-bottom: 1.75rem;
        }
        
        .account_table thead tr th, 
        .account_table tbody tr td {
            padding: .25rem 1rem;
            border: 2px dashed #000;
        }
        
        .account_btn_grp {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            column-gap: 1rem;
            border-radius: .5rem;
        }
        
        .account_btn_grp a {
            font-family: var(--body-font);
            font-weight: var(--font-semi-bold);
        }
        
        .account_btn_grp img {
            width: 50px;
        }
        
        .account_button {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }
        
        .account_button_earn:hover, 
        .account_button_earn.active {
            background: #C1DAD0;
        }
        
        .account_button_external:hover, 
        .account_button_external.active {
            background: #C8CBED;
        }
        
        .account_button_expense:hover, 
        .account_button_expense.active {
            background: #EBC9D1;
        }
        
        .account_button_report:hover, 
        .account_button_report.active {
            background: #E3BCA1;
        }
        
        .account_details_card {
            height: max-content;
        }
        
        .account_details_card h4, 
        .account_details_card>div {
            border: 2px dashed #000;
            border-bottom: 0;
            padding: 1rem;
        }
        
        .account_table tbody, 
        .account_details_card>div {
            font-size: 14px;
        }
        
        .account_details_card>div:last-child {
            border-bottom: 2px dashed #000;
        }
        
        .account_details {
            font-size: 1.12rem;
            font-weight: 700;
        }
        
        .account_details_title {
            font-size: 0.938rem;
            font-weight: 700;
        }
        
        .issue_by {
            font-size: 10px;
            margin-top: .25rem;
        }
        
        .signature {
            display: flex;
            justify-content: right;
            margin-top: 1.75rem;
        }
        
        .signature>div {
            width: max-content;
            border-top: 1px solid;
            padding: 0.25rem 1.5rem 0;
        }
    </style>

    <title>BH - Admin</title>
</head>
<body>
    
<?php // initialize today
$today = date('Y-m-d', time());

$today_text = date('d M, Y', time());

if (isset($_POST['get_report'])) {
    $report_date = $_POST['report_date'];
    $today = date('Y-m-d', strtotime($report_date));

    $today_text = date('d M, Y', strtotime($report_date));
}
?>
<div class="account_report">
    <h3 class="box_title">Today's Report (<?= $today_text ?>)</h3>

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
        </div>
        
        <?php $report_details = array();
        // fetch earning details
        $select_earning_details = "SELECT *, COUNT(id) as person FROM hc_account WHERE type = 'Earn' AND DATE(insert_time) = '$today' GROUP BY purpose";
        $sql_earning_details = mysqli_query($db, $select_earning_details);
        $num_earning_details = mysqli_num_rows($sql_earning_details);
        if ($num_earning_details > 0) {
            while ($row_earning_details = mysqli_fetch_assoc($sql_earning_details)) {
                $purpose = $row_earning_details['purpose'];
                $person = $row_earning_details['person'];

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
    
    <div class="issue_by">Issued From biohaters.com || Issued By <?= $admin_name ?></div>
    
    <div class="signature">
        <div>Signature</div>
    </div>
</div>

<script>
    window.print();
</script>
</body>
</html>