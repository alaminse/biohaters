<?php include('../assets/includes/header.php'); ?>

<!--=========== COMMON SECTION ===========-->
<section class="common_section hc_section">
    <div class="hc_container text_center">
        <h1 class="common_section_title">নোটিশ</h1>
    </div>
</section>

<!--=========== LIKED CHAPTER PURCHASE FORM ===========-->
<form action="" method="post">
    <!--=========== LIKED CHAPTER ===========-->
    <section class="liked_chapter_section hc_section">
        <div class="hc_container ep_grid">
            <!-- LIKED CHAPTER BOX -->
            <div class="liked_chapter_box">
                <div>
                    <table class="hc_table notice_table">
                        <thead>
                            <tr>
                                <th>নং</th>
                                <th>নোটিশ</th>
                                <th>সংযুক্তি</th>
                                <th>প্রকাশ</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (isset($result['notice'])) {
                                $si = 0;
                                foreach ($result['notice'] as $key => $notice) {
                                    // notice id
                                    $notice_id = $notice['id'];

                                    $si++;
                                    ?>
                                    <!-- notice ROW -->
                                    <tr>
                                        <td><?= $si ?></td>

                                        <td><?= $notice['name'] ?></td>
                                        
                                        <td><?php if (!empty($notice['attachment'])) {
                                            $notice_attachment = substr($notice['attachment'], 2);
                                            echo '<a href="' . $base_url . 'admin' . $notice_attachment . '" target="_blank" class="button btn_sm no_hover notice_btn m_auto">Download</a>';
                                        }?></td>

                                        <td><?= date('d M, Y', strtotime($notice['created_date'])) ?></td>

                                        <td><a href="<?= $base_url ?>notice-view/?notice=<?= $notice_id ?>">View</a></td>
                                    </tr>
                                    <?php 
                                }
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</form>

<?php include('../assets/includes/footer.php'); ?>