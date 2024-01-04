<?php include('../assets/includes/header.php'); ?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Marked Book PDF</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE BLOG ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Manage PDF - <?php if (isset($_GET['physics'])) { echo 'Physics'; } elseif (isset($_GET['chemistry'])) { echo 'Chemistry'; } else { echo 'Biology'; }?></h5>
                    
                    <div class="btn_grp">
                        <a href="../marked-book/" class="button btn_sm btn_trp <?php if (!isset($_GET['physics']) && !isset($_GET['chemistry']) && !isset($_GET['english'])) { echo 'btn_active'; }?>">Biology</a>
                        <a href="../marked-book/?physics" class="button btn_sm btn_trp <?php if (isset($_GET['physics'])) { echo 'btn_active'; }?>">Physics</a>
                        <a href="../marked-book/?chemistry" class="button btn_sm btn_trp <?php if (isset($_GET['chemistry'])) { echo 'btn_active'; }?>">Chemistry</a>
                        <a href="../marked-book/?english" class="button btn_sm btn_trp <?php if (isset($_GET['english'])) { echo 'btn_active'; }?>">English</a>
                    </div>
                </div>

                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Subject</th>
                            <th>Chapter</th>
                            <th>PDF Exist</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php // fetch marked book chapter list
                        if (isset($_GET['physics'])) {
                            $select = "SELECT * FROM hc_marked_book_chapter WHERE subject IN ('পদার্থবিজ্ঞান প্রথম পত্র','পদার্থবিজ্ঞান দ্বিতীয় পত্র') AND is_delete = 0 ORDER BY id ASC";
                        } elseif (isset($_GET['chemistry'])) {
                            $select = "SELECT * FROM hc_marked_book_chapter WHERE subject IN ('রসায়ন প্রথম পত্র','রসায়ন দ্বিতীয় পত্র') AND is_delete = 0 ORDER BY id ASC";
                        } elseif (isset($_GET['english'])) {
                            $select = "SELECT * FROM hc_marked_book_chapter WHERE subject IN ('English') AND is_delete = 0 ORDER BY id ASC";
                        } else {
                            $select = "SELECT * FROM hc_marked_book_chapter WHERE subject IN ('উদ্ভিদবিজ্ঞান','প্রাণীবিজ্ঞান') AND is_delete = 0 ORDER BY id ASC";
                        }
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num == 0) {
                            echo "<tr><td colspan='5' class='text_center'>There are no Chapter</td></tr>";
                        } else {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $chapter_list_id            = $row['id'];
                                $chapter_list_subject       = $row['subject'];
                                $chapter_list_chapter       = $row['chapter'];
                                
                                // select existing pdf writer
                                $select_writer = "SELECT * FROM hc_marked_book_pdf WHERE chapter = '$chapter_list_id' AND is_delete = 0 ORDER BY id ASC";
                                $sql_writer = mysqli_query($db, $select_writer);
                                $num_writer = mysqli_num_rows($sql_writer);
                                if ($num_writer == 0) {
                                    $writer = '--';
                                } else {
                                    $writer = '';
                                    while ($row_writer = mysqli_fetch_assoc($sql_writer)) {
                                        $pdf_id            = $row_writer['id'];
                                        $pdf_writer       = $row_writer['writer'];
                                        $writer = $pdf_writer . ', ' . $writer;
                                    }
                                    $writer = substr($writer, 0, -2);
                                }
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td><?php echo $chapter_list_subject; ?></td>
                                    
                                    <td><?php echo $chapter_list_chapter; ?></td>

                                    <td><?php echo $writer; ?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <a href="../marked-book-pdf/?chapter=<?php echo $chapter_list_id; ?>" class="text_sm">View List</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                            }
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>