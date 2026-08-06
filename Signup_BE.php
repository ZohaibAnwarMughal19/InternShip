<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'db.php';

$Name = $_POST['name'];
$Email = $_POST['email'];
$Password = $_POST['pass'];
$Phone_No = $_POST['PN'];
$Gender = $_POST['gender'];

$Hobbies="";
if(isset($_POST['Hobbies'])){
    $Hobbies = implode(",",$_POST['Hobbies']);
}

$Address = $_POST['Address'];
$City = $_POST['City'];

// Check if Email already exists in database
$check_sql = "SELECT * FROM users WHERE Email='$Email'";
$check_res = mysqli_query($conn, $check_sql);

if(mysqli_num_rows($check_res) > 0) {
    echo "<div style='background-color:#fff3cd; color:#856404; padding:15px; border-radius:5px;'>
        Warning ⚠️ This Email address ($Email) is already registered! Please use another Email or Login.
    </div>";
} else {
    $File_name = isset($_FILES['myfile']['name']) ? $_FILES['myfile']['name'] : '';
    $temp_name = isset($_FILES['myfile']['tmp_name']) ? $_FILES['myfile']['tmp_name'] : '';
    $uploads = 'uploads/' . $File_name;

    if(!empty($File_name)) {
        move_uploaded_file($temp_name, $uploads);
    } else {
        $uploads = '';
    }

    $token = md5(rand());
    $is_verified = 0;

    $sql = "INSERT INTO users(Name,Email,Password,Phone_No,Gender,Hobbies,Address,File_Path,City,token,is_verified) 
    VALUES ('$Name','$Email','$Password','$Phone_No','$Gender','$Hobbies','$Address','$uploads','$City','$token','$is_verified')";

    if(mysqli_query($conn,$sql)){
        $v_link = "http://localhost/InternShip/verify.php?email=" . urlencode($Email) . "&token=" . $token;
        
        $mail = new PHPMailer(true);

        try {
            // Namecheap Shared Hosting SMTP Settings
            $mail->isSMTP();
            $mail->Host       = 'mail.exceptionalcore.online'; // Replace YOURDOMAIN.COM with your actual domain (e.g. mail.mysite.com)
            $mail->SMTPAuth   = true;
            
            // Sender Credentials (Your Namecheap domain email & password)
            $mail->Username   = 'no_reply@exceptionalcore.online'; // Replace with your domain email
            $mail->Password   = 'YOUR_EMAIL_PASSWORD_HERE'; // Replace with your domain email password
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL encryption for Namecheap
            $mail->Port       = 465;                         // Namecheap SSL Port 465

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
            $mail->Subject = 'Email Verification - Internship Portal';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd;'>
                    <h2>Hello $Name, Welcome to Internship Portal! 🎉</h2>
                    <p>Thank you for signing up. Please click the button below to verify your email address and activate your account:</p>
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='$v_link' style='background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verify My Email Address</a>
                    </p>
                    <p>Or copy and paste this link into your browser:<br><a href='$v_link'>$v_link</a></p>
                </div>
            ";

            $mail->send();
            
            echo "<div style='background-color:#d4edda; color:#155724; padding:15px; border-radius:5px; margin-top:10px;'>
                <b>Dear ".$Name.", your registration was successful! 🎉</b><br><br>
                📩 A verification link has been sent to your Gmail address: <b>".$Email."</b>.<br>
                Please open your email inbox and click on the verification link to verify your account!
            </div>";

        } catch (Exception $e) {
            echo "<div style='background-color:#fff3cd; color:#856404; padding:15px; border-radius:5px;'>
                Registration successful, but failed to send email. Please configure SMTP credentials in <b>Signup_BE.php</b>.<br>
                Error: {$mail->ErrorInfo}
            </div>";
        }
    }
    else{
        echo "<div style='background-color:#f8d7da; color:#721c24; padding:15px; border-radius:5px;'>Error ❌ Form is not submitted!</div>";
    }
}

mysqli_close($conn);

?>






