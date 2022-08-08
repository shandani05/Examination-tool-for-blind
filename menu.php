  <?php
    session_start();
    require_once('dbconfig.php');
    $userid = $_SESSION['usersession'];
    if($userid == null){
        header('Location: index.php');
    }
    $result=mysqli_query($conn,"SELECT * FROM user WHERE userid = '$userid'");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)) {
            $_SESSION['usersession'] = $userid;
        ?>

            <!DOCTYPE html>
            <html>
                <head>
                    <title>MCQ Test</title>
                    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
                    <link rel="stylesheet" type="text/css" href="main.css">
                    <link rel="stylesheet" type="text/css" href="css/font/flaticon.css">
                    <link href="https://fonts.googleapis.com/css?family=Fira+Sans|Josefin+Sans" rel="stylesheet">
                    <meta charset="UTF-8">
                    <meta name="description" content="Online Exam">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <?php
                        require_once('dbconfig.php');
                    ?>
                </head>
                <body>
                    <div class="oq-header">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class=""><a href="index.php"></a></div>
                                </div>
                                <div class="col-md-8">
                                    <div class="oq-userArea pull-right">
                                       
                                        <a class="btn btn-primary" href="logout.php?logout">Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="oq-menuBody">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="oq-menu">
                                        <span id="oq-subjectsList"><span class="oq-menuHead"><br><br>Welcome to Online Quiz <?php echo $row['username'];?>...</span><br><br>To know about all the subjects, say List the subjects</span>
                                        <p id="spresult" class="spresult"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script src="js/jquery-3.1.1.min.js"></script>
                    <script src="js/bootstrap.js"></script>
                    <script type="text/javascript">
                        var syn = window.speechSynthesis;
                        var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
                        var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList;
                        var SpeechRecognitionEvent = SpeechRecognitionEvent || webkitSpeechRecognitionEvent;
                        var lsub = document.getElementById("oq-subjectsList").textContent;
                        var slsub = new SpeechSynthesisUtterance(lsub);
                        slsub.rate=0.5;
                        syn.speak(slsub);
                        slsub.onend = function(event) {
                            console.log("end");
                            subjects();
                        }
                        var resultPara = document.querySelector('.spresult');
                        var word = "list the subjects | list the subject | subject | subjects";
                        function subjects(){
                            var flag=0;
                            console.log("subjects");
                            var grammar = '#JSGF V1.0; grammar phrase; public <phrase> = ' + word +';';
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
                                if(speechResult == 'list the subjects' || speechResult == 'list the subject' || speechResult == 'subject' || speechResult == 'subjects') {
                                    flag = 1;
                                    console.log("speech result: "+speechResult);
                                    resultPara.textContent = 'Speech received: ' + speechResult + '.';
                                    var syn = window.speechSynthesis;
                                    var testhead = document.getElementById("spresult").textContent;
                                    var testThis = new SpeechSynthesisUtterance(testhead);
                                    testThis.rate=0.5;
                                    syn.speak(testThis);
                                    console.log(testThis);
                                    testThis.onend = function(event) {
                                           console.log("gej");
                                           window.location = "sublist.php";
                                    }
                                }else {
                                    flag = 2;
                                    resultPara.textContent = 'Speech received: ' + speechResult + '. No such operation.';
                                    var syn = window.speechSynthesis;
                                    var noOper = document.getElementById("spresult").textContent;
                                    var noOpr = new SpeechSynthesisUtterance(noOper);
                                    noOpr.rate=0.5;
                                    syn.speak(noOpr);
                                    console.log("-else");
                                    console.log(noOpr);
                                    noOpr.onend = function(event) {
                                        console.log('No such operation over. speak time:' + event.elapsedTime + ' milliseconds.');
                                        speakSub();
                                    }
                                }
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
                                        speakSub();
                                    }
                                }
                            }
                            
                            recognition.onerror = function(event) {
                                flag = 4;
                                console.log("flag :"+flag);
                                speakSub();
                            }
                        }
                        function speakSub(){
                            console.log("in speakSub");
                            var lsub = document.getElementById("oq-subjectsList").textContent;
                            var slsub = new SpeechSynthesisUtterance(lsub);
                            slsub.rate=0.5;
                            syn.speak(slsub);
                            console.log("in speakSub");
                            slsub.onend = function(event) {
                                console.log("speekSubend");
                                subjects();
                            }
                        }
                    </script>
                </body>
            </html>

        <?php
            }
        }
?>