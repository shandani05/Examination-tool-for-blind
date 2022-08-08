<!DOCTYPE html>
<html>
<?php
    session_start();
    require_once('dbconfig.php');
    $id = $_SESSION['usersession'];
    $lang = $_SESSION['lang'];
    if($id == null){
        header('Location: admin.php');
    }
    if(isset($_GET['test'])){
        $test = $_GET['test'];
        $testtitle = $lang.'-'.$test;
        $_SESSION['adminsession'] = $id;
        $_SESSION['test'] = $test;
        $_SESSION['lang'] = $lang;
        echo "<head>
                <title>MCQ Test</title>
                <link rel='stylesheet' type='text/css' href='css/bootstrap.css'>
                <link rel='stylesheet' type='text/css' href='main.css'>
                <link rel='stylesheet' type='text/css' href='css/font/flaticon.css'>
                <!--<link href='https://fonts.googleapis.com/css?family=Fira+Sans|Josefin+Sans' rel='stylesheet'>-->
                <meta charset='UTF-8'>
                <meta name='description' content='Online Exam'>
               
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>";  
            if($result1 = mysqli_query($conn,"SELECT * FROM `$lang` WHERE tests = '$test'")){
                if(mysqli_num_rows($result1) > 0){
                    while($row2 = mysqli_fetch_assoc($result1)){
                        $ttime = $row2['testtime'];
                        echo "<script>
                                function timer(){
                                    console.log(".json_encode($row2['testtime']).");
                                    var expDate = new Date().getTime();
                                    var countDown = ".json_encode($row2['testtime']).";
                                    console.log('countDown'+countDown);
                                    var countDownDate = expDate+(countDown*60000);
                                    var x = setInterval(function() {
                                    var now = new Date().getTime();
                                    var distance = countDownDate - now;
                                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                    document.getElementById('timer').innerHTML = minutes + 'm ' + seconds + 's ';
                                    if (distance < 0) {
                                        clearInterval(x);
                                        alert('time up!! click enter see you score');
                                        getScore(quiz);
                                    }
                                    }, 1000);
                                }
                            </script>
                            </head>";
                    }
                }
            }
            else{
                echo "error ".mysqli_error($conn);
            }
    }
    else{
        echo "error ".mysqli_error($conn);
    }
?>
            
                        
        
                    <body>
                        <div class="oq-header">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class=""><a href="index.php"></a></div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="oq-userArea pull-right">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="oq-viewTestsBody">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="oq-viewTests" id="test">
                                            <div class="oq-instruct">
                                                <h3 id="testHead">Online Mock Exam - Test of <?php echo $lang;?></h3><br>
                                                <h3 id="instructHead">Say the word "instructions" to listen instructions or "skip" to skip instructions</h3><br><br>
                                                <p id="spresult7" class="spresult7"></p>
                                                <p id="spresult8" class="spresult8"></p>
                                                <p id="spresult" class="spresult"></p>
                                                <p id="instructions">Instructions are,<br><br> The questions will be spoken out, you need to select the correct option by saying its option number. Example, if the correct answer is option 2, then you need to say option 2, then that particular option will be selected.<br><br> To repeat a question, you need to say the word repeat question.<br><br> You can even navigate between questions by saying the question numbers, Example,Say question 2, then question 2 will be spoken.<br><br> You can submit any time while taking the quiz, to submit you need to say submit, your test will be submitted and results are shown,<br><br>Say start to start your exam with timer<br></p><br>
                                                <p id="spresult1" class="spresult1"></p>
                                                <h4 id="speakTimer">Time remaining : <span id="timer">40 Minutes 0 seconds</span></h4>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <form action="result.php" method="post" name="oqform">
                                                    <?php
                                                        $i = 1;
                                                        if($res = mysqli_query($conn,"SELECT * FROM `$testtitle`")){
                                                            if(mysqli_num_rows($res)>0){
                                                                $rowscount = mysqli_num_rows($res);
                                                                echo "<input type='hidden' id='rowscount' name='totalQus' value='".$rowscount."'><p id='spresult2' class='spresult2'></p>";
                                                                while($row = mysqli_fetch_assoc($res)){
                                                                    $j = 0;
                                                                    echo "
                                                                        <div id='ques".$i."'>";
                                                                        if($row['guidelines']){
                                                                            echo "<span>".nl2br($row['guidelines'])."</span><br><br>";
                                                                        }
                                                                        echo "<button class='oq-aLeft oq-questions oq-form-control' disabled>Question ".$i.") ".nl2br($row['questions'])."</button><br>";
                                                                       echo "<input type='hidden' value='".$i."' id='quesNo'>";
                                                                        echo "<span class='oq-options'><input type='radio' name='qus".$i."' value='A' id='temp".$i."'> &nbsp;1) <span id='oq-options".$i.++$j."'>".nl2br($row['option1'])."</span></span><br><br>";
                                                                        echo "<span class='oq-options'><input type='radio' name='qus".$i."' value='B'> &nbsp;2) <span id='oq-options".$i.++$j."'>".nl2br($row['option2'])."</span></span><br><br>
                                                                       <span class='oq-options'><input type='radio' name='qus".$i."' value='C'> &nbsp;3) <span id='oq-options".$i.++$j."'>".nl2br($row['option3'])."</span></span><br><br>
                                                                       <span class='oq-options'><input type='radio' name='qus".$i."' value='D'> &nbsp;4) <span id='oq-options".$i.++$j."'>".nl2br($row['option4'])."<br><br><br>
                                                                       </div>
                                                                    ";
                                                                    $i++;
                                                                }
                                                                echo "<input type='submit' class='oq-btn' name='submit' id='oqsubmit' value='SUBMIT TEST'>";
                                                            }
                                                        }
                                                        else{
                                                            echo "error ".mysqli_error($conn);
                                                        }
                                                    ?>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script src="js/jquery-3.1.1.min.js"></script>
                        <script src="js/bootstrap.js"></script>
                        <!--<script src="js/script.js"></script>-->
                        <script type="text/javascript">
                           var count = document.getElementById("rowscount").value;
                            console.log(count);
                            var i=1;
                            var syn = window.speechSynthesis;
                            var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
                            var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList;
                            var SpeechRecognitionEvent = SpeechRecognitionEvent || webkitSpeechRecognitionEvent;
                            var testhead = document.getElementById("testHead").textContent;
                            var testThis = new SpeechSynthesisUtterance(testhead);
                            testThis.rate=1;
                            syn.speak(testThis);
                            testThis.onend = function(event) {
                                //console.log('Test head over. speak time:' + event.elapsedTime + ' milliseconds.');
                                instruct();
                            }

                            function instruct(){
                                var instructHead = document.getElementById("instructHead").textContent;
                                var instructThis = new SpeechSynthesisUtterance(instructHead);
                                instructThis.rate=1;
                                syn.speak(instructThis);
                                console.log('instructions');
                                instructThis.onend = function(event) {
                                    testSpeech();
                                }
                            }

                            var phrases = "instructions | instruction | skip";

                            var resultPara = document.querySelector('.spresult');
                            var resultPara7 = document.querySelector('.spresult7');
                            var resultPara8 = document.querySelector('.spresult8');

                            function testSpeech() {
                                var flag=0;
                                console.log("in testspeech");
                                var grammar = '#JSGF V1.0; grammar phrase; public <phrase> = ' + phrases +';';
                                var recognition = new SpeechRecognition();
                                var speechRecognitionList = new SpeechGrammarList();
                                speechRecognitionList.addFromString(grammar, 1);
                                recognition.grammars = speechRecognitionList;
                                recognition.lang = 'en-IN';
                                recognition.interimResults = false;
                                recognition.maxAlternatives = 1;
                                recognition.start();
                                recognition.onresult = function(event) {
                                    var speechResult = event.results[0][0].transcript;
                                    if(speechResult == "instructions" || speechResult == "instruction") {
                                        flag = 1;
                                        console.log("speech result: "+speechResult);
                                        resultPara.textContent = 'Speech received: ' + speechResult + '.';
                                        var syn = window.speechSynthesis;
                                        var testhead = document.getElementById("spresult").textContent;
                                        var testThis = new SpeechSynthesisUtterance(testhead);
                                        testThis.rate=1;
                                        syn.speak(testThis);
                                        testThis.onend = function(event) {
                                            console.log('Instructions over. speak time:' + event.elapsedTime + ' milliseconds.');
                                            startTimer();
                                        }  
                                    }
                                    else if(speechResult == "skip")
                                    {
                                       flag = 1;
                                       resultPara1.textContent = 'Speech received: ' + speechResult + '.';
                                       var syn = window.speechSynthesis;
                                       var testhead = document.getElementById("spresult1").textContent;
                                       var testThis = new SpeechSynthesisUtterance(testhead);
                                       testThis.rate=1;
                                       syn.speak(testThis);
                                       testThis.onend = function(event) {
                                           console.log('speak time:' + event.elapsedTime + ' milliseconds.');
                                           timer();
                                           readTimerQuestion();
                                        }  
                                    }
                                    else {
                                        flag = 2;
                                        console.log("speech result: "+speechResult);
                                        resultPara.textContent = 'Speech received: ' + speechResult + '. No such operation.';
                                        var syn = window.speechSynthesis;
                                        var testhead = document.getElementById("spresult").textContent;
                                        var testThis = new SpeechSynthesisUtterance(testhead);
                                        testThis.rate=1;
                                        syn.speak(testThis);
                                        testThis.onend = function(event) {
                                            console.log('No such operation over. speak time:' + event.elapsedTime + ' milliseconds.');
                                            instruct();
                                        }
                                    }
                                    console.log('Confidence: ' + event.results[0][0].confidence);
                                }
                                recognition.onend = function() {
                                    console.log("onend");
                                    if(flag == 1 || flag == 2){
                                        console.log("onend if");
                                        console.log("flag :"+flag);
                                        recognition.stop();
                                    }
                                    else{
                                        if(flag == 4){
                                            console.log("flag :"+flag);
                                            recognition.stop();
                                        }
                                        else{
                                            console.log("onend else else");
                                            recognition.stop();
                                            console.log("flag :"+flag);
                                            instruct();
                                        }
                                    }
                                }

                                recognition.onerror = function(event) {
                                    flag = 4;
                                    console.log("flag :"+flag);
                                    instruct();
                                }
                            }
                            function startTimer(){
                                var show = document.getElementById("instructions").textContent;
                                var utterThis = new SpeechSynthesisUtterance(show);
                                utterThis.rate=1;
                                syn.speak(utterThis);
                                utterThis.onend = function(event) {
                                    console.log('Instructions over. speak time:' + event.elapsedTime + ' milliseconds.');
                                    speech("start");
                                }
                            }

                            var resultPara1 = document.querySelector('.spresult1');

                            function speech(testValue){
                                var flag = 0;
                              console.log("in speech");
                              var grammar = '#JSGF V1.0; grammar phrase; public <phrase> = ' + testValue +';';
                              console.log(SpeechRecognition);
                              var recognition = new SpeechRecognition();
                              var speechRecognitionList = new SpeechGrammarList();
                              speechRecognitionList.addFromString(grammar, 1);
                              recognition.grammars = speechRecognitionList;
                              recognition.lang = 'en-IN';
                              recognition.interimResults = false;
                              recognition.maxAlternatives = 1;

                              recognition.start();

                              recognition.onresult = function(event) {
                                var speechResult = event.results[0][0].transcript;
                                if(speechResult === testValue) {
                                    flag = 1;
                                    resultPara1.textContent = 'Speech received: ' + speechResult + '.';
                                    var syn = window.speechSynthesis;
                                    var testhead = document.getElementById("spresult1").textContent;
                                    var testThis = new SpeechSynthesisUtterance(testhead);
                                    testThis.rate=1;
                                    syn.speak(testThis);
                                    testThis.onend = function(event) {
                                        console.log('speak time:' + event.elapsedTime + ' milliseconds.');
                                        timer();
                                        readTimerQuestion();
                                    }
                                } else {
                                    flag = 2;
                                    resultPara1.textContent = 'Speech received: ' + speechResult + '. No such operation.';
                                    var syn = window.speechSynthesis;
                                    var testhead = document.getElementById("spresult1").textContent;
                                    var testThis = new SpeechSynthesisUtterance(testhead);
                                    testThis.rate=1;
                                    syn.speak(testThis);
                                    testThis.onend = function(event) {
                                        console.log('speak time:' + event.elapsedTime + ' milliseconds.');
                                        instruct();
                                    }
                                }

                                console.log('Confidence: ' + event.results[0][0].confidence);
                              }

                                recognition.onend = function() {
                                    console.log("onend");
                                    if(flag == 1 || flag == 2){
                                        console.log("onend if");
                                        recognition.stop();
                                    }
                                    else{
                                        if (flag == 4) {
                                            recognition.stop();
                                        }else{
                                            console.log("onend else");
                                            recognition.stop();
                                            startTimer();
                                        }
                                        flag = 3;
                                    }
                                }

                                recognition.onerror = function(event) {
                                    if(flag != 3){
                                        flag = 4;
                                        startTimer();
                                    }
                                }
                            }

                            function timer() {
                                    var expDate = new Date().getTime();
                                    var countDownDate = expDate + 2401100;
                                    var x = setInterval(function() {
                                    var now = new Date().getTime();
                                    var distance = countDownDate - now;
                                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                        document.getElementById("timer").innerHTML = minutes + "minutes " + seconds + "seconds ";
                                        if (distance < 0) {
                                            clearInterval(x);
                                            document.getElementById("oqsubmit").click();
                                        }
                                    }, 1000);
                            }

                            var qNo = 1;

                            function readTimerQuestion(){
                                var speakTimer = document.getElementById("speakTimer").textContent;
                                var timeThis = new SpeechSynthesisUtterance(speakTimer);
                                timeThis.rate=1;
                                syn.speak(timeThis);
                                var ques = document.getElementById("ques1").textContent;
                                var quesThis = new SpeechSynthesisUtterance(ques);
                                quesThis.rate=1;
                                syn.speak(quesThis);
                                quesThis.onend = function(event) {
                                    console.log('speak time:' + event.elapsedTime + ' milliseconds.');
                                    speechQuestion(qNo,0);
                                }
                            }
                            function readQuestion(qNo){
                                var ques = document.getElementById("ques"+qNo).textContent;
                                var quesThis = new SpeechSynthesisUtterance(ques);
                                quesThis.rate=1;
                                syn.speak(quesThis);
                                quesThis.onend = function(event) {
                                    console.log('speak time:' + event.elapsedTime + ' milliseconds.');
                                    speechQuestion(qNo,0);
                                }
                            }


                            var resultPara2 = document.querySelector('.spresult2'); 
                            var navQus = 0;
                            function speechQuestion(qNo,navQus){
                                var flag = 0;
                                var l = 1;
                                var qwords = "";
                                var words = "repeat question | repeat options | option 1 | option one | option b | option c | option d | option e | next question";
                                while(l <= count){
                                    qwords = qwords + "question "+l+" | question "+inWords(l)+" | ";
                                    console.log("qwords : "+qwords);
                                    l++;
                                } 
                                qwords = qwords + words;
                                console.log("qwords "+ qwords);
                                console.log("in testspeech");
                                var grammar = '#JSGF V1.0; grammar phrase; public <phrase> = ' + qwords +';';
                                console.log(SpeechRecognition);
                                var recognition = new SpeechRecognition();
                                var speechRecognitionList = new SpeechGrammarList();
                                speechRecognitionList.addFromString(grammar, 1);
                                recognition.grammars = speechRecognitionList;
                                recognition.lang = 'en-IN';
                                recognition.interimResults = false;
                                recognition.maxAlternatives = 1;
                                recognition.start();
                                console.log("qNo(speechQus:"+qNo);
                                recognition.onresult = function(event) {
                                    var speechResult = event.results[0][0].transcript;
                                    console.log("speechResult:"+speechResult);
                                    var syn = window.speechSynthesis;
                                    var ans;
                                    var radios;
                                    console.log("no "+qNo);
                                    for(l=1;l<=count;l++){
                                        var temper = "question "+l;
                                        var temper1 = "question "+inWords(l); 
                                        console.log("temper:"+temper+" temper1:"+temper1)
                                        if(speechResult == temper || speechResult == temper1){
                                            qNo = l;
                                            flag = 1;
                                            readQuestion(qNo);
                                            break;
                                        }
                                    }
                                    if(l > count){
                                        if(speechResult == "repeat question") {
                                            flag = 1;
                                            speakQuestion(speechResult);
                                            readQuestion(qNo);
                                        }
                                        else if(speechResult == "option 1" || speechResult == "option one") {
                                            flag = 1;
                                            option(qNo,0,speechResult);
                                        }
                                        else if(speechResult == "option 2" || speechResult == "option two" || speechResult == "option to") {
                                            flag = 1;
                                            option(qNo,1,speechResult);
                                        }
                                        else if(speechResult == "option 3" || speechResult == "option three") {
                                            flag = 1;
                                            option(qNo,2,speechResult);
                                        }
                                        else if(speechResult == "option 4" || speechResult == "option four" || speechResult == "option for") {
                                            flag = 1;
                                            option(qNo,3,speechResult);
                                        }
                                        else if(speechResult == "option 5" || speechResult == "option five") {
                                            flag = 1;
                                            option(qNo,4,speechResult);
                                        }
                                        else if(speechResult == "next question"){
                                            flag = 1;
                                            qNo++;
                                            readQuestion(qNo);
                                        }
                                        else if(speechResult == "previous question"){
                                            flag = 1;
                                            qNo--;
                                            readQuestion(qNo);
                                        }
                                        else if(speechResult == "submit" || speechResult == "submit test"){
                                            flag = 1;
                                            document.getElementById("oqsubmit").click();
                                        }
                                        else{
                                            if(navQus == 0){
                                                flag = 2;
                                                console.log("ques if");
                                                resultPara2.textContent = 'Speech received: ' + speechResult + '. No such operation.';
                                                var testhead = document.getElementById("spresult2").textContent;
                                                var navQustion = new SpeechSynthesisUtterance(testhead);
                                                navQustion.rate=1;
                                                syn.speak(navQustion);
                                                navQustion.onend = function(event){
                                                    readQuestion(qNo);
                                                }
                                            }
                                            else{
                                                flag = 4;
                                                console.log("ques else");
                                                if(navQus == 1){
                                                    var navQustions;
                                                    navQustions = new SpeechSynthesisUtterance("For next question say next question");
                                                    navQustions.rate=1;
                                                }
                                                else if(navQus == 2){
                                                    var navQustions;
                                                    navQustions = new SpeechSynthesisUtterance("This is your final question. For previous question say previous question or to submit the test say submit");
                                                    navQustions.rate=1;
                                                }
                                                else if(navQus == 3){
                                                    var navQustions;
                                                    navQustions = new SpeechSynthesisUtterance("For next question say next question and for previous question say previous question");
                                                    navQustions.rate=1;
                                                }
                                                syn.speak(navQustions);
                                                navQustions.onend = function(event) {
                                                    console.log('speak time:' + event.elapsedTime + ' milliseconds.');
                                                    console.log("question no: "+qNo);
                                                    speechQuestion(qNo,navQus);
                                                }
                                            }
                                        }
                                    }

                                    console.log('Confidence: ' + event.results[0][0].confidence);
                                }


                                recognition.onend = function() {
                                    console.log("onend");
                                    if(flag == 1 || flag == 2 || flag == 4 || flag == 6){
                                        console.log("onend if");
                                        recognition.stop();
                                    }
                                    else if(navQus > 0){
                                        var navQustions;
                                        console.log("next else"+navQus);
                                        recognition.stop();
                                        if(navQus == 1){
                                            console.log("next if");
                                            navQustions = new SpeechSynthesisUtterance("For next question say next question");
                                            navQustions.rate=1;
                                            speakNav(navQustions);
                                        }
                                        else if(navQus == 2){
                                            console.log("next else if");
                                            navQustions = new SpeechSynthesisUtterance("This is your final question. For previous question say previous question or to submit the test say submit");
                                            navQustions.rate=1;
                                            speakNav(navQustions);
                                        }
                                        else if(navQus == 3){
                                            console.log("next else");
                                            navQustions = new SpeechSynthesisUtterance("For next question say next question and for previous question say previous question");
                                            navQustions.rate=1;
                                            speakNav(navQustions);   
                                        }
                                        console.log("next end");
                                    }
                                    else{
                                        if (flag == 5 || flag == 6) {
                                            recognition.stop();
                                        }
                                        else{
                                            console.log("onend else");
                                            recognition.stop();
                                            readQuestion(qNo);
                                        }
                                        flag = 3;
                                    }
                                }

                                recognition.onerror = function(event) {
                                    if(navQus > 0){
                                        flag = 6;
                                        recognition.stop();
                                        console.log("error next else");
                                        if(navQus == 1){
                                            var navQustion;
                                            navQustion = new SpeechSynthesisUtterance("For next question say next question");
                                            navQustion.rate=1;
                                        }
                                        else if(navQus == 2){
                                            var navQustion;
                                            navQustion = new SpeechSynthesisUtterance("This is your final question. For previous question say previous question or to submit the test say submit");
                                            navQustion.rate=1;
                                        }
                                        else if(navQus == 3){
                                            var navQustion;
                                            navQustion = new SpeechSynthesisUtterance("For next question say next question and for previous question say previous question"); 
                                            navQustion.rate=1;
                                        }
                                        syn.speak(navQustion);
                                        navQustion.onend = function(event) {
                                            console.log('speak time:' + event.elapsedTime + ' milliseconds.');
                                            console.log("question no: "+qNo);
                                            speechQuestion(qNo,navQus);
                                        }
                                    }
                                    else if(flag != 3){
                                        flag = 5;
                                        console.log("error next");
                                        recognition.stop();
                                        readQuestion(qNo);
                                    }
                                }
                            }


                            var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
                            var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

                            function inWords (num) {
                                if ((num = num.toString()).length > 9) return 'overflow';
                                n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
                                if (!n) return; var str = '';
                                str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
                                str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
                                str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
                                str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
                                str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) : '';
                                return str;
                            }


                            function speakNav(navQustions){
                                console.log("navQustion:"+navQustions);
                                syn.speak(navQustions);
                                navQustions.onend = function(event) {
                                    console.log('speak time:' + event.elapsedTime + ' milliseconds.');
                                    console.log("question no: "+qNo);
                                    speechQuestion(qNo,navQus);
                                }
                            }


                            function option(m,n,speechResult){
                                var navQ;
                                console.log("m "+m+" n "+n);
                                console.log("qNo(option):"+qNo);
                                speakOption(speechResult);
                                radios = document.getElementsByName('qus'+m);
                                radios[n].checked = "true";
                                console.log("oq-options"+m+""+(n+1));
                                ans = document.getElementById("oq-options"+m+""+(n+1)).innerHTML;
                                var opt = new SpeechSynthesisUtterance(ans);
                                opt.rate=1;
                                syn.speak(opt);
                                if(m == 1){
                                    navQ = new SpeechSynthesisUtterance("For next question say next question");
                                    navQ.rate=1;
                                    syn.speak(navQ);
                                    navQ.onend = function(event) {
                                        console.log('speak time:' + event.elapsedTime + ' milliseconds.');
                                        speechQuestion(m,1);
                                    }
                                }else if(m == count){
                                    navQ = new SpeechSynthesisUtterance("This is your final question. For previous question say previous question or to submit the test say submit.");
                                    navQ.rate=1;
                                    syn.speak(navQ);
                                    navQ.onend = function(event) {
                                        console.log('speak time:' + event.elapsedTime + ' milliseconds.');
                                        speechQuestion(m,2);
                                    }
                                }else{
                                    navQ = new SpeechSynthesisUtterance("For next question say next question and for previous question say previous question");
                                    navQ.rate=1;
                                    syn.speak(navQ);
                                    navQ.onend = function(event) {
                                        console.log('speak time:' + event.elapsedTime + ' milliseconds.');
                                        speechQuestion(m,3);
                                    }
                                }
                            }

                            function speakOption(speechResult){
                                resultPara2.textContent = 'Speech received: ' + speechResult + '.';
                                var syn = window.speechSynthesis;
                                var testhead = document.getElementById("spresult2").textContent;
                                var testThis = new SpeechSynthesisUtterance(testhead);
                                testThis.rate=1;
                                syn.speak(testThis);
                            }
                        </script>
                    </body>
                </html>
