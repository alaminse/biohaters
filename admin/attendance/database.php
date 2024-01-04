<?php 
include('db.php');
session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="buttons.dataTables.min.css">
</head>
<body>
<?php 
    if (isset($_GET['batch'])) {
        $batch = $_GET['batch'];
        
        date_default_timezone_set('Asia/Dhaka');
        $date = date('Y-m-d', time());
        ?>
        <a href="comment_absent.php?batch=<?php echo $batch; ?>" class="mx-5">Comment Absent</a>
        <a href="add_cmnt.php" class="mx-5">Pre Comment</a>
        <div class="container" style="margin-top: 5vh;">
            <h1 class="text-center mb-5"><?php if ($batch == 1) {
                echo "10:00 - 11:30 AM";
            } elseif ($batch == 2) {
                echo "02:30 - 04:00 AM";
            } elseif ($batch == 3) {
                echo "04:00 - 05:30 AM";
            }?></h1>

            <div class="row">
                <table class="table table-hover">
                    <thead>
                        <th>SI</th>
                        <th>Date</th>
                        <th>Total Attend</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php $select_list = "SELECT DATE(entry) as dates, COUNT(id) as total FROM attendance WHERE batch = '$batch' GROUP BY dates";
                        $list_sql = mysqli_query($conn, $select_list);
                        $sl = 0;
                        while ($row_list = mysqli_fetch_assoc($list_sql)) {
                            $date = $row_list['dates'];
                            $total = $row_list['total'];
                            $sl++;
                            ?>
                            <tr>
                                <td><?php echo $sl; ?></td>
                                <td><?php echo $date; ?></td>
                                <td><?php echo $total; ?></td>
                                <td><a href="database.php?batch=<?php echo $batch; ?>&date=<?php echo $date; ?>">View</a></td>
                            </tr>
                            <?php 
                        }?>
                    </tbody>
                </table>
            </div>
            
            <?php if (isset($_GET['date'])) {
                $date = $_GET['date'];
                ?>
                <div class="row">
                    <div class="col-12 mb-5">
                        <table class="table display nowrap" id="data">
                            <thead>
                                <td>SI</td>
                                <td>Name</td>
                                <td>Roll</td>
                                <td>Phone</td>
                                <td>Entry Time</td>
                            </thead>
                            <tbody>
                                <?php $select_table = "SELECT * FROM attendance WHERE batch = '$batch' AND DATE(entry) = '$date'";
                                $table_sql = mysqli_query($conn, $select_table);
                                $num = mysqli_num_rows($table_sql);
                                $si = 0;
                                if ($num > 0) {
                                    while ($row = mysqli_fetch_assoc($table_sql)) {
                                        $roll = $row['roll'];
                                        $name = $row['name'];
                                        $phone = $row['phone'];
                                        $entry = $row['entry'];
                                        $si++;
                                        ?>
                                        <tr>
                                            <td><?php echo $si; ?></td>
                                            <td><?php echo $name; ?></td>
                                            <td><?php echo $roll; ?></td>
                                            <td><?php echo $phone; ?></td>
                                            <td><?php echo $entry; ?></td>
                                        </tr>
                                    <?php
                                    }
                                } ?>
                            </tbody>
                        </table>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Send Absent SMS
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure to send sms to absent student's gurdian.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <a href="absent_sms.php?batch=<?php echo $batch; ?>&date=<?php echo $date; ?>" class="btn btn-primary">Send Absent SMS</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="database.php?batch=<?php echo $batch; ?>&date=<?php echo $date; ?>&absent" class="btn btn-primary mb-5">
                        Absent List
                        </a>
                        
                        <?php if (isset($_GET['absent'])) {
                            ?>
                            <table class="table display nowrap" id="absent">
                                <thead>
                                    <td>Name</td>
                                    <td>Roll</td>
                                    <td>Phone</td>
                                </thead>
                                <tbody>
                                    <?php $present_array = '';
                                    $select_present = "SELECT * FROM attendance WHERE batch = '$batch' AND DATE(entry) = '$date'";
                                    $sql_present = mysqli_query($conn, $select_present);
                                    $num_present = mysqli_num_rows($sql_present);
                                    while ($row_present = mysqli_fetch_assoc($sql_present)) {
                                        $bh_reg_present = $row_present['roll'];

                                        $present_array = $bh_reg_present.','.$present_array;
                                    }
                                    $present_array = substr($present_array, 0, -1);
                                    $select_absents = "SELECT * FROM list WHERE batch = '$batch' AND roll NOT IN ($present_array)";
                                    $sql_absents = mysqli_query($conn, $select_absents);
                                    $num_absents = mysqli_num_rows($sql_absents);
                                    if ($num_absents > 0) {
                                        while ($row_absents = mysqli_fetch_assoc($sql_absents)) {
                                            $bh_reg_absents = $row_absents['roll'];
                                            $phone_absents = $row_absents['phone'];
                                            $gen_absents = $row_absents['gen'];
                                            $name_absents = $row_absents['name'];
                                            ?>
                                            <tr>
                                                <td><?php echo $name_absents; ?></td>
                                                <td><?php echo $bh_reg_absents; ?></td>
                                                <td><?php echo $phone_absents; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }?>
                                </tbody>
                            </table>
                            <?php 
                        }?>
                    </div>
                </div>
                <?php 
            }?>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
        <script>
            $(document).ready( function () {
                $('#data').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                } );
            } );

            $(document).ready( function () {
                $('#absent').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                } );
            } );
        </script>
        <?php 
    } else {
        ?>
        <div class="container" style="margin-top: 30vh;">
            <div class="row">
                <div class="col-md-4 d-flex justify-content-center">
                    <a href="database.php?batch=1" class="btn btn-primary">10:00 Tar Batch</a>
                </div>
                <div class="col-md-4 d-flex justify-content-center">
                    <a href="database.php?batch=2" class="btn btn-primary">02:30 Tar Batch</a>
                </div>
                <div class="col-md-4 d-flex justify-content-center">
                    <a href="database.php?batch=3" class="btn btn-primary">04:00 Tar Batch</a>
                </div>
            </div>
        </div>
        <?php 
    }
?>
</body>
</html>