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
  <!--=========== BOOTSTRAP CSS ===========-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <!--=========== JQUERY ===========-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!--=========== BOOTSTRAP JS ===========-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <!--=========== DATATABLE ===========-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="buttons.dataTables.min.css">
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.0.7" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
</head>
<table class="table align-items-center mb-0" id="datatable">
  <thead>
    <tr>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sufi Ahmed</th>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Muhib Rahman</th>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mim Onti</th>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Emdadul Haque</th>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mehedi Hasan</th>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Md. Al Amin</th>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shohag</th>
    </tr>
  </thead>
  <tbody>
    <?php if (isset($_GET['month'])) {
        $month = $_GET['month'];
    }
    $select = "SELECT *, DATE(entry_time) as entry_date FROM attendance WHERE MONTHNAME(entry_time) = '$month' GROUP BY DATE(entry_time)";
    $sql = mysqli_query($db, $select);
    $num = mysqli_num_rows($sql);
    if ($num > 0) {
        while($row = mysqli_fetch_assoc($sql)) {
            $entry_date = $row['entry_date'];
            
            // fetch attend data
            $select_attend_sufi = "SELECT * FROM attendance WHERE phone = '01680920753' AND DATE(entry_time) = '$entry_date'";
            $sql_attend_sufi = mysqli_query($db, $select_attend_sufi);
            $num_attend_sufi = mysqli_num_rows($sql_attend_sufi);
            if ($num_attend_sufi > 0) {
                $row_attend_sufi = mysqli_fetch_assoc($sql_attend_sufi);
                $attend_sufi = date("j M Y g:i A",  strtotime($row_attend_sufi['entry_time']));
            } else {
                $attend_sufi = '--';
            }
            
            $select_attend_muhib = "SELECT * FROM attendance WHERE phone = '01796086202' AND DATE(entry_time) = '$entry_date'";
            $sql_attend_muhib = mysqli_query($db, $select_attend_muhib);
            $num_attend_muhib = mysqli_num_rows($sql_attend_muhib);
            if ($num_attend_muhib > 0) {
                $row_attend_muhib = mysqli_fetch_assoc($sql_attend_muhib);
                $attend_muhib = date("j M Y g:i A",  strtotime($row_attend_muhib['entry_time']));
            } else {
                $attend_muhib = '--';
            }
            
            $select_attend_mim = "SELECT * FROM attendance WHERE phone = '01616752861' AND DATE(entry_time) = '$entry_date'";
            $sql_attend_mim = mysqli_query($db, $select_attend_mim);
            $num_attend_mim = mysqli_num_rows($sql_attend_mim);
            if ($num_attend_mim > 0) {
                $row_attend_mim = mysqli_fetch_assoc($sql_attend_mim);
                $attend_mim = date("j M Y g:i A",  strtotime($row_attend_mim['entry_time']));
            } else {
                $attend_mim = '--';
            }
            
            $select_attend_anik = "SELECT * FROM attendance WHERE phone = '01952756497' AND DATE(entry_time) = '$entry_date'";
            $sql_attend_anik = mysqli_query($db, $select_attend_anik);
            $num_attend_anik = mysqli_num_rows($sql_attend_anik);
            if ($num_attend_anik > 0) {
                $row_attend_anik = mysqli_fetch_assoc($sql_attend_anik);
                $attend_anik = date("j M Y g:i A",  strtotime($row_attend_anik['entry_time']));
            } else {
                $attend_anik = '--';
            }
            
            $select_attend_mehedi = "SELECT * FROM attendance WHERE phone = '01616720009' AND DATE(entry_time) = '$entry_date'";
            $sql_attend_mehedi = mysqli_query($db, $select_attend_mehedi);
            $num_attend_mehedi = mysqli_num_rows($sql_attend_mehedi);
            if ($num_attend_mehedi > 0) {
                $row_attend_mehedi = mysqli_fetch_assoc($sql_attend_mehedi);
                $attend_mehedi = date("j M Y g:i A",  strtotime($row_attend_mehedi['entry_time']));
            } else {
                $attend_mehedi = '--';
            }
            // Al Amin
            $select_attend_alamin = "SELECT * FROM attendance WHERE phone = '01518448104' AND DATE(entry_time) = '$entry_date'";
            $sql_attend_alamin = mysqli_query($db, $select_attend_alamin);
            $num_attend_alamin = mysqli_num_rows($sql_attend_alamin);
            if ($num_attend_alamin > 0) {
                $row_attend_alamin = mysqli_fetch_assoc($sql_attend_alamin);
                $attend_alamin = date("j M Y g:i A",  strtotime($row_attend_alamin['entry_time']));
            } else {
                $attend_alamin = '--';
            }
            
            $select_attend_shohag = "SELECT * FROM attendance WHERE phone = '01409750320' AND DATE(entry_time) = '$entry_date'";
            $sql_attend_shohag = mysqli_query($db, $select_attend_shohag);
            $num_attend_shohag = mysqli_num_rows($sql_attend_shohag);
            if ($num_attend_shohag > 0) {
                $row_attend_shohag = mysqli_fetch_assoc($sql_attend_shohag);
                $attend_shohag = date("j M Y g:i A",  strtotime($row_attend_shohag['entry_time']));
            } else {
                $attend_shohag = '--';
            }?>
            <tr>
                <td><?= $entry_date ?></td>
                <td><?= $attend_sufi ?></td>
                <td><?= $attend_muhib ?></td>
                <td><?= $attend_mim ?></td>
                <td><?= $attend_anik ?></td>
                <td><?= $attend_mehedi ?></td>
                <td><?= $attend_alamin ?></td>
                <td><?= $attend_shohag ?></td>
            </tr>
            <?php 
        }
    }?>
  </tbody>
</table>

<!--=========== DATATABLE ===========-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>

<script>
/*========= DATATABLE CUSTOM =========*/
$(document).ready( function () {
    $('#datatable').DataTable( {
        dom: 'Bfrtip',
        // order: [[0, 'desc']],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>