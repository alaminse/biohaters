<?php include('../assets/includes/header.php'); ?>

<!-- GET COURSE -->
<?php if (isset($_GET['course'])) {
    $course_id = $_GET['course'];

    if (empty($course_id)) {
        ?>
        <script type="text/javascript">
            window.location.href = '../course/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE COURSE ==========-->
            <div class="mng_category">
                <?php $select = "SELECT * FROM hc_purchase_details WHERE purchase_item = '1' AND item_id = '$course_id' GROUP BY student_id ORDER BY id DESC";
                $sql = mysqli_query($db, $select);
                $num = mysqli_num_rows($sql);
                if ($num > 0) {
                    $si = 0;
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $course_stutdent = $row['student_id'];
                        
                        // fetch student
                        $select_stutdent = "SELECT * FROM hc_student WHERE id = '$course_stutdent'";
                        $sql_stutdent = mysqli_query($db, $select_stutdent);
                        $num_stutdent = mysqli_num_rows($sql_stutdent);
                        if ($num_stutdent > 0) {
                            $row_stutdent       = mysqli_fetch_assoc($sql_stutdent);
                            $stutdent_id        = $row_stutdent['id'];
                            $stutdent_email     = $row_stutdent['email'];
                            $stutdent_phone     = $row_stutdent['phone'];
                        }

                        $msg = "";
                    }
                }?>
            </div>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>