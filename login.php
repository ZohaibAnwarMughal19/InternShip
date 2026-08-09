<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BGNU Portal</title>
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
            max-width: 440px;
            margin: 35px auto;
            background: #ffffff;
            border-radius: 16px;
            padding: 35px 30px;
            text-align: center;
            box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.08), 0 8px 10px -6px rgba(76, 29, 149, 0.04);
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
            margin-bottom: 25px;
            letter-spacing: -0.5px;
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
            transition: all 0.2s ease;
            background: #fdfbf7;
        }
        .form-group input:focus {
            border-color: #7e22ce;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(126, 34, 206, 0.18);
        }
        .btn-login {
            background: linear-gradient(135deg, #3b0764 0%, #581c87 50%, #7e22ce 100%);
            color: white;
            width: 100%;
            padding: 13px;
            border-radius: 10px;
            border: none;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(76, 29, 149, 0.3);
            margin-top: 10px;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #4c1d95 0%, #6b21a8 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(76, 29, 149, 0.4);
        }
        #FP {
            color: #d97706;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            display: inline-block;
            margin-top: 18px;
        }
        #FP:hover {
            color: #b45309;
            text-decoration: underline;
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
        .link-text {
            font-size: 14.5px;
            margin-top: 15px;
            color: #4b5563;
        }

        /* Modal Popup Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(46, 16, 101, 0.6);
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
        }

        .modal-card {
            background-color: #ffffff;
            width: 90%;
            max-width: 420px;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: popupAnimation 0.25s ease-out;
            border: 1px solid #e9d5ff;
        }

        @keyframes popupAnimation {
            from { transform: scale(0.85); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .modal-header h3 {
            margin: 0;
            color: #dc2626;
            font-size: 20px;
            font-weight: 700;
        }

        .modal-icon {
            font-size: 26px;
        }

        .modal-body {
            font-size: 15.5px;
            color: #1f2937;
            margin-bottom: 22px;
            line-height: 1.5;
            word-break: break-word;
        }

        .btn-close-modal {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 10px 32px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(220, 38, 38, 0.25);
        }

        .btn-close-modal:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(220, 38, 38, 0.35);
        }
    </style>
</head>

<body>

<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<form action="Log.php" method="POST" id="LoginForm">

<div id="box">

    <h2>🔑 Student Login</h2>

    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="Enter Registered Email" required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="pass" placeholder="Enter Password" required>
    </div>

    <button type="submit" class="btn-login">Login Now</button>
    
    <div><a id="FP" href="forgot_password.php">🔑 Forgot Password?</a></div>

    <p class="link-text">Don't have an account? <a id="RF" href="SignUp.php">Register Here</a></p>

</div>

</form>

<!-- Custom Error / Alert Popup Modal -->
<div id="loginModal" class="modal">
    <div class="modal-card">
        <div class="modal-header">
            <span class="modal-icon">⚠️</span>
            <h3>Login Warning</h3>
        </div>
        <div class="modal-body" id="modalMsg">
            <!-- Dynamic Error Message -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-close-modal" onclick="closeLoginModal()">OK</button>
        </div>
    </div>
</div>

<script>
function showLoginModal(msgText) {
    $('#modalMsg').html(msgText);
    $('#loginModal').css('display', 'flex');
}

function closeLoginModal() {
    $('#loginModal').hide();
}

$(document).ready(function(){

    $('#loginModal').on('click', function(e) {
        if (e.target === this) {
            closeLoginModal();
        }
    });

    $('#LoginForm').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url: 'Log.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response){
                if(response.trim() === "success"){
                    window.location = "Dashbord.php";
                } else {
                    showLoginModal(response);
                }
            }
        });
    });
});
</script>

<?php include('footer.php'); ?>
</body>
</html>