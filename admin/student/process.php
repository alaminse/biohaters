<?php // Include database
include('../db/db.php');

if (isset($_POST['search_student']) && $_POST['search_student'] != '') {
    $search_student = $_POST['search_student'];

    $search_data = "SELECT * FROM hc_student WHERE name LIKE '%$search_student%' OR email LIKE '%$search_student%' OR phone LIKE '%$search_student%' OR roll LIKE '%$search_student%' OR father_phone LIKE '%$search_student%' OR mother_phone LIKE '%$search_student%'";
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
                        <a href="../student-edit/?student=<?php echo $student_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>

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
}// Check if the checkbox status is received via POST
if (isset($_POST['search_student']) && $_POST['search_student'] != '') {
    $search_student = $_POST['search_student'];

    $search_data = "SELECT * FROM hc_student WHERE name LIKE '%$search_student%'";
    $search_sql = mysqli_query($db, $search_data);
    $search_num = mysqli_num_rows($search_sql);
    
    $response_mcq = '';
    
    // Perform any PHP operations or database queries you want to execute here
    // For this example, let's return a simple message
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
            
            $response_mcq .=    '<tr>
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
                                    <a href="../student-edit/?student=<?php echo $student_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>
            
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
                        </tr>';
        }
    }
} else {
    // In case the checkbox status is not set or is unchecked
    $response_mcq = "";
}

// Send the response back to the AJAX call
echo $response_mcq;?>