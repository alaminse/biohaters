<?php include('../assets/includes/header.php'); ?>

<main>
    <?php
        $query = "SELECT id, profile FROM hc_student"; // Your query
        $result = mysqli_query($db, $query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $imageData = $row['profile'];

            $imageSizeKB = strlen($imageData) / 1024;
            if ($imageSizeKB > .1){
                // Assuming $imageData contains the relative/absolute file path
                $imageFilePath = $imageData; // Update this based on how the path is stored in your database
            
                if (file_exists($imageFilePath)) {
                    unlink($imageFilePath); // Delete the file from the folder
                    echo "Image File Deleted: $imageFilePath <br>";
                } else {
                    echo "Image File Not Found: $imageFilePath <br>";
                }
            
                // Update the database to set profile as NULL for the specific ID
                $deleteQuery = "UPDATE hc_student SET profile = NULL WHERE id = $id";
                mysqli_query($db, $deleteQuery);
            }
            
        }
    ?>
    <!-- page title -->
    <div class="ep_section">
        <div class="ep_container">
            <h4 class="welcome_admin_title">Image compression</h4>
        </div>
    </div>
    <div class="ep_section">
        <div class="ep_container">
            <!--========== MANAGE BLOG ==========-->
            <div class="mng_category">
                <div class="ep_flex mb_75">
                    
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('../assets/includes/footer.php'); ?>