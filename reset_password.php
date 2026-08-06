<?php
require_once 'db.php';

$msg = "";
$valid = false;
$email = "";
$token = "";

if(isset($_GET['email']) && isset($_GET['token'])){
    $email = mysqli_real_escape_string($conn, $_GET['email']);
    $token = mysqli_real_escape_string($conn, $_GET['token']);

    // Verify if token exists in database
    $sql = "SELECT * FROM users WHERE Email='$email' AND token='$token'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $valid = true;
    } else {
        $msg = "<div style='background-color:#f8d7da; color:#721c24; padding:15px; border-radius:5px; margin-top:20px;'>
            ❌ Invalid or Expired Password Reset Link! Please request a new one.
        </div>";
    }
} else if(isset($_POST['update_password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if($new_pass !== $confirm_pass) {
        $valid = true;
        $msg = "<div style='background-color:#fff3cd; color:#856404; padding:15px; border-radius:5px; margin-top:10px;'>
            ⚠️ Passwords do not match! Please enter matching passwords.
        </div>";
    } else {
        // Verify token again before updating
        $sql = "SELECT * FROM users WHERE Email='$email' AND token='$token'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0){
            $new_token = md5(rand() . time()); // Invalidate old token
            $update_sql = "UPDATE users SET Password='$new_pass', token='$new_token' WHERE Email='$email'";

            if(mysqli_query($conn, $update_sql)){
                $msg = "<div style='background-color:#d4edda; color:#155724; padding:20px; border-radius:5px; margin-top:20px;'>
                    <h2>Password Reset Successful! 🎉</h2>
                    <p>Your password has been updated successfully. You can now login with your new password.</p>
                    <br>
                    <a href='login.php' style='background-color:#28a745; color:white; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>Go to Login Page</a>
                </div>";
                $valid = false; // Hide form after success
            } else {
                $valid = true;
                $msg = "<div style='color:red;'>Failed to update password! Please try again.</div>";
            }
        } else {
            $msg = "<div style='color:red;'>Invalid session or token!</div>";
        }
    }
} else {
    $msg = "<div style='color:red;'>Invalid Request!</div>";
}
?>

<html>
<style>
body {
    font-family: Arial;
    margin: 0;
    padding: 0;
}
label {
    display: block;
    width: auto;
    margin-bottom: 5px;
}

input {
    width: 260px;
    padding: 8px;
    margin: 0;
    text-align: center;
}

#box {
    width: 92%;
    margin: auto;
    border: 1px solid black;
    padding: 20px;
    text-align: center;
    margin-bottom: 0;
    padding-bottom: 20px;
}

form {
    margin: 0;
    padding: 0;
}

footer {
    margin-top: 0;
    padding-top: 0;
}

button {
    background-color: #28a745;
    color: white;
    padding: 10px 25px;
    border-radius: 12px;
    border: 2px solid white;
    cursor: pointer;
    font-size: 15px;
}

button:hover {
    background-color: #218838;
}
</style>

<body>

<?php include('header.php'); ?>

<div id="box">

    <?php if($valid): ?>
        <h2>🔑 Set New Password</h2>
        <p style="color: #555;">Please enter your new password below for <b><?php echo htmlspecialchars($email); ?></b></p>
        
        <?php echo $msg; ?>

        <form action="reset_password.php" method="POST" style="margin-top: 20px;">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <label><b>New Password:</b></label>
            <input type="password" name="new_pass" placeholder="Enter new password" required>

            <br><br>

            <label><b>Confirm New Password:</b></label>
            <input type="password" name="confirm_pass" placeholder="Confirm new password" required>

            <br><br>

            <button type="submit" name="update_password"><b>Update Password</b></button>
        </form>

    <?php else: ?>
        <?php echo $msg; ?>
    <?php endif; ?>

</div>

</body>
<?php include('footer.php'); ?>
</html>

<?php mysqli_close($conn); ?>
