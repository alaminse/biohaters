<?php include('../assets/includes/dashboard_header.php'); ?>

<!--=========== PAGE TITLE SECTION ===========-->
<section class="page_section hc_section">
    <div class="ep_flex hc_container">
        <h3 class="hc_page_title">Purchase History</h3>
    </div>
</section>

<!--=========== PURCHASE HISTORY SECTION ===========-->
<section class="hc_section">
    <div class="invoice_container hc_container ep_grid">
        <?php if (isset($result['purchase_history'])) {
            // invoice list
            foreach ($result['purchase_history'] as $key => $purchase_history) {
                // invoice id
                $purchase_history_id = $purchase_history['id'];

                // purchase item divide into text
                if ($purchase_history['purchase_item'] == '1') {
                    $purchase_history_item_text = 'Course';
                } elseif ($purchase_history['purchase_item'] == '2') {
                    $purchase_history_item_text = 'Chapter';
                }

                // purchase date convert to text
                $purchase_history_date_text = date('d M, Y', strtotime($purchase_history['purchase_date']));
                ?>
                <!--=========== INVOICE CARD ===========-->
                <div class="invoice_card">
                    <div class="ep_flex ep_start invoice_card_header mb_75">
                        <i class='bx bx-receipt'></i>

                        <div>
                            <h4 class="invoice_card_no">INVOICE # <?= $purchase_history_id ?></h4>
                            <p class="invoice_card_date"><?= $purchase_history_date_text ?></p>
                        </div>
                    </div>
                    <h3 class="invoice_card_item"><?= $purchase_history_item_text ?> Item</h3>
                    <p class="invoice_card_amount">BDT <?= $purchase_history['total_amount'] ?>.00/-</p>
                    <a href="<?= $base_url ?>purchase-details/?invoice=<?= $purchase_history_id ?>" class="button btn_sm">View <i class='bx bxs-chevron-right'></i></a>
                </div>
                <?php 
            }
        }?>
    </div>
</section>

<?php include('../assets/includes/dashboard_footer.php'); ?>