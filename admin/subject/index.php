<?php include('../assets/includes/header.php'); ?>

<?php // subject add
$alert = '';
if (isset($_POST['add'])) {
    $subject = mysqli_escape_string($db, $_POST['subject']);

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($subject)) {
        $alert = "<p class='warning mb_75'>Required Subject Name.....</p>";
    } else {
        $add = "INSERT INTO hc_subject (subject, author, created_date) VALUES ('$subject', '$admin_id', '$created_date')";
        $sql_add = mysqli_query($db, $add);
        if ($sql_add) {
            ?>
            <script type="text/javascript">
                window.location.href = '../subject/';
            </script>
            <?php 
        }
    }
}

// subject delete
if (isset($_POST['delete'])) {
    $delete_id      = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_subject SET is_delete = 1 WHERE id = '$delete_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../subject/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Subject</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container ep_grid grid_2">
            <!--========== subject ==========-->
            <div>
                <!--========== ADD subject ==========-->
                <div class="add_day">
                    <h5 class="box_title">Add Subject</h5>

                    <?php echo $alert; ?>

                    <form action="" method="post" class="single_col_form">
                        <div>
                            <label for="product-cat-name">Subject*</label>
                            <input type="text" id="product-cat-name" name="subject" placeholder="Subject Name">
                        </div>

                        <button type="submit" name="add">Add Subject</button>
                    </form>
                </div>
                </div>

                <div>
                <!--========== MANAGE subject ==========-->
                <div class="mng_category">
                    <div class="ep_flex mt_75 mb_75">
                        <h5 class="box_title">Manage Subject</h5>
                        <a href="../chapter/" class="button btn_sm">Add Chapter</a>
                    </div>

                    <table class="ep_table">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Subject</th>
                                <th>Author</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select = "SELECT * FROM hc_subject WHERE is_delete = 0 ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num == 0) {
                                echo "<tr><td colspan='6' class='text_center'>There are no Subject</td></tr>";
                            } else {
                                $si = 0;
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $subject_id        = $row['id'];
                                    $subject_name      = $row['subject'];
                                    $subject_author    = $row['author'];
                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?php echo $si; ?></td>
                                        
                                        <td><?php echo $subject_name; ?></td>

                                        <td><?php $select_subject_author = "SELECT * FROM admin WHERE id = '$subject_author'";
                                        $sql_subject_author = mysqli_query($db, $select_subject_author);
                                        $num_subject_author = mysqli_num_rows($sql_subject_author);
                                        $row_subject_author = mysqli_fetch_assoc($sql_subject_author);
                                        echo $row_subject_author['name'];?></td>

                                        <td>
                                            <div class="btn_grp">
                                                <!-- DELETE MODAL BUTTON -->
                                                <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $subject_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                            </div>

                                            <!-- DELETE MODAL -->
                                            <div class="modal fade" id="delete<?php echo $subject_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Category</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $subject_name; ?></span>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="delete_id" id="" value="<?php echo $subject_id; ?>">
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
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>