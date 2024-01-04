<?php include('../assets/includes/header.php'); ?>

<?php // ADD COURSE 
$alert = '';
if (isset($_POST['add'])) {
    $name               = mysqli_escape_string($db, $_POST['name']);
    $des                = mysqli_escape_string($db, $_POST['des']);
    $status             = $_POST['status'];
    $for_whom           = $_POST['for_whom'];
    $attachment         = $_FILES['attachment']['name'];
    $attachment_tmp     = $_FILES['attachment']['tmp_name'];
    $attachment_size    = $_FILES['attachment']['size'];

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($name) || empty($des)) {
        $alert = "<p class='warning mb_75'>Required Fields.....</p>";
    } else {
        if (empty($attachment)) {
            // add Notice
            $add = "INSERT INTO hc_notice (name, description, for_whom, status, author, created_date) VALUES ('$name', '$des', '$for_whom', '$status', '$admin_id', '$created_date')";
            $sql_add = mysqli_query($db, $add);
            if ($sql_add) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../notice/';
                </script>
                <?php 
            }
        } else {
            if ($attachment_size <= 20000000) {
                $random_prev = rand(0, 999999);
                $random = rand(0, 999999);
                $random_next = rand(0, 999999);
                $time = date('Ymdhis');
    
                $final_attachment = "../assets/notice/hc_".$random_prev."_".$random."_".$random_next."_".$time."_".$attachment;
    
                move_uploaded_file($attachment_tmp, $final_attachment);
    
                // add Notice
                $add = "INSERT INTO hc_notice (name, description, for_whom, attachment, status, author, created_date) VALUES ('$name', '$des', '$for_whom', '$final_attachment', '$status', '$admin_id', '$created_date')";
                $sql_add = mysqli_query($db, $add);
                if ($sql_add) {
                    ?>
                    <script type="text/javascript">
                        window.location.href = '../notice/';
                    </script>
                    <?php 
                }
            } else {
                $alert = '<p class="danger mb_75">Attachment should be under 20MB</p>';
            }
        }
    }
}?>

<!-- DELETE COURSE -->
<?php if (isset($_POST['delete'])) {
    $notice_id      = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_notice SET is_delete = 1 WHERE id = '$notice_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../notice/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Notice</h4>
        </div>
    </div>
    <?php if (isset($_GET['add'])) {
        ?>
        <!-- NOTICE LIST -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== ADD NOTICE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Add Notice</h5>
                    </div>
                </div>

                <?= $alert ?>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Notice Title*</label>
                        <input type="text" id="" name="name" placeholder="Notice Title">
                    </div>

                    <div>
                        <label for="">Status</label>
                        <select id="" name="status">
                            <option value="0">Choose Status</option>
                            <option value="1">Published</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>

                    <div>
                        <label for="">For Whom</label>
                        <select id="" name="for_whom">
                            <option value="0">For All</option>
                            <?php $select = "SELECT * FROM hc_course WHERE is_delete = 0 ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $course_id     = $row['id'];
                                    $course_name   = $row['name'];
                                    ?>
                                    <option value="<?php echo $course_id; ?>"><?php echo $course_name; ?></option>
                                    <?php 
                                }
                            }?>
                        </select>
                    </div>

                    <div>
                        <label for="">Attachment</label>
                        <input type="file" id="" name="attachment" class="input_sm">
                    </div>

                    <div class="grid_col_3">
                        <label for="">Notice Description*</label>
                        <textarea id="" name="des" placeholder="Course Description" rows="4"></textarea>
                    </div>

                    <button type="submit" name="add">Add Notice</button>
                </form>
            </div>
        </div>
        <?php 
    } else {
        ?>
        <!-- NOTICE LIST -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== MANAGE NOTICE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Manage Notice</h5>
                        <a href="../notice/?add" class="button btn_sm"><i class='bx bxs-file'></i>Add Notice</a>
                    </div>

                    <table class="ep_table" id="datatable">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Title</th>
                                <th>For Whom</th>
                                <th>Attachment</th>
                                <th>Status</th>
                                <th>Published on</th>
                                <th>Author</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select = "SELECT * FROM hc_notice WHERE is_delete = 0 ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                $si = 0;
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $notice_id              = $row['id'];
                                    $notice_name            = $row['name'];
                                    $notice_description     = $row['description'];
                                    $notice_for_whom        = $row['for_whom'];
                                    $notice_attachment      = $row['attachment'];
                                    $notice_status          = $row['status'];
                                    $notice_author          = $row['author'];
                                    $notice_created_date    = $row['created_date'];

                                    // joined date convert to text
                                    $notice_created_date_text = date('d M, Y', strtotime($notice_created_date));

                                    if ($notice_for_whom == '0') {
                                        $notice_for_whom_name = 'All';
                                    } else {
                                        $select_course = "SELECT * FROM hc_course WHERE id = '$notice_for_whom'";
                                        $sql_course = mysqli_query($db, $select_course);
                                        $row_course = mysqli_fetch_assoc($sql_course);
                                        $course_id      = $row_course['id'];
                                        $course_name    = $row_course['name'];
                                        $notice_for_whom_name = $course_name;
                                    }

                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?php echo $si; ?></td>

                                        <td><?php echo $notice_name; ?></td>

                                        <td><?php echo $notice_for_whom_name; ?></td>

                                        <td><?php if (!empty($notice_attachment)) {
                                            echo '<div class="ep_badge bg_success text_success">Yes</div>';
                                        } else {
                                            echo '<div class="ep_badge bg_danger text_danger">No</div>';
                                        }?></td>

                                        <td><?php if ($notice_status == 1) {
                                            echo '<div class="ep_badge bg_success text_success">Published</div>';
                                        } else {
                                            echo '<div class="ep_badge bg_danger text_danger">Draft</div>';
                                        }?></td>

                                        <td><?php echo $notice_created_date_text; ?></td>
                                        
                                        <td><?php $select_notice_author = "SELECT * FROM admin WHERE id = '$notice_author'";
                                        $sql_notice_author = mysqli_query($db, $select_notice_author);
                                        $num_notice_author = mysqli_num_rows($sql_notice_author);
                                        $row_notice_author = mysqli_fetch_assoc($sql_notice_author);
                                        echo $row_notice_author['name'];?></td>

                                        <td>
                                            <div class="btn_grp">
                                                <!-- EDIT BUTTON -->
                                                <a href="../notice-edit/?notice=<?php echo $notice_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>
                                            
                                                <!-- DELETE MODAL BUTTON -->
                                                <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $notice_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                            </div>

                                            <!-- DELETE MODAL -->
                                            <div class="modal fade" id="delete<?php echo $notice_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $notice_name; ?></span>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="delete_id" id="" value="<?php echo $notice_id; ?>">
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

<!--========== CKEDITOR JS =============-->
<script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>

<script>
/*========== POST DESCRIPTION CKEDITOR =============*/
CKEDITOR.replace( 'des' );
</script>

<!--=========== DATATABLE ===========-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>

<script>
/*========= DATATABLE CUSTOM =========*/
$(document).ready( function () {
    $('#datatable').DataTable( {
        dom: 'Bfrtip',
        // order: [[0, 'desc']],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>

<?php include('../assets/includes/footer.php'); ?>