<?php include('../assets/includes/header.php'); ?>

<?php // chapter add
$alert = '';
if (isset($_POST['add'])) {
    $chapter    = mysqli_escape_string($db, $_POST['chapter']);
    $subject    = mysqli_escape_string($db, $_POST['subject']);
    $price      = mysqli_escape_string($db, $_POST['price']);
    $sale_price = mysqli_escape_string($db, $_POST['sale_price']);
    $status     = mysqli_escape_string($db, $_POST['status']);
    $cover_pic          = $_FILES['cover_pic']['name'];
    $cover_pic_tmp      = $_FILES['cover_pic']['tmp_name'];
    $cover_pic_size     = $_FILES['cover_pic']['size'];

    $created_date = date('Y-m-d H:i:s', time());

    if (empty($chapter) || empty($subject) || $price == '') {
        $alert = "<p class='warning mb_75'>Required Fields.....</p>";
    } else {
        if ($price < $sale_price) {
            $alert = "<p class='warning mb_75'>Sale price must be lower than Regular price.....</p>";
        } else {
            $array_img = explode('.', $cover_pic);
            $extension_img = end($array_img);

            if ($extension_img == 'jpg' || $extension_img == 'png' || $extension_img == '') {
                if ($cover_pic_size <= 60000) {
                    $random_prev = rand(0, 999999);
                    $random = rand(0, 999999);
                    $random_next = rand(0, 999999);
                    $time = date('Ymdhis');

                    $final_img = "../assets/chapter/hc_".$random_prev."_".$random."_".$random_next."_".$time."_".$cover_pic;

                    move_uploaded_file($cover_pic_tmp, $final_img);

                    // add chapter
                    $add = "INSERT INTO hc_chapter (chapter, subject, price, sale_price, cover_photo, status, author, created_date) VALUES ('$chapter', '$subject', '$price', '$sale_price', '$final_img', '$status', '$admin_id', '$created_date')";
                    $sql_add = mysqli_query($db, $add);
                    if ($sql_add) {
                        ?>
                        <script type="text/javascript">
                            window.location.href = '../chapter/';
                        </script>
                        <?php 
                    }
                } else {
                    $alert = '<p class="danger mb_75">Cover Pic should be under 60KB</p>';
                }
            } else {
                $alert = '<p class="danger mb_75">Give PNG or JPG file</p>';
            }
        }
    }
}

// chapter delete
if (isset($_POST['delete'])) {
    $delete_id      = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_chapter SET is_delete = 1 WHERE id = '$delete_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../chapter/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Chapter</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container ep_grid grid_1_2">
            <!--========== Chapter ==========-->
            <div>
                <!--========== ADD Chapter ==========-->
                <div class="add_day">
                    <h5 class="box_title">Add Chapter</h5>

                    <?php echo $alert; ?>

                    <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                        <div>
                            <label for="">Chapter*</label>
                            <input type="text" name="chapter" id="" placeholder="Chapter Name">
                        </div>

                        <div>
                            <label for="">Price*</label>
                            <input type="text" name="price" id="" placeholder="Regular Price">
                        </div>

                        <div>
                            <label for="">Sale Price</label>
                            <input type="text" name="sale_price" id="" placeholder="Sale Price">
                        </div>

                        <div>
                            <label for="">Cover Photo* (360*200px, max: 60kb)</label>
                            <input type="file" id="" name="cover_pic" class="input_sm">
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
                            <label for="">Subject*</label>
                            <select id="" name="subject">
                                <option value="">Choose Subject</option>
                                <?php $select = "SELECT * FROM hc_subject WHERE is_delete = 0 ORDER BY id DESC";
                                $sql = mysqli_query($db, $select);
                                $num = mysqli_num_rows($sql);
                                if ($num > 0) {
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        $subject_id     = $row['id'];
                                        $subject_name   = $row['subject'];

                                        echo '<option value="'.$subject_id.'">'.$subject_name.'</option>';
                                    }
                                }?>
                            </select>
                        </div>

                        <button type="submit" name="add">Add Chapter</button>
                    </form>
                </div>
                </div>

                <div>
                <!--========== MANAGE Chapter ==========-->
                <div class="mng_category">
                    <div class="ep_flex mt_75 mb_75">
                        <h5 class="box_title">Manage Chapter</h5>
                    </div>

                    <table class="ep_table">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Chapter</th>
                                <th>Subject</th>
                                <th>Lecture</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Author</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select = "SELECT * FROM hc_chapter WHERE is_delete = 0 ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num == 0) {
                                echo "<tr><td colspan='6' class='text_center'>There are no Chapter</td></tr>";
                            } else {
                                $si = 0;
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $chapter_id         = $row['id'];
                                    $chapter_name       = $row['chapter'];
                                    $chapter_subject    = $row['subject'];
                                    $chapter_price      = $row['price'];
                                    $chapter_sale       = $row['sale_price'];
                                    $chapter_status     = $row['status'];
                                    $chapter_author     = $row['author'];
                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?php echo $si; ?></td>
                                        
                                        <td><?php echo $chapter_name; ?></td>

                                        <td><?php $select_subject_name = "SELECT * FROM hc_subject WHERE id = '$chapter_subject'";
                                        $sql_subject_name = mysqli_query($db, $select_subject_name);
                                        $num_subject_name = mysqli_num_rows($sql_subject_name);
                                        $row_subject_name = mysqli_fetch_assoc($sql_subject_name);
                                        echo $row_subject_name['subject'];?></td>

                                        <td>0</td>

                                        <td><?php if (empty($chapter_sale)) {
                                            echo '<p class="text_semi">'.$chapter_price.'.00/- BDT</p></td>';
                                        } else {
                                            echo '<span class="text_sm text_strike">'.$chapter_price.'.00/- BDT</span><br>
                                            <p class="text_semi">'.$chapter_sale.'.00/- BDT</p></td>';
                                        }?></td>

                                        <td><?php if ($chapter_status == 1) {
                                            echo '<div class="ep_badge bg_success text_success">Published</div>';
                                        } else {
                                            echo '<div class="ep_badge bg_danger text_danger">Draft</div>';
                                        }?></td>

                                        <td><?php $select_chapter_author = "SELECT * FROM admin WHERE id = '$chapter_author'";
                                        $sql_chapter_author = mysqli_query($db, $select_chapter_author);
                                        $num_chapter_author = mysqli_num_rows($sql_chapter_author);
                                        $row_chapter_author = mysqli_fetch_assoc($sql_chapter_author);
                                        echo $row_chapter_author['name'];?></td>

                                        <td>
                                            <div class="btn_grp">
                                                <!-- EDIT BUTTON -->
                                                <form action="../chapter-edit/" method="post">
                                                    <input type="hidden" name="edit_id" id="" value="<?php echo $chapter_id; ?>">
                                                    <button type="submit" name="edit_chapter" class="btn_icon"><i class="bx bxs-edit"></i></button>
                                                </form>
                                                <!-- DELETE MODAL BUTTON -->
                                                <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $chapter_id; ?>"><i class="bx bxs-trash-alt"></i></button>

                                                <!-- MORE BUTTON -->
                                                <div class="hc_dropdown">
                                                    <div class="hc_dropdown_wrapper btn_icon">
                                                        <i class='bx bx-dots-vertical-rounded hc_dropdown_btn'></i>
                                                    </div>
                                                    
                                                    <div class="hc_dropdown_list">
                                                        <!-- LECTURE ADD -->
                                                        <a href="../chapter-lecture-add/?chapter=<?php echo $chapter_id; ?>">Chapter Lecture Add</a>

                                                        <!-- LECTURE LIST -->
                                                        <a href="../chapter-lecture/?chapter=<?php echo $chapter_id; ?>">Chapter Lecture List</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- DELETE MODAL -->
                                            <div class="modal fade" id="delete<?php echo $chapter_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Chapter</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $chapter_name; ?></span>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="delete_id" id="" value="<?php echo $chapter_id; ?>">
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