<?php include('../assets/includes/header.php'); ?>

<!-- DELETE COURSE -->
<?php if (isset($_POST['delete'])) {
    $token_id = mysqli_escape_string($db, $_POST['delete_id']);

    $delete = "UPDATE hc_token SET is_delete = 1 WHERE id = '$token_id'";
    $sql_delete = mysqli_query($db, $delete);
    if ($sql_delete) {
        ?>
        <script type="text/javascript">
            window.location.href = '../secret-file-token/';
        </script>
        <?php 
    }
}?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Secret File Token</h4>
        </div>
    </div>

    <?php if (isset($_GET['add'])) {
        // ADD SUCCESS STUDENT 
        $alert = '';
        if (isset($_POST['add'])) {
            $qty = mysqli_escape_string($db, $_POST['qty']);
        
            $created_date = date('Y-m-d H:i:s', time());

            for ($t = 1; $t <= $qty; $t++) { 
                do {
                    // generate token
                    $tokenLength = 20; // Length of the token in bytes

                    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz123456789';
                    $charactersLength = strlen($characters);
                    $token = '';

                    for ($i = 0; $i < $tokenLength; $i++) {
                        $token .= $characters[random_int(0, $charactersLength - 1)];
                    }
                
                    // check token
                    $check_token = "SELECT * FROM hc_token WHERE token = '$token'";
                    $sql_check_token = mysqli_query($db, $check_token);
                    $num_check_token = mysqli_num_rows($sql_check_token);
                } while ($num_check_token != 0);
            
                // add success student with photo
                $add = "INSERT INTO hc_token (token, author, created_date) VALUES ('$token', '$admin_id', '$created_date')";
                $sql_add = mysqli_query($db, $add);
            }

            if ($sql_add) {
                ?>
                <script type="text/javascript">
                    window.location.href = '../secret-file-token/';
                </script>
                <?php 
            }
        }?>
        <div class="ep_section">
            <div class="ep_container">
                <!--========== ADD COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Add Token</h5>
                    </div>
                </div>

                <form action="" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">How much you want?*</label>
                        <input type="text" id="" name="qty" placeholder="Quantity" required>
                    </div>

                    <button type="submit" name="add" class="grid_col_3">Add</button>
                </form>
            </div>
        </div>
        <?php 
    } elseif (isset($_GET['print'])) {
        ?>
        <div class="ep_section">
            <div class="ep_container">
                <!--========== ADD COURSE ==========-->
                <div class="mng_category">
                    <div class="ep_flex mb_75">
                        <h5 class="box_title">Print Token</h5>
                    </div>
                </div>

                <form action="../secret-file-token-print/" method="post" class="double_col_form" enctype="multipart/form-data">
                    <div>
                        <label for="">First Serial?*</label>
                        <input type="text" id="" name="qty_first" placeholder="First Serial" required>
                    </div>
                    
                    <div>
                        <label for="">Last Serial?*</label>
                        <input type="text" id="" name="qty_last" placeholder="Last Serial" required>
                    </div>

                    <button type="submit" name="print" class="grid_col_3">Print</button>
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
                        <h5 class="box_title">Manage Token</h5>
                        <div class="btn_grp">
                            <a href="../secret-file-token/?print" class="button btn_sm">Print Token</a>
                            <a href="../secret-file-token/?add" class="button btn_sm">Add Token</a>
                        </div>
                    </div>
                    
                    <div class="mb_75">
                        <form action="" method="get" class="double_col_form" enctype="multipart/form-data">
                            <div>
                                <label for="">Search Token*</label>
                                <input type="text" id="search-token" name="search_token" placeholder="Token">
                            </div>
                            
                            <button type="submit" name="search">Search</button>
            			</form>
                    </div>
    
                    <table class="ep_table">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Token</th>
                                <th>Author</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($_GET['search'])) {
                                $search_token = $_GET['search_token'];
                            
                                $search_data = "SELECT * FROM hc_token WHERE token LIKE '%$search_token%' AND is_delete = 0 ORDER BY id DESC";
                                $search_sql = mysqli_query($db, $search_data);
                                $search_num = mysqli_num_rows($search_sql);
                            
                                if ($search_num > 0) {
                                    $si = 0;
                                    while ($search = mysqli_fetch_assoc($search_sql)) {
                                        $token_id           = $search['id'];
                                        $token_name         = $search['token'];
                                        $token_author       = $search['author'];
                                        $token_created_date = $search['created_date'];
                                        
                                        // joined date convert to text
                                        $token_created_date_text = date('d M, Y', strtotime($token_created_date));
                            
                                        $si++;
                                        ?>
                                        <tr>
                                            <td><?php echo $si; ?></td>
        
                                            <td><?php echo $token_name; ?></td>
        
                                            <td><?php echo $token_author; ?></td>
        
                                            <td><?php echo $token_created_date_text; ?></td>
        
                                            <td>
                                                <div class="btn_grp">
                                                    <!-- DELETE MODAL BUTTON -->
                                                    <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $token_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                                </div>
        
                                                <!-- DELETE MODAL -->
                                                <div class="modal fade" id="delete<?php echo $token_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $token_name; ?></span>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                                <form action="" method="post">
                                                                    <input type="hidden" name="delete_id" id="" value="<?php echo $token_id; ?>">
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
                                } else {
                                    echo '<tr><td colspan="5">No data in this database.....</td></tr>';
                                }
                            }?>
                            
                            <?php if (isset($_GET['unused'])) {
                                // fetch done token
                                $select_token = "SELECT * FROM hc_secret_file_entry ORDER BY id ASC";
                                $sql_token = mysqli_query($db, $select_token);
                                $num_token = mysqli_num_rows($sql_token);
                                if ($num_token == 0) {
                                    $token_array = '0';
                                } else {
                                    $token_array = '';
                                    while ($row_token = mysqli_fetch_assoc($sql_token)) {
                                        $token = $row_token['token'];
                                        $token_array = '"' . $token . '",' . $token_array;
                                    }
    
                                    $token_array = substr($token_array, 0, -1);
                                }
                                
                                $search_data = "SELECT * FROM hc_token WHERE token NOT IN ($token_array) AND is_delete = 0 ORDER BY id DESC LIMIT 1";
                                $search_sql = mysqli_query($db, $search_data);
                                $search_num = mysqli_num_rows($search_sql);
                            
                                if ($search_num > 0) {
                                    $si = 0;
                                    while ($search = mysqli_fetch_assoc($search_sql)) {
                                        $token_id           = $search['id'];
                                        $token_name         = $search['token'];
                                        $token_author       = $search['author'];
                                        $token_created_date = $search['created_date'];
                                        
                                        // joined date convert to text
                                        $token_created_date_text = date('d M, Y', strtotime($token_created_date));
                            
                                        $si = $token_id;
                                        ?>
                                        <tr>
                                            <td><?php echo $si; ?></td>
        
                                            <td><?php echo $token_name; ?></td>
        
                                            <td><?php echo $token_author; ?></td>
        
                                            <td><?php echo $token_created_date_text; ?></td>
        
                                            <td>
                                                <div class="btn_grp">
                                                    <!-- DELETE MODAL BUTTON -->
                                                    <button type="button" class="btn_icon" data-bs-toggle="modal" data-bs-target="#delete<?php echo $token_id; ?>"><i class="bx bxs-trash-alt"></i></button>
                                                </div>
        
                                                <!-- DELETE MODAL -->
                                                <div class="modal fade" id="delete<?php echo $token_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Product Category</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Do you want to delete <span class ="ep_p text_semi bg_danger text_danger"><?php echo $token_name; ?></span>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="button btn_trp" data-bs-dismiss="modal">Close</button>
                                                                <form action="" method="post">
                                                                    <input type="hidden" name="delete_id" id="" value="<?php echo $token_id; ?>">
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
                                } else {
                                    echo '<tr><td colspan="5">No data in this database.....</td></tr>';
                                }
                            }?>
                        </tbody>
                    </table>
                    
                    <div class="btn_grp">
                        <a href="../secret-file-token/?unused" class="button btn_sm">Give A Unused Token</a>
                    </div>
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