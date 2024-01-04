<?php include('../assets/includes/header.php'); ?>

<!-- check SESSION cart -->
<?php if (empty($_SESSION['cart'])) {
    ?>
    <script type="text/javascript">
        window.location.href = '<?= $base_url ?>chapter/';
    </script>
    <?php 
}?>

<!--=========== COMMON SECTION ===========-->
<section class="common_section hc_section">
    <div class="hc_container text_center">
        <h1 class="common_section_title">পছন্দের অধ্যায় সমূহ</h1>
    </div>
</section>

<!--=========== LIKED CHAPTER PURCHASE FORM ===========-->
<form action="<?= $base_url ?>purchase/" method="post">
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
                                <th>চ্যাপ্টার নাম</th>
                                <th>ক্লাস</th>
                                <th>সময়</th>
                                <th>চ্যাপ্টার ফী</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $total_price = 0;
                            $total_discount = 0;
                            $si = 0;
                            foreach ($_SESSION['cart'] as $key => $cart_chapter) {
                                $si++;

                                // chapter id
                                $chapter_id = $cart_chapter['id'];

                                // fetch chapter
                                $select_chapter = "SELECT * FROM hc_chapter WHERE id = '$chapter_id'";
                                $sql_chapter    = mysqli_query($db, $select_chapter);
                                $row_chapter    = mysqli_fetch_assoc($sql_chapter);
                                $chapter_id             = $row_chapter['id'];
                                $chapter_name           = $row_chapter['chapter'];
                                $chapter_subject        = $row_chapter['subject'];
                                $chapter_price          = $row_chapter['price'];
                                $chapter_sale           = $row_chapter['sale_price'];
                                $chapter_cover_photo    = $row_chapter['cover_photo'];
                                $chapter_status         = $row_chapter['status'];
                                $chapter_author         = $row_chapter['author'];
                                $chapter_created_date   = $row_chapter['created_date'];

                                // exact price
                                if ($chapter_sale > 0) {
                                    $exact_price = $chapter_sale;
                                } else {
                                    $exact_price = $chapter_price;
                                }
                                
                                // $discount = floor($exact_price * 0.2);
            
                                $discount = 0;
                                
                                $subtotal = $exact_price - $discount;

                                $total_price += $exact_price;
                                
                                $total_discount += $discount;

                                // connected with chapter
                                $select_chapter_lecture = "SELECT COUNT(id) as chapter_lecture, SUM(duration) as chapter_duration FROM hc_chapter_lecture WHERE chapter = '$chapter_id' AND is_delete = 0";
                                $sql_chapter_lecture = mysqli_query($db, $select_chapter_lecture);
                                $row_chapter_lecture = mysqli_fetch_assoc($sql_chapter_lecture);
                                $chapter_lecture = $row_chapter_lecture['chapter_lecture'];
                                $chapter_duration = $row_chapter_lecture['chapter_duration'];
                                ?>
                                <!-- CHAPTER ROW -->
                                <tr>
                                    <td>
                                        <?= $si ?>
                                        <input type="hidden" name="item_id[]" id="" value="<?= $chapter_id ?>">
                                        <input type="hidden" name="price[]" id="" value="<?= $subtotal ?>">
                                    </td>
                                    <td>
                                        <?= $chapter_name ?>
                                        <?php if ($chapter_subject == 1) {
                                            echo ' <span class="success">উদ্ভিদবিজ্ঞান</span>';
                                        } elseif ($chapter_subject == 2) {
                                            echo ' <span class="danger">প্রাণিবিজ্ঞান</span>';
                                        }?>
                                    </td>
                                    <td><?= $chapter_lecture ?>টি</td>
                                    <td><?php if ($chapter_lecture > 0) {
                                        echo gmdate('H:i:s', $chapter_duration)." Hours";
                                    }?></td>
                                    <td><?php if ($chapter_sale > 0) {
                                        ?>
                                        <span class="text_strike text_light text_sm">৳<?= $chapter_price ?></span>
                                        <span class="text_lg">৳<?= $chapter_sale ?></span>
                                        <?php 
                                    } else {
                                        ?>
                                        <span class="text_lg">৳<?= $chapter_price ?></span>
                                        <?php 
                                    }?></td>
                                </tr>
                                <?php 
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- CHECKOUT CARD -->
            <?php // bkash charge
            $bkash_charge = floor($total_price * 0); // 0.015
            
            $final_subtotal = $total_price - $total_discount;

            // grant total
            $grant_total = $total_price + $bkash_charge - $total_discount;?>
            <div class="single_chapter_card">
                <h4 class="single_chapter_card_title">পেমেন্ট ফর্ম</h4>

                <div class="payment_details">
                    <div class="ep_flex">
                        <p class="text_semi">চ্যাপ্টার ফীঃ </p>
                        <p>৳<?= $total_price ?></p>
                    </div>

                    <div class="ep_flex">
                        <p class="text_semi">বিকাশ চার্জঃ </p>
                        <p>৳<?= $bkash_charge ?></p>
                    </div>
                    
                    <!--<div class="ep_flex payment_details_discount">-->
                    <!--    <p class="text_semi">Festival Disount (20%): </p>-->
                    <!--    <p>- ৳<?= $total_discount ?></p>-->
                    <!--</div>-->

                    <div class="ep_flex grant_total">
                        <p class="text_semi">মোট ফীঃ </p>
                        <p>৳<?= $grant_total ?></p>
                    </div>
                </div>

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
                            <input type="text" id="checkout-name" name="name" placeholder="আপনার নাম">
                        </div>

                        <div>
                            <label for="checkout-email">আপনার ইমেইল *</label>
                            <input type="text" id="checkout-email" name="email" placeholder="আপনার ইমেইল">
                        </div>

                        <div>
                            <label for="checkout-phone">আপনার ফোন নম্বর *</label>
                            <input type="text" id="checkout-phone" name="phone" minlength="11" maxlength="11" placeholder="আপনার ফোন নম্বর">
                        </div>
                        <?php 
                    }?>

                    <!-- TRANSACTION DETAILS INPUT -->
                    <input type="hidden" name="purchase_item" id="" value="2">
                    <input type="hidden" name="subtotal" value="<?= $final_subtotal ?>">
                    <input type="hidden" name="bkash_charge" value="<?= $bkash_charge ?>">
                    <input type="hidden" name="grant_total" value="<?= $grant_total ?>">

                    <button type="submit" name="checkout" class="w_100 mt_75">পেমেন্ট করুন</button>
                </div>
            </div>
        </div>
    </section>
</form>

<?php include('../assets/includes/footer.php'); ?>