<?php
    session_start();
    require_once('dbconfig.php');
    $fail =0;
    if(isset($_POST['login'])){
        $uid = $_POST['userid'];
        $upass = $_POST['userpass'];
        $password = md5($upass);
        $result=mysqli_query($conn,"SELECT * FROM user WHERE userid = '$uid' AND password = '$password'");
        if(mysqli_num_rows($result) > 0){
            $_SESSION['usersession'] = $uid;
            header("Location: menu.php");
        }
        else{
            $fail = 1;
        }
    }
    if(isset($_SESSION['usersession'])){
        header("Location: menu.php"); 
    }
    if(isset($_SESSION['adminsession'])){
        header("Location: adminmenu.php"); 
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>MCQ Test</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="main.css">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans|Josefin+Sans" rel="stylesheet" >
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
                    <div class="col-md-2 pull-right">
                        <div class="oq-adminArea">
                            <a class="oq-admin" href="admin.php"><br><br>Admin Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="oq-indexBody">
            <div class="container-fluid">
                <div class="row">
                        <div class="oq-bodyContent">
                            <h1><center>Welcome to Online Test</center></h1>
                            <p><center>This site offers tests on  various subjects of interest. You must log in to take the online test.</center></p>
                        </div>
                    </div>
                    <div class="row col-md-offset-1" style="width:50%;margin-left: 350px;">
                        <div class="oq-login text-center">
                           <br><br>
                            <form class="form" action="" method="post">
                                <?php
                                    if($fail == 1){
                                        echo "<span class='oq-error'>*Incorrect details</span><br><br>";
                                    }
                                    if(isset($_GET['signup'])){
                                        echo "<span class='oq-success'>Signup successful please login</span><br><br>";
                                    }
                                ?>
                                <input type="text" class="form-control" placeholder="Enter your Login ID" name="userid"><br>
                                <input type="password" class="form-control" placeholder="Enter your Password" name="userpass"><br>
                                <input type="submit" class="form-control oq-btn" value="Login" name="login"><br><br>
                                New user? <a href="signup.php" class="">Signup for New Account</a><br><br>
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="js/jquery-3.1.1.min.js"></script>
        <script src="js/bootstrap.js"></script>
        
    </body>
    
</html>