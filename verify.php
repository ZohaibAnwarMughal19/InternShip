<?php
require_once 'db.php';

if(isset($_GET['email']) && isset($_GET['token'])){
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Database mein check karo email aur token match karta hai ya nahi
    $sql = "SELECT * FROM users WHERE Email='$email' AND token='$token'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);

        if($user['is_verified'] == 1){
            echo "<div style='text-align:center; margin-top:50px; font-family:Arial;'>";
            echo "<h2 style='color:blue;'>Your Email is already verified! 😃</h2>";
            echo "<a href='login.php' style='font-size:18px;'>Click here to Login</a>";
            echo "</div>";
        } else {
            // Email status ko verify (1) kar do
            $update_sql = "UPDATE users SET is_verified=1 WHERE Email='$email'";
            if(mysqli_query($conn, $update_sql)){
                echo "<div style='text-align:center; margin-top:50px; font-family:Arial;'>";
                echo "<h2 style='color:green;'>Email Verified Successfully! 🎉</h2>";
                echo "<a href='login.php' style='font-size:18px;'>Click here to Login</a>";
                echo "</div>";
            } else {
                echo "<h2 style='color:red;'>Error updating record!</h2>";
            }
        }
    } else {
        echo "<div style='text-align:center; margin-top:50px; font-family:Arial;'>";
        echo "<h2 style='color:red;'>Invalid or Expired Verification Link! ❌</h2>";
        echo "</div>";
    }
} else {
    echo "<h2 style='color:red;'>Invalid Request!</h2>";
}

mysqli_close($conn);
?>
