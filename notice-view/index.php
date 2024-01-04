<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['notice'])) {
    $notice_id = $_GET['notice'];

    // fetch notice
    $select_notice  = "SELECT * FROM hc_notice WHERE id = '$notice_id' AND for_whom IN ($my_notice) AND status = 1 AND is_delete = 0 ORDER BY id DESC";
    $sql_notice     = mysqli_query($db, $select_notice);
    $num_notice     = mysqli_num_rows($sql_notice);
    if ($num_notice > 0) {
        $i = 0;
        while ($row_notice = mysqli_fetch_assoc($sql_notice)) {
            $notice_id              = $row_notice['id'];
            $notice_name            = $row_notice['name'];
            $notice_description     = $row_notice['description'];
            $notice_for_whom        = $row_notice['for_whom'];
            $notice_attachment      = $row_notice['attachment'];
            $notice_status          = $row_notice['status'];
            $notice_author          = $row_notice['author'];
            $notice_created_date    = $row_notice['created_date'];

            // joined date convert to text
            $notice_created_date_text = date('d M, Y | h:i:s a', strtotime($notice_created_date));
        }
    } else {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>notice/';
        </script>
        <?php 
    }?>
    <!--=========== COMMON SECTION ===========-->
    <section class="common_section hc_section">
        <div class="hc_container text_center">
            <h1 class="common_section_title"><?= $notice_name ?></h1>
        </div>
    </section>

    <!--=========== NOTICE ===========-->
    <section class="course_details_section hc_section">
        <div class="hc_container ep_grid">
            <!-- NOTICE DETAILS -->
            <div class="ep_grid">
                <h4><?= $notice_name ?></h4>
                
                <h6>প্রকাশের সময়: <?= $notice_created_date_text ?></h6>

                <p><?= $notice_description ?></p>
            </div>
        </div>
    </section>
    <?php 
} else {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>notice/';
    </script>
    <?php 
}?>

<?php include('../assets/includes/footer.php'); ?>