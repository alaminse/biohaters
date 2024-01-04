<?php include('../assets/includes/header.php'); ?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Marked Book PDF</h4>
        </div>
    </div>
    
    <?php if ((isset($_GET['chapter'])) && $_GET['chapter'] != '') {
        $chapter = $_GET['chapter'];
        
        // select chapter data
        $select_chapter = "SELECT * FROM hc_marked_book_chapter WHERE id = '$chapter' AND is_delete = 0 ORDER BY id ASC";
        $sql_chapter = mysqli_query($db, $select_chapter);
        $num_chapter = mysqli_num_rows($sql_chapter);
        if ($num_chapter > 0) {
            while ($row_chapter = mysqli_fetch_assoc($sql_chapter)) {
                $chapter_id         = $row_chapter['id'];
                $chapter_subject    = $row_chapter['subject'];
                $chapter_name       = $row_chapter['chapter'];
            }
        }
        
        if (isset($_POST['delete'])) {
            $pdf_id = mysqli_escape_string($db, $_POST['delete_id']);
        
            $delete = "UPDATE hc_marked_book_pdf SET is_delete = 1 WHERE id = '$pdf_id'";
            $sql_delete = mysqli_query($db, $delete);
            if ($sql_delete) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../marked-book-pdf/?chapter=<?php echo $chapter_id; ?>';
                </script>
                <?php 
            }
        }?>
        <!-- welcome message -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== MANAGE BLOG ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Manage PDF - <?php echo $chapter_name; ?></h5>
                        
                        <div class="btn_grp">
                            <a href="../marked-book-pdf/?chapter=<?php echo $chapter_id; ?>&add" class="button btn_sm">Add</a>
                        </div>
                    </div>
                    
                    <?php if (isset($_GET['add'])) {
                        if (isset($_POST['add'])) {
                            $pdf_link   = mysqli_escape_string($db, $_POST['pdf_link']);
                            $solve_link = mysqli_escape_string($db, $_POST['solve_link']);
                            $class_link = mysqli_escape_string($db, $_POST['class_link']);
                            $writer     = $_POST['writer'];
                            $created_date = date('Y-m-d H:i:s', time());
                            $scheduled = date('Y-m-d H:i:s', strtotime($_POST['scheduled']));
                            // insert pdf
                            $insert = "INSERT INTO hc_marked_book_pdf (chapter, writer, marked_doc, mcq_solve, class_video, author, created_date, scheduled) VALUES ('$chapter_id', '$writer', '$pdf_link', '$solve_link', '$class_link', '$admin_id', '$created_date', '$scheduled')";
                            $sql_insert = mysqli_query($db, $insert);
                            if ($sql_insert) {
                                ?>
                                <script type="text/javascript">
                                    window.location.href = '../marked-book-pdf/?chapter=<?php echo $chapter_id; ?>';
                                </script>
                                <?php 
                            }
                        }?>
                        <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                            <div>
                                <label for="">Writer*</label>
                                <select id="" name="writer" required>
                                    <option value="">Choose Writer</option>
                                    <?php if ($chapter_subject == 'উদ্ভিদবিজ্ঞান' || $chapter_subject == 'প্রাণীবিজ্ঞান') {
                                        ?>
                                        <option value="হাসান স্যার">হাসান স্যার</option>
                                        <option value="আজিবুর স্যার">আজিবুর স্যার</option>
                                        <option value="আজমল স্যার">আজমল স্যার</option>
                                        <option value="আলীম স্যার">আলীম স্যার</option>
                                        <option value="মাজেদা ম্যাম">মাজেদা ম্যাম</option>
                                        <?php 
                                    } elseif ($chapter_subject == 'পদার্থবিজ্ঞান প্রথম পত্র' || $chapter_subject == 'পদার্থবিজ্ঞান দ্বিতীয় পত্র') {
                                        ?>
                                        <option value="ইসহাক স্যার">ইসহাক স্যার</option>
                                        <option value="তপন স্যার">তপন স্যার</option>
                                        <option value="তোফাজ্জল স্যার">তোফাজ্জল স্যার</option>
                                        <?php 
                                    } elseif ($chapter_subject == 'English') {
                                        ?>
                                        <option value="NCTB text book">NCTB text book</option>
                                        <?php 
                                    } elseif ($chapter_subject == 'রসায়ন প্রথম পত্র' || $chapter_subject == 'রসায়ন দ্বিতীয় পত্র') {
                                        ?>
                                        <option value="হাজারী স্যার">হাজারী স্যার</option>
                                        <option value="কবির স্যার">কবির স্যার</option>
                                        <option value="গুহ স্যার">গুহ স্যার</option>
                                        <?php 
                                    }?>
                                </select>
                            </div>
    
                            <div>
                                <label for="">Marked PDF Link*</label>
                                <textarea id="" name="pdf_link" placeholder="Marked PDF Link" rows="2"></textarea>
                            </div>
                            
                            <div>
                                <label for="">MCQ Solve PDF Link*</label>
                                <textarea id="" name="solve_link" placeholder="MCQ Solve PDF Link" rows="2"></textarea>
                            </div>
                            
                            <div>
                                <label for="">Youtube Video Link*</label>
                                <textarea id="" name="class_link" placeholder="Youtube Video Link" rows="2"></textarea>
                            </div>
                            <div>
                                <label for="">Scheduled Time*</label>
                                <input type="datetime-local" id="" name="scheduled">
                            </div>
    
                            <button type="submit" name="add">Add</button>
                        </form>
                        <?php 
                    } elseif (isset($_GET['edit'])) {
                        $edit_id = $_GET['edit'];
                        
                        // fetch edit info
                        $select_edit_info = "SELECT * FROM hc_marked_book_pdf WHERE id = '$edit_id' AND is_delete = 0 ORDER BY id ASC";
                        $sql_edit_info = mysqli_query($db, $select_edit_info);
                        $num_edit_info = mysqli_num_rows($sql_edit_info);
                        if ($num_edit_info > 0) {
                            while ($row_edit_info = mysqli_fetch_assoc($sql_edit_info)) {
                                $edit_info_writer   = $row_edit_info['writer'];
                                $edit_info_doc      = $row_edit_info['marked_doc'];
                                $edit_info_solve    = $row_edit_info['mcq_solve'];
                                $edit_info_class    = $row_edit_info['class_video'];
                                $scheduled          = $row_edit_info['scheduled'];
                            }
                        }
                        
                        if (isset($_POST['edit'])) {
                            $pdf_link   = mysqli_escape_string($db, $_POST['pdf_link']);
                            $solve_link = mysqli_escape_string($db, $_POST['solve_link']);
                            $class_link = mysqli_escape_string($db, $_POST['class_link']);
                            $writer     = $_POST['writer'];
                            $scheduled     = $_POST['scheduled'];
                            
                            // edit pdf
                            $edit = "UPDATE hc_marked_book_pdf SET writer = '$writer', marked_doc = '$pdf_link', mcq_solve = '$solve_link', class_video = '$class_link', scheduled = '$scheduled' WHERE id = '$edit_id'";
                            $sql_edit = mysqli_query($db, $edit);
                            if ($sql_edit) {
                                ?>
                                <script type="text/javascript">
                                    window.location.href = '../marked-book-pdf/?chapter=<?php echo $chapter_id; ?>';
                                </script>
                                <?php 
                            }
                        }?>
                        <form action="" method="post" class="single_col_form" enctype="multipart/form-data">
                            <div>
                                <label for="">Writer*</label>
                                <select id="" name="writer" required>
                                    <option value="">Choose Writer</option>
                                    <?php if ($chapter_subject == 'উদ্ভিদবিজ্ঞান' || $chapter_subject == 'প্রাণীবিজ্ঞান') {
                                        ?>
                                        <option value="হাসান স্যার" <?php if ($edit_info_writer == 'হাসান স্যার') { echo 'selected'; }?>>হাসান স্যার</option>
                                        <option value="আজিবুর স্যার" <?php if ($edit_info_writer == 'আজিবুর স্যার') { echo 'selected'; }?>>আজিবুর স্যার</option>
                                        <option value="আজমল স্যার" <?php if ($edit_info_writer == 'আজমল স্যার') { echo 'selected'; }?>>আজমল স্যার</option>
                                        <option value="আলীম স্যার" <?php if ($edit_info_writer == 'আলীম স্যার') { echo 'selected'; }?>>আলীম স্যার</option>
                                        <option value="মাজেদা ম্যাম" <?php if ($edit_info_writer == 'মাজেদা ম্যাম') { echo 'selected'; }?>>মাজেদা ম্যাম</option>
                                        <?php 
                                    } elseif ($chapter_subject == 'পদার্থবিজ্ঞান প্রথম পত্র' || $chapter_subject == 'পদার্থবিজ্ঞান দ্বিতীয় পত্র') {
                                        ?>
                                        <option value="ইসহাক স্যার" <?php if ($edit_info_writer == 'ইসহাক স্যার') { echo 'selected'; }?>>ইসহাক স্যার</option>
                                        <option value="তপন স্যার" <?php if ($edit_info_writer == 'তপন স্যার') { echo 'selected'; }?>>তপন স্যার</option>
                                        <option value="তোফাজ্জল স্যার" <?php if ($edit_info_writer == 'তোফাজ্জল স্যার') { echo 'selected'; }?>>তোফাজ্জল স্যার</option>
                                        <?php 
                                    } elseif ($chapter_subject == 'রসায়ন প্রথম পত্র' || $chapter_subject == 'রসায়ন দ্বিতীয় পত্র') {
                                        ?>
                                        <option value="হাজারী স্যার" <?php if ($edit_info_writer == 'হাজারী স্যার') { echo 'selected'; }?>>হাজারী স্যার</option>
                                        <option value="কবির স্যার" <?php if ($edit_info_writer == 'কবির স্যার') { echo 'selected'; }?>>কবির স্যার</option>
                                        <option value="গুহ স্যার" <?php if ($edit_info_writer == 'গুহ স্যার') { echo 'selected'; }?>>গুহ স্যার</option>
                                        <?php 
                                    }?>
                                </select>
                            </div>
    
                            <div>
                                <label for="">PDF Link*</label>
                                <textarea id="" name="pdf_link" placeholder="PDF Link" rows="2"><?php echo $edit_info_doc; ?></textarea>
                            </div>
                            
                            <div>
                                <label for="">MCQ Solve PDF Link*</label>
                                <textarea id="" name="solve_link" placeholder="MCQ Solve PDF Link" rows="2"><?php echo $edit_info_solve; ?></textarea>
                            </div>
                            
                            <div>
                                <label for="">Youtube Video Link*</label>
                                <textarea id="" name="class_link" placeholder="Youtube Video Link" rows="2"><?php echo $edit_info_class; ?></textarea>
                            </div>           
                            <div>
                                <label for="">Scheduled Time*</label>
                                <input type="datetime-local" id="" name="scheduled" value="<?php echo $scheduled; ?>">
                            </div>
    
                            <button type="submit" name="edit">Add</button>
                        </form>
                        <?php 
                    } else {
                        ?>
                        <table class="ep_table">
                            <thead>
                                <tr>
                                    <th>SI</th>
                                    <th>Chapter</th>
                                    <th>Writer</th>
                                    <th>Marked Book</th>
                                    <th>Solve PDF</th>
                                    <th>Solve Class</th>
                                    <th>Status</th>
                                    <th>Author</th>
                                    <th>Publish Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $select = "SELECT * FROM hc_marked_book_pdf WHERE chapter = '$chapter' AND is_delete = 0 ORDER BY id ASC";
                                $sql = mysqli_query($db, $select);
                                $num = mysqli_num_rows($sql);
                                if ($num == 0) {
                                    echo "<tr><td colspan='7' class='text_center'>There are no PDF</td></tr>";
                                } else {
                                    $si = 0;
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        $mark_book_id           = $row['id'];
                                        $mark_book_writer       = $row['writer'];
                                        $mark_book_doc          = $row['marked_doc'];
                                        $mark_book_solve        = $row['mcq_solve'];
                                        $mark_book_class        = $row['class_video'];
                                        $scheduled              = $row['scheduled'];
                                        $mark_book_author       = $row['author'];
                                        $mark_book_created_date = $row['created_date'];
                                        $si++;
        
                                        $mark_book_created_date = date('d M Y', strtotime($mark_book_created_date));
                                        ?>
                                        <tr>
                                            <td><?php echo $si; ?></td>
                                            
                                            <td><?php echo $chapter_name; ?></td>
                                            
                                            <td><?php echo $mark_book_writer; ?></td>
                                            
                                            <td><?php if ($mark_book_doc != '') {
                                                ?>
                                                <a href="<?php echo $mark_book_doc; ?>" target="_blank" class="ep_badge bg_success text_success">View</a>
                                                <?php
                                            } else { echo '--'; }?></td>
                                            
                                            <td><?php if ($mark_book_solve != '') {
                                                ?>
                                                <a href="<?php echo $mark_book_solve; ?>" target="_blank" class="ep_badge bg_info text_info">View</a>
                                                <?php
                                            } else { echo '--'; }?></td>
                                            
                                            <td><?php if ($mark_book_class != '') {
                                                ?>
                                                <a href="<?php echo $mark_book_class; ?>" target="_blank" class="ep_badge bg_danger text_danger">View</a>
                                                <?php
                                            } else { echo '--'; }?></td>
        
                                            <td>
                                                <?php
                                                $old_check = date('Y-m-d h:i:s A', strtotime('2023-12-03 17:00:00'));
                                                $now = date('Y-m-d h:i:s A', time());
                                                $marked_book_scheduled = date('Y-m-d h:i:s A', strtotime($scheduled));
                                                
                                                if ($scheduled) {
                                                    if ($now >= $marked_book_scheduled) {
                                                        echo '<div class="ep_badge bg_success text_success">Published</div>';
                                                    } else {
                                                        echo '<div class="ep_badge bg_info text_info">Scheduled : ' . $marked_book_scheduled . '</div>';
                                                    }
                                                } elseif($now >= $old_check){
                                                    echo '<div class="ep_badge bg_success text_success">Published</div>';
                                                } else {
                                                    echo '<div class="ep_badge bg_danger text_danger">Draft</div>';
                                                }?>
                                            </td>

                                            <td><?php echo $scheduled; ?></td>
                                            <td><?php $select_mark_book_author = "SELECT * FROM admin WHERE id = '$mark_book_author'";
                                            $sql_mark_book_author = mysqli_query($db, $select_mark_book_author);
                                            $num_mark_book_author = mysqli_num_rows($sql_mark_book_author);
                                            $row_mark_book_author = mysqli_fetch_assoc($sql_mark_book_author);
                                            echo $row_mark_book_author['name'];?></td>
                                            
                                            <td><?php echo $mark_book_created_date; ?></td>
        
                                            <td>
                                                <div class="btn_grp">
                                                    <!-- EDIT BUTTON -->
                                                    <a href="../marked-book-pdf/?chapter=<?php echo $chapter_id; ?>&edit=<?php echo $mark_book_id; ?>" class="btn_icon"><i class="bx bxs-edit"></i></a>
                                                    
                                                    <!-- DELETE MODAL BUTTON -->
                                                    <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $mark_book_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                                </div>
                                                
                                                <!-- DELETE MODAL -->
                                                <div class="modal fade" id="delete<?php echo $mark_book_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Mark PDF</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $chapter_name.' - '.$mark_book_writer; ?></span>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                                <form action="" method="post">
                                                                    <input type="hidden" name="delete_id" id="" value="<?php echo $mark_book_id; ?>">
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
                        <?php 
                    }?>
                </div>
            </div>
        </div>
        <?php 
    } else { ?><script type="text/javascript">window.location.href = '../marked-book-pdf/?chapter=<?php echo $chapter_id; ?>';</script><?php }?>
</main>

<?php include('../assets/includes/footer.php'); ?>