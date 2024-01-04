<?php include('db.php');
session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="adapter.min.js"></script>
    <script src="vue.min.js"></script>
    <script src="instascan.min.js"></script>
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
        <div class="container" style="margin-top: 5vh;">
            <h1 class="text-center mb-5"><?php if ($batch == 1) {
                echo "10:00 - 11:30 AM";
            } elseif ($batch == 2) {
                echo "02:30 - 04:00 AM";
            } elseif ($batch == 3) {
                echo "04:00 - 05:30 AM";
            }?></h1>
            <div class="row">
                <div class="col-md-4">
                    <video id="preview" width="100%" style="border-radius: 1.25rem;"></video>
                    <?php if (isset($_SESSION['error'])) {
                        echo "<div class='alert alert-danger' style='background: red; color: white;'><h4>Error!</h4>".$_SESSION['error']."</div>";
                        $message = $_SESSION['error'];
                        $message = htmlspecialchars($message);
                        $message = rawurlencode($message);
                        $voice_generate = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q='.$message.'&tl=en-IN');
                        echo "<audio controls='controls' autoplay style='opacity: 0;'><source src='data:audio/mpeg;base64,".base64_encode($voice_generate)."'></audio>";
                        unset($_SESSION['error']);
                    }

                    if (isset($_SESSION['success'])) {
                        echo "<div class='alert alert-success' style='background: green; color: white;'><h4>Success</h4>".$_SESSION['success']."</div>";
                        $message = $_SESSION['success'];
                        $message = htmlspecialchars($message);
                        $message = rawurlencode($message);
                        $voice_generate = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q='.$message.'&tl=en-IN');
                        echo "<audio controls='controls' autoplay style='opacity: 0;'><source src='data:audio/mpeg;base64,".base64_encode($voice_generate)."'></audio>";
                        unset($_SESSION['success']);
                        unset($_SESSION['voice']);
                    }?>
                </div>
                <div class="col-md-8">
                    <form action="insert.php" method="post" class="form-horizontal">
                        <label>Roll Number</label>
                        <input type="text" class="form-control mb-3" id="roll" name="roll" readonly="" placeholder="BH Roll Number">
                        <input type="text" class="form-control mb-4" id="batch" name="batch" readonly="" value="<?php echo $batch; ?>">
                    </form>
                    <div class="d-flex justify-content-end mb-3 mt-3">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add">
                            Add Manual Attendance
                        </button>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Manually Add</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="manual.php" method="post">
                                            <div class="mb-3">
                                                <label class="form-label">Roll Number</label>
                                                <input type="number" class="form-control" name="manual_roll" placeholder="Roll NO.">
                                                <input type="hidden" class="form-control" name="manual_batch" value="<?php echo $batch; ?>">
                                            </div>
                                            <button type="submit" name="manual_add" class="btn btn-primary">Add</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table display nowrap" id="data">
                        <thead>
                            <td>SI</td>
                            <td>Name</td>
                            <td>Roll</td>
                            <td>Entry Time</td>
                        </thead>
                        <tbody>
                            <?php $select_table = "SELECT * FROM attendance WHERE batch = '$batch' AND DATE(entry) = '$date' ORDER BY id DESC";
                            $table_sql = mysqli_query($conn, $select_table);
                            $num = mysqli_num_rows($table_sql);
                            $si = 0;
                            if ($num > 0) {
                                while ($row = mysqli_fetch_assoc($table_sql)) {
                                    $roll = $row['roll'];
                                    $name = $row['name'];
                                    $entry = $row['entry'];
                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?php echo $si; ?></td>
                                        <td><?php echo $name; ?></td>
                                        <td><?php echo $roll; ?></td>
                                        <td><?php echo $entry; ?></td>
                                    </tr>
                                <?php
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
            let scanner = new Instascan.Scanner({ video: document.getElementById('preview')});
            Instascan.Camera.getCameras().then(function(cameras){
                if(cameras.length > 0){
                    scanner.start(cameras[0]);
                } else{
                    alert('No cameras found');
                }
            }).catch(function(e) {
                console.log(e);
            });

            scanner.addListener('scan',function(c){
                document.getElementById('roll').value=c;
                document.forms[0].submit();
            });

            $(document).ready( function () {
                $('#data').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                } );
            } );
        </script>
        <?php 
    }
?>
</body>
</html>