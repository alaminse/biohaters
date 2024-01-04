<?php include('../assets/includes/header.php'); ?>

<!--=========== COMMON SECTION ===========-->
<section class="common_section hc_section">
    <div class="hc_container text_center">
        <h1 class="common_section_title">পছন্দের অধ্যায় সমূহ</h1>
    </div>
</section>

<!-- add to cart -->
<?php if (isset($_POST['add_to_cart'])) {   
    if (empty($_POST['chapter_id'])) {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>chapter/';
        </script>
        <?php 
    } else {
        // selected chapter ID in array
        foreach ($_POST['chapter_id'] as $chapter_id) {
            // fetch chapter
            $select_chapter = "SELECT * FROM hc_chapter WHERE id = '$chapter_id'";
            $sql_chapter    = mysqli_query($db, $select_chapter);
            $row_chapter    = mysqli_fetch_assoc($sql_chapter);

            $chapter_id     = $row_chapter['id'];
            $chapter_price  = $row_chapter['price'];
            $chapter_sale   = $row_chapter['sale_price'];

            // set exact price
            if ($chapter_sale > 0) {
                $exact_price = $chapter_sale;
            } else {
                $exact_price = $chapter_price;
            }

            // set array
            if (isset($_SESSION['cart'])) {
                $session_array_id = array_column($_SESSION['cart'], 'id');
                $cart_count = count($_SESSION['cart']);
        
                $_SESSION['cart'][$cart_count] = array(
                    'id' => $chapter_id,
                    'price' => $exact_price,
                );
            } else {
                // set array
                $_SESSION['cart'][0] = array(
                    'id' => $chapter_id,
                    'price' => $exact_price,
                );
            }
        }?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>selected-chapter/';
        </script>
        <?php 
    }
} else {
    unset($_SESSION['cart']);
}?>

<!--=========== LIKED CHAPTER PURCHASE FORM ===========-->
<form action="" method="post">
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
                            <?php foreach ($result['all_chapter'] as $key => $all_chapter) {
                                // chapter id
                                $chapter_id = $all_chapter['id'];

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
                                        <input type="checkbox" name="chapter_id[]" id="" value="<?= $chapter_id ?>">
                                    </td>
                                    <td>
                                        <div class="ep_flex ep_start">
                                            <a class="button btn_trp btn_sm no_hover" data-modal-target="#chapter-<?= $chapter_id ?>"><?= $all_chapter['name'] ?></a>

                                            <div class="modal_container" id="chapter-<?= $chapter_id ?>">
                                                <div class="modal_content">
                                                    <div class="modal_close button_hover close_modal" title="Close" data-close-button><i class="bx bx-x"></i></div>

                                                    <h4 class="modal_title"><?= $all_chapter['name'] ?> এর লেকচার সমূহ</h4>

                                                    <div class="modal_body">
                                                        <?php // fetch lecture list
                                                        $select_lecture_list = "SELECT * FROM hc_chapter_lecture WHERE chapter = '$chapter_id' AND is_delete = 0";
                                                        $sql_lecture_list = mysqli_query($db, $select_lecture_list);
                                                        $num_lecture_list = mysqli_num_rows($sql_lecture_list);
                                                        if ($num_lecture_list > 0) {
                                                            while ($row_lecture_list = mysqli_fetch_assoc($sql_lecture_list)) {
                                                                $lecture_id = $row_lecture_list['id'];
                                                                $lecture_name = $row_lecture_list['name'];
                                                                ?>
                                                                <div class="modal_chapter_lecture"><?= $lecture_name ?></div>
                                                                <?php 
                                                            }
                                                        }?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($all_chapter['subject'] == 1) {
                                                echo ' <span class="success">উদ্ভিদবিজ্ঞান</span>';
                                            } elseif ($all_chapter['subject'] == 2) {
                                                echo ' <span class="danger">প্রাণিবিজ্ঞান</span>';
                                            }?>
                                        </div>
                                    </td>
                                    <td><?= $row_chapter_lecture['chapter_lecture'] ?>টি</td>
                                    <td><?php if ($row_chapter_lecture['chapter_lecture'] > 0) {
                                        echo gmdate('H:i:s', $row_chapter_lecture['chapter_duration'])." Hours";
                                    }?></td>
                                    <td><?php if ($all_chapter['sale_price'] > 0) {
                                        ?>
                                        <span class="text_strike text_light text_sm">৳<?= $all_chapter['price'] ?></span>
                                        <span class="text_lg">৳<?= $all_chapter['sale_price'] ?></span>
                                        <?php 
                                    } else {
                                        ?>
                                        <span class="text_lg">৳<?= $all_chapter['price'] ?></span>
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
            <div class="single_chapter_card">
                <button type="submit" name="add_to_cart" class="w_100">অগ্রসর হউন</button>
            </div>
        </div>
    </section>
</form>

<?php include('../assets/includes/footer.php'); ?>