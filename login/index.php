<?php include('../assets/includes/header.php'); ?>

<?php if ($login_validity == 1) {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>dashboard/';
    </script>
    <?php 
}?>

<!--=========== PART SUBJECT ===========-->
<section class="login_section hc_section">
    <div class="login_container hc_container ep_grid">
        <div class="login_content position_relative">
            <img src="../assets/img/human-brain.png" alt="">
        </div>

        <div class="login_data">
            <form action="<?= $base_url ?>get-otp/" method="post" class="single_col_form">

                <?php if (isset($_SESSION['msg'])) {
                    echo $_SESSION['msg'];
                    unset($_SESSION['msg']);
                }?>

                <div>
                    <label for="login-email">আপনার ইমেইল *</label>
                    <input type="text" id="login-email" name="email" placeholder="আপনার ইমেইল">
                </div>

                <button type="submit" name="get_otp" class="w_100 mt_75">ওটিপি কোড পাঠান</button>
            </form>
        </div>
    </div>
</section>

<?php include('../assets/includes/footer.php'); ?>