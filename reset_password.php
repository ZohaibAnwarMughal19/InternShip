<?php
require_once 'db.php';

$msg = "";
$valid = false;
$email = "";
$token = "";

if(isset($_GET['email']) && isset($_GET['token'])){
    $email = mysqli_real_escape_string($conn, $_GET['email']);
    $token = mysqli_real_escape_string($conn, $_GET['token']);

    $sql = "SELECT * FROM users WHERE Email='$email' AND token='$token'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $valid = true;
    } else {
        $msg = "<div style='background-color:#fee2e2; color:#991b1b; padding:15px; border-radius:8px; margin-top:20px;'>
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
        $msg = "<div style='background-color:#fffbeb; color:#92400e; padding:15px; border-radius:8px; margin-top:10px;'>
            ⚠️ Passwords do not match! Please enter matching passwords.
        </div>";
    } else {
        $sql = "SELECT * FROM users WHERE Email='$email' AND token='$token'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0){
            $new_token = md5(rand() . time());
            $update_sql = "UPDATE users SET Password='$new_pass', token='$new_token' WHERE Email='$email'";

            if(mysqli_query($conn, $update_sql)){
                $msg = "<div style='background-color:#f3e8ff; color:#581c87; padding:20px; border-radius:10px; margin-top:20px;'>
                    <h2>Password Reset Successful! 🎉</h2>
                    <p>Your password has been updated successfully. You can now login with your new password.</p>
                    <br>
                    <a href='login.php' style='background:linear-gradient(135deg, #3b0764 0%, #581c87 100%); color:white; padding:10px 22px; text-decoration:none; border-radius:8px; font-weight:bold; display:inline-block;'>Go to Login Page</a>
                </div>";
                $valid = false;
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password - BGNU Portal</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #faf5ef;
            color: #3b0764;
        }
        #box {
            width: 90%;
            max-width: 450px;
            margin: 35px auto;
            background: #ffffff;
            border-radius: 16px;
            padding: 35px 30px;
            text-align: center;
            box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.08);
            border: 1px solid #e9d5ff;
            position: relative;
            overflow: hidden;
        }
        #box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #3b0764 0%, #7e22ce 50%, #f59e0b 100%);
        }
        #box h2 {
            margin-top: 0;
            color: #3b0764;
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            color: #581c87;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #e9d5ff;
            border-radius: 8px;
            font-size: 14.5px;
            outline: none;
            text-align: left;
            background: #fdfbf7;
            transition: all 0.2s ease;
        }
        .form-group input:focus {
            border-color: #7e22ce;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(126, 34, 206, 0.18);
        }
        .btn-submit {
            background: linear-gradient(135deg, #3b0764 0%, #581c87 50%, #7e22ce 100%);
            color: white;
            width: 100%;
            padding: 13px;
            border-radius: 10px;
            border: none;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(76, 29, 149, 0.25);
            margin-top: 10px;
        }
        .btn-submit:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>

<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="box">

    <?php if($valid): ?>
        <h2>🔑 Set New Password</h2>
        <p style="color: #4b5563; font-size: 14px; margin-bottom: 20px;">Please enter your new password below for <b><?php echo htmlspecialchars($email); ?></b></p>
        
        <?php echo $msg; ?>

        <form action="reset_password.php" method="POST">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_pass" placeholder="Enter new password" required>
            </div>

            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_pass" placeholder="Confirm new password" required>
            </div>

            <button type="submit" name="update_password" class="btn-submit">Update Password</button>
        </form>

    <?php else: ?>
        <?php echo $msg; ?>
    <?php endif; ?>

</div>

<?php include('footer.php'); ?>
</body>
</html>
<?php mysqli_close($conn); ?>
