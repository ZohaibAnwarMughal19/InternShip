<html>
<head>
    <!--- jQuery --->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table.t5 a { color: black; }
    </style>
</head>
<body style="background-color:none;">

    <div style="background-color: aquamarine; color:Green; font-size: xx-large;">
        <marquee>IN THE NAME OF ALLAH THE MOST BENEFICANT THE MOST MERCIFULL!</marquee>
    </div>
    
    <?php include('header.php'); ?>
    <?php include('menu.php'); ?>
    

    <form method="POST" action="Signup_BE.php" enctype="multipart/form-data">
        <table border="1px" align="Center" width="95%" class="t5">
            
            <tr>
                <td>Name :</td>
                <td><input type="text" name="name" placeholder="Enter Name_ _ _" required></td>
            </tr>
            
          
            <tr>
                <td>E-Mail :</td>
                <td><input type="email" name="email" placeholder="Enter E-Mail_ _ _" required></td>
            </tr>
           
            <tr>
                <td>Password :</td>
                <td> <input type="password" name="pass" placeholder="Enter Password _ _ _ " required> </td>
            </tr>
                <tr>
                <td>Phone_Number :</td>
                <td><input type="text" placeholder="Enter Your Phone No _ _ _" name="PN" pattern="03[0-9]{9}" required></td>
                </tr>
           
            <tr>
                <td><label>Gender :</label></td>
                <td>
                    <input type="radio" name="gender" value="Male"> Male
                    <input type="radio" name="gender" value="Female"> Female
                </td>
            </tr>
            
           
            <tr>
                <td>Hobbies :</td>
                <td>
                    <input type="checkbox" value="Football" name="Hobbies[]">Football
                    <input type="checkbox" value="Badminton" name="Hobbies[]">Badminton
                    <input type="checkbox" value="Poetry" name="Hobbies[]">Poetry
                     <input type="checkbox" value="etc" name="Hobbies[]">etc
                </td>
            </tr>
            
           
            <tr>
                <td>Address :</td>
                <td><textarea name="Address" placeholder="Write your Addres_ _ _" required></textarea></td>
            </tr>
            
     
            <tr>
                <td>Choose File :</td>
                <td>
                    <input type="file" name="myfile" id="inputFile">
                    <img id="previewImage" width="40px">
                </td>
            </tr>
            
           
            <tr>
                <td>Select your City :</td>
                <td>
                    <select name="City" required>
                        <option>Choose City</option>
                        <option value="Nankana Sahib">NNS</option>
                        <option value="Lahore">LHR</option>
                        <option value="Faisalabad">FSD</option>
                        <option value="Islamabad">ISL</option>  
                    </select>
                </td>
            </tr>
            
            
            <tr>
                <td>Button For Submission :</td>
                <td>
                    <button type="submit" name="upload" style="background-color:aqua; color:red">Click Here . . .</button>
                </td>
            </tr>
        </table>
    </form>
      

    <script>
    $(document).ready(function(){
        
   
        $('#inputFile').change(function(){
            var file = this.files[0];
            if(file){
                $('#previewImage').attr('src', URL.createObjectURL(file));
            } else {
                $('#previewImage').attr('src', ''); 
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
                    console.log(response);   
                    if(response.includes("Successfully")){
                        $('form')[0].reset(); 
                        $('#previewImage').attr('src', ''); 
                    }
                },
                error: function(response){
                  $("#showerror").html(response);
                }
            });
        });
    });
 
    alert('Kindly Register Yourself through Admission Form and Then Login to your Account ! ☺️🫱🏽‍🫲🏼');

    </script>
</body>
<?php include('footer.php'); ?>
   <br>
   <div align="center">
    <span id="msg"><b>Do you have Already Account ! </b></span>
    <a href="login.php" style="color:blue" >Login now</a>
</div>
<div id="showerror" style="color:red;"></div>
</html>