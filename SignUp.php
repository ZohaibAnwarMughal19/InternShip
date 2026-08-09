<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form - BGNU Portal</title>
    <!-- jQuery -->
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
        .bgnu-bismillah {
            background: linear-gradient(135deg, #2e1065 0%, #4c1d95 50%, #6b21a8 100%);
            color: #fef08a;
            font-size: 19px;
            font-weight: 700;
            padding: 9px 0;
            text-align: center;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 10px rgba(76,29,149,0.2);
        }
        .signup-container {
            width: 95%;
            max-width: 900px;
            margin: 25px auto;
            background: #ffffff;
            border-radius: 16px;
            padding: 35px 30px;
            box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.08);
            border: 1px solid #e9d5ff;
            position: relative;
            overflow: hidden;
        }
        .signup-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #3b0764 0%, #7e22ce 50%, #f59e0b 100%);
        }
        .signup-container h2 {
            margin-top: 0;
            color: #3b0764;
            text-align: center;
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 28px;
            border-bottom: 2px solid #e9d5ff;
            padding-bottom: 12px;
        }
        .signup-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-group label {
            font-weight: 600;
            margin-bottom: 6px;
            color: #581c87;
            font-size: 14px;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group select,
        .form-group textarea {
            padding: 11px 13px;
            border: 1px solid #e9d5ff;
            border-radius: 8px;
            font-size: 14.5px;
            outline: none;
            background: #fdfbf7;
            transition: all 0.2s ease;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #7e22ce;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(126, 34, 206, 0.18);
        }
        .checkbox-group, .radio-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 6px;
        }
        .checkbox-group label, .radio-group label {
            font-weight: normal;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            color: #1f2937;
        }
        .btn-register {
            background: linear-gradient(135deg, #3b0764 0%, #581c87 50%, #7e22ce 100%);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16.5px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(76, 29, 149, 0.25);
            margin-top: 18px;
        }
        .btn-register:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(76, 29, 149, 0.35);
        }
        #previewImage {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #3b0764;
            margin-top: 8px;
            display: none;
        }
        .login-link-box {
            text-align: center;
            margin-top: 22px;
            font-size: 15px;
            color: #374151;
        }
        .login-link-box a {
            color: #3b0764;
            font-weight: 800;
            text-decoration: none;
        }
        .login-link-box a:hover {
            color: #7e22ce;
            text-decoration: underline;
        }
        #showerror {
            margin-top: 18px;
            text-align: center;
        }
        @media (max-width: 600px) {
            .signup-container {
                padding: 22px 16px;
            }
        }
    </style>
</head>
<body>

    <div class="bgnu-bismillah">
        <marquee>IN THE NAME OF ALLAH THE MOST BENEFICENT THE MOST MERCIFUL!</marquee>
    </div>
    
    <?php include('header.php'); ?>
    <?php include('menu.php'); ?>
    
    <div class="signup-container">
        <h2>📝 Student Admission & Registration Form</h2>

        <form method="POST" action="Signup_BE.php" enctype="multipart/form-data">
            <div class="signup-grid">
                
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" placeholder="Enter Full Name" required>
                </div>
                
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" placeholder="Enter Email Address" required>
                </div>
               
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="pass" placeholder="Enter Password" required>
                </div>

                <div class="form-group">
                    <label>Phone Number *</label>
                    <input type="text" name="PN" placeholder="03001234567" pattern="03[0-9]{9}" required>
                </div>
               
                <div class="form-group">
                    <label>Gender *</label>
                    <div class="radio-group">
                        <label><input type="radio" name="gender" value="Male" required> Male</label>
                        <label><input type="radio" name="gender" value="Female" required> Female</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Hobbies</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" value="Football" name="Hobbies[]"> Football</label>
                        <label><input type="checkbox" value="Badminton" name="Hobbies[]"> Badminton</label>
                        <label><input type="checkbox" value="Poetry" name="Hobbies[]"> Poetry</label>
                        <label><input type="checkbox" value="etc" name="Hobbies[]"> etc</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Select City *</label>
                    <select name="City" required>
                        <option value="">Choose City</option>
                        <option value="Nankana Sahib">Nankana Sahib (NNS)</option>
                        <option value="Lahore">Lahore (LHR)</option>
                        <option value="Faisalabad">Faisalabad (FSD)</option>
                        <option value="Islamabad">Islamabad (ISL)</option>  
                    </select>
                </div>

                <div class="form-group">
                    <label>Profile Picture (Optional)</label>
                    <input type="file" name="myfile" id="inputFile" accept="image/*">
                    <img id="previewImage" alt="Preview Image">
                </div>

                <div class="form-group full-width">
                    <label>Address *</label>
                    <textarea name="Address" rows="3" placeholder="Write your permanent address..." required></textarea>
                </div>

            </div>

            <button type="submit" name="upload" class="btn-register">Submit Registration</button>
        </form>

        <div class="login-link-box">
            <span><b>Already have an account? </b></span>
            <a href="login.php">Login Now</a>
        </div>

        <div id="showerror"></div>
    </div>

    <script>
    $(document).ready(function(){
        
        $('#inputFile').change(function(){
            var file = this.files[0];
            if(file){
                $('#previewImage').attr('src', URL.createObjectURL(file)).show();
            } else {
                $('#previewImage').hide().attr('src', ''); 
            }
        });

        $('form').on('submit', function(e){
            e.preventDefault(); 
            var formData = new FormData(this);
            
            $.ajax({
                url: 'Signup_BE.php',
                type: 'POST',
                data: formData,
                contentType: false,  
                processData: false,  
                success: function(response){
                    $("#showerror").html(response);
                    if(response.includes("Successfully")){
                        $('form')[0].reset(); 
                        $('#previewImage').hide().attr('src', ''); 
                    }
                },
                error: function(response){
                    $("#showerror").html(response);
                }
            });
        });
    });
    </script>

    <?php include('footer.php'); ?>
</body>
</html>