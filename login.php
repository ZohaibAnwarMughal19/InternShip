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
    width: 220px;
    padding: 5px;
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
    padding-bottom: 10px;
}

#LoginForm {
    margin: 0;
    margin-bottom: 0;
}

form {
    margin: 0;
    padding: 0;
}


footer {
    margin-top: 0;
    padding-top: 0;
}
button{
    background-color:springgreen;
    color:white;
    width:100px;
    border-radius:12px;
    border:2px solid white;
}
#RF{
     color: Blue;
}
#FP {
    color: #0275d8;
    text-decoration: none;
    font-weight: bold;
}
#FP:hover {
    color: #01437d;
    text-decoration: underline;
}
</style>

<body>

<?php include('header.php'); ?>

<form action="Log.php" method="POST" id="LoginForm">

<div id="box">

<br>

    <label>E-mail :</label>
<input type="email" name="email" placeholder="Enter Email" required>

<br><br>

    <label>Password :</label>
<input type="password" name="pass" placeholder="Enter Password" required>

<br><br>

<button type="submit"><b>Login</b></button>
    
    <p><a id="FP" href="forgot_password.php">🔑 Forgot Password?</a></p>

    <p> If Don't have an Account then Register Yourself <a id="RF" href="SignUp.php">>Registration Form</a> </p>


<div id="msg" style="color: red; font-size:large;"></div>

</div>

</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(document).ready(function(){

$('#LoginForm').on('submit', function(e){

e.preventDefault();

$.ajax({

url:'Log.php',
type:'POST',
data:$(this).serialize(),

success:function(response){

if(response.trim()=="success"){
window.location="Dashbord.php";
}
else{
$("#msg").text(response);
}

}

});

});

});

</script>

</body>
<?php include('footer.php') ?>
</html>