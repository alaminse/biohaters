<?php include('../db/db.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!--=========== GOOGLE FONT ===========-->
    <link rel="preload" href="../assets/LiSubhaLetterpressUnicode.woff2" as="font" type="font/woff2" crossorigin>

    <!--=========== JQUERY ===========-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    
    <!--=========== FAV ICON ===========-->
    <link rel="shortcut icon" type="image/png" href="../assets/img/logo.png">

    <!--=========== KALPURUSH FONT ===========-->
    <!-- <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet"> -->

    <!--=========== SOLAIMANLIPI FONT ===========-->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    <!--=========== STYLE CSS ===========-->
    <style type="text/css">
        * {margin: 0; padding: 0;}
        
        @font-face {
            font-family: 'Li Subha Letterpress Unicode';
            src: url('../assets/LiSubhaLetterpressUnicode.woff2') format('woff2'),
                url('../assets/LiSubhaLetterpressUnicode.woff') format('woff');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
        
        body {
            max-width: 800px;
            margin: 0 auto;
            overflow-x: hidden;
            position: relative;
            font-family: 'Li Subha Letterpress Unicode' !important;
            font-weight: normal;
            font-style: normal;
        }
        
        img {
            width: 100%;
        }
        
        .token_box {
            position: relative;
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
        }
        
        .token {
            position: absolute;
            font-size: 20px;
            z-index: 100;
            bottom: 200px;
            left: 184px;
            width: 261px;
            height: 38px;
            text-align: center;
            line-height: 38px;
        }
    </style>

    <title>BH - Admin</title>
</head>
<body>
    
<?php if (isset($_POST['print'])) {
    $qty_first  = $_POST['qty_first'];
    $qty_last   = $_POST['qty_last'];
    
    // select tokens
    $select = "SELECT * FROM hc_token WHERE id >= '$qty_first' AND id <= '$qty_last' ORDER BY id ASC";
    $sql = mysqli_query($db, $select);
    $num = mysqli_num_rows($sql);
    if ($num > 0) {
        $si = 0;
        while ($row = mysqli_fetch_assoc($sql)) {
            $token = $row['token'];
            ?>
            <div class="token_box">
                <img src="../assets/img/secret-file-letter.jpg" alt="">
                <div class="token"><?= $token ?></div>
            </div>
            <?php
        }
    } 
}?>

<script>
    window.print();
</script>
</body>
</html>