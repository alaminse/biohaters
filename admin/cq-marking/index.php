<?php include('../assets/includes/header.php'); ?>

<?php if ($admin_role == 9) {
    ?>
    <script type="text/javascript">
        window.location.href = '../dashboard/';
    </script>
    <?php 
}?>

<?php if (isset($_GET['exam'])) {
    $exam = $_GET['exam'];

    if (empty($exam)) {
        ?>
        <script type="text/javascript">
            window.location.href = '../exam/';
        </script>
        <?php 
    }

    // get exam name
    $select_exam  = "SELECT * FROM hc_exam WHERE id = '$exam' AND is_delete = 0";
    $sql_exam     = mysqli_query($db, $select_exam);
    $row_exam     = mysqli_fetch_assoc($sql_exam);

    $exam_id                = $row_exam['id'];
    $exam_name              = $row_exam['name'];
    $exam_course            = $row_exam['course_id'];
    $exam_total_question    = $row_exam['total_question'];
    
    if (isset($_GET['student_id'])) {
        $student_id = $_GET['student_id'];
        
        // get student roll
        $select_roll  = "SELECT * FROM hc_student WHERE id = '$student_id'";
        $sql_roll     = mysqli_query($db, $select_roll);
        $row_roll     = mysqli_fetch_assoc($sql_roll);
        
        $student_roll = $row_roll['roll'];
    }
    
    if (isset($_POST['add'])) {
        $checked_pdf      = $_FILES['checked_pdf']['name'];
        $checked_pdf_tmp  = $_FILES['checked_pdf']['tmp_name'];
        
        $insert_date = date('Y-m-d H:i:s', time());
        
        if ($checked_pdf != '') {
            $array_pdf = explode('.', $checked_pdf);
            $extension_pdf = end($array_pdf);
    
            if ($extension_pdf == 'pdf') {
                $time = date('Ymdhis');
    
                $final_pdf = "../assets/checked_pdf/hc_".$student_roll."_".$time."_".$checked_pdf;
    
                move_uploaded_file($checked_pdf_tmp, $final_pdf);
    
                // update checked pdf
                $update = "UPDATE hc_cq_attempt SET checked_pdf = '$final_pdf' WHERE student_id = '$student_id' AND roll = '$student_roll' AND exam = '$exam_id'";
                $sql_update = mysqli_query($db, $update);
            } else {
                $alert = '<p class="danger mb_75">Give PDF file</p>';
            }
        }
        
        if (isset($_POST["reference"])) {
            foreach ($_POST["reference"] as $index => $reference) {
                $reference_text = mysqli_escape_string($db, $reference);
                $subreference   = mysqli_escape_string($db, $_POST['subreference'][$index]);
                $marking        = $_POST['marking'][$index];
                $comment        = mysqli_escape_string($db, $_POST['comment'][$index]);
                
                $main_ref = $reference_text . '.' . $subreference;
                
                // marking into the database
                $add_marking = "INSERT INTO hc_cq_marking (student_id, roll, exam, question_reference, marking, comments, author, insert_date) VALUES ('$student_id', '$student_roll', '$exam_id', '$main_ref', '$marking', '$comment', '$admin_id', '$insert_date')";
                $sql_add_marking = mysqli_query($db, $add_marking);
            }
        }?>
        <script type="text/javascript">
            window.location.href = '../cq-submit-list/?exam=<?php echo $exam_id; ?>';
        </script>
        <?php 
    }?>

<?php if (isset($_POST['delete'])) {
    $delete_id = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "DELETE FROM hc_cq_marking WHERE id = '$delete_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../cq-marking/?exam=<?php echo $exam_id; ?>&student_id=<?php echo $student_id; ?>';
        </script>
        <?php 
    }
}?>

<?php if (isset($_POST['edit'])) {
    $reference_text = mysqli_escape_string($db,$_POST['reference']);
    $subreference   = mysqli_escape_string($db, $_POST['subreference']);
    $marking        = $_POST['marking'];
    $comment        = mysqli_escape_string($db, $_POST['comment']);
    $edit_id = mysqli_escape_string($db, $_POST['edit_id']);
    
    $main_ref = $reference_text . '.' . $subreference;
    
    // marking into the database
    $edit_marking = "UPDATE hc_cq_marking SET question_reference = '$main_ref', marking = '$marking', comments = '$comment' WHERE id = '$edit_id'";
    $sql_edit_marking = mysqli_query($db, $edit_marking);
    if ($sql_edit_marking) {
        ?>
        <script type="text/javascript">
            window.location.href = '../cq-marking/?exam=<?php echo $exam_id; ?>&student_id=<?php echo $student_id; ?>';
        </script>
        <?php 
    }
}?>
<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">CQ Marking</h4>
        </div>
    </div>
    
    <!-- NOTICE LIST -->
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE NOTICE ==========-->
            <div class="mng_category mb_75">
                <div class="ep_flex mb_75">
                    <h5 class="box_title">Marking - <?= $exam_name ?> | Roll - <?= $student_roll ?></h5>
                </div>
                
                <form action="" method="post" class="" enctype="multipart/form-data">
                    <div class="mb_75">
                        <label for="">Checked PDF*</label>
                        <input type="file" id="" name="checked_pdf" class="input_sm">
                    </div>
                    
                    <table class="table table-bordered table-hover" id="tab_logic">
        				<thead>
        					<tr>
        						<th class="">SI</th>
        						<th class="">Main Question</th>
        						<th class="">Sub Question</th>
        						<th class="">Marking</th>
        						<th class="">Comments</th>
        					</tr>
        				</thead>
        				
        				<tbody>
        					<tr id='addr0'>
        						<td>1</td>
        						
        						<td>
        						    <select name='reference[]' class="form-control" required>
        						        <option value="">Choose Question</option>
        						        <option value="১">১</option>
        						        <option value="২">২</option>
        						        <option value="৩">৩</option>
        						        <option value="৪">৪</option>
        						        <option value="৫">৫</option>
        						        <option value="৭">৭</option>
        						        <option value="৮">৮</option>
        						        <option value="৯">৯</option>
        						        <option value="১০">১০</option>
        						    </select>
        						</td>
        						
        						<td>
        						    <select name='subreference[]' class="form-control">
        						        <option value="">Choose Sub-Question</option>
        						        <option value="ক">ক</option>
        						        <option value="খ">খ</option>
        						        <option value="গ">গ</option>
        						        <option value="ঘ">ঘ</option>
        						    </select>
        						</td>
        						
        						<td>
        						    <input type="text" name='marking[]' placeholder='Enter Marking' class="form-control" required>
        						</td>
        						
        						<td>
        						    <input type="text" name='comment[]' placeholder='Enter Comments' class="form-control">
        						</td>
        					</tr>
        					
                            <tr id='addr1'></tr>
        				</tbody>
        			</table>
        			
        			<div class="btn_grp mb_75">
        			    <a id="add_row" class="button btn_sm">Add Row</a><a id='delete_row' class="button btn_sm">Delete Row</a>
        			</div>
                    
                    <button type="submit" name="add">Add Mark</button>
                </form>
            </div>
            
            <div class="mng_category">
                <table class="ep_table">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Reference</th>
                            <th>Mark</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select_mark = "SELECT * FROM hc_cq_marking WHERE student_id = '$student_id' AND exam = '$exam_id' ORDER BY id ASC";
                        $sql_mark = mysqli_query($db, $select_mark);
                        $num_mark = mysqli_num_rows($sql_mark);
                        if ($num_mark == 0) {
                            echo "<tr><td colspan='5' class='text_center'>There are no marking</td></tr>";
                        } else {
                            $si = 0;
                            while ($row_mark = mysqli_fetch_assoc($sql_mark)) {
                                $mark_id        = $row_mark['id'];
                                $mark_question  = $row_mark['question_reference'];
                                $mark_marking   = $row_mark['marking'];
                                $mark_comments  = $row_mark['comments'];
                                
                                $si++;
                                ?>
                                <tr>
                                    <td><?php echo $si; ?></td>
                                    
                                    <td><?php echo $mark_question; ?></td>
                                    
                                    <td><?php echo $mark_marking; ?></td>
                                    
                                    <td><?php echo $mark_comments; ?></td>
                                    
                                    <td>
                                        <div class="btn_grp">
                                            <!-- EDIT MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#edit<?php echo $mark_id; ?>"><i class='bx bxs-edit'></i></button>
                                            
                                            <!-- DELETE MODAL BUTTON -->
                                            <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $mark_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                        </div>
                                        
                                        <!-- Edit MODAL -->
                                        <div class="modal fade" id="edit<?php echo $mark_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" method="post" class="cq_marking_modal">
                                                            <div>
                                                                <label>Main Question</label>
                                                                <select name='reference' class="form-control" required>
                                    						        <option value="">Choose Question</option>
                                    						        <option value="১">১</option>
                                    						        <option value="২">২</option>
                                    						        <option value="৩">৩</option>
                                    						        <option value="৪">৪</option>
                                    						        <option value="৫">৫</option>
                                    						        <option value="৭">৭</option>
                                    						        <option value="৮">৮</option>
                                    						        <option value="৯">৯</option>
                                    						        <option value="১০">১০</option>
                                    						    </select>
                                                            </div>
                                                            
                                                            <div>
                                                                <label>Main Question</label>
                                                                <select name='subreference' class="form-control">
                                    						        <option value="">Choose Sub-Question</option>
                                    						        <option value="ক">ক</option>
                                    						        <option value="খ">খ</option>
                                    						        <option value="গ">গ</option>
                                    						        <option value="ঘ">ঘ</option>
                                    						    </select>
                                                            </div>
                                                            
                                                            <div>
                                                                <label>Main Question</label>
                                                                <input type="text" name='marking' placeholder='Enter Marking' class="form-control w_100" required  value="<?php echo $mark_marking; ?>">
                                                            </div>
                                                            
                                                            <div class="mb_75">
                                                                <label>Main Question</label>
                                                                <input type="text" name='comment' placeholder='Enter Comments' class="form-control w_100" value="<?php echo $mark_comments; ?>">
                                                            </div>
                                                            
                                                            <input type="hidden" name="edit_id" id="" value="<?php echo $mark_id; ?>">
                                                            
                                                            <button type="submit" name="edit" class="button bg_info text_info text_semi">Edit</button>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="delete<?php echo $mark_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Do you want to delete?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                        <form action="" method="post">
                                                            <input type="hidden" name="delete_id" id="" value="<?php echo $mark_id; ?>">
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

<script>
$(document).ready(function(){
    var i=1;
    $("#add_row").click(function(){b=i-1;
        $('#addr'+i).html($('#addr'+b).html()).find('td:first-child').html(i+1);
        $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
        i++; 
    });
    $("#delete_row").click(function(){
        if(i>1){
            $("#addr"+(i-1)).html('');
            i--;
        }
    });

});
</script>

<?php } else { ?><script type="text/javascript">window.location.href = '../exam/';</script><?php } ?>

<?php include('../assets/includes/footer.php'); ?>