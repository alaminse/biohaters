<?php include('../assets/includes/header.php'); ?>

<main>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selectedMonth'])) {
            $date = $_POST['selectedMonth'];
        } else {
            $date = date('Y-m', time());
        }
        $year = date("F Y", strtotime($date));
        $firstDayOfMonth = date('Y-m-01', strtotime($date));
        $lastDayOfMonth = date('Y-m-t', strtotime($date));

        $select_report = "SELECT DATE(insert_time) AS transaction_date, SUM(CASE WHEN type = 'Earn' THEN amount ELSE 0 END) AS total_earnings, SUM(CASE WHEN type = 'Expense' THEN amount ELSE 0 END) AS total_expenses FROM hc_account WHERE DATE(insert_time) BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth' GROUP BY DATE(insert_time)";
        $sql_report = mysqli_query($db, $select_report);
        
        $earnings = array();
        $expenses = array();

        if ($sql_report) {
            while ($row_report = mysqli_fetch_assoc($sql_report)) {
                $total_earnings = (int)$row_report['total_earnings'];
                $total_expenses = (int)$row_report['total_expenses'];
                $transaction_date = strtotime($row_report['transaction_date']) * 1000;

                $earnings[] = array(
                    "x" => $transaction_date,
                    "y" => $total_earnings
                );
                $expenses[] = array(
                    "x" => $transaction_date,
                    "y" => $total_expenses
                );
            }
        }
       ?>
    <style>
        .form-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Monthly Transection Report</h4>
        </div>
    </div>
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE BLOG ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                <h5 class="box_title">Report For <span style="color: red"><?php echo $year ?></span></h5>
                <form method="POST" action="" class="form-row">
                    <label for="selectedMonth">Select Month:</label>
                    <input type="month" id="selectedMonth" name="selectedMonth">
                    <button type="submit">Show Data</button>
                </form>
                </div>
                <div id="chartContainer" style="height: 500px; width: 100%;"></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script>
        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "Monthly Transection Report"
                },
                subtitles: [{
                    text: "Earnings & Expenses",
                    fontSize: 18
                }],
                axisY: {
                    prefix: "৳"
                },
                legend: {
                    cursor: "pointer",
                    itemclick: toggleDataSeries
                },
                toolTip: {
                    shared: true
                },
                data: [
                    {
                    type: "area",
                    name: "Earnings",
                    showInLegend: "true",
                    xValueType: "dateTime",
                    xValueFormatString: "DD MMM YYYY", // Format for day granularity
                    yValueFormatString: "৳#,##0.##",
                    dataPoints: <?php echo json_encode($earnings); ?>
                },
                {
                    type: "area",
                    name: "Expenses",
                    showInLegend: "true",
                    xValueType: "dateTime",
                    xValueFormatString: "DD MMM YYYY", // Format for day granularity
                    yValueFormatString: "৳#,##0.##",
                    dataPoints: <?php echo json_encode($expenses); ?>
                }
                ]
            });
            chart.render();
            
            function toggleDataSeries(e){
                if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                }
                else{
                    e.dataSeries.visible = true;
                }
                chart.render();
            }
        }
    </script>
</main>

<?php include('../assets/includes/footer.php'); ?>