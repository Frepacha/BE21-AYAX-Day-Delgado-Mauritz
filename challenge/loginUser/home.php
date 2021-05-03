<?php 
    session_start();
    require_once('components/db_connect.php');
    require_once('components/boot.php');
//if admin, redirect to dashboard
    if (isset($_SESSION['adm'])){
        header("Location: dashboard.php");
        exit;
    }
    //if !admin and !user, then proceed to login page, because user is not logged in.
    if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])){
        header("Location: index.php");
        exit;
    }

    //select the if the logged in person is a user
    $res = mysqli_query($connect, "SELECT * FROM user WHERE id=" . $_SESSION['user']);
    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

    $connect->close();
?>

<!DOCTYPE html>
<html lang="en" >
<head>
   <meta charset="UTF-8">
    <meta name="viewport"   content="width=device-width, initial-scale=1.0">
<title>Welcome - <?php  echo $row['first_name']; ?></title >
<style>
.userImage{
width: 200px;
height: 200px;
}
.hero {
   background: rgb(2,0,36);
    background: linear-gradient(24deg, rgba(2,0,36,1) 0%, rgba(0,212,255,1) 100%);  
}
</style>
</head>
<body>
<div class ="container">
   <div class="hero">
       <img class= "userImage" src="pictures/<?php echo $row['picture']; ?>" alt="<?php echo $row['first_name']; 
      ?>">
       <p class ="text-white">Hi <?php echo $row['first_name']; ?></p >
   </div>
   <a href="logout.php?logout">Sign Out</a>
   <a href="update.php?id=<?php echo $_SESSION['user'] ?>">Update your profile</a>
</div>
</body >
</html>