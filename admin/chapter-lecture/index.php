<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_GET['chapter'])) { 
    $chapter = $_GET['chapter'];

    // get chapter name
    $select_chapter  = "SELECT * FROM hc_chapter WHERE id = '$chapter' AND is_delete = 0";
    $sql_chapter     = mysqli_query($db, $select_chapter);
    $row_chapter     = mysqli_fetch_assoc($sql_chapter);
    $chapter_name    = $row_chapter['chapter'];

    // delete lecture
    if (isset($_POST['delete'])) {
        $lecture_id = mysqli_escape_string($db, $_POST['delete_id']);

        $delete = "UPDATE hc_chapter_lecture SET is_delete = 1 WHERE id = '$lecture_id'";
        $sql_delete = mysqli_query($db, $delete);
        if ($sql_delete) {
            ?>
            <script type="text/javascript">
                window.location.href = '../chapter-lecture/?chapter=<?php echo $chapter; ?>';
            </script>
            <?php 
        }
    }?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Chapter Lectures</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE PRODUCT CATEGORY ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title"><?= $chapter_name; ?> - Lectures</h5>
                    <a href="../chapter-lecture-add/?chapter=<?php echo $chapter; ?>" class="button btn_sm">Add Lecture</a>
                </div>

                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_chapter_lecture WHERE chapter = '$chapter' AND is_delete = 0 ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num == 0) {
                            echo "<tr><td colspan='6' class='text_center'>There are no Chapter Lectures</td></tr>";
                        } else {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $chapter_lecture_id              = $row['id'];
                                $chapter_lecture_name            = $row['name'];
                                $chapter_lecture_chapter         = $row['chapter'];
                                $chapter_lecture_status          = $row['status'];
                                $chapter_lecture_author          = $row['author'];
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td><?php echo $chapter_lecture_name; ?></td>

                                    <td><?php if ($chapter_lecture_status == 1) {
                                        echo '<div class="ep_badge bg_success text_success">Published</div>';
                                    } else {
                                        echo '<div class="ep_badge bg_danger text_danger">Draft</div>';
                                    }?></td>

                                    <td><?php $select_chapter_lecture_author = "SELECT * FROM admin WHERE id = '$chapter_lecture_author'";
                                    $sql_chapter_lecture_author = mysqli_query($db, $select_chapter_lecture_author);
                                    $num_chapter_lecture_author = mysqli_num_rows($sql_chapter_lecture_author);
                                    $row_chapter_lecture_author = mysqli_fetch_assoc($sql_chapter_lecture_author);
                                    echo $row_chapter_lecture_author['name'];?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <!-- EDIT BUTTON -->
                                            <a href="../chapter-lecture-edit/?edit_id=<?php echo $chapter_lecture_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>

                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $chapter_lecture_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                        </div>

                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="delete<?php echo $chapter_lecture_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Lecture</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $chapter_lecture_name; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <!-- DELETE BUTTON -->
                                                        <form action="" method="post">
                                                            <input type="hidden" name="delete_id" id="" value="<?php echo $chapter_lecture_id; ?>">
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
<?php } else { ?><script type="text/javascript">window.location.href = '../chapter/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>