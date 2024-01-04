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
    <script src="adapter.min.js"></script>
    <script src="vue.min.js"></script>
    <script src="instascan.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="buttons.dataTables.min.css">
</head>
<body>
    <div class="container" style="margin-top: 30vh;">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <a href="attendance.php?batch=4" class="btn btn-primary">Employee</a>
            </div>
        </div>
        
        <div class="row mb-5">
            <div class="col-md-4 d-flex justify-content-center mb-5">
                <a href="attendance.php?batch=1" class="btn btn-primary">10:00 Tar Batch</a>
            </div>
            <div class="col-md-4 d-flex justify-content-center mb-5">
                <a href="attendance.php?batch=2" class="btn btn-primary">02:30 Tar Batch</a>
            </div>
            <div class="col-md-4 d-flex justify-content-center mb-5">
                <a href="attendance.php?batch=3" class="btn btn-primary">04:00 Tar Batch</a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 text-center">
                <a href="database.php" class="btn btn-primary">Attendance Database</a>
            </div>
        </div>
    </div>
</body>
</html>