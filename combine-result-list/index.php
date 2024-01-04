<?php include('../assets/includes/dashboard_header.php'); ?>

<!--=========== PAGE TITLE SECTION ===========-->
<section class="page_section hc_section">
    <div class="ep_flex hc_container">
        <h3 class="hc_page_title">Combined Result List</h3>
    </div>
</section>

<!--=========== Combined Result SECTION ===========-->
<section class="hc_section">
    <div class="marked_book_container hc_container ep_grid">
        <?php if (isset($_GET['course']) && $_GET['course'] != '') {
            $course_id = $_GET['course'];
            
            // fetch combined list by course
            $select = "SELECT * FROM hc_combined_list WHERE course = '$course_id' AND is_delete = 0 ORDER BY id DESC";
            $sql = mysqli_query($db, $select);
            $num = mysqli_num_rows($sql);
            if ($num > 0) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    $combined_list_id   = $row['id'];
                    $combined_list_name = $row['name'];
                    $combined_list_date = $row['created_date'];
                    
                    $combined_list_date_text = date('M d, Y | h:i a', strtotime($combined_list_date));
                    
                    // intialize now time
                    $now = date('Y-m-d H:i:s', time());
                    
                    // fetch course
                    $select_course  = "SELECT * FROM hc_course WHERE id = '$course_id' AND status = 1 AND is_delete = 0";
                    $sql_course     = mysqli_query($db, $select_course);
                    $num_course     = mysqli_num_rows($sql_course);
                    if ($num_course > 0) {
                        $row_course = mysqli_fetch_assoc($sql_course);
                        $course_id   = $row_course['id'];
                        $course_name = $row_course['name'];
                    }
                    
                    if ($now >= $combined_list_date) {
                        ?>
                        <div class="marked_book_card">
                            <h4 class="marked_book_title"><?= $combined_list_name ?></h4>
                            <h6 class="marked_book_subtitle">- <?= $course_name ?></h6>
                            
                            <a href="<?= $base_url ?>combine-result/?list=<?= $combined_list_id ?>">View</a>
                        </div>
                        <?php 
                    }
                }
            }
        } else {
            ?>
            <script type="text/javascript">
                window.location.href = '<?= $base_url ?>dashboard/';
            </script>
            <?php 
        }?>
    </div>
</section>

<?php include('../assets/includes/dashboard_footer.php'); ?>