<!--
=========================================================
* Soft UI Dashboard - v1.0.7
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<?php // Set the character set for the output
header('Content-Type: text/html; charset=utf-8');

// include database
include('db.php');

// set local time zone
date_default_timezone_set('Asia/Dhaka');

// checking cookie & redirect to valid folder
if (empty($_COOKIE['admin_id'])) {
    ?>
    <script type="text/javascript">
        window.location.href = '../';
    </script>
    <?php 
}

// include common variable
include('../assets/includes/variable.php');?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>
    Soft UI Dashboard by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.0.7" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
</head>

<?php $response = '';
$today_date     = date('Y-m-d', time());
$today_time     = date('Y-m-d H:i:s', time());
$today_time_new = new DateTime($today_time);
if (isset($_POST['attend'])) {
    // Get the phone number from the POST data
    $phone = mysqli_escape_string($db, $_POST['phone']);
    $attend_type = $_POST['attend_type'];

    if ($phone == '') {
      $response = '<div class="col-12 col-md-8 mx-auto badge bg-gradient-primary">Give a phone number</div>';
    } else {
      if ($phone != '01680920753' && $phone != '01518448104' && $phone != '01796086202' && $phone != '01616752861' && $phone != '01952756497' && $phone != '01616720009' && $phone != '01409750320') {
        $response = '<div class="col-12 col-md-8 mx-auto badge bg-gradient-primary">You are not a employee of BH</div>';
      } else {
        if ($attend_type === 'Entry') {
          // check today attendance
          $check_attendance = "SELECT * FROM attendance WHERE phone = '$phone' AND DATE(entry_time) = '$today_date'";
          $sql_check_attendance = mysqli_query($db, $check_attendance);
          $num_check_attendance = mysqli_num_rows($sql_check_attendance);
          if ($num_check_attendance == 0) {
            $insert = "INSERT INTO attendance (phone, entry_time) VALUES ('$phone', '$today_time')";
            $sql = mysqli_query($db, $insert);
            if ($sql) {
                $response = '<div class="col-12 col-md-8 mx-auto badge bg-gradient-success">Enter in office</div>';
                if ($phone == '01680920753') {
                    $name = "Sufi Ahmed";
                } elseif ($phone == '01796086202') {
                    $name = "Muhib Rahman";
                } elseif ($phone == '01616752861') {
                    $name = "Mim Onti";
                } elseif ($phone == '01952756497') {
                    $name = "Md. Emdadul Haque";
                } elseif ($phone == '01616720009') {
                    $name = "Mehedi Hasan";
                } elseif ($phone == '01518448104') {
                    $name = "Md. Al Amin";
                } elseif ($phone == '01409750320') {
                    $name = "Shohag Khan";
                }
                $msg = $name . " has arrived at " . $today_time;
                
                // send OTP by sms
                $to = "01680920753,01863188699";
                $token = "913518264916767232092ebe8e002b72391add1856353f4a8c3b";
                $message = "$msg";
            
                $url = "http://api.greenweb.com.bd/api.php?json";
            
            
                $data= array(
                'to'=>"$to",
                'message'=>"$message",
                'token'=>"$token"
                ); 
                $ch = curl_init(); 
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_ENCODING, '');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $smsresult = curl_exec($ch);
            }
          } else {
            $response = '<div class="col-12 col-md-8 mx-auto badge bg-gradient-danger">You have already attended</div>';
          }
        }

        if ($attend_type === 'Exit') {
          // check today attendance
          $check_attendance = "SELECT * FROM attendance WHERE phone = '$phone' AND DATE(entry_time) = '$today_date'";
          $sql_check_attendance = mysqli_query($db, $check_attendance);
          $num_check_attendance = mysqli_num_rows($sql_check_attendance);
          if ($num_check_attendance > 0) {
            // check exit null?
            $check_exit = "SELECT * FROM attendance WHERE phone = '$phone' AND exit_time IS NULL";
            $sql_check_exit = mysqli_query($db, $check_exit);
            $num_check_exit = mysqli_num_rows($sql_check_exit);
            if ($num_check_exit == 0) {
              $response = '<div class="col-12 col-md-8 mx-auto badge bg-gradient-danger">You have already exited</div>';
            } else {
              $insert = "UPDATE attendance SET exit_time = '$today_time' WHERE phone = '$phone' AND DATE(entry_time) = '$today_date'";
              $sql = mysqli_query($db, $insert);
              if ($sql) {
                $response = '<div class="col-12 col-md-8 mx-auto badge bg-gradient-success">Exit from office</div>';
              }
            }
          } else {
            $response = '<div class="col-12 col-md-8 mx-auto badge bg-gradient-danger">You have to attend first.</div>';
          }
        }
      }
    }
}?>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="http://127.0.0.1/employee/" target="_blank">
        <img src="assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Soft UI Dashboard</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="https://biohaters.com/admin/employee/">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>shop </title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(0.000000, 148.000000)">
                        <path class="color-background opacity-6" d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
                        <path class="color-background" d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="https://biohaters.com/admin/employee/report.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>office</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-1869.000000, -293.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g id="office" transform="translate(153.000000, 2.000000)">
                        <path class="color-background opacity-6" d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z"></path>
                        <path class="color-background" d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Report</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="pages/billing.html">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>credit-card</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(453.000000, 454.000000)">
                        <path class="color-background opacity-6" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"></path>
                        <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Billing</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="pages/virtual-reality.html">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>box-3d-50</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(603.000000, 0.000000)">
                        <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z"></path>
                        <path class="color-background opacity-6" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z"></path>
                        <path class="color-background opacity-6" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Virtual Reality</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Dashboard</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row my-3">
        <div class="col-md-6 col-sm-12 mx-auto">
          <div class="card text-center pt-4">          
            <div class="card-body pt-2">
              <h4 class="card-title h5 d-block text-darker">
                Attendance Form
              </h4>
              <form id="attend-form" action="" method="post">
                <div class="row text-center mt-3">
                  <div class="col-8 mx-auto">
                    <div class="input-group mb-2">
                      <span class="input-group-text"><i class="fas fa-phone-alt" aria-hidden="true"></i></span>
                      <input class="form-control" name="phone" placeholder="Give your phone number" type="text">
                    </div>
                  </div>
                  <div class="col-8 d-flex mx-auto text-start mb-3">
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="attend_type" value="Entry" id="entry" checked>
                        <label class="form-check-label" for="entry">
                          Entry
                        </label>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="attend_type" value="Exit" id="exit">
                        <label class="form-check-label" for="exit">
                          Exit
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-8 mx-auto">
                    <button type="submit" class="btn btn-primary" name="attend">Submit</button>
                  </div>
                  <?= $response ?>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Authors table</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Author</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Designation</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Entry Time</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Exit Time</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Duration</th>
                    </tr>
                  </thead>
                  <?php if ($admin_role == 0 || $admin_role == 1 || $admin_role == 2) {
                    ?>
                    <tbody>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="assets/img/team-2.jpg" class="avatar avatar-sm me-3" alt="user5">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">Sufi Ahmed</h6>
                                <p class="text-xs text-secondary mb-0">01680920753</p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0">Managing Director</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <?php // check sufi
                            $check_sufi = "SELECT * FROM attendance WHERE phone = '01680920753' AND DATE(entry_time) = '$today_date'";
                            $sql_sufi = mysqli_query($db, $check_sufi);
                            $num_sufi = mysqli_num_rows($sql_sufi);
                            if ($num_sufi > 0) {
                              $row_sufi = mysqli_fetch_assoc($sql_sufi);
                              $entry_time = $row_sufi['entry_time'];
                              $exit_time  = $row_sufi['exit_time'];
                              $entry_time_new = new DateTime($entry_time);
                              $exit_time_new  = new DateTime($exit_time);
                              $entry_time_format = date('d-M-Y h:i a', strtotime($entry_time));
                              if ($exit_time != '') {
                                $duration = $entry_time_new->diff($exit_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = date('d-M-Y h:i a', strtotime($exit_time));
                              } else {
                                $duration = $entry_time_new->diff($today_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = '--';
                              }
                              echo '<span class="badge badge-sm bg-gradient-success">Present</span>';
                            } else {
                              echo '<span class="badge badge-sm bg-gradient-secondary">Absent</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_sufi > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $entry_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_sufi > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $exit_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_sufi > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $duration . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="assets/img/team-4.jpg" class="avatar avatar-sm me-3" alt="user6">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">Muhib Rahman</h6>
                                <p class="text-xs text-secondary mb-0">01796086202</p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0">Account Executive</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <?php // check muhib
                            $check_muhib = "SELECT * FROM attendance WHERE phone = '01796086202' AND DATE(entry_time) = '$today_date'";
                            $sql_muhib = mysqli_query($db, $check_muhib);
                            $num_muhib = mysqli_num_rows($sql_muhib);
                            if ($num_muhib > 0) {
                              $row_muhib = mysqli_fetch_assoc($sql_muhib);
                              $entry_time = $row_muhib['entry_time'];
                              $exit_time  = $row_muhib['exit_time'];
                              $entry_time_new = new DateTime($entry_time);
                              $exit_time_new  = new DateTime($exit_time);
                              $entry_time_format = date('d-M-Y h:i a', strtotime($entry_time));
                              if ($exit_time != '') {
                                $duration = $entry_time_new->diff($exit_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = date('d-M-Y h:i a', strtotime($exit_time));
                              } else {
                                $duration = $entry_time_new->diff($today_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = '--';
                              }
                              echo '<span class="badge badge-sm bg-gradient-success">Present</span>';
                            } else {
                              echo '<span class="badge badge-sm bg-gradient-secondary">Absent</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_muhib > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $entry_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_muhib > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $exit_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_muhib > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $duration . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="assets/img/team-3.jpg" class="avatar avatar-sm me-3" alt="user2">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">Mim Onti</h6>
                                <p class="text-xs text-secondary mb-0">01616752861</p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0">Junior Executive</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <?php // check onti
                            $check_onti = "SELECT * FROM attendance WHERE phone = '01616752861' AND DATE(entry_time) = '$today_date'";
                            $sql_onti = mysqli_query($db, $check_onti);
                            $num_onti = mysqli_num_rows($sql_onti);
                            if ($num_onti > 0) {
                              $row_onti = mysqli_fetch_assoc($sql_onti);
                              $entry_time = $row_onti['entry_time'];
                              $exit_time  = $row_onti['exit_time'];
                              $entry_time_new = new DateTime($entry_time);
                              $exit_time_new  = new DateTime($exit_time);
                              $entry_time_format = date('d-M-Y h:i a', strtotime($entry_time));
                              if ($exit_time != '') {
                                $duration = $entry_time_new->diff($exit_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = date('d-M-Y h:i a', strtotime($exit_time));
                              } else {
                                $duration = $entry_time_new->diff($today_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = '--';
                              }
                              echo '<span class="badge badge-sm bg-gradient-success">Present</span>';
                            } else {
                              echo '<span class="badge badge-sm bg-gradient-secondary">Absent</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_onti > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $entry_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_onti > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $exit_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_onti > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $duration . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="assets/img/team-3.jpg" class="avatar avatar-sm me-3" alt="user2">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">Md. Al Amin</h6>
                                <p class="text-xs text-secondary mb-0">01518448104</p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0">Software Engineer</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <?php // check alamin
                            $check_alamin = "SELECT * FROM attendance WHERE phone = '01518448104' AND DATE(entry_time) = '$today_date'";
                            $sql_alamin = mysqli_query($db, $check_alamin);
                            $num_alamin = mysqli_num_rows($sql_alamin);
                            if ($num_alamin > 0) {
                              $row_alamin = mysqli_fetch_assoc($sql_alamin);
                              $entry_time = $row_alamin['entry_time'];
                              $exit_time  = $row_alamin['exit_time'];
                              $entry_time_new = new DateTime($entry_time);
                              $exit_time_new  = new DateTime($exit_time);
                              $entry_time_format = date('d-M-Y h:i a', strtotime($entry_time));
                              if ($exit_time != '') {
                                $duration = $entry_time_new->diff($exit_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = date('d-M-Y h:i a', strtotime($exit_time));
                              } else {
                                $duration = $entry_time_new->diff($today_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = '--';
                              }
                              echo '<span class="badge badge-sm bg-gradient-success">Present</span>';
                            } else {
                              echo '<span class="badge badge-sm bg-gradient-secondary">Absent</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_alamin > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $entry_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_alamin > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $exit_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_alamin > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $duration . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="assets/img/team-2.jpg" class="avatar avatar-sm me-3" alt="user5">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">Md. Emdadul Haque</h6>
                                <p class="text-xs text-secondary mb-0">01952756497</p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0">Graphic Designer</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <?php // check anik
                            $check_anik = "SELECT * FROM attendance WHERE phone = '01952756497' AND DATE(entry_time) = '$today_date'";
                            $sql_anik = mysqli_query($db, $check_anik);
                            $num_anik = mysqli_num_rows($sql_anik);
                            if ($num_anik > 0) {
                              $row_anik = mysqli_fetch_assoc($sql_anik);
                              $entry_time = $row_anik['entry_time'];
                              $exit_time  = $row_anik['exit_time'];
                              $entry_time_new = new DateTime($entry_time);
                              $exit_time_new  = new DateTime($exit_time);
                              $entry_time_format = date('d-M-Y h:i a', strtotime($entry_time));
                              if ($exit_time != '') {
                                $duration = $entry_time_new->diff($exit_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = date('d-M-Y h:i a', strtotime($exit_time));
                              } else {
                                $duration = $entry_time_new->diff($today_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = '--';
                              }
                              echo '<span class="badge badge-sm bg-gradient-success">Present</span>';
                            } else {
                              echo '<span class="badge badge-sm bg-gradient-secondary">Absent</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_anik > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $entry_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_anik > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $exit_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_anik > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $duration . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="assets/img/team-4.jpg" class="avatar avatar-sm me-3" alt="user6">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">Mehedi Hasan</h6>
                                <p class="text-xs text-secondary mb-0">01616720009</p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0">Website Programmer</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <?php // check mehedi
                            $check_mehedi = "SELECT * FROM attendance WHERE phone = '01616720009' AND DATE(entry_time) = '$today_date'";
                            $sql_mehedi = mysqli_query($db, $check_mehedi);
                            $num_mehedi = mysqli_num_rows($sql_mehedi);
                            if ($num_mehedi > 0) {
                              $row_mehedi = mysqli_fetch_assoc($sql_mehedi);
                              $entry_time = $row_mehedi['entry_time'];
                              $exit_time  = $row_mehedi['exit_time'];
                              $entry_time_new = new DateTime($entry_time);
                              $exit_time_new  = new DateTime($exit_time);
                              $entry_time_format = date('d-M-Y h:i a', strtotime($entry_time));
                              if ($exit_time != '') {
                                $duration = $entry_time_new->diff($exit_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = date('d-M-Y h:i a', strtotime($exit_time));
                              } else {
                                $duration = $entry_time_new->diff($today_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = '--';
                              }
                              echo '<span class="badge badge-sm bg-gradient-success">Present</span>';
                            } else {
                              echo '<span class="badge badge-sm bg-gradient-secondary">Absent</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_mehedi > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $entry_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_mehedi > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $exit_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_mehedi > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $duration . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="assets/img/team-3.jpg" class="avatar avatar-sm me-3" alt="user2">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">Shohag</h6>
                                <p class="text-xs text-secondary mb-0">01409750320</p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0">Office Assistant</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <?php // check shohag
                            $check_shohag = "SELECT * FROM attendance WHERE phone = '01409750320' AND DATE(entry_time) = '$today_date'";
                            $sql_shohag = mysqli_query($db, $check_shohag);
                            $num_shohag = mysqli_num_rows($sql_shohag);
                            if ($num_shohag > 0) {
                              $row_shohag = mysqli_fetch_assoc($sql_shohag);
                              $entry_time = $row_shohag['entry_time'];
                              $exit_time  = $row_shohag['exit_time'];
                              $entry_time_new = new DateTime($entry_time);
                              $exit_time_new  = new DateTime($exit_time);
                              $entry_time_format = date('d-M-Y h:i a', strtotime($entry_time));
                              if ($exit_time != '') {
                                $duration = $entry_time_new->diff($exit_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = date('d-M-Y h:i a', strtotime($exit_time));
                              } else {
                                $duration = $entry_time_new->diff($today_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = '--';
                              }
                              echo '<span class="badge badge-sm bg-gradient-success">Present</span>';
                            } else {
                              echo '<span class="badge badge-sm bg-gradient-secondary">Absent</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_shohag > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $entry_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_shohag > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $exit_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_shohag > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $duration . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                        </tr>
                      </tbody>
                    <?php 
                  }?>
                  <?php if ($admin_role == 4) {
                    ?>
                    <tbody>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="assets/img/team-4.jpg" class="avatar avatar-sm me-3" alt="user6">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">Muhib Rahman</h6>
                                <p class="text-xs text-secondary mb-0">01796086202</p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0">Account Executive</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <?php // check muhib
                            $check_muhib = "SELECT * FROM attendance WHERE phone = '01796086202' AND DATE(entry_time) = '$today_date'";
                            $sql_muhib = mysqli_query($db, $check_muhib);
                            $num_muhib = mysqli_num_rows($sql_muhib);
                            if ($num_muhib > 0) {
                              $row_muhib = mysqli_fetch_assoc($sql_muhib);
                              $entry_time = $row_muhib['entry_time'];
                              $exit_time  = $row_muhib['exit_time'];
                              $entry_time_new = new DateTime($entry_time);
                              $exit_time_new  = new DateTime($exit_time);
                              $entry_time_format = date('d-M-Y h:i a', strtotime($entry_time));
                              if ($exit_time != '') {
                                $duration = $entry_time_new->diff($exit_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = date('d-M-Y h:i a', strtotime($exit_time));
                              } else {
                                $duration = $entry_time_new->diff($today_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = '--';
                              }
                              echo '<span class="badge badge-sm bg-gradient-success">Present</span>';
                            } else {
                              echo '<span class="badge badge-sm bg-gradient-secondary">Absent</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_muhib > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $entry_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_muhib > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $exit_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_muhib > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $duration . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                        </tr>
                    </tbody>
                    <?php 
                  }?>
                  <?php if ($admin_role == 5) {
                    ?>
                    <tbody>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="assets/img/team-3.jpg" class="avatar avatar-sm me-3" alt="user2">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">Mim Onti</h6>
                                <p class="text-xs text-secondary mb-0">01616752861</p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0">Junior Executive</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <?php // check onti
                            $check_onti = "SELECT * FROM attendance WHERE phone = '01616752861' AND DATE(entry_time) = '$today_date'";
                            $sql_onti = mysqli_query($db, $check_onti);
                            $num_onti = mysqli_num_rows($sql_onti);
                            if ($num_onti > 0) {
                              $row_onti = mysqli_fetch_assoc($sql_onti);
                              $entry_time = $row_onti['entry_time'];
                              $exit_time  = $row_onti['exit_time'];
                              $entry_time_new = new DateTime($entry_time);
                              $exit_time_new  = new DateTime($exit_time);
                              $entry_time_format = date('d-M-Y h:i a', strtotime($entry_time));
                              if ($exit_time != '') {
                                $duration = $entry_time_new->diff($exit_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = date('d-M-Y h:i a', strtotime($exit_time));
                              } else {
                                $duration = $entry_time_new->diff($today_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = '--';
                              }
                              echo '<span class="badge badge-sm bg-gradient-success">Present</span>';
                            } else {
                              echo '<span class="badge badge-sm bg-gradient-secondary">Absent</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_onti > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $entry_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_onti > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $exit_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_onti > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $duration . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="assets/img/team-3.jpg" class="avatar avatar-sm me-3" alt="user2">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">Shohag</h6>
                                <p class="text-xs text-secondary mb-0">01409750320</p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0">Office Assistant</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <?php // check shohag
                            $check_shohag = "SELECT * FROM attendance WHERE phone = '01409750320' AND DATE(entry_time) = '$today_date'";
                            $sql_shohag = mysqli_query($db, $check_shohag);
                            $num_shohag = mysqli_num_rows($sql_shohag);
                            if ($num_shohag > 0) {
                              $row_shohag = mysqli_fetch_assoc($sql_shohag);
                              $entry_time = $row_shohag['entry_time'];
                              $exit_time  = $row_shohag['exit_time'];
                              $entry_time_new = new DateTime($entry_time);
                              $exit_time_new  = new DateTime($exit_time);
                              $entry_time_format = date('d-M-Y h:i a', strtotime($entry_time));
                              if ($exit_time != '') {
                                $duration = $entry_time_new->diff($exit_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = date('d-M-Y h:i a', strtotime($exit_time));
                              } else {
                                $duration = $entry_time_new->diff($today_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = '--';
                              }
                              echo '<span class="badge badge-sm bg-gradient-success">Present</span>';
                            } else {
                              echo '<span class="badge badge-sm bg-gradient-secondary">Absent</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_shohag > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $entry_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_shohag > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $exit_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_shohag > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $duration . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                        </tr>
                      </tbody>
                    <?php 
                  }?>
                  <?php if ($admin_role == 6) {
                    ?>
                    <tbody>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="assets/img/team-2.jpg" class="avatar avatar-sm me-3" alt="user5">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">Md. Emdadul Haque</h6>
                                <p class="text-xs text-secondary mb-0">01952756497</p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0">Graphic Designer</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <?php // check anik
                            $check_anik = "SELECT * FROM attendance WHERE phone = '01952756497' AND DATE(entry_time) = '$today_date'";
                            $sql_anik = mysqli_query($db, $check_anik);
                            $num_anik = mysqli_num_rows($sql_anik);
                            if ($num_anik > 0) {
                              $row_anik = mysqli_fetch_assoc($sql_anik);
                              $entry_time = $row_anik['entry_time'];
                              $exit_time  = $row_anik['exit_time'];
                              $entry_time_new = new DateTime($entry_time);
                              $exit_time_new  = new DateTime($exit_time);
                              $entry_time_format = date('d-M-Y h:i a', strtotime($entry_time));
                              if ($exit_time != '') {
                                $duration = $entry_time_new->diff($exit_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = date('d-M-Y h:i a', strtotime($exit_time));
                              } else {
                                $duration = $entry_time_new->diff($today_time_new)->format('%h hrs %i mins');
                                $exit_time_format  = '--';
                              }
                              echo '<span class="badge badge-sm bg-gradient-success">Present</span>';
                            } else {
                              echo '<span class="badge badge-sm bg-gradient-secondary">Absent</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_anik > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $entry_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_anik > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $exit_time_format . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                          <td class="align-middle text-center">
                            <?php if ($num_anik > 0) {
                              echo '<span class="text-secondary text-xs font-weight-bold">' . $duration . '</span>';
                            } else {
                              echo '<span class="text-secondary text-xs font-weight-bold">--</span>';
                            }?>
                          </td>
                        </tr>
                      </tbody>
                    <?php 
                  }?>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="assets/js/plugins/chartjs.min.js"></script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
</body>

</html>