<?php
    @include '../../config.php';

    session_start();

    if(!isset($_SESSION['studentUsername'])){
        header('Location: ../../login.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script>
    <link rel="stylesheet" href="results.css">
</head>
<body style="background:#f1f1f3;">
    <?php include 'studentNavbar.php' ?>  
    <?php require '../Backend/ManageResults/resultsLogic.php';?>  

<div style="margin: 1.5rem 8rem;">
                <div class="title-examSelect-container">
                    <h2>
                    <?php
                          $currentPage = basename($_SERVER['PHP_SELF']);
                          $pageTitle = str_replace(".php", "", $currentPage);
                          echo $pageTitle;  
                    ?>
                    </h2>
                    <form action="" method="post">
                        <select name="examId" id="">
                            <option value="">Exam 1</option>
                            <option value="">Exam 2</option>
                        </select>
                    </form>
               </div>
               
               <div class="examScore">
                    <span style="font-size: 20px;">Your score</span>
                    <div class="box">
                        <div class="chart" data-percent="<?php echo number_format(($scoreRow['Score']/$maxPointsRow['MaxPoints'])*100, 2)?>"><?php echo number_format(($scoreRow['Score']/$maxPointsRow['MaxPoints'])*100, 2)?>%</div>
                    </div>
                </div>

                <div>
                <div style="margin-top:2rem;"><span style="font-size:1.5rem; border-bottom:3px solid #f7b092;">Answer Key</span></div>
                
                <?php 
                    while ($answerRow = mysqli_fetch_array($answerResult)){ 
                    ?>
                        <div class="answerKeyContainer row">
                        <div class="questionContainer col-5 ">
                            <div><span>Question <?php echo $answerRow['QuestionId'] ?></span></div>
                            <div><p><?php echo $answerRow['QuestionTitle'] ?></p></div>
                        </div>
                        <div class="answersContainer col-7">
                            <?php
                            $option = 'A'; 
                            do {
                            ?>
                            <div class="<?php if (($answerRow['Status'] == '1' && $answerRow['SelectedAnswer'] == '1') || ($answerRow['Status'] == '1' && $answerRow['SelectedAnswer'] == '0')) { 
                                echo 'correct';
                                }else if(($answerRow['Status'] == '0' && $answerRow['SelectedAnswer'] == '1')){
                                echo 'incorrect';
                                }
                                elseif((($answerRow['Status'] == '1' || $answerRow['Status'] == '0')  && $answerRow['SelectedAnswer'] == '0')){
                                    echo '';
                                }
                                else{
                                    echo '';
                                }
                                ?>" style="position:relative">
                                <span class="col-auto"><?php echo $option; ?></span>
                                <p class="col-11"><?php echo $answerRow['AnswerTitle']; ?></p>
                                <span style="position:absolute; width:auto; margin:10px 0px 0 10px; border:none; bottom:0;right:0; font-size:12px"><?php if (($answerRow['Status'] == '1' && $answerRow['SelectedAnswer'] == '1') || ($answerRow['Status'] == '0' && $answerRow['SelectedAnswer'] == '1')){
                                        echo 'Your answer';
                                }
                                elseif(($answerRow['Status'] == '1' && $answerRow['SelectedAnswer'] == '0')){
                                    echo 'Correct answer';
                                }
                                else{
                                    echo '';
                                }?></span>
                            </div>
                            <?php
                                $option++; 
                            } while ($answerRow = mysqli_fetch_array($answerResult)); // Use a do-while loop
                            ?>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    
                </div>
                


</div>

</body>
<script>
      $(document).ready(function() {
            $('nav .logo-container ul li a.dashboard').removeClass('active');
            $('nav .logo-container ul li a.results').addClass('active');
            $('nav .logo-container ul li a.profile').removeClass('active');
    })
    $(function() {
    $('.chart').easyPieChart({
        size: 250,  
        barColor: "#2ed15a",
        scaleLength: 0,
        lineWidth: 10,
        trackColor: "#f5f5f5",
        lineCap: "circle",
        animate: 2000,
    });
});

// Get all the answer key divs
const answerKeyDivs = document.querySelectorAll('.answerKeyContainer .answersContainer div');

// Loop through each div and set the height of the span to match the height of the p
answerKeyDivs.forEach(div => {
  const p = div.querySelector('p');
  const span = div.querySelector('span');
  span.style.height = `${p.offsetHeight}px`;
});

</script>
</html>