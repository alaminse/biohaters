<?php include('../assets/includes/header.php'); ?>

<?php if (isset($_POST['delete'])) {
    $note_id = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_all_notes SET is_delete = 1 WHERE id = '$note_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../notes/';
        </script>
        <?php 
    }
}?>

<?php if (isset($_POST['add'])) {
    $name       = mysqli_real_escape_string($db, $_POST['name']);
    $college    = mysqli_real_escape_string($db, $_POST['college']);
    $note_link  = mysqli_real_escape_string($db, $_POST['note_link']);
    $chapter    = $_POST['chapter'];
    
    $created_date = date('Y-m-d H:i:s', time());
    
    $add = "INSERT INTO hc_all_notes (chapter, note_link, credit_name, credit_college, author, created_date) VALUES ('$chapter', '$note_link', '$name', '$college', '$admin_id', '$created_date')";
    $sql_add = mysqli_query($db, $add);
    if ($sql_add) {
        ?>
        <script type="text/javascript">
            window.location.href = '../notes/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">All Notes</h4>
        </div>
    </div>
    
    <?php if (isset($_GET['add'])) {
        ?>
        <div class="ep_section">
            <div class="ep_container">
                <!--========== ADD COURSE CATEGORY ==========-->
                <div class="add_category">
                    <h5 class="box_title">Add Notes</h5>
    
                    <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                        <div>
                            <label for="">Chapter*</label>
                            <select id="" name="chapter" required>
                                <option value="">Choose Chapter</option>
                                <?php $select = "SELECT * FROM hc_marked_book_chapter WHERE is_delete = 0 ORDER BY id ASC";
                                $sql = mysqli_query($db, $select);
                                $num = mysqli_num_rows($sql);
                                if ($num > 0) {
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        $chapter_id     = $row['id'];
                                        $chapter_name   = $row['chapter'];

                                        echo '<option value="'.$chapter_id.'">'.$chapter_name.'</option>';
                                    }
                                }?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="">Credit Name*</label>
                            <input type="text" id="" name="name" placeholder="Credit Name" required>
                        </div>
                        
                        <div>
                            <label for="">Credit College</label>
                            <input type="text" id="" name="college" placeholder="Credit College">
                        </div>
    
                        <div>
                            <label for="">Note Link*</label>
                            <textarea id="" name="note_link" placeholder="Note Link" rows="4" required></textarea>
                        </div>
    
                        <button type="submit" name="add" class="grid_col_3">Add Note</button>
                    </form>
                </div>
            </div>
        </div>
        <?php 
    } else {
        ?>
        <!-- welcome message -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== MANAGE PRODUCT CATEGORY ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Manage Notes</h5>
                        <a href="../notes/?add" class="button btn_sm">Add Note</a>
                    </div>
    
                    <table class="ep_table">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Chapter</th>
                                <th>Note</th>
                                <th>Credit</th>
                                <th>Author</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select = "SELECT * FROM hc_all_notes ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num == 0) {
                                echo "<tr><td colspan='6' class='text_center'>There are no Notes</td></tr>";
                            } else {
                                $si = 0;
                                $now = date('Y-m-d H:i:s', time());
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $note_id            = $row['id'];
                                    $note_chapter       = $row['chapter'];
                                    $note_note_link     = $row['note_link'];
                                    $note_credit_name   = $row['credit_name'];
                                    $note_author        = $row['author'];
                                    $note_created_date  = $row['created_date'];
                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?php echo $si; ?></td>
    
                                        <td><?php $select_note_chapter = "SELECT * FROM hc_marked_book_chapter WHERE id = '$note_chapter'";
                                        $sql_note_chapter = mysqli_query($db, $select_note_chapter);
                                        $row_note_chapter = mysqli_fetch_assoc($sql_note_chapter);
                                        echo $row_note_chapter['chapter'];?></td>
                                        
                                        <td>
                                            <a href="<?php echo $note_note_link; ?>" target="_blank" class="ep_badge bg_success text_success">View</a>
                                        </td>
                                        
                                        <td><?php echo $note_credit_name; ?></td>
    
                                        <td><?php $select_note_author = "SELECT * FROM admin WHERE id = '$note_author'";
                                        $sql_note_author = mysqli_query($db, $select_note_author);
                                        $num_note_author = mysqli_num_rows($sql_note_author);
                                        $row_note_author = mysqli_fetch_assoc($sql_note_author);
                                        echo $row_note_author['name'];?></td>
    
                                        <td>
                                            <div class="btn_grp">
                                                <!-- EDIT BUTTON -->
                                                <a href="../notes-edit/?edit_id=<?php echo $note_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>
    
                                                <!-- DELETE MODAL BUTTON -->
                                                <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $note_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                            </div>
    
                                            <!-- DELETE MODAL -->
                                            <div class="modal fade" id="delete<?php echo $note_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Note</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $row_note_chapter['chapter'] . ' - ' . $note_credit_name; ?></span>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                            <!-- DELETE BUTTON -->
                                                            <form action="" method="post">
                                                                <input type="hidden" name="delete_id" id="" value="<?php echo $note_id; ?>">
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
        <?php 
    }?>
</main>

<?php include('../assets/includes/footer.php'); ?>