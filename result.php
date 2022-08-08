<?php
    session_start();
    require_once('dbconfig.php');
    $id = $_SESSION['adminsession'];
    $lang = $_SESSION['lang'];
    $test = $_SESSION['test'];
    if($id == null){
        header('Location: admin.php');
    }
    $testtitle = $lang.'-'.$test;
    $_SESSION['adminsession'] = $id;
    $_SESSION['test'] = $test;
    if($result = mysqli_query($conn,"SELECT answer FROM `$testtitle`")){
        $quscount = mysqli_num_rows($result);
        $answer = array();
        $i=1;
        while($row1 = mysqli_fetch_assoc($result)){
            $answer[$i] = $row1['answer'];
            $i++;
        }
    }
?>
<!DOCTYPE html>
<html>
	<head>
	    <title>MCQ Test</title>
	    <link rel='stylesheet' type='text/css' href='css/bootstrap.css'>
	    <link rel='stylesheet' type='text/css' href='main.css'>
	    <link rel='stylesheet' type='text/css' href='css/font/flaticon.css'>
	    <link href='https://fonts.googleapis.com/css?family=Fira+Sans|Josefin+Sans' rel='stylesheet'>
	    <meta charset='UTF-8'>
	</head>
	<body>
		<div class="oq-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-8">
                        <div class="oq-userArea pull-right">
                                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><center><b>RESULT</b></center><br><br>    
        <div class="oq-scoreBoardBody">
        	<div class="oq-scoreBoard">
        		<div id="scoreboard">
                        <div>
                            <center><p id="retake">To take another test say the word "Menu", or say the word "STOP" to stop, or say the word "Result" to know the results.</p></center><br>
        			<p id="spresult"></p>
        		</div>
        		<?php
        			if(isset($_POST['submit'])){
				        $radio = array();
						$tQus = $_POST['totalQus'];
						for($i=1;$i<=$tQus;$i++){
				            if(!isset($_POST['qus'.$i])){
				                $radio[$i]=" ";
				            }
				            else{
				                $radio[$i]=$_POST['qus'.$i];   
				            }
				        }
				        $score = 0;
				        $wrong = 0;
				        $uans = 0;
				        $correctAns = "";
				        $wrongAns = "";
				        $qUnAns = "";
				        for($i=1;$i<=$tQus;$i++){
				            if($radio[$i] == " "){
				                $uans++;
				                $qUnAns = $qUnAns.", Question ".$i." ";
				            }
				            else if($answer[$i] == $radio[$i]){
				                $score++;
				                $correctAns = $correctAns." Question ".$i." ";
				            }
				            else{
				                $wrong++;
				                $wrongAns = $wrongAns." Question ".$i." ";
				            }
				        }
				        if($wrongAns == "")
				        	$wrongAns = "none";
				        if($qUnAns == "")
				        	$qUnAns = "none";
				        if($correctAns == "")
				        	$correctAns = "none";
				        echo "<p id='scoreboard1' style='text-align:center'>Your score : ".$score."</p>";
				        echo "<p id='scoreboard2' style='text-align:center'>Number of wrong answers : ".$wrong."</p>";
				        echo "<p id='scoreboard3' style='text-align:center'>Number of unanswered questions : ".$uans."</p>";
				        echo "<p id='scoreboard4' style='text-align:center'>Correct answered questions : ".$correctAns."</p>";
				        echo "<p id='scoreboard5' style='text-align:center'>Unanswered questions : ".$qUnAns."</p>";
				        echo "<p id='scoreboard6' style='text-align:center'>Wrong answered questions : ".$wrongAns."</p>";
					}
					?>
        		</div>
        	</div>
        </div>
		<div class="oq-footer">
        	<div class="container-fluid">
            </div>
        </div>
        <script src="js/jquery-3.1.1.min.js"></script>
        <script src="js/bootstrap.js"></script>
        <script type="text/javascript">
        	var resultPara = document.querySelector('#spresult');
        	var syn = window.speechSynthesis;
			var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
			var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList;
			var SpeechRecognitionEvent = SpeechRecognitionEvent || webkitSpeechRecognitionEvent;
			var test;
			var testThis;
                        function speakTest(){
				var words = "menu | stop | result";
				var flag=0;
				var grammar = '#JSGF V1.0; grammar phrase; public <phrase> = ' + words +';';
			    var recognition = new SpeechRecognition();
			    var speechRecognitionList = new SpeechGrammarList();
			    speechRecognitionList.addFromString(grammar, 1);
			    recognition.grammars = speechRecognitionList;
			    recognition.lang = 'en-IN';
			    recognition.interimResults = false;
			    recognition.maxAlternatives = 1;
			    recognition.start();
			    console.log(SpeechRecognition);
			    recognition.onresult = function(event) {
			        var speechResult = event.results[0][0].transcript;
			        if(speechResult == "menu") {
			        	flag = 1;
			        	window.location = 'menu.php';
			        }
			        else if(speechResult == "stop"){
			        	flag = 2;
			        	console.log("Stop");
			        }
                                else if(speechResult == "result"){
                                    flag=5;
                                    speakScore(1);
                                }
                                else  {
				        flag = 3;
				        resultPara.textContent = 'Speech received: ' + speechResult + '. No such operation.';
				        var syn = window.speechSynthesis;
				        var testhead = document.getElementById("spresult").textContent;
				        var testThis = new SpeechSynthesisUtterance(testhead);
                                        testThis.rate=1;
				        syn.speak(testThis);
				        testThis.onend = function(event) {
				            console.log('speak time:' + event.elapsedTime + ' milliseconds.');
				            retest();
				        }
				    }
                                 
			    }
			    recognition.onend = function() {
			        console.log("onend");
			        if(flag == 1 || flag == 2 || flag == 3|| flag == 5){
			        	console.log("onend if");
            			recognition.stop();
			        }else{
            			if(flag == 4){
            				recognition.stop();
            			}else{
            				console.log("onend else");
			                recognition.stop();
			                retest();
            			}
            		}
			    }
			    recognition.onerror = function(event) {
			        flag = 4;
			        console.log("flag :"+flag);
			        retest();
			    }
			}
                        function retest(){
				test = document.getElementById("retake").textContent;
				testThis = new SpeechSynthesisUtterance(test);
                                testThis.rate=1;
				syn.speak(testThis);
				testThis.onend = function(event) {
					speakTest();
				}
			}
                        retest();
			//speakScore(1);
			function speakScore(i){
				console.log("i"+i);
				test = document.getElementById("scoreboard"+i).textContent;
				testThis = new SpeechSynthesisUtterance(test);
                                testThis.rate=1;
				syn.speak(testThis);
				testThis.onend = function(event) {
					i++;
					if(i<=6){
						speakScore(i);
					}
					if(i>6){
						retest();
					}
				}
			}
        </script>
	</body>
</html>