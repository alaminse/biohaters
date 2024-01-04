<?php include('../assets/includes/header.php'); ?>

<!-- DELETE COURSE -->
<?php if (isset($_POST['delete'])) {
    $student_id      = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_student SET is_delete = 1 WHERE id = '$student_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../student/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Student</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE COURSE ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Manage Student</h5>
                    <a href="../success-student/" class="button btn_sm"><i class='bx bxs-graduation' ></i>Success Student</a>
                </div>
                
                <div class="mb_75">
                    <form action="" method="get" class="double_col_form" enctype="multipart/form-data">
                        <div>
                            <label for="">Search Student*</label>
                            <input type="text" id="search-student" name="search_student" placeholder="Name, Roll, Email, Phone, Father's Phone, Mother's Phone">
                        </div>
                        
                        <button type="submit" name="search">Search</button>
        			</form>
                </div>

                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Roll</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Father's Phone</th>
                            <th>Mother's Phone</th>
                            <th>College</th>
                            <th>Join Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($_GET['search'])) {
                            $search_student = $_GET['search_student'];
                        
                            $search_data = "SELECT * FROM hc_student WHERE (name LIKE '%$search_student%' OR email LIKE '%$search_student%' OR phone LIKE '%$search_student%' OR roll LIKE '%$search_student%' OR father_phone LIKE '%$search_student%' OR mother_phone LIKE '%$search_student%') AND is_delete = 0";
                            $search_sql = mysqli_query($db, $search_data);
                            $search_num = mysqli_num_rows($search_sql);
                        
                            if ($search_num > 0) {
                                $si = 0;
                                while ($search = mysqli_fetch_assoc($search_sql)) {
                                    $student_id             = $search['id'];
                                    $student_name           = $search['name'];
                                    $student_phone          = $search['phone'];
                                    $student_email          = $search['email'];
                                    $student_roll           = $search['roll'];
                                    $student_father_phone   = $search['father_phone'];
                                    $student_mother_phone   = $search['mother_phone'];
                                    $student_college        = $search['college'];
                                    $student_profile        = $search['profile'];
                                    $student_join_date      = $search['join_date'];
                        
                                    // joined date convert to text
                                    $student_join_date_text = date('d M, Y', strtotime($student_join_date));
                        
                                    // detect path of student profile
                                    if (!empty($student_profile)) {
                                        $student_profile_tmp = substr($student_profile, 2);
                                        $student_profile_img = $base_url . 'admin' . $student_profile_tmp;
                                    } else {
                                        $student_profile_img = $base_url . 'admin/assets/img/admin.png';
                                    }
                        
                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?php echo $si; ?></td>
                        
                                        <td>
                                            <div class="table_img_profile">
                                                <img src="<?php echo $student_profile_img; ?>" alt="">
                                            </div>
                                        </td>
                        
                                        <td><?php echo $student_name; ?></td>
                        
                                        <td><?php echo $student_roll; ?></td>
                        
                                        <td><?php echo $student_phone; ?></td>
                        
                                        <td><?php echo $student_email; ?></td>
                        
                                        <td><?php echo $student_father_phone; ?></td>
                        
                                        <td><?php echo $student_mother_phone; ?></td>
                        
                                        <td><?php echo $student_college; ?></td>
                        
                                        <td><?php echo $student_join_date_text; ?></td>
                        
                                        <td>
                                            <div class="btn_grp">
                                                <!-- EDIT BUTTON -->
                                                <a href="../student-edit/?student=<?php echo $student_id; ?>" target="_blank" class="btn_icon"><i class="bx bxs-edit"></i></a>
                        
                                                <!-- DELETE MODAL BUTTON -->
                                                <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $student_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                            </div>
                        
                                            <!-- DELETE MODAL -->
                                            <div class="modal fade" id="delete<?php echo $student_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $student_name; ?></span>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="delete_id" id="" value="<?php echo $student_id; ?>">
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
                            } else {
                                echo '<tr><td colspan="11">No data in this database.....</td></tr>';
                            }
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>