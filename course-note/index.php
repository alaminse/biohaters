<?php include('../assets/includes/dashboard_header.php'); ?>

<!--=========== PAGE TITLE SECTION ===========-->
<section class="page_section hc_section">
    <div class="ep_flex hc_container">
        <h3 class="hc_page_title">Course Notes</h3>
    </div>
</section>

<!--=========== NOTES SECTION ===========-->
<?php if ((isset($_GET['course'])) && ($_GET['course'] != '')) {
    $note_course = $_GET['course'];
    // check this exam course is my course
    $course_found = false;
    foreach ($result['my_courses'] as $my_courses) {
        if ($my_courses['item_id'] === $note_course) {
            $course_found = true;
            break;
        }
    }
    
    if ($course_found) {
        ?>
        <section class="hc_section">
            <div class="invoice_container course_note_container hc_container ep_grid">
                <?php // connected with course notes
                $select_note_chapter = "SELECT * FROM hc_course_note WHERE course = '$note_course' AND is_delete = 0";
                $sql_note_chapter = mysqli_query($db, $select_note_chapter);
                $num_note_chapter = mysqli_num_rows($sql_note_chapter);
                if ($num_note_chapter > 0) {
                    while ($row_note_chapter = mysqli_fetch_assoc($sql_note_chapter)) {
                        $note_chapter_id    = $row_note_chapter['chapter'];
                        
                        // get chapter data
                        $select_chapter_data = "SELECT * FROM hc_marked_book_chapter WHERE id = '$note_chapter_id' AND is_delete = 0";
                        $sql_chapter_data = mysqli_query($db, $select_chapter_data);
                        $num_chapter_data = mysqli_num_rows($sql_chapter_data);
                        if ($num_chapter_data > 0) {
                            while ($row_chapter_data = mysqli_fetch_assoc($sql_chapter_data)) {
                                $chapter_data_id    = $row_chapter_data['id'];
                                $chapter_data_name  = $row_chapter_data['chapter'];
                            }
                        }?>
                        <div class="hc_card">
                            <h4 class="hc_card_title"><?= $chapter_data_name ?></h4>
            
                            <div class="dashboard_list">
                                <?php // get note data
                                $select_note_data = "SELECT * FROM hc_all_notes WHERE chapter = '$chapter_data_id' AND is_delete = 0";
                                $sql_note_data = mysqli_query($db, $select_note_data);
                                $num_note_data = mysqli_num_rows($sql_note_data);
                                if ($num_note_data > 0) {
                                    while ($row_note_data = mysqli_fetch_assoc($sql_note_data)) {
                                        $note_data_id    = $row_note_data['id'];
                                        $note_data_link  = $row_note_data['note_link'];
                                        $note_data_credit_name      = $row_note_data['credit_name'];
                                        $note_data_credit_college   = $row_note_data['credit_college'];
                                        ?>
                                        <a href="<?= $note_data_link ?>" class="dashboard_list_card">
                                            <div class="dashboard_list_card_header">
                                                <div class="dashboard_list_card_badge dashboard_list_card_badge_notice">
                                                    <i class='bx bxs-pencil'></i> Note
                                                </div>
                                                <i class='bx bxs-chevrons-right'></i>
                                            </div>
        
                                            <div class="dashboard_list_card_content">
                                                <div class="dashboard_list_card_title"><?= $note_data_credit_name ?></div>
                                                <div class="dashboard_list_card_date"><?= $note_data_credit_college ?></div>
                                            </div>
                                        </a>
                                        <?php 
                                    }
                                }?>
                            </div>
                        </div>
                        <?php 
                    }
                }?>
            </div>
        </section>
        <?php 
    } else {
        ?>
        <script type="text/javascript">
            window.location.href = '<?= $base_url ?>course-note/';
        </script>
        <?php 
    }
} else {
    ?>
    <section class="hc_section">
        <div class="invoice_container course_note_container hc_container ep_grid">
            <?php if (isset($result['my_courses'])) {
                foreach ($result['my_courses'] as $key => $my_courses) {
                    // courses id
                    $my_courses_id = $my_courses['item_id'];
        
                    // fetch my course
                    $select_my_course  = "SELECT * FROM hc_course WHERE id = '$my_courses_id' AND status = 1 AND is_delete = 0 ORDER BY id DESC";
                    $sql_my_course     = mysqli_query($db, $select_my_course);
                    $num_my_course     = mysqli_num_rows($sql_my_course);
                    if ($num_my_course > 0) {
                        $row_my_course = mysqli_fetch_assoc($sql_my_course);
        
                        // my course variable
                        $my_course_id              = $row_my_course['id'];
                        $my_course_name            = $row_my_course['name'];
        
                        // connected with course notes
                        $select_course_note = "SELECT * FROM hc_course_note WHERE course = '$my_course_id' AND is_delete = 0";
                        $sql_course_note = mysqli_query($db, $select_course_note);
                        $num_course_note = mysqli_num_rows($sql_course_note);
                        if ($num_course_note > 0) {
                            ?>
                            <!-- COURSE CARD -->
                            <a href="<?= $base_url ?>course-note/?course=<?= $my_course_id ?>" class="invoice_card">
                                <div class="ep_flex ep_start invoice_card_header">
                                    <i class='bx bxs-pencil'></i>
            
                                    <div>
                                        <h4 class="invoice_card_no no_margin"><?= $my_course_name ?></h4>
                                    </div>
                                </div>
                            </a>
                            <?php 
                        }
                    }
                }
            }?>
        </div>
    </section>
    <?php 
}?>

<?php include('../assets/includes/dashboard_footer.php'); ?>