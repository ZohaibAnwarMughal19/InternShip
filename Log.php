<?php

session_start();

$conn = mysqli_connect("localhost", "root", "", "internship_db");

if($conn){ 
$email=$_POST['email'];
$password=$_POST['pass'];

$sql="SELECT * FROM users WHERE Email='$email' AND Password='$password'";

$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0){

$user=mysqli_fetch_assoc($result);

$_SESSION['User']=$user;

echo "success";

}
else{

echo "Invalid Email or Password";

}
}
else{
    echo " Connection Error ❌ !";
}
?>