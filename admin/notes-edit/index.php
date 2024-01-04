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

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">All Notes</h4>
        </div>
    </div>
    
    <?php if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];

        // if edit id is not valid, redirect to another page
        if (empty($edit_id)) { ?><script type="text/javascript">window.location.href = '../notes/';</script><?php }
        
        // get note
        $select_note  = "SELECT * FROM hc_all_notes WHERE id = '$edit_id' AND is_delete = 0";
        $sql_note     = mysqli_query($db, $select_note);
        $row_note     = mysqli_fetch_assoc($sql_note);
        $note_id                = $row_note['id'];
        $note_chapter           = $row_note['chapter'];
        $note_link              = $row_note['note_link'];
        $note_credit_name       = $row_note['credit_name'];
        $note_credit_college    = $row_note['credit_college'];
        
        if (isset($_POST['edit'])) {
            $name       = mysqli_real_escape_string($db, $_POST['name']);
            $college    = mysqli_real_escape_string($db, $_POST['college']);
            $note_link  = mysqli_real_escape_string($db, $_POST['note_link']);
            $chapter    = $_POST['chapter'];
            
            $update = "UPDATE hc_all_notes SET chapter = '$chapter', note_link = '$note_link', credit_name = '$name', credit_college = '$college' WHERE id = '$edit_id'";
            $sql_update = mysqli_query($db, $update);
            if ($sql_update) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../notes/';
                </script>
                <?php 
            }
        }?>
        <div class="ep_section">
            <div class="ep_container">
                <!--========== ADD COURSE CATEGORY ==========-->
                <div class="add_category">
                    <h5 class="box_title">Edit Notes</h5>
    
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
                                        ?>
                                        <option value="<?= $chapter_id ?>" <?php if ($chapter_id == $note_chapter) { echo 'selected'; }?>><?= $chapter_name ?></option>
                                        <?php 
                                    }
                                }?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="">Credit Name*</label>
                            <input type="text" id="" name="name" placeholder="Credit Name" value="<?= $note_credit_name ?>" required>
                        </div>
                        
                        <div>
                            <label for="">Credit College</label>
                            <input type="text" id="" name="college" placeholder="Credit College" value="<?= $note_credit_college ?>">
                        </div>
    
                        <div>
                            <label for="">Note Link*</label>
                            <textarea id="" name="note_link" placeholder="Note Link" rows="4" required><?= $note_link ?></textarea>
                        </div>
    
                        <button type="submit" name="edit" class="grid_col_3">Edit Note</button>
                    </form>
                </div>
            </div>
        </div>
        <?php 
    } else { ?><script type="text/javascript">window.location.href = '../notes/';</script><?php } ?>
</main>

<?php include('../assets/includes/footer.php'); ?>