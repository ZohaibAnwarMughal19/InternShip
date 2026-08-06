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
    background-color: #0275d8;
    color: white;
    padding: 8px 20px;
    border-radius: 12px;
    border: 2px solid white;
    cursor: pointer;
    font-size: 15px;
}

button:hover {
    background-color: #0157a0;
}

#RF {
    color: Blue;
}
</style>

<body>

<?php include('header.php'); ?>

<form id="ForgotForm">

<div id="box">

    <h2>🔒 Forgot Password</h2>
    <p style="color: #555;">Enter your registered email address below and we will send you a link to reset your password.</p>

    <br>

    <label><b>Registered E-mail :</b></label>
    <input type="email" name="email" placeholder="Enter your registered email" required>

    <br><br>

    <button type="submit" id="btnSubmit"><b>Send Reset Link</b></button>

    <br><br>
    <p>Remember your password? <a id="RF" href="login.php">Back to Login</a></p>

    <div id="msg" style="margin-top: 15px;"></div>

</div>

</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){
    $('#ForgotForm').on('submit', function(e){
        e.preventDefault();
        
        $('#btnSubmit').html('<b>Sending... ⏳</b>').prop('disabled', true);
        $('#msg').html('');

        $.ajax({
            url: 'forgot_password_BE.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response){
                $('#btnSubmit').html('<b>Send Reset Link</b>').prop('disabled', false);
                $('#msg').html(response);
            },
            error: function(){
                $('#btnSubmit').html('<b>Send Reset Link</b>').prop('disabled', false);
                $('#msg').html("<div style='color:red;'>Something went wrong! Please try again.</div>");
            }
        });
    });
});
</script>

</body>
<?php include('footer.php'); ?>
</html>
