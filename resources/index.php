<?php include('../assets/includes/dashboard_header.php'); ?>

<!--=========== PAGE TITLE SECTION ===========-->
<section class="page_section hc_section">
    <div class="ep_flex hc_container">
        <h3 class="hc_page_title">Resources</h3>
    </div>
</section>

<!--=========== RESOURCE SECTION ===========-->
<section class="hc_section">
    <div class="resource_container hc_container ep_grid">
        <table class="hc_table">
            <thead>
                <tr>
                    <th>নং</th>
                    <th>ডকুমেন্ট নাম</th>
                    <th>কোর্সের নাম</th>
                    <th>প্রকাশের তারিখ</th>
                    <th>সংযুক্তি</th>
                </tr>
            </thead>

            <tbody>
                <?php if (isset($result['my_courses'])) {
                    foreach ($result['my_courses'] as $key => $my_courses) {
                        // courses id
                        $my_courses_id = $my_courses['item_id'];
    
                        // fetch my course
                        $select_my_course  = "SELECT * FROM hc_course WHERE id = '$my_courses_id' AND status = 1 AND is_delete = 0";
                        $sql_my_course     = mysqli_query($db, $select_my_course);
                        $num_my_course     = mysqli_num_rows($sql_my_course);
                        if ($num_my_course > 0) {
                            $row_my_course = mysqli_fetch_assoc($sql_my_course);
    
                            // my course id
                            $my_course_id   = $row_my_course['id'];
                            $my_course_name = $row_my_course['name'];
    
                            $si = 0;
    
                            // fetch lecture sheet
                            $select_lecture_sheet = "SELECT * FROM hc_course_lecture WHERE course = '$my_course_id' AND is_delete = 0 ORDER BY document_date DESC";
                            $sql_lecture_sheet = mysqli_query($db, $select_lecture_sheet);
                            $num_lecture_sheet = mysqli_num_rows($sql_lecture_sheet);
                            if ($num_lecture_sheet > 0) {
                                while ($row_lecture_sheet = mysqli_fetch_assoc($sql_lecture_sheet)) {
                                    $lecture_sheet_id       = $row_lecture_sheet['id'];
                                    $lecture_sheet_name     = $row_lecture_sheet['name'];
                                    $lecture_sheet_doc      = $row_lecture_sheet['document'];
                                    $lecture_sheet_doc_date = $row_lecture_sheet['document_date'];
    
                                    $lecture_sheet_doc_date = date('d M, Y', strtotime($lecture_sheet_doc_date));
                                    if (!empty($lecture_sheet_doc)) {
                                        $si++;
                                        ?>
                                        <tr>
                                            <td><?= $si ?></td>
                                            <td><?= $lecture_sheet_name ?></td>
                                            <td><?= $my_course_name ?></td>
                                            <td><?= $lecture_sheet_doc_date ?></td>
                                            <td>
                                                <a href="<?= $lecture_sheet_doc ?>" target="_blank" class="ep_flex ep_start warning w_max m_auto" download><i class='bx bxs-file-archive'></i> Download</a>
                                            </td>
                                        </tr>
                                        <?php 
                                    }
                                }
                            }
                        }
                    }
                }?>
            </tbody>
        </table>
    </div>
</section>

<?php include('../assets/includes/dashboard_footer.php'); ?>