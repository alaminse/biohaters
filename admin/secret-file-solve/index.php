<?php include('../assets/includes/header.php'); ?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Secret File Solve</h4>
        </div>
    </div>

    <?php if (isset($_GET['add'])) {
        // ADD SUCCESS STUDENT 
        $alert = '';
        if (isset($_POST['add'])) {
            $day        = $_POST['day'];
            $solve_link = mysqli_escape_string($db, $_POST['solve_link']);
        
            $created_date = date('Y-m-d H:i:s', time());
            
            // check previous solve
            $select_solve = "SELECT * FROM secret_file_solve WHERE file_set = '$day'";
            $sql_solve = mysqli_query($db, $select_solve);
            $num_solve = mysqli_num_rows($sql_solve);
            if ($num_solve > 0) {
                // update solve
                $add = "UPDATE secret_file_solve SET solve_link = '$solve_link', created_date = '$created_date' WHERE file_set = '$day'";
            } else {
                // add solve
                $add = "INSERT INTO secret_file_solve (file_set, solve_link, created_date) VALUES ('$day', '$solve_link', '$created_date')";
            }
            
            $sql_add = mysqli_query($db, $add);

            if ($sql_add) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../secret-file-solve/';
                </script>
                <?php 
            }
        }?>
        <div class="ep_section">
            <div class="ep_container">
                <!--========== ADD COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Add Solve</h5>
                    </div>
                </div>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">DAY or SET*</label>
                        <select name="day" id="" required>
                            <option value="">Choose Day</option>
                            <?php $question_set_code = 1020323001;
                            for ($i=1; $i < 112 ; $i++) { 
                                ?>
                                <option value="day<?php echo $i; ?>">Day <?php echo $i; ?> [Set Code: <?php echo $question_set_code++; ?>]</option>
                                <?php 
                            }?>
                        </select>
                    </div>
                    
                    <div></div>
                    
                    <div></div>
                    
                    <div>
                        <label for="">Solve Link*</label>
                        <textarea name="solve_link" id="" rows="5" placeholder="Google drive link" required></textarea>
                    </div>

                    <button type="submit" name="add" class="grid_col_3">Add / Update</button>
                </form>
            </div>
        </div>
        <?php 
    } else {
        ?>
        <!-- welcome message -->
        <div class="ep_section">
            <div class="ep_container">
                <!--========== MANAGE COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Manage Solve</h5>
                        <div class="btn_grp">
                            <a href="../secret-file-solve/?add" class="button btn_sm">Add Solve</a>
                        </div>
                    </div>
    
                    <table class="ep_table" id="datatable">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Day</th>
                                <th>Attachment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select = "SELECT * FROM secret_file_solve ORDER BY id DESC";
                            $sql = mysqli_query($db, $select);
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                $si = 0;
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    $solve_id   = $row['id'];
                                    $solve_day  = $row['file_set'];
                                    $solve_link = $row['solve_link'];
                                    $solve_date = $row['created_date'];
    
                                    // joined date convert to text
                                    $solve_date_text = date('d M, Y', strtotime($solve_date));
    
                                    $si++;
                                    ?>
                                    <tr>
                                        <td><?php echo $si; ?></td>
    
                                        <td><?php echo $solve_day; ?></td>
    
                                        <td><a href="<?php echo $solve_link; ?>" class="ep_badge bg_success text_success">View</a></td>
    
                                        <td><?php echo $solve_date_text; ?></td>
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