<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - BGNU Portal</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        }
        .btn-submit:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }
        #RF {
            color: #3b0764;
            font-weight: 800;
            text-decoration: none;
        }
        #RF:hover {
            color: #7e22ce;
            text-decoration: underline;
        }
    </style>
</head>

<body>

<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<form id="ForgotForm">

<div id="box">
    <h2>🔒 Forgot Password</h2>
    <p style="color: #4b5563; font-size: 14px; margin-bottom: 25px;">Enter your registered email address below and we will send you a link to reset your password.</p>

    <div class="form-group">
        <label>Registered Email Address</label>
        <input type="email" name="email" placeholder="Enter your registered email" required>
    </div>

    <button type="submit" id="btnSubmit" class="btn-submit">Send Reset Link</button>

    <p style="margin-top: 22px; font-size: 14.5px;">Remember your password? <a id="RF" href="login.php">Back to Login</a></p>

    <div id="msg" style="margin-top: 15px;"></div>
</div>

</form>

<script>
$(document).ready(function(){
    $('#ForgotForm').on('submit', function(e){
        e.preventDefault();
        
        $('#btnSubmit').html('Sending... ⏳').prop('disabled', true);
        $('#msg').html('');

        $.ajax({
            url: 'forgot_password_BE.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response){
                $('#btnSubmit').html('Send Reset Link').prop('disabled', false);
                $('#msg').html(response);
            },
            error: function(){
                $('#btnSubmit').html('Send Reset Link').prop('disabled', false);
                $('#msg').html("<div style='color:red;'>Something went wrong! Please try again.</div>");
            }
        });
    });
});
</script>

<?php include('footer.php'); ?>
</body>
</html>
