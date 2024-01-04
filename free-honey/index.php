<?php include('../assets/includes/header.php'); ?>

<!--=========== COMMON SECTION ===========-->
<section class="common_section hc_section">
    <div class="hc_container text_center">
        <h1 class="common_section_title">দাগানো বই ও সলভ ক্লাস</h1>
    </div>
</section>

<!--=========== LIKED CHAPTER PURCHASE FORM ===========-->
<form action="<?= $base_url ?>purchase/" method="post">
    <!--=========== LIKED CHAPTER ===========-->
    <section class="liked_chapter_section hc_section">
        <div class="part_chapter_btn_container hc_container ep_grid">
            <div class="marked_book_tabs">
                <a href="<?= $base_url ?>free-honey/" class="marked_book_tab button btn_sm <?php if (!isset($_GET['physics']) && !isset($_GET['chemistry']) && !isset($_GET['english'])) { echo 'active'; }?>">Biology</a>
                <a href="<?= $base_url ?>free-honey/?physics" class="marked_book_tab button btn_sm <?php if (isset($_GET['physics'])) { echo 'active'; }?>">Physics</a>
                <a href="<?= $base_url ?>free-honey/?chemistry" class="marked_book_tab button btn_sm <?php if (isset($_GET['chemistry'])) { echo 'active'; }?>">Chemistry</a>
                <a href="<?= $base_url ?>free-honey/?english" class="marked_book_tab button btn_sm <?php if (isset($_GET['english'])) { echo 'active'; }?>">English</a>
            </div>
        </div>
        <div class="marked_book_container hc_container ep_grid">
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
            if ($num > 0) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    $chapter_list_id            = $row['id'];
                    $chapter_list_subject       = $row['subject'];
                    $chapter_list_chapter       = $row['chapter'];
                    $now = date('Y-m-d H:i:s', time());
                    // select existing pdf writer
                    $select_writer = "SELECT * FROM hc_marked_book_pdf WHERE chapter = '$chapter_list_id' AND scheduled <= '$now' AND is_delete = 0 ORDER BY id ASC";

                    // $select_writer = "SELECT * FROM hc_marked_book_pdf WHERE chapter = '$chapter_list_id' WHERE scheduled =< '$now' AND is_delete = 0 ORDER BY id ASC";
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
                                        
                                        <!-- <tr>
                                            <td class="text_bold">সলভ ক্লাস</td>
                                            
                                            <td>
                                                <?php if ($pdf_marked_class != '') {
                                                    ?>
                                                    <a href="<?= $pdf_marked_class ?>" target="_blank"><i class='bx bxs-video' ></i>View</a>
                                                    <?php
                                                } else { echo '-/-'; }?>
                                            </td>
                                        </tr> -->
                                        <tr>
                                            <td class="text_bold">সলভ ক্লাস x</td>
                                            
                                            <td>
                                                <?php if ($pdf_marked_class != '') {
                                                    ?>
                                                         <!-- class MODAL BUTTON -->
                                                    <a type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#class<?php echo $pdf_id; ?>"><i class='bx bxs-video' ></i>View</a>
                                                    <!-- <a href="<?= $pdf_marked_class ?>" target="_blank"><i class='bx bxs-video' ></i>View</a> -->
                                                    <?php
                                                } else { echo '-/-'; }?>
                                            </td>
                                        </tr>
                                            <!-- class MODAL -->
                                        <div class="modal fade" id="class<?php echo $pdf_id; ?>" tabindex="0" aria-labelledby="youtubVideo" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="youtubVideo"><?= $chapter_list_chapter ?></h5>
                                                            <p>- <?= $pdf_writer ?></p>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <iframe id="videoFrame<?= $pdf_id ?>" width="100%" height="100%" src="<?= $pdf_marked_class ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                // When the modal is shown, play the video
                                                $('#class<?= $pdf_id; ?>').on('shown.bs.modal', function () {
                                                    var video = document.getElementById('videoFrame<?= $pdf_id ?>');
                                                    video.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
                                                });

                                                // When the modal is hidden, pause the video
                                                $('#class<?= $pdf_id; ?>').on('hidden.bs.modal', function () {
                                                    var video = document.getElementById('videoFrame<?= $pdf_id ?>');
                                                    video.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                                                });
                                            </script>
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
</form>

<style>
    .modal-body {
        width: 100%;
        height: 400px;
    }

    .modal-body iframe {
        width: 100%;
        height: 100%;
    }

    .modal-title {
        margin-right: 1rem;
    }
</style>

<?php include('../assets/includes/footer.php'); ?>