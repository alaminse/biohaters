<?php include('../assets/includes/header.php'); ?>

<?php // day add
$day_alert = '';
if (isset($_POST['add_day'])) {
    $day = mysqli_escape_string($db, $_POST['day']);

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($day)) {
        $day_alert = "<p class='warning mb_75'>Required Days.....</p>";
    } else {
        $add = "INSERT INTO hc_day (day_to_day, author, created_date) VALUES ('$day', '$admin_id', '$created_date')";
        $sql_add = mysqli_query($db, $add);
        if ($sql_add) {
            ?>
            <script type="text/javascript">
                window.location.href = '../day-time/';
            </script>
            <?php 
        }
    }
}

// day delete
if (isset($_POST['delete_day'])) {
    $delete_id      = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_day SET is_delete = 1 WHERE id = '$delete_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../day-time/';
        </script>
        <?php 
    }
}

// time add
$time_alert = '';
if (isset($_POST['add_time'])) {
    $time = mysqli_escape_string($db, $_POST['time']);

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($time)) {
        $time_alert = "<p class='warning mb_75'>Required Time.....</p>";
    } else {
        $add = "INSERT INTO hc_time (time_to_time, author, created_date) VALUES ('$time', '$admin_id', '$created_date')";
        $sql_add = mysqli_query($db, $add);
        if ($sql_add) {
            ?>
            <script type="text/javascript">
                window.location.href = '../day-time/';
            </script>
            <?php 
        }
    }
}

// time delete
if (isset($_POST['delete_time'])) {
    $delete_id      = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_time SET is_delete = 1 WHERE id = '$delete_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../day-time/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Day & Time Schedule</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container ep_grid grid_2">
            <!--========== Day Schedule ==========-->
            <div>
                <!--========== ADD Day ==========-->
                <div class="add_day">
                    <h5 class="box_title">Day Schedule</h5>

                    <?php echo $day_alert; ?>

                    <form action="" method="post" class="single_col_form">
                        <div>
                            <label for="product-cat-name">Days*</label>
                            <input type="text" id="product-cat-name" name="day" placeholder="Sunday, Tuesday, Thursday">
                        </div>

                        <button type="submit" name="add_day">Add Day</button>
                    </form>
                </div>

                <!--========== MANAGE Day ==========-->
                <div class="mng_category">
                    <div class="ep_flex mt_75 mb_75">
                        <h5 class="box_title">Manage Days</h5>
                    </div>

                    <table class="ep_table">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Days</th>
                                <th>Author</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select_day = "SELECT * FROM hc_day WHERE is_delete = 0 ORDER BY id DESC";
                            $sql_day = mysqli_query($db, $select_day);
                            $num_day = mysqli_num_rows($sql_day);
                            if ($num_day == 0) {
                                echo "<tr><td colspan='6' class='text_center'>There are no Day Schedule</td></tr>";
                            } else {
                                $si = 0;
                                while ($row_day = mysqli_fetch_assoc($sql_day)) {
                                    $day_id        = $row_day['id'];
                                    $day_name      = $row_day['day_to_day'];
                                    $day_author    = $row_day['author'];
                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?php echo $si; ?></td>
                                        
                                        <td><?php echo $day_name; ?></td>

                                        <td><?php $select_day_author = "SELECT * FROM admin WHERE id = '$day_author'";
                                        $sql_day_author = mysqli_query($db, $select_day_author);
                                        $num_day_author = mysqli_num_rows($sql_day_author);
                                        $row_day_author = mysqli_fetch_assoc($sql_day_author);
                                        echo $row_day_author['name'];?></td>

                                        <td>
                                            <div class="btn_grp">
                                                <!-- DELETE MODAL BUTTON -->
                                                <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#day-delete<?php echo $day_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                            </div>

                                            <!-- DELETE MODAL -->
                                            <div class="modal fade" id="day-delete<?php echo $day_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $day_name; ?></span>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="delete_id" id="" value="<?php echo $day_id; ?>">
                                                                <button type="submit" name="delete_day" class="button bg_danger text_danger text_semi">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                }
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!--========== TIME Schedule ==========-->
            <div>
                <!--========== ADD TIME ==========-->
                <div class="add_day">
                    <h5 class="box_title">Time Schedule</h5>

                    <?php echo $time_alert; ?>

                    <form action="" method="post" class="single_col_form">
                        <div>
                            <label for="product-cat-name">Time*</label>
                            <input type="text" id="product-cat-name" name="time" placeholder="4:00PM - 5:30PM">
                        </div>

                        <button type="submit" name="add_time">Add Time</button>
                    </form>
                </div>

                <!--========== MANAGE TIME ==========-->
                <div class="mng_category">
                    <div class="ep_flex mt_75 mb_75">
                        <h5 class="box_title">Manage Time</h5>
                    </div>

                    <table class="ep_table">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Time</th>
                                <th>Author</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select_time = "SELECT * FROM hc_time WHERE is_delete = 0 ORDER BY id DESC";
                            $sql_time = mysqli_query($db, $select_time);
                            $num_time = mysqli_num_rows($sql_time);
                            if ($num_time == 0) {
                                echo "<tr><td colspan='6' class='text_center'>There are no Time Schedule</td></tr>";
                            } else {
                                $si = 0;
                                while ($row_time = mysqli_fetch_assoc($sql_time)) {
                                    $time_id        = $row_time['id'];
                                    $time_name      = $row_time['time_to_time'];
                                    $time_author    = $row_time['author'];
                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?php echo $si; ?></td>
                                        
                                        <td><?php echo $time_name; ?></td>

                                        <td><?php $select_time_author = "SELECT * FROM admin WHERE id = '$time_author'";
                                        $sql_time_author = mysqli_query($db, $select_time_author);
                                        $num_time_author = mysqli_num_rows($sql_time_author);
                                        $row_time_author = mysqli_fetch_assoc($sql_time_author);
                                        echo $row_time_author['name'];?></td>

                                        <td>
                                            <div class="btn_grp">
                                                <!-- DELETE MODAL BUTTON -->
                                                <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#time-delete<?php echo $time_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                            </div>

                                            <!-- DELETE MODAL -->
                                            <div class="modal fade" id="time-delete<?php echo $time_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $time_name; ?></span>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="delete_id" id="" value="<?php echo $time_id; ?>">
                                                                <button type="submit" name="delete_time" class="button bg_danger text_danger text_semi">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                }
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>