<?php include('../assets/includes/header.php'); ?>

<!--=========== COMMON SECTION ===========-->
<section class="common_section hc_section">
    <div class="hc_container text_center">
        <h1 class="common_section_title">৩০ দিনের ফ্রি ট্রায়াল</h1>
    </div>
</section>

<!--=========== LIKED CHAPTER PURCHASE FORM ===========-->
<form action="<?= $base_url ?>get-trial/" method="post">
    <!--=========== LIKED CHAPTER ===========-->
    <section class="liked_chapter_section hc_section">
        <div class="liked_chapter_container hc_container ep_grid">
            <!-- LIKED CHAPTER BOX -->
            <div class="liked_chapter_box">
                <div>
                    <table class="hc_table">
                        <thead>
                            <tr>
                                <th>নং</th>
                                <th>লেকচার নাম</th>
                                <th>সময়</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $total_price = 0;
                            $si = 0;
                            // fetch free lecture with chapter
                            $select_chapter_lecture = "SELECT name, duration FROM hc_chapter_lecture WHERE is_free = '1' AND is_delete = 0";
                            $sql_chapter_lecture = mysqli_query($db, $select_chapter_lecture);
                            $num_chapter_lecture = mysqli_num_rows($sql_chapter_lecture);
                            while ($row_chapter_lecture = mysqli_fetch_assoc($sql_chapter_lecture)) {
                                $si++;
                                $lecture_name = $row_chapter_lecture['name'];
                                $lecture_duration = $row_chapter_lecture['duration'];
                                ?>
                                <tr>
                                    <td>
                                        <?= $si ?>
                                    </td>
                                    <td>
                                        <?= $lecture_name ?>
                                    </td>
                                    <td><?php echo gmdate('H:i:s', $lecture_duration)." Hours"; ?></td>
                                </tr>
                                <?php 
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- CHECKOUT CARD -->
            <?php // bkash charge
            $bkash_charge = floor($total_price * 0.015);

            // grant total
            $grant_total = $total_price + $bkash_charge;?>
            <div class="single_chapter_card">
                <h4 class="single_chapter_card_title">৩০ দিনের ফ্রি ট্রায়াল</h4>

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

                    <button type="submit" name="checkout" class="w_100 mt_75">৩০ দিনের ফ্রি ট্রায়াল নিন</button>
                </div>
            </div>
        </div>
    </section>
</form>

<?php include('../assets/includes/footer.php'); ?>