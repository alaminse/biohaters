<?php include('../assets/includes/header.php'); ?>

<!-- DELETE COURSE -->
<?php if (isset($_POST['delete'])) {
    $review_id      = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_review SET is_delete = 1 WHERE id = '$review_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../review/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Review</h4>
        </div>
    </div>

    <?php if (isset($_GET['add'])) {
        // ADD SUCCESS STUDENT 
        $alert = '';
        if (isset($_POST['add'])) {
            $name           = mysqli_escape_string($db, $_POST['name']);
            $college        = mysqli_escape_string($db, $_POST['college']);
            $review         = mysqli_escape_string($db, $_POST['review']);
            $cover_pic      = $_FILES['cover_pic']['name'];
            $cover_pic_tmp  = $_FILES['cover_pic']['tmp_name'];
            $cover_pic_size = $_FILES['cover_pic']['size'];
        
            $created_date = date('Y-m-d H:i:s', time());
        
            if (empty($name) || empty($college) || empty($review) || empty($cover_pic)) {
                $alert = "<p class='warning mb_75'>Required Fields.....</p>";
            } else {
                if (!empty($cover_pic)) {
                    $array_img = explode('.', $cover_pic);
                    $extension_img = end($array_img);
            
                    if ($extension_img == 'jpg' || $extension_img == 'png') {
                        if ($cover_pic_size <= 85000) {
                            $random_prev = rand(0, 999999);
                            $random = rand(0, 999999);
                            $random_next = rand(0, 999999);
                            $time = date('Ymdhis');
            
                            $final_img = "../assets/review/hc_".$random_prev."_".$random."_".$random_next."_".$time."_".$cover_pic;
            
                            move_uploaded_file($cover_pic_tmp, $final_img);
            
                            // add success student with photo
                            $add = "INSERT INTO hc_review (name, college, photo, review, insert_date) VALUES ('$name', '$college', '$final_img', '$review', '$created_date')";
                            $sql_add = mysqli_query($db, $add);
                            if ($sql_add) {
                                ?>
                                <script type="text/javascript">
                                    window.location.href = '../review/';
                                </script>
                                <?php 
                            }
                        } else {
                            $alert = '<p class="danger mb_75">Cover Pic should be under 85KB</p>';
                        }
                    } else {
                        $alert = '<p class="danger mb_75">Give PNG or JPG file</p>';
                    }
                }
            }
        }?>
        <div class="ep_section">
            <div class="ep_container">
                <!--========== ADD COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Add Review</h5>
                    </div>
                </div>

                <?php echo $alert; ?>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Name*</label>
                        <input type="text" id="" name="name" placeholder="Student Name">
                    </div>

                    <div>
                        <label for="">College Name*</label>
                        <input type="text" id="" name="college" placeholder="College Name">
                    </div>

                    <div>
                        <label for="">Cover Photo* (350*350px, max: 85kb)</label>
                        <input type="file" id="" name="cover_pic" class="input_sm">
                    </div>

                    <div class="grid_col_3">
                        <label for="">Review*</label>
                        <textarea id="" name="review" placeholder="Review" rows="4"></textarea>
                    </div>

                    <button type="submit" name="add" class="grid_col_3">Add</button>
                </form>
            </div>
        </div>
        <?php 
    }?>

    <?php if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];

        if (empty($edit_id)) {
            ?>
            <script type="text/javascript">
                window.location.href = '../review/';
            </script>
            <?php 
        }

        // fetch student
        $select = "SELECT * FROM hc_review WHERE id = '$edit_id' AND is_delete = 0";
        $sql = mysqli_query($db, $select);
        $num = mysqli_num_rows($sql);
        if ($num > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $review_id             = $row['id'];
                $review_name           = $row['name'];
                $review_college        = $row['college'];
                $review_photo          = $row['photo'];
                $review_review         = $row['review'];
            }
        }

        // EDIT SUCCESS STUDENT 
        $alert = '';
        if (isset($_POST['edit'])) {
            $id             = mysqli_escape_string($db, $_POST['id']);
            $name           = mysqli_escape_string($db, $_POST['name']);
            $college        = mysqli_escape_string($db, $_POST['college']);
            $review         = mysqli_escape_string($db, $_POST['review']);
            $cover_pic      = $_FILES['cover_pic']['name'];
            $cover_pic_tmp  = $_FILES['cover_pic']['tmp_name'];
            $cover_pic_size = $_FILES['cover_pic']['size'];
        
            // cover unlink path
            $previous_cover_photo = mysqli_escape_string($db, $_POST['cover_photo']);
        
            if (empty($name) || empty($college) || empty($review)) {
                $alert = "<p class='warning mb_75'>Required Fields.....</p>";
            } else {
                if (!empty($cover_pic)) {
                    $array_img = explode('.', $cover_pic);
                    $extension_img = end($array_img);
            
                    if ($extension_img == 'jpg' || $extension_img == 'png') {
                        if ($cover_pic_size <= 85000) {
                            $random_prev = rand(0, 999999);
                            $random = rand(0, 999999);
                            $random_next = rand(0, 999999);
                            $time = date('Ymdhis');
            
                            $final_img = "../assets/review/hc_".$random_prev."_".$random."_".$random_next."_".$time."_".$cover_pic;
            
                            move_uploaded_file($cover_pic_tmp, $final_img);

                            if (!empty($previous_cover_photo)) {
                                // delete previous cover photo
                                unlink($previous_cover_photo);
                            }
            
                            // update success student with photo
                            $update = "UPDATE hc_review SET name = '$name', college = '$college', photo = '$final_img', review = '$review' WHERE id = '$id'";
                        } else {
                            $alert = '<p class="danger mb_75">Cover Pic should be under 85KB</p>';
                        }
                    } else {
                        $alert = '<p class="danger mb_75">Give PNG or JPG file</p>';
                    }
                } else {
                    // update success student without photo
                    $update = "UPDATE hc_review SET name = '$name', college = '$college', photo = '$previous_cover_photo', review = '$review' WHERE id = '$id'";
                }

                if (!empty($update)) {
                    $sql_update = mysqli_query($db, $update);
                    if ($sql_update) {
                        ?>
                        <script type="text/javascript">
                            window.location.href = '../review/';
                        </script>
                        <?php 
                    }
                }
            }
        }?>
        <div class="ep_section">
            <div class="ep_container">
                <!--========== ADD COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Edit Review</h5>
                    </div>
                </div>

                <?php echo $alert; ?>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">Name*</label>
                        <input type="text" id="" name="name" placeholder="Student Name" value="<?= $review_name ?>">
                    </div>

                    <div>
                        <label for="">College Name*</label>
                        <input type="text" id="" name="college" placeholder="College Name" value="<?= $review_college ?>">
                    </div>

                    <div>
                        <label for="">Cover Photo (350*350px, max: 85kb)</label>
                        <input type="file" id="" name="cover_pic" class="input_sm">
                    </div>

                    <div class="grid_col_3">
                        <label for="">Review*</label>
                        <textarea id="" name="review" placeholder="Review" rows="4"><?= $review_review ?></textarea>
                    </div>

                    <!-- get previous cover photo to unlink easily -->
                    <input type="hidden" name="cover_photo" value="<?php echo $review_photo; ?>">
                        
                    <!-- get id -->
                    <input type="hidden" name="id" value="<?php echo $review_id; ?>">

                    <button type="submit" name="edit" class="grid_col_3">Edit</button>
                </form>
            </div>
        </div>
        <?php 
    }?>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE COURSE ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Manage Review</h5>
                    <a href="../review/?add" class="button btn_sm">Add Review</a>
                </div>

                <table class="ep_table" id="datatable">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>College</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM hc_review WHERE is_delete = 0 ORDER BY id DESC";
                        $sql = mysqli_query($db, $select);
                        $num = mysqli_num_rows($sql);
                        if ($num > 0) {
                            $si = 0;
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $review_id             = $row['id'];
                                $review_name           = $row['name'];
                                $review_college        = $row['college'];
                                $review_photo          = $row['photo'];
                                $review_review         = $row['review'];
                                $review_insert_date    = $row['insert_date'];

                                // joined date convert to text
                                $review_insert_date_text = date('d M, Y', strtotime($review_insert_date));

                                // detect path of student profile
                                if (!empty($review_photo)) {
                                    $review_profile_tmp = substr($review_photo, 2);
                                    $review_profile_img = $base_url . 'admin' . $review_profile_tmp;
                                } else {
                                    $review_profile_img = $base_url . 'admin/assets/img/admin.png';
                                }

                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td>
                                        <div class="table_img_profile">
                                            <img src="<?php echo $review_profile_img; ?>" alt="">
                                        </div>
                                    </td>

                                    <td><?php echo $review_name; ?></td>

                                    <td><?php echo $review_college; ?></td>

                                    <td>
                                        <div class="btn_grp">
                                            <!-- EDIT BUTTON -->
                                            <a href="../review/?edit_id=<?php echo $review_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>
                                        
                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $review_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                        </div>

                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="delete<?php echo $review_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $review_name; ?></span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <form action="" method="post">
                                                            <input type="hidden" name="delete_id" id="" value="<?php echo $review_id; ?>">
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

<!--=========== SELECT2 ===========-->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!--=========== DATATABLE ===========-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>

<script>
/*========= SELECT2 CUSTOM =========*/
$('#hc-select').select2({
    theme: 'bootstrap-5'
});

/*========= DATATABLE CUSTOM =========*/
$(document).ready( function () {
    $('#datatable').DataTable({
        dom: 'Bfrtip',
        // order: [[0, 'desc']],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>

<?php include('../assets/includes/footer.php'); ?>