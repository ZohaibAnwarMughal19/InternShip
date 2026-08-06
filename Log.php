<?php

session_start();

require_once 'db.php';

if($conn){ 
$email=$_POST['email'];
$password=$_POST['pass'];

$sql="SELECT * FROM users WHERE Email='$email' AND Password='$password'";

$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0){

$user=mysqli_fetch_assoc($result);

if($user['is_verified'] == 0){
    echo "⚠️ Please verify your Email first! Verification link has been provided upon signup.";
} else {
    $_SESSION['User']=$user;
    echo "success";
}

}
else{

echo "Invalid Email or Password";

}
}
else{
    echo " Connection Error ❌ !";
}
?>