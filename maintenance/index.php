<?php include('../assets/includes/header.php'); ?>

<?php if ($maintenance_mode == 0) {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>';
    </script>
    <?php 
}?>

<!--=========== PART SUBJECT ===========-->
<section class="login_section hc_section">
    <div class="login_container hc_container ep_grid">
        <div class="login_content position_relative">
            <img src="../assets/img/human-brain.png" alt="">
        </div>

        <div class="maitenance_data text_center">
            <h1>Maitenance Mode</h1>
            <p>Please wait and hold patience. This site will be available after a few time.</p>
        </div>
    </div>
</section>

<?php include('../assets/includes/footer.php'); ?>