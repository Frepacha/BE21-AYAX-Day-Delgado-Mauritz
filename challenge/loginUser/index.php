<?php 
    session_start();

    require_once('components/db_connect.php');
    require_once('components/boot.php');

    //if session is active, will not let you open login page
    if (isset($_SESSON['user']) != ""){
        header("Location: home.php");
        exit;
    }
    if (isset($_SESSION['adm']) != ""){
        header("Location: dashboard.php");
    }

    $error = false;
    $email = $password = $emailError = $passError = '';

    if (isset($_POST['btn-login'])){
        //prevent sql injections, and generally sanitize this data
        $email = trim($_POST['email']);
        $email = strip_tags($email);
        $email = htmlspecialchars($email);

        $pass = trim($_POST['pass']);
        $pass = strip_tags($pass);
        $pass = htmlspecialchars($pass);
        // error checking section
        if(empty($email)){ //UX improvements/ you cannot login in without your email!
            $error=true;
            $emailError = "You cannot login without your email!";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = true;
            $emailError = "Email was not valid";
        }
        if (empty($pass)){ //if password empty, tell user to input it
            $error = true;
            $passError = "Please enter your password";
        }
        //if error free, proceed with login
        if (!$error){
            $password = hash('sha256', $pass);
            $sqlSelect = "SELECT id, first_name, password, status FROm user WHERE email = ?"; // sql select
            $stmt = $connect->prepare($sqlSelect);
            $stmt->bind_param("s", $email);
            $work = $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $count = $result->num_rows;
            if ($count == 1 && $row['password'] == $password){
                if($row['status'] == 'adm'){
                    $_SESSION['adm'] = $row['id'];
                    header("Location: dashboard.php");
                } else {
                    $_SESSION['user'] = $row['id'];
                    header("Location: home.php");
                    }
                } else {
                $errMSG = "Incorrect Credentials, Try again...";
            }
        }
    }
    $connect->close();
?>

<!DOCTYPE html>
<html lang="en" >
<head>
   <meta charset="UTF-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1.0">
<title>Login & Registration System </title>
</head>
<body>
   <div class="container">
       <form class="w-75"  method="post" action= "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off" >
           <h2>LogIn</h2>
           <hr/>
            <?php
           if (isset($errMSG)) {
               echo $errMSG;
           }
           ?>
       
           <input type="email" autocomplete="off" name= "email" class="form-control"  placeholder="Your Email" value="<?php echo $email; ?>" maxlength ="40"/>
           <span class="text-danger" ><?php echo $emailError; ?></span>

           <input  type="password" name= "pass" class="form-control" placeholder="Your Password" maxlength="15"/>
           <span class= "text-danger"><?php echo $passError; ?></span>
           <hr/>
           <button class="btn btn-block btn-primary" type="submit" name ="btn-login">Sign In</button>
           <hr/>
           <a href="register.php"> Not registered yet? Click here</a>
        </form>
   </div>
</body >
</html>