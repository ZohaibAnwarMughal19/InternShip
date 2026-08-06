<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'db.php';

if(isset($_POST['email'])) {
    $Email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if Email exists in database
    $sql = "SELECT * FROM users WHERE Email='$Email'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $Name = $user['Name'];

        // Generate new reset token
        $reset_token = md5(rand() . time());

        // Update token in database
        $update_sql = "UPDATE users SET token='$reset_token' WHERE Email='$Email'";
        
        if(mysqli_query($conn, $update_sql)) {
            $reset_link = "http://localhost/InternShip/reset_password.php?email=" . urlencode($Email) . "&token=" . $reset_token;

            $mail = new PHPMailer(true);

            try {
                // Namecheap Shared Hosting SMTP Settings
                $mail->isSMTP();
                $mail->Host       = 'mail.exceptionalcore.online';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'no_reply@exceptionalcore.online';
                $mail->Password   = '{Pookie_!@!_Bachy}';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // SSL certificate verification options for localhost / Namecheap
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer'       => false,
                        'verify_peer_name'  => false,
                        'allow_self_signed' => true
                    )
                );

                // Sender & Receiver
                $mail->setFrom('no_reply@exceptionalcore.online', 'Internship Portal');
                $mail->addAddress($Email, $Name);

                // Email Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request - Internship Portal';
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd; max-width: 600px; margin: auto;'>
                        <h2 style='color: #0275d8;'>Password Reset Request 🔑</h2>
                        <p>Hello <b>$Name</b>,</p>
                        <p>We received a request to reset your password for your Internship Portal account. Click the button below to set a new password:</p>
                        <p style='text-align: center; margin: 30px 0;'>
                            <a href='$reset_link' style='background-color: #d9534f; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>Reset My Password</a>
                        </p>
                        <p>Or copy and paste this link into your browser:<br><a href='$reset_link'>$reset_link</a></p>
                        <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                        <p style='font-size: 12px; color: #888;'>If you did not request a password reset, please ignore this email.</p>
                    </div>
                ";

                $mail->send();

                echo "<div style='background-color:#d4edda; color:#155724; padding:15px; border-radius:5px;'>
                    <b>Success! 🎉</b><br>
                    📩 A password reset link has been sent to <b>".$Email."</b>.<br>
                    Please check your email inbox (or Junk folder) and click the link to reset your password.
                </div>";

            } catch (Exception $e) {
                echo "<div style='background-color:#f8d7da; color:#721c24; padding:15px; border-radius:5px;'>
                    Failed to send email. Error: {$mail->ErrorInfo}
                </div>";
            }
        } else {
            echo "<div style='color:red;'>Failed to process request. Please try again!</div>";
        }
    } else {
        echo "<div style='background-color:#fff3cd; color:#856404; padding:15px; border-radius:5px;'>
            ⚠️ No account found with this email address (<b>$Email</b>). Please check your email or register a new account.
        </div>";
    }
} else {
    echo "<div style='color:red;'>Invalid Request!</div>";
}

mysqli_close($conn);
?>
