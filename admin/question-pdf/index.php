<?php include('../db/db.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question PDF</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!--=========== SOLAIMANLIPI FONT ===========-->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <style>
        @page  
        { 
            size: A4;
            /* this affects the margin in the printer settings */ 
            margin: 15mm 15mm 15mm 15mm;  
        }
        
        body {
            font-size: 18px;
            max-width: 1400px;
            margin: 0 auto;
            font-family: 'SolaimanLipi', sans-serif;
        }
        
        .center {
            display: flex;
            justify-content: center;
        }
        
        img {
            width: 140px;
            margin: 0 auto .5rem;
        }
        
        .box_title {
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 1.75rem;
        }
        
        .exam_attempt_container {
            /*display: grid;*/
            /*grid-template-columns: repeat(2, 1fr);*/
            /*gap: .5rem;*/
            columns: 2;
            column-gap: 1.25rem;
        }
        
        .exam_attempt_single {
            margin-bottom: 1rem;
        }
            
        .exam_attempt_single_question {
            display: flex;
            font-weight: 500;
            margin-bottom: .75rem;
            justify-content: flex-start;
            align-items: center;
            gap: .75rem;
        }
        
        .exam_attempt_single_options {
            display: grid;
            align-items: start;
            gap: .1rem;
            grid-template-columns: repeat(2, 1fr);
        }
        
        .exam_attempt_single_option {
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            column-gap: 0.75rem;
        }
        
        .red_text {
            color: red;
        }
        
        .answer_reference {
            color: #666;
            font-style: italic;
            display: grid;
            row-gap: 0.3rem;
        }
    </style>
</head>
<body>

<?php if ((isset($_GET['exam'])) && (isset($_GET['ques_type']))) {
    $exam_id = $_GET['exam'];
    $ques_type = $_GET['ques_type'];

    if (empty($exam_id) || empty($ques_type)) {
        ?>
        <script type="text/javascript">
            window.location.href = '../exam/';
        </script>
        <?php 
    }

    // fetch exam
    $select_exam = "SELECT * FROM hc_exam WHERE id = '$exam_id' AND is_delete = 0";
    $sql_exam = mysqli_query($db, $select_exam);
    $num_exam = mysqli_num_rows($sql_exam);
    if ($num_exam > 0) {
        while ($row_exam = mysqli_fetch_assoc($sql_exam)) {
            $exam_id                = $row_exam['id'];
            $exam_name              = $row_exam['name'];
            $exam_course            = $row_exam['course_id'];
            $exam_mcq               = $row_exam['mcq'];
            $exam_total_question    = $row_exam['total_question'];
            $exam_mark_per_question = $row_exam['mark_per_question'];
            $exam_negative_marking  = $row_exam['negative_marking'];
            $exam_cq                = $row_exam['cq'];
            $exam_mark              = $row_exam['mark'];
            $exam_mcq_duration      = $row_exam['mcq_duration'];
            $exam_cq_duration       = $row_exam['cq_duration'];
            $exam_valid_time        = $row_exam['valid_time'];
            $exam_date              = $row_exam['created_date'];

            $exam_date_text = date('d M, Y', strtotime($exam_date));

            // fetch exam elements
            $select_exam_elements = "SELECT * FROM hc_exam_question WHERE exam = '$exam_id' AND course = '$exam_course' AND type = 'MCQ' AND is_delete = 0 ORDER BY id ASC";
            $sql_exam_elements = mysqli_query($db, $select_exam_elements);
            $num_exam_elements = mysqli_num_rows($sql_exam_elements);
            if ($num_exam_elements > 0) {
                $examData = array(
                    'exam_id'   => $exam_id,
                    'exam_name' => $exam_name,
                );
                // Mock exam data (Replace this with data fetched from the database)
                while ($row_exam_elements = mysqli_fetch_assoc($sql_exam_elements)) {
                    $question_id    = $row_exam_elements['id'];
                    $question_title = $row_exam_elements['title'];
                    $question_topic = $row_exam_elements['topic'];
                    $question_explaination = $row_exam_elements['explaination'];
    
                    // fetch question option
                    $select_options = "SELECT * FROM hc_question_option WHERE question = '$question_id' ORDER BY id ASC";
                    $sql_options = mysqli_query($db, $select_options);
                    $num_options = mysqli_num_rows($sql_options);
                    if ($num_options > 0) {
                        $question = array(
                            'question_id' => $question_id,
                            'question_text' => $question_title,
                            'question_topic' => $question_topic,
                            'question_explaination' => $question_explaination,
                        );
                        while ($row_options = mysqli_fetch_assoc($sql_options)) {
                            $option_id      = $row_options['id'];
                            $option_title   = $row_options['option_name'];
                            $option_correct = $row_options['is_correct'];
                            $question['options'][] = array('option_id' => $option_id, 'option_text' => $option_title, 'is_correct' => $option_correct);
                        }
                    }
    
                    $examData['questions'][] = $question;
                }
            }
        }
    }?>
    <!--========== MANAGE COURSE ==========-->
    <div class="mng_category">
        <div class="center">
            <img src="../assets/img/logo.png">
        </div>
        
        <div class="ep_flex mb_75">
            <h5 class="box_title text_center"><?= $examData['exam_name']; ?></h5>
        </div>
        
        <div class="exam_attempt_container ep_grid">
            <?php $si = 0;
            foreach ($examData['questions'] as $question) { $si++; ?>
                <!--=== Single Question ===-->
                <div class="exam_attempt_single">
                    <!--=== Question ===-->
                    <input type="hidden" name="question[]" id="" value="<?= $question['question_id'] ?>">
                    <div class="exam_attempt_single_question">
                        <?= $si . '. ' . $question['question_text'] ?>
                    </div>

                    <!--=== Options ===-->
                    <div class="exam_attempt_single_options">
                        <?php $opt = range('a', 'z');
                        foreach ($question['options'] as $index => $option) { $current_opt = $opt[$index]; ?>
                            <!--=== Option ===-->
                            <div class="exam_attempt_single_option <?php if ($option['is_correct'] == 1 && $ques_type == 'answer') { echo 'red_text'; }?>">
                                <?= '(' . $current_opt . ')'; ?>
                                <label for="option_<?= $option['option_id'] ?>"><?= $option['option_text'] ?></label>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <?php if ($ques_type == 'answer') {
                        ?>
                        <div class="mt_75 answer_reference">
                            <div>টপিকঃ <?php echo $question['question_topic']; ?></div>
                            <div>ব্যাখ্যাঃ <?php echo $question['question_explaination']; ?></div>
                        </div>
                        <?php 
                    }?>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php 
} else {
    ?>
    <script type="text/javascript">
        window.location.href = '../exam/';
    </script>
    <?php 
}?>

<script>
    window.print();
</script>
</body>
</html>