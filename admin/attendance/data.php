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
    <div class="container" style="margin-top: 20vh;">
        <div class="row">
            <div class="col-md-6">
                <?php if (isset($_SESSION['error'])) {
                    echo "<div class='alert alert-danger' style='background: red; color: white;'><h4>Error!</h4>".$_SESSION['error']."</div>";
                    unset($_SESSION['error']);
                }

                if (isset($_SESSION['success'])) {
                    echo "<div class='alert alert-success' style='background: green; color: white;'><h4>Success</h4>".$_SESSION['success']."</div>";
                    unset($_SESSION['success']);
                }?>
            </div>
            <div class="col-md-6">
                <form action="insert.php" method="post" class="form-horizontal">
                    <label>Roll Number</label>
                    <input type="text" class="form-control" id="roll" name="roll" readonly="" placeholder="BH Roll Number">
                </form>
                <table class="table display nowrap" id="data">
                    <thead>
                        <td>SI</td>
                        <td>Roll</td>
                        <td>Entry Time</td>
                    </thead>
                    <tbody>
                        <?php $select_table = "SELECT * FROM attendance WHERE DATE(entry) = CURDATE()";
                        $table_sql = mysqli_query($conn, $select_table);
                        $num = mysqli_num_rows($table_sql);
                        $si = 0;
                        if ($num > 0) {
                            while ($row = mysqli_fetch_assoc($table_sql)) {
                                $roll = $row['roll'];
                                $entry = $row['entry'];
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
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
        $(document).ready( function () {
            $('#data').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            } );
        } );
    </script>
</body>
</html>