<?php 
include('db.php');
session_start(); ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="buttons.dataTables.min.css">

	<title>Common - Database</title>
</head>
<body>
    
<?php 
    if (isset($_POST['add'])) {
        $comment = mysqli_escape_string($conn, $_POST['comment']);
        
        if (empty($comment)) {
            echo "Field Empty.....";
        } else {
            $add = "INSERT INTO pre_cmnts (comment) VALUES ('$comment')";
            $sql = mysqli_query($conn, $add);
        }
    }
?>
    
<form action="" method="post" class="d-flex my-3 container gap-3">
    <input type="text" name="comment" class="form-control" placeholder="Write Comment">
    <button type="submit" name="add" class="btn btn-success">Add Comment</button>
</form>
   
<table class="my-4 container" id="common-attend">
    <thead>
        <tr>
            <th>SI</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>
    <?php // select all comments
    $select = "SELECT * FROM pre_cmnts ORDER BY id DESC";
    $sql = mysqli_query($conn, $select);
    $si = 0;
    while ($row = mysqli_fetch_assoc($sql)) {
        $id = $row['id'];
        $comment = $row['comment'];
        $si++;
        ?>
        <tr>
            <td><?php echo $si; ?></td>
            <td><?php echo $comment; ?></td>
        </tr>
        <?php 
    }?>
    </tbody>
</table>

</body>
</html>