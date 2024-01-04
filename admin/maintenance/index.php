<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role != 0) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<!-- FETCH MAINTENANCE MODE -->
<?php $select = "SELECT * FROM hc_maintenance WHERE id = 1";
$sql = mysqli_query($db, $select);
$row = mysqli_fetch_assoc($sql);
$id             = $row['id'];
$status         = $row['status'];
$last_update    = $row['last_update'];
$last_update = date('d M Y, H:i:s', strtotime($last_update)); ?>

<!-- CHANGE MAINTENANCE MODE -->
<?php if (isset($_POST['save'])) {
    if (isset($_POST['maintenance'])) {
        $maintenance = 1;
    } else {
        $maintenance = 0;
    }

    $update_date = date('Y-m-d H:i:s', time());

    if ($maintenance != $status) {
        $update = "UPDATE hc_maintenance SET status = $maintenance, last_update = '$update_date' WHERE id = 1";
        $sql_update = mysqli_query($db, $update);
        if ($sql_update) {
            ?>
            <script type="text/javascript">
                window.location.href = '../maintenance/';
            </script>
            <?php 
        }
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Maintenance</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <form action="" method="post" class="single_col_form mb_75">
                <div>
                    <label for="maintenance" class="checkbox_label">
                        <input type="checkbox" class="checkbox" name="maintenance" id="maintenance" <?php if ($status == 1) {echo "checked";}?>>
                        <span class="checked"></span>
                        <span class="text_semi">Maintenance <?php if ($status == 1) {echo "On";} elseif ($status == 0) {echo "Off";}?></span>
                    </label>
                </div>

                <button type="submit" name="save" class="btn_sm mt_75">Save</button>
            </form>

            <p class="mt_75 text_sm">Last Updated on: <span class="text_semi"><?php echo $last_update; ?></span></p>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>