<?php include('../assets/includes/header.php'); ?>

<!--=========== COMMON SECTION ===========-->
<section class="common_section hc_section">
    <div class="hc_container text_center">
        <h1 class="common_section_title">Secret Files: War Edition</h1>
    </div>
</section>

<!--=========== LIKED CHAPTER PURCHASE FORM ===========-->
<form action="<?= $base_url ?>entry-secret-files/" method="post">
    <!--=========== LIKED CHAPTER ===========-->
    <section class="liked_chapter_section hc_section">
        <div class="liked_chapter_container hc_container ep_grid">
            <!-- LIKED CHAPTER BOX -->
            <div class="liked_chapter_box">
                <img src="../assets/img/secret.png" alt="">
            </div>

            <!-- CHECKOUT CARD -->
            <div class="single_chapter_card">
                <h4 class="single_chapter_card_title">Secret Files: War Edition</h4>

                <div class="single_col_form">
                    <?php if ($login_validity == 1) {
                        ?>
                        <div class="ep_flex">
                            <label for="">নাম</label>
                            <p><?= $student_name ?></p>
                        </div>

                        <div class="ep_flex">
                            <label for="">ইমেইল</label>
                            <p><?= $student_email ?></p>
                        </div>

                        <div class="ep_flex">
                            <label for="">ফোন নম্বর</label>
                            <p><?= $student_phone ?></p>
                        </div>
                        
                        <input type="hidden" id="" name="student_name" value="<?= $student_name ?>">
                        <input type="hidden" id="" name="student_email" value="<?= $student_email ?>">
                        <input type="hidden" id="" name="student_phone" value="<?= $student_phone ?>">
                        <?php 
                    } else {
                        ?>
                        <div>
                            <label for="checkout-name">আপনার নাম *</label>
                            <input type="text" id="checkout-name" name="name" placeholder="আপনার নাম" required>
                        </div>

                        <div>
                            <label for="checkout-email">আপনার ইমেইল *</label>
                            <input type="text" id="checkout-email" name="email" placeholder="আপনার ইমেইল" required>
                        </div>

                        <div>
                            <label for="checkout-phone">আপনার ফোন নম্বর *</label>
                            <input type="text" id="checkout-phone" name="phone" minlength="11" maxlength="11" placeholder="আপনার ফোন নম্বর" required>
                        </div>
                        <?php 
                    }?>

                    <div>
                        <label for="checkout-name">আপনার সিক্রেট ফাইলে থাকা গোপন কোড*</label>
                        <input type="text" id="checkout-name" name="secret_code" placeholder="আপনার সিক্রেট ফাইলে থাকা গোপন কোড" required>
                    </div>

                    <button type="submit" name="checkout" class="w_100 mt_75">Entry Now</button>
                </div>
                
                <div class="hc_alert hc_alert_danger mt_1_5">
                    <h6 class="hc_alert_message">
                        যারা সঠিকভাবে সিক্রেট ফাইলস এন্ট্রি করেছেন। তারা এখানে লগিন করুন || 
                        <a href="http://localhost/biohaters/login/">Login Here</a>
                    </h6>
                </div>
            </div>
        </div>
    </section>
</form>

<?php include('../assets/includes/footer.php'); ?>