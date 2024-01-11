<?php // php extension file redirecting to folder
function current_url()
{
    $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $valid_url = str_replace("&", "&amp;", $url);

    return $valid_url;
}

$current_url = current_url();

$array_url = explode('/', $current_url);
$extension_url = end($array_url);

if ($extension_url == 'index.php') {
    $redirect_url = substr($current_url, 0, -9); ?>
    <script type="text/javascript">
        window.location.href = '<?php echo $redirect_url; ?>';
    </script>
    <?php 
}

$base_url = 'http://localhost/biohaters/';

// include database
include('../admin/db/db.php');

// session start
session_start();

// set local time zone
date_default_timezone_set('Asia/Dhaka');

// checking cookie & redirect to valid folder
include('../assets/includes/login_validity.php');

// include common variable
include('../assets/includes/variable.php');

// include purchase variable
include('../assets/includes/purchase_variable.php');

// checking dashboard validity & redirect to valid folder
include('../assets/includes/dashboard_validity.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=========== BOOTSTRAP CSS ===========-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!--=========== JQUERY ===========-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!--=========== BOOTSTRAP JS ===========-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!--=========== MDB CSS ===========-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/morris.js/0.5.1/morris.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
    <script src="https://cdn.jsdelivr.net/morris.js/0.5.1/morris.min.js"></script>

    <!--=============== BOX ICONS ===============-->
	<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <!--=========== SOLAIMANLIPI FONT ===========-->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    <!--=========== FAV ICON ===========-->
    <link rel="shortcut icon" type="image/png" href="../assets/img/logo.png">

    <!--=========== STYLE CSS ===========-->
    <link rel="stylesheet" href="../assets/css/dashboard-style.css">

    <!--=========== LOGIN CSS ===========-->
    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <title>Biology Haters</title>
</head>
<body>

<?php if (isset($_GET['invoice'])) {
    $purchase_id = $_GET['invoice'];

    // check invoice validity
    $select_invoice = "SELECT * FROM hc_purchase WHERE id = '$purchase_id' AND student_id  = '$student_id'";
    $sql_invoice = mysqli_query($db, $select_invoice);
    $num_invoice = mysqli_num_rows($sql_invoice);
    if ($num_invoice > 0) {
        $row_invoice = mysqli_fetch_assoc($sql_invoice);
        
        // invoice data variable
        $invoice_id                = $row_invoice['id'];
        $invoice_item              = $row_invoice['purchase_item'];
        $invoice_status            = $row_invoice['status'];
        $invoice_payment_status    = $row_invoice['payment_status'];
        $invoice_method            = $row_invoice['method'];
        $invoice_trx_id            = $row_invoice['trx_id'];
        $invoice_subtotal          = $row_invoice['subtotal'];
        $invoice_charge            = $row_invoice['charge'];
        $invoice_total_amount      = $row_invoice['total_amount'];
        $invoice_purchase_date     = $row_invoice['purchase_date'];

        // fetch invoice items
        $select_items = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$purchase_id' ORDER BY id DESC";
        $sql_items = mysqli_query($db, $select_items);
        $num_items = mysqli_num_rows($sql_items);
        if ($num_items > 0) {
            $net_payable = 0;
            while ($row_items = mysqli_fetch_assoc($sql_items)) {
                // purchase items data variable
                $items_id           = $row_items['id'];
                $items_purchase_id  = $row_items['purchase_id'];
                $items_item         = $row_items['item_id'];
                $items_price        = $row_items['price'];

                // net payable
                $net_payable += $items_price;
            }

            // charge
            if ($invoice_method == 'Bkash') {
                $charge = floor($net_payable * 0.015);
            } elseif ($invoice_method == 'Cash') {
                $charge = 0;
            }

            $total_net_payable = $net_payable + $charge;

            $due_amount = $total_net_payable - $invoice_total_amount;
        }
    }
}?>

<!--=========== PURCHASE HISTORY SECTION ===========-->
<section class="hc_section">
    <div class="invoice_details_container hc_container ep_grid">
        <div class="invoice_details_content">
            <div class="invoice_details_icon text_center">
                <img src="../assets/img/logo.png" alt="">
            </div>

            <p class="invoice_details_subtitle text_center">Payment Success!</p>
            <p class="invoice_details_title text_center">BDT <?= $invoice_total_amount ?>.00</p>

            <div class="invoice_details_data">
                <div class="ep_flex">
                    <div class="invoice_details_properties">Invoice No.</div>
                    <div class="invoice_details_value"># <?= $invoice_id ?></div>
                </div>

                <div class="ep_flex">
                    <div class="invoice_details_properties">TRX ID</div>
                    <div class="invoice_details_value"><?= $invoice_trx_id ?></div>
                </div>

                <div class="ep_flex">
                    <div class="invoice_details_properties">Payment Time</div>
                    <div class="invoice_details_value text_right"><?= $invoice_purchase_date ?></div>
                </div>

                <div class="ep_flex">
                    <div class="invoice_details_properties">Payment Method</div>
                    <div class="invoice_details_value"><?= $invoice_method ?></div>
                </div>

                <div class="ep_flex">
                    <div class="invoice_details_properties">Sender Name</div>
                    <div class="invoice_details_value"><?= $student_name ?></div>
                </div>
            </div>

            <div class="invoice_details_summery">
                <div class="ep_flex">
                    <div class="invoice_details_properties">Amount</div>
                    <div class="invoice_details_value">BDT <?= $invoice_subtotal ?>.00</div>
                </div>

                <div class="ep_flex">
                    <div class="invoice_details_properties">Charge</div>
                    <div class="invoice_details_value">BDT <?= $invoice_charge ?>.00</div>
                </div>

                <div class="ep_flex">
                    <div class="invoice_details_properties">Due</div>
                    <div class="invoice_details_value">BDT <?= $due_amount ?>.00</div>
                </div>
            </div>
        </div>

        <div class="invoice_details_wrapper">
            <h4 class="invoice_details_data_title text_center">Purchase Items</h4>

            <div class="invoice_details_table">
                <?php // fetch invoice items
                $select_items = "SELECT * FROM hc_purchase_details WHERE purchase_id = '$purchase_id' ORDER BY id DESC";
                $sql_items = mysqli_query($db, $select_items);
                $num_items = mysqli_num_rows($sql_items);
                if ($num_items > 0) {
                    $net_payable = 0;
                    while ($row_items = mysqli_fetch_assoc($sql_items)) {
                        // purchase items data variable
                        $items_id           = $row_items['id'];
                        $items_purchase_id  = $row_items['purchase_id'];
                        $items_item         = $row_items['item_id'];
                        $items_price        = $row_items['price'];

                        // fetch item name
                        if ($invoice_item == '1') {
                            $select_item_name = "SELECT * FROM hc_course WHERE id = '$items_item'";
                        } elseif ($invoice_item == '2') {
                            $select_item_name = "SELECT * FROM hc_chapter WHERE id = '$items_item'";
                        }
                        $sql_item_name = mysqli_query($db, $select_item_name);
                        $row_item_name = mysqli_fetch_assoc($sql_item_name);
                        if ($invoice_item == '1') {
                            $item_name = $row_item_name['name'];
                        } elseif ($invoice_item == '2') {
                            $item_name = $row_item_name['chapter'];
                        }?>
                        <div class="ep_flex">
                            <div class="invoice_details_properties">Item</div>
                            <div class="invoice_details_value"><?= $item_name ?></div>
                        </div>

                        <div class="ep_flex">
                            <div class="invoice_details_properties">Price</div>
                            <div class="invoice_details_value">BDT <?= $items_price ?>.00</div>
                        </div>
                        <?php 
                    }
                }?>
            </div>
        </div>
    </div>
</section>

<script>
    window.print();
</script>

<?php include('../assets/includes/dashboard_footer.php'); ?>