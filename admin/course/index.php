<?php include('../assets/includes/header.php'); ?>

<!-- DELETE COURSE -->
<?php if (isset($_POST['delete'])) {
    $course_id      = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_course SET is_delete = 1 WHERE id = '$course_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../course/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Course</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE COURSE ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Manage Course - <?php if (isset($_GET['offline'])) { echo 'Offline'; } else { echo 'Online'; }?></h5>
                    
                    <div class="btn_grp">
                        <a href="../course/" class="button btn_sm btn_trp <?php if (!isset($_GET['offline'])) { echo 'btn_active'; }?>">Online</a>
                        <a href="../course/?offline" class="button btn_sm btn_trp <?php if (isset($_GET['offline'])) { echo 'btn_active'; }?>">Offline</a>
                    </div>
                    
                    <div class="btn_grp">
                        <a href="../course-category/" class="button btn_sm">Course Category</a>
                        <a href="../course-add/" class="button btn_sm">Add Course</a>
                    </div>
                </div>

                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Schedule</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($_GET['offline'])) {
                            $type = 0;
                        } else {
                            $type = 1;
                        }
                        $select = "SELECT * FROM hc_course WHERE type = $type AND is_delete = 0 ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num == 0) {
                            echo "<tr><td colspan='8' class='text_center'>There are no course</td></tr>";
                        } else {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $course_id              = $row['id'];
                                $course_name            = $row['name'];
                                $course_type            = $row['type'];
                                $course_category        = $row['category'];
                                $course_day_schedule    = $row['day_schedule'];
                                $course_time_schedule   = $row['time_schedule'];
                                $course_status          = $row['status'];
                                $course_tags            = $row['tags'];
                                $course_price           = $row['price'];
                                $course_sale            = $row['sale_price'];
                                $course_cover_photo     = $row['cover_photo'];
                                $course_author          = $row['author'];
                                $si++;
                                
                                // fetch total student who purchased this course
                                $select_purchase = "SELECT COUNT(id) as total_purchase FROM hc_purchase_details WHERE purchase_item = 1 AND item_id = '$course_id'";
                                $sql_purchase = mysqli_query($db, $select_purchase);
                                $row_purchase = mysqli_fetch_assoc($sql_purchase);
                                $total_purchase = $row_purchase['total_purchase'];
                                
                                $today = date('Y-m-d', time());
                                
                                // fetch total student who purchased this course only today
                                $select_purchase_today = "SELECT COUNT(id) as total_purchase_today FROM hc_purchase_details WHERE purchase_item = 1 AND item_id = '$course_id' AND DATE(payment_time) = '$today'";
                                $sql_purchase_today = mysqli_query($db, $select_purchase_today);
                                $row_purchase_today = mysqli_fetch_assoc($sql_purchase_today);
                                $total_purchase_today = $row_purchase_today['total_purchase_today'];
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td>
                                        <div><?php echo $course_name; ?></div>
                                        <div class="ep_flex ep_start">
                                            <div>
                                                <b>Total: <?php echo $total_purchase; ?></b>
                                            </div>
                                            
                                            <div>
                                                <b>New: <?php echo $total_purchase_today; ?></b>
                                            </div>
                                        </div>
                                    </td>

                                    <td><?php $select_course_category = "SELECT * FROM hc_course_category WHERE id = '$course_category'";
                                    $sql_course_category = mysqli_query($db, $select_course_category);
                                    $row_course_category = mysqli_fetch_assoc($sql_course_category);
                                    echo $row_course_category['name'];?></td>

                                    <td><?php echo "Day: ".$course_day_schedule."<br>Time: ".$course_time_schedule; ?></td>

                                    <td><?php if (empty($course_sale)) {
                                        echo '<p class="text_semi">'.$course_price.'.00/- BDT</p></td>';
                                    } else {
                                        echo '<span class="text_sm text_strike">'.$course_price.'.00/- BDT</span><br>
                                        <p class="text_semi">'.$course_sale.'.00/- BDT</p></td>';
                                    }?></td>

                                    <td><?php if ($course_status == 1) {
                                        echo '<div class="ep_badge bg_success text_success">Published</div>';
                                    } else {
                                        echo '<div class="ep_badge bg_danger text_danger">Draft</div>';
                                    }?></td>

                                    <td><?php $select_course_author = "SELECT * FROM admin WHERE id = '$course_author'";
                                    $sql_course_author = mysqli_query($db, $select_course_author);
                                    $num_course_author = mysqli_num_rows($sql_course_author);
                                    $row_course_author = mysqli_fetch_assoc($sql_course_author);
                                    echo $row_course_author['name'];?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <!-- EDIT BUTTON -->
                                            <form action="../course-edit/" method="post">
                                                <input type="hidden" name="edit_id" id="" value="<?php echo $course_id; ?>">
                                                <button type="submit" name="edit_course" class="btn_icon"><i class="bx bxs-edit"></i></button>
                                            </form>
                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $course_id; ?>"><i class="bx bxs-trash-alt"></i></button>

                                            <!-- MORE BUTTON -->
                                            <div class="hc_dropdown">
                                                <div class="hc_dropdown_wrapper btn_icon">
                                                    <i class='bx bx-dots-vertical-rounded hc_dropdown_btn'></i>
                                                </div>
                                                
                                                <div class="hc_dropdown_list">
                                                    <!-- MODULE -->
                                                    <a href="../module/?course=<?php echo $course_id; ?>">Module</a>

                                                    <!-- LECTURE ADD -->
                                                    <a href="../course-lecture-add/?course=<?php echo $course_id; ?>">Course Lecture Add</a>

                                                    <!-- LECTURE LIST -->
                                                    <a href="../course-lecture/?course=<?php echo $course_id; ?>">Course Lecture List</a>

                                                    <!-- COURSE TAB -->
                                                    <a href="../course-tab/?course=<?php echo $course_id; ?>">Course Description Tab</a>

                                                    <!-- COURSE NEW ENROLLMENT -->
                                                    <a href="../course-new-enroll/?course=<?php echo $course_id; ?>">Course New Enrollment</a>

                                                    <!-- COURSE ENROLLED LIST -->
                                                    <a href="../course-enroll-list/?course=<?php echo $course_id; ?>">Course Enrolled List</a>
                                                    
                                                    <!-- MODULE -->
                                                    <a href="../course-note/?course=<?php echo $course_id; ?>">Course Notes</a>
                                                    
                                                    <?php if ($course_type == 0) {
                                                        ?>
                                                        <!-- COURSE BATCH -->
                                                        <a href="../course-batch/?course=<?php echo $course_id; ?>">Batch</a>
                                                        <?php 
                                                    }?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="delete<?php echo $course_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $course_name; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <form action="" method="post">
                                                            <input type="hidden" name="delete_id" id="" value="<?php echo $course_id; ?>">
                                                            <button type="submit" name="delete" class="button bg_danger text_danger text_semi">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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

<script>
/*========= DROPDOWN =========*/
document.querySelectorAll(".hc_dropdown").forEach(multiAction => {
    const menuButton = multiAction.querySelector(".hc_dropdown_wrapper");
    const list = multiAction.querySelector(".hc_dropdown_list");

    menuButton.addEventListener("click", () => {
        list.classList.toggle("active");
    });
});

document.addEventListener("click", e => {
    const keepOpen = (
        e.target.matches(".hc_dropdown_list")
        || e.target.matches(".hc_dropdown_wrapper")
        || e.target.matches(".hc_dropdown_btn")
    );

    if (keepOpen) return;

    document.querySelectorAll(".hc_dropdown_list").forEach(list => {
        list.classList.remove("active");
    });
});
</script>

<?php include('../assets/includes/footer.php'); ?>