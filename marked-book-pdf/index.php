<?php include('../assets/includes/dashboard_header.php'); ?>

<!--=========== PAGE TITLE SECTION ===========-->
<section class="page_section hc_section">
    <div class="ep_flex hc_container">
        <h3 class="hc_page_title">Free Honey For You</h3>
    </div>
</section>

<!--=========== MARKED BOOK SECTION ===========-->
<section class="hc_section">
    <!--==== MARKED BOOK SUBJECT TAB ====-->
    <div class="marked_book_tabs hc_container ep_flex ep_start">
        <a href="<?= $base_url ?>marked-book-pdf/" class="marked_book_tab button <?php if (!isset($_GET['physics']) && !isset($_GET['chemistry'])) { echo 'active'; }?>">Biology</a>
        <a href="<?= $base_url ?>marked-book-pdf/?physics" class="marked_book_tab button <?php if (isset($_GET['physics'])) { echo 'active'; }?>">Physics</a>
        <a href="<?= $base_url ?>marked-book-pdf/?chemistry" class="marked_book_tab button <?php if (isset($_GET['chemistry'])) { echo 'active'; }?>">Chemistry</a>
    </div>
    
    <div class="marked_book_container hc_container ep_grid">
        <?php // fetch marked book chapter list
        if (isset($_GET['physics'])) {
            $select = "SELECT * FROM hc_marked_book_chapter WHERE subject IN ('পদার্থবিজ্ঞান প্রথম পত্র','পদার্থবিজ্ঞান দ্বিতীয় পত্র') AND is_delete = 0 ORDER BY id ASC";
        } elseif (isset($_GET['chemistry'])) {
            $select = "SELECT * FROM hc_marked_book_chapter WHERE subject IN ('রসায়ন প্রথম পত্র','রসায়ন দ্বিতীয় পত্র') AND is_delete = 0 ORDER BY id ASC";
        } else {
            $select = "SELECT * FROM hc_marked_book_chapter WHERE subject IN ('উদ্ভিদবিজ্ঞান','প্রাণীবিজ্ঞান') AND is_delete = 0 ORDER BY id ASC";
        }
        $sql = mysqli_query($db, $select);
        $num = mysqli_num_rows($sql);
        if ($num > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $chapter_list_id            = $row['id'];
                $chapter_list_subject       = $row['subject'];
                $chapter_list_chapter       = $row['chapter'];
                
                // select existing pdf writer
                $select_writer = "SELECT * FROM hc_marked_book_pdf WHERE chapter = '$chapter_list_id' AND is_delete = 0 ORDER BY id ASC";
                $sql_writer = mysqli_query($db, $select_writer);
                $num_writer = mysqli_num_rows($sql_writer);
                if ($num_writer > 0) {
                    ?>
                    <div class="marked_book_card">
                        <h4 class="marked_book_title"><?= $chapter_list_chapter ?></h4>
                        <h6 class="marked_book_subtitle">- <?= $chapter_list_subject ?></h6>
                        
                        <table class="marked_book_table w_100">
                            <thead>
                                <tr>
                                    <th>লেখক</th>
                                    <!--<th>পিডিএফ</th>-->
                                    <th colspan="2">কন্টেন্ট</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row_writer = mysqli_fetch_assoc($sql_writer)) {
                                    $pdf_id             = $row_writer['id'];
                                    $pdf_writer         = $row_writer['writer'];
                                    $pdf_marked_doc     = $row_writer['marked_doc'];
                                    $pdf_marked_solve   = $row_writer['mcq_solve'];
                                    $pdf_marked_class   = $row_writer['class_video'];
                                    ?>
                                    <tr>
                                        <td rowspan="3" class="text_bold"><?= $pdf_writer ?></td>
                                        
                                        <td class="text_bold">দাগানো বই</td>
                                        
                                        <td>
                                            <?php if ($pdf_marked_doc != '') {
                                                ?>
                                                <a href="<?= $pdf_marked_doc ?>" download><i class='bx bxs-download'></i>Download</a>
                                                <?php
                                            } else { echo '-/-'; }?>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="text_bold">অনুশীলনী সলভ</td>
                                        
                                        <td>
                                            <?php if ($pdf_marked_solve != '') {
                                                ?>
                                                <a href="<?= $pdf_marked_solve ?>" download><i class='bx bxs-download'></i>Download</a>
                                                <?php
                                            } else { echo '-/-'; }?>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="text_bold">সলভ ক্লাস</td>
                                        
                                        <td>
                                            <?php if ($pdf_marked_class != '') {
                                                ?>
                                                <a href="<?= $pdf_marked_class ?>" target="_blank"><i class='bx bxs-video' ></i>View</a>
                                                <?php
                                            } else { echo '-/-'; }?>
                                        </td>
                                    </tr>
                                    <?php 
                                }?>
                            </tbody>
                        </table>
                    </div>
                    <?php 
                }
            }
        }?>
    </div>
</section>

<?php include('../assets/includes/dashboard_footer.php'); ?>