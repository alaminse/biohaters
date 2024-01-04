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

// include database
include('../db/db.php');

$base_url = 'https://biohaters.com/';

// session start
session_start();

// checking cookie & redirect to valid folder
if (empty($_COOKIE['admin_id'])) {
    ?>
    <script type="text/javascript">
        window.location.href = '../';
    </script>
    <?php 
}

// Set the character set for the output
header('Content-Type: text/html; charset=utf-8');

// set local time zone
date_default_timezone_set('Asia/Dhaka');

// include common variable
include('../assets/includes/variable.php');

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

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<body>
    <div class="container">
        <h1 class="mt-5 text-center">Biology Haters</h1>
        <h5 class="mt-3 text-center">Attendance System</h5>
        
        <?php // select course group
        $select = "SELECT * FROM hc_course_batch GROUP BY course ORDER BY course DESC";
        $sql = mysqli_query($db, $select);
        $num = mysqli_num_rows($sql);
        if ($num > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $course = $row['course'];
                
                // select course name
                $select_course = "SELECT * FROM hc_course WHERE id = '$course'";
                $sql_course = mysqli_query($db, $select_course);
                $num_course = mysqli_num_rows($sql_course);
                if ($num_course > 0) {
                    while ($row_course = mysqli_fetch_assoc($sql_course)) {
                        $course_name = $row_course['name'];
                    }
                }?>
                <!-- List of Courses -->
                <div class="mt-5">
                    <h4><?= $course_name ?></h4>
                    <div class="row">
                        <?php // select batch
                        $select_batch = "SELECT * FROM hc_course_batch WHERE course = '$course' AND is_delete = 0";
                        $sql_batch = mysqli_query($db, $select_batch);
                        $num_batch = mysqli_num_rows($sql_batch);
                        if ($num_batch > 0) {
                            while ($row_batch = mysqli_fetch_assoc($sql_batch)) {
                                $batch_id = $row_batch['id'];
                                $batch_name = $row_batch['name'];
                                $batch_time = $row_batch['start_time'];
                                
                                $batch_time = date('h:i a', strtotime($batch_time));
                                ?>
                                <div class="col">
                                    <a href="attendance.php?batch=<?= $batch_id ?>" class="btn btn-primary"><?= $batch_name . ' - ' . $batch_time ?></a>
                                </div>
                                <?php 
                            }
                        }?>
                    </div>
                </div>
                <?php 
            }
        }?>
    </div>
</body>
</html>