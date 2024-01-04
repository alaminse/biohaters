<?php include('../assets/includes/header.php'); ?>

<main>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Combine List</h4>
        </div>
    </div>

    <!-- welcome message -->
    <div class="ep_section">
        <div class="ep_container">
            <table class="ep_table" id="datatable">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th>Roll</th>
                        <th>Total Mark Obtained</th>
                        <th>College</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_GET['list'])) {
                        $get_list_id = $_GET['list'];
                        
                        // fetch list
                        $select_list = "SELECT * FROM hc_combined_list WHERE id = '$get_list_id' AND is_delete = 0 ORDER BY id DESC";
                        $sql_list = mysqli_query($db, $select_list);
                        $num_list = mysqli_num_rows($sql_list);
                        if ($num_list > 0) {
                            while ($row_list = mysqli_fetch_assoc($sql_list)) {
                                $list_id      = $row_list['id'];
                                
                                // fetch list data
                                $select_list_data = "SELECT * FROM hc_combined_result WHERE combined_list_id = '$list_id' ORDER BY rank ASC";
                                $sql_list_data = mysqli_query($db, $select_list_data);
                                $num_list_data = mysqli_num_rows($sql_list_data);
                                if ($num_list_data > 0) {
                                    while ($row_list_data = mysqli_fetch_assoc($sql_list_data)) {
                                        $list_data_id       = $row_list_data['id'];
                                        $list_data_rank     = $row_list_data['rank'];
                                        $list_data_name     = $row_list_data['name'];
                                        $list_data_roll     = $row_list_data['roll'];
                                        $list_data_marking  = $row_list_data['marking'];
                                        $list_data_college  = $row_list_data['college'];
                                        ?>
                                        <tr>
                                            <td><?= $list_data_rank ?></td>
                                            <td><?= $list_data_name ?></td>
                                            <td><?= $list_data_roll ?></td>
                                            <td><?= $list_data_marking ?></td>
                                            <td><?= $list_data_college ?></td>
                                        </tr>
                                        <?php 
                                    }
                                }
                            }
                        } else {
                            ?>
                            <script type="text/javascript">
                                window.location.href = '../result-combine/?list';
                            </script>
                            <?php 
                        }
                    }?>
                </tbody>
            </table>
        </div>
    </div>
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
    $('#datatable').DataTable( {
        dom: 'Bfrtip',
        // order: [[0, 'desc']],
        pageLength: 25,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>

<?php include('../assets/includes/footer.php'); ?>