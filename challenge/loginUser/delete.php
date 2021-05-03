<?php 
    session_start();
    require_once('components/db_connect.php');
    require_once('components/boot.php');

    //redirect to login page 
    if(!isset($_SESSION['adm']) && !isset($_SESSION['user'])){
        header("Location: index.php");
        exit;
    }

    if(isset($_SESSION['user'])){
        header("Location: home.php");
        exit;
    }
    //initial bootstrap 
    $class = 'd-none';
    // The GET method SQL retrieves the users data
    if ($_GET['id']){
        $id = $_GET['id']; //get id of user
        $sql = "SELECT * FROM user WHERE id = {$id}"; //sql query to find the data related to user id
        $result = $connect->query($sql);//store the query in $result
        $data = $result->fetch_assoc(); //get the associative array from $result, assign to $data
        if ($result->num_rows == 1){
            $f_name = $data['first_name']; // assign individual user data to $variable
            $l_name = $data['last_name'];
            $email = $data['email'];
            $data_of_birth = $data['date_of_birth'];
            $picture = $data['picture'];
        }
    }

    //Post method to delte user Data
    if ($_POST){
        $id = $_POST['id']; //retrieving the data
        $picture = $_POST['picture']; // "
        ($picture == "avatar.png")?: unlink("pictures/$picture"); //unlink the pictures

        $sql = "DELETE FROM user WHERE id = {$id}"; //sql query to delete
        if ($connect->query($sql) === TRUE){ // if the query is successfully connected/executed, run the messages below
            $class = "alert alert-success";
            $message = "Successfully Deleted!";
            header("refresh:3;url=dashboard.php"); //redirect to dashboard after 3 second delay
        } else {
            $class = "alert alert-danger";
            $message = "The entry was not deleted due to: <br>". $connect->error; //display error message should error occur.
        }
    }
    $connect->close();

?>

<!DOCTYPE html>
<html lang="en" >
<head>
   <meta charset="UTF-8">
    <meta name="viewport"   content="width=device-width, initial-scale=1.0">
   <title>Delete User</title>
   <style type= "text/css" >
      fieldset {
           margin: auto;
           margin-top: 100px;
           width: 70% ;
       }    
       .img-thumbnail{
           width: 70px  !important;
           height: 70px !important;
       }    
  </style>
</head>
<body>
<div class="<?php echo $class; ?>" role="alert" >
       <p><?php echo ($message) ?? ''; ?></p>           
</div>
<fieldset>
<legend class='h2 mb-3' >Delete request <img class= 'img-thumbnail rounded-circle'  src='pictures/<?php echo $picture ?>' alt="<?php echo $f_name ?>"></legend >
<h5>You have selected the data below: </h5>
<table  class="table w-75 mt-3">
<tr>
           <td><?php echo "$f_name $l_name"?></td>
           <td><?php echo $email?></ td>
           <td ><?php echo $date_of_birth?></ td>
</tr>
</table>

<h3 class="mb-4" >Do you really want to delete this user?</h3 >
<form method="post">
  <input type="hidden" name ="id" value= "<?php echo $id ?>"/>
  <input type= "hidden" name= "picture" value= "<?php echo $picture ?>"/>
  <button class="btn btn-danger" type="submit"> Yes, delete it! </button>
  <a href="dashboard.php" ><button class="btn btn-warning" type= "button">No, go back!</button></a>
</form>
</fieldset>
</body>
</html>