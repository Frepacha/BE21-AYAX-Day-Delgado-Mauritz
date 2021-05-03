<?php 
    session_start();
    // if (isset($_SESSION['user']) != ""){
    //     header("Location: home.php");
    // }
    // if (isset($_SESSION['adm']) != ""){
    //     header("Location: dashboard.php");
    // }
    require_once('components/db_connect.php');
    require_once('components/file_upload.php');
    $error = false;
    $fname = $lname = $email = $date_of_birth = $pass = $picture = '';
    $fnameError = $lnameError = $emailError = $dateError = $passError = $picError = '';
    if (isset($_POST['btn-signup'])){

        // //sanitizes data to prevent sql injection by stripping all white space on either end of the characters
        // $fname = trim($_POST['fname']);
        // //then remove php html tags from the string, for security again
        // $fname = strip_tags($fname);
        // //rerenders the string with like different html tags or so nt 100% sure, security
        // $fname = htmlspecialchars($fname);

        function sanitizeData($variable){
            $result = trim($variable);
            $result = strip_tags($result);
            $result = htmlspecialchars($result);
            return $result;
        }

        $fname = sanitizeData($_POST['fname']);
        $lname = sanitizeData($_POST['lname']);
        $email = sanitizeData($_POST['email']);
        $date_of_birth = sanitizeData($_POST['date_of_birth']);
        $pass = sanitizeData($_POST['pass']);

        $uploadError = '';
        $picture = file_upload($_FILES['picture']);
        //name validation
        if (empty($fname) || empty($lname)){
            $error = true;
            $fnameError = "Please enter full name";
        } else if (strlen($fname) < 3 || strlen($lname) < 3){
            $error = true;
            $fnameError = "Name and surname must be at least 3 characters long.";
        } else if (!preg_match("/^[a-zA-Z]+$/", $fname) || !preg_match("/^[a-zA-Z]+$/", $lname)){
            $error = true;
            $fnameError = "Name and surname must contain only letters and no spaces.";
        }
        //email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = true;
            $emailError = "Please enter valid email address";
        } else {
            //check email exsits in DB
            $query = "SELECT email FROM user WHERE email='$email'";
            $result = mysqli_query($connect, $query);
            $count = mysqli_num_rows($result);
            if ($count != 0){
                $error = true;
                $emailError = "Provided email already in use";
            }
        }
        //checks if date input was left empty 
        if (empty($date_of_birth)){
            $error = true;
            $dateError = "Please enter your date of birth";
        }
        //password validation
        if (empty($pass)){
            $error = true;
            $passError = "Please enter your password";
        } else if (strlen($pass) < 4){
            $error = true;
            $passError = "Password must have at least 6 characters";
        }
        //giving password hash for security or so
        $password = hash('sha256', $pass);
        if (!$error){
            //if no error, begin to insert into DB via sql query
            $query = "INSERT INTO user(first_name, last_name, password, date_of_birth, email, picture) 
            Values ('$fname', '$lname', '$password', '$date_of_birth', '$email', '$picture->fileName')";
            $res = mysqli_query($connect, $query);
            
            if ($res){
                $errTyp = "success";
                $errMSG = "Successfully registered, you may login now";
                $uploadError = ($picture->error != 0) ? $picture->ErrorMessage: '';
            }
        }
    }
    $connect->close();
?>
<!DOCTYPE html>
<html lang="en" >
<head>
   <meta charset="UTF-8">
    <meta name="viewport"   content="width=device-width, initial-scale=1.0">
<title>Login & Registration System </title>
<?php require_once  'components/boot.php'?>
</head>
<body>
<div class ="container">
  <form class="w-75"  method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off"  enctype="multipart/form-data">
            <h2>Sign Up a User</h2>
            <a href='dashboard.php'><button type='button' class='btn btn-primary btn-sm'>Back To Dashboard</button></a>
           <hr/>
           <?php
           if (isset($errMSG)) {
           ?>
           <div class="alert alert-<?php echo $errTyp ?>"  >
                        <p><?php echo $errMSG; ?></p>
                        <p><?php echo $uploadError; ?></ p>
           </div>
           <?php
           }
           ?>
           <input type ="text" name="fname"  class="form-control"placeholder="First name" maxlength="50" value= "<?php echo $fname ?>"/>
              <span class="text-danger" > <?php echo $fnameError; ?></span>

            <input type ="text" name="lname" class ="form-control" placeholder= "Surname" maxlength="50"  value="<?php echo $lname ?>"/>
              <span class="text-danger" > <?php echo  $fnameError; ?> </span>

           <input  type="email" name="email" class ="form-control" placeholder ="Enter Your Email" maxlength="40" value = "<?php echo $email ?>"/>
              <span  class="text-danger" > <?php  echo $emailError; ?> </span>
            <div class ="d-flex">
               <input class='form-control w-50'  type="date"   name="date_of_birth"  value = "<?php echo $date_of_birth ?>"/>
                <span  class="text-danger" > <?php  echo $dateError; ?></span>

                <input class='form-control w-50' type="file" name= "picture">
                <span class= "text-danger">  <?php echo $picError; ?></span>
            </div>
            <input type = "password" name = "pass" class = "form-control" placeholder = "Enter Password" maxlength = "15"/>
              <span   class = "text-danger"><?php echo  $passError; ?> </span>
            <hr/>
            <button type = "submit" class = "btn btn-block btn-primary" name = "btn-signup">Create User</button>
            <hr/>
    </form >
  </div>
</body>
</html>