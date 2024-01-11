<?php include('../assets/includes/header.php'); ?>

<?php include 'token.php';
    
$credentials_json = file_get_contents('config.json'); 
$credentials_arr = json_decode($credentials_json,true);

$grant_total;
if(isset($_POST['item_id']) && isset($_POST['grant_total']))
{
    $course_id = $_POST['item_id'];

    $select_related_course  = "SELECT * FROM hc_course WHERE id = '$course_id' AND status = 1 AND is_delete = 0";
    $sql_related_course     = mysqli_query($db, $select_related_course);
    $num_course_module    = mysqli_num_rows($sql_related_course);
    if ($num_course_module > 0) {
        while($course = mysqli_fetch_assoc($sql_related_course)) {
            $grant_total = $course['sale_price'];
        }
    }
}

function create($url, $grant_total)
{
    getToken();
    global $credentials_arr;
    $post_token = array(
        'mode' => '0011',
        'amount' => $grant_total,
        'payerReference' => " ",
        'callbackURL' => "http://localhost/biohaters/purchase/callback.php?" . $url, // Your callback URL
        'currency' => 'BDT',
        'intent' => 'sale',
        'merchantInvoiceNumber' => 'Inv'.rand()
    );

    $url = curl_init($credentials_arr['base_url']."/checkout/create");
    $post_token = json_encode($post_token);
    $header = array(
        'Content-Type:application/json',
        'Authorization:'. $_SESSION["token"],
        'X-APP-Key:'. $credentials_arr['app_key']
    );

    curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_POSTFIELDS, $post_token);
    curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
    $result_data = curl_exec($url);
    curl_close($url);
    
    $response = json_decode($result_data, true);
    $bkashURL = $response['bkashURL'];
    print_r($bkashURL);
    header("Location: ".$bkashURL);
    exit;
}

// checkout process
if (isset($_POST['checkout'])) {
    if ($login_validity == 1) {
        $name   = mysqli_escape_string($db, $_POST['student_name']);
        $email  = mysqli_escape_string($db, $_POST['student_email']);
        $phone  = mysqli_escape_string($db, $_POST['student_phone']);
    } else {
        $name   = mysqli_escape_string($db, $_POST['name']);
        $email  = mysqli_escape_string($db, $_POST['email']);
        $phone  = mysqli_escape_string($db, $_POST['phone']);
    }

    $purchase_item  = mysqli_escape_string($db, $_POST['purchase_item']);
    $subtotal       = mysqli_escape_string($db, $_POST['subtotal']);
    $bkash_charge   = mysqli_escape_string($db, $_POST['bkash_charge']);
    $grant_total    = $grant_total;
    // $grant_total    = mysqli_escape_string($db, $_POST['grant_total']);

    if (empty($name) || empty($email) || empty($phone)) {
        ?>
        <div class="modal_container payment_modal show-modal" id="">
            <div class="modal_content payment_content">
                <div class="modal_body">
                    <div class="payment_icon_error text_center">
                        <i class='bx bxs-error'></i>
                    </div>

                    <p class="payment_success_subtitle text_center">Required Fields are Invalid!</p>
                </div>

                <div class="">
                    <a href="<?= $base_url ?>" class="button no_hover btn_sm m_auto">Go Back</a>
                </div>
            </div>
        </div>
        <?php 
    } else {
        $phone = str_replace('+880', '0', $phone);
        $phone = str_replace(' ', '', $phone);

        $phone_verify = substr($phone, 0, 3);
        
        if ((!preg_match("/^([0-9]{11})$/", $phone))) {
            ?>
            <div class="modal_container payment_modal show-modal" id="">
                <div class="modal_content payment_content">
                    <div class="modal_body">
                        <div class="payment_icon_error text_center">
                            <i class='bx bxs-error'></i>
                        </div>
    
                        <p class="payment_success_subtitle text_center">Phone Number is Invalid!</p>
                    </div>
    
                    <div class="">
                        <a href="<?= $base_url ?>" class="button no_hover btn_sm m_auto">Go Back</a>
                    </div>
                </div>
            </div>
            <?php 
        } else {
            if (($phone_verify == '013') || ($phone_verify == '014') || ($phone_verify == '015') || ($phone_verify == '016') || ($phone_verify == '017') || ($phone_verify == '018') || ($phone_verify == '019')) {
                $email = str_replace(' ', '', $email);

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    ?>
                    <div class="modal_container payment_modal show-modal" id="">
                        <div class="modal_content payment_content">
                            <div class="modal_body">
                                <div class="payment_icon_error text_center">
                                    <i class='bx bxs-error'></i>
                                </div>
        
                                <p class="payment_success_subtitle text_center">Phone Number is Invalid!</p>
                            </div>
        
                            <div class="">
                                <a href="<?= $base_url ?>" class="button no_hover btn_sm m_auto">Go Back</a>
                            </div>
                        </div>
                    </div>
                    <?php 
                } else {
                    $array_email = explode('@', $email);
                    $extension_email = end($array_email);

                    if ($extension_email == 'gmail.com' || $extension_email == 'yahoo.com' || $extension_email == 'icloud.com' || $extension_email == 'outlook.com') {
                        do {
                            // generate token
                            $tokenLength = 100; // Length of the token in bytes
                
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
                            $check_token = "SELECT * FROM hc_purchase_token WHERE payment_token = '$token'";
                            $sql_check_token = mysqli_query($db, $check_token);
                            $num_check_token = mysqli_num_rows($sql_check_token);
                        } while ($num_check_token != 0);
            
                        $token_date = date('Y-m-d H:i:s', time());
                        
                        // create url
                        $url = "bh_tokenized=".$token;
                
                        // collect item id & price
                        // foreach ($_POST['item_id'] as $key_item => $item_id) {
                        if($_POST['item_id']) {
                            $item_id = $_POST['item_id'];
                            $price = $_POST['price'];
            
                            // insert tokenized information
                            $insert = "INSERT INTO hc_purchase_token (payment_token, price, subtotal, charge, total_amount, name, email, phone, purchase_item, item_id, token_date) VALUES ('$token', '$price', '$subtotal', '$bkash_charge', '$grant_total', '$name', '$email', '$phone', '$purchase_item', '$item_id', '$token_date')";
                            mysqli_query($db, $insert);
                        }
                        
                        echo create($url, $grant_total);
                    } else {
                        ?>
                        <div class="modal_container payment_modal show-modal" id="">
                            <div class="modal_content payment_content">
                                <div class="modal_body">
                                    <div class="payment_icon_error text_center">
                                        <i class='bx bxs-error'></i>
                                    </div>
                
                                    <p class="payment_success_subtitle text_center">Email Address is Invalid!</p>
                                </div>
                
                                <div class="">
                                    <a href="<?= $base_url ?>" class="button no_hover btn_sm m_auto">Go Back</a>
                                </div>
                            </div>
                        </div>
                        <?php 
                    }
                }
            } else {
                ?>
                <div class="modal_container payment_modal show-modal" id="">
                    <div class="modal_content payment_content">
                        <div class="modal_body">
                            <div class="payment_icon_error text_center">
                                <i class='bx bxs-error'></i>
                            </div>
        
                            <p class="payment_success_subtitle text_center">Phone Number is Invalid!</p>
                        </div>
        
                        <div class="">
                            <a href="<?= $base_url ?>" class="button no_hover btn_sm m_auto">Go Back</a>
                        </div>
                    </div>
                </div>
                <?php 
            }
        }
    }
} else {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>';
    </script>
    <?php 
}?>

<?php include('../assets/includes/footer.php'); ?>