<?php include('db.php'); ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!--=============== GOOGLE FONT ===============-->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

	<!--=============== FAV ICON ===============-->
	<link rel="shortcut icon" href="../images/rubizco_fav.png">

	<!--=============== BOOTSTRAP CSS ===============-->
	<?php include('../css/bootstrap-css.php'); ?>

	<!--=============== JQUERY LIBRARY ===============-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

	<!--=============== STYLE CSS ===============-->
	<?php include('../css/style.php'); ?>

	<title>Common - Database</title>
</head>
<body>
   
<table class="rubizco_table" id="common-attend">
    <thead>
        <tr>
            <th>Roll</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>
    <?php if (isset($_GET['batch'])) {
        $batch = $_GET['batch'];
        
        if (isset($_GET['roll'])) {
            $roll = $_GET['roll'];
            
            if (isset($_POST['add_cmnt'])) {
                $roll = $_POST['roll'];
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $comment = $_POST['comment'];
                if (empty($comment)) {
                    echo 'add comment';
                } else {
                    $add = "INSERT INTO absent_comment (roll, name, phone, comment, insert_date) VALUES ('$roll', '$name', '$phone', '$comment', now())";
                    $sql = mysqli_query($conn, $add);
                    header("Location: comment_absent.php?batch=".$batch);
                }
            }
            
            $select_students = "SELECT * FROM list WHERE batch = '$batch' AND roll = '$roll'";
            $sql_students = mysqli_query($conn, $select_students);
            if ($row_students = mysqli_fetch_assoc($sql_students)) {
                $roll = $row_students['roll'];
                $name = $row_students['name'];
                $phone = $row_students['phone'];
                ?>
                <form action="" method="post">
                    <tr>
                        <td><input type="text" name="roll" value="<?php echo $roll; ?>" readonly=""></td>
                        <td><input type="text" name="name" value="<?php echo $name; ?>" readonly=""></td>
                        <td><input type="text" name="phone" value="<?php echo $phone; ?>" readonly=""></td>
                        <td>
                            <select name="comment">
                                <option value="">Choose comment</option>
                                <?php $select_cmnt = "SELECT * FROM pre_cmnts";
                                $sql_cmnt = mysqli_query($conn, $select_cmnt);
                                while ($row_cmnt = mysqli_fetch_assoc($sql_cmnt)) {
                                    $id = $row_cmnt['id'];
                                    $comment = $row_cmnt['comment'];
                                    ?>
                                    <option value="<?php echo $comment; ?>"><?php echo $comment; ?></option>
                                    <?php 
                                }?>
                            </select>
                            <button type="submit" name="add_cmnt" class="btn btn-success">Add</button>
                        </td>
                    </tr>
                </form>
            <?php 
            }
        }

        // select batch all students
        $select_students = "SELECT * FROM list WHERE batch = '$batch'";
        $sql_students = mysqli_query($conn, $select_students);
        while ($row_students = mysqli_fetch_assoc($sql_students)) {
            $roll = $row_students['roll'];
            $name = $row_students['name'];
            $phone = $row_students['phone'];
            ?>
            <form action="" method="post">
                <tr>
                    <?php // fetch classes
                    $select_list = "SELECT DATE(entry) as dates FROM attendance WHERE batch = '$batch' GROUP BY dates ORDER BY dates DESC LIMIT 3";
                    $list_sql = mysqli_query($conn, $select_list);
                    $count = 0;
                    while ($row_list = mysqli_fetch_assoc($list_sql)) {
                        $date = $row_list['dates'];
    
                        // fetch attendance
                        $select_attend = "SELECT * FROM attendance WHERE roll = '$roll' AND DATE(entry) = '$date'";
                        $attend_sql = mysqli_query($conn, $select_attend);
                        $num_attend = mysqli_num_rows($attend_sql);
                        if ($num_attend == 0) {
                            $status = '--';
                            $count++;
                        } elseif ($num_attend == 1) {
                            $status = 'P';
                        }
                        ?>
                        <?php 
                    }?>
                    
                    <?php if ($count == 3) {
                        ?>
                        <td><?php echo $roll; ?></td>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $phone; ?></td>
                        <td>
                            <!--<input type="text" name="roll" value="<?php //echo $roll; ?>" readonly="">-->
                            <!--<input type="text" name="name" value="<?php// echo $name; ?>" readonly="">-->
                            <!--<input type="text" name="phone" value="<?php// echo $phone; ?>" readonly="">-->
                            <a href="comment_absent.php?batch=<?php echo $batch; ?>&roll=<?php echo $roll; ?>">Edit</a>
                            <?php // echo $count; ?>
                        </td>
                        <?php 
                    }?>
                </tr>
            </form>
            <?php 
        }
    }?>
    </tbody>
</table>

</body>
</html>