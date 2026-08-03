<?php

$conn = mysqli_connect("localhost", "root", "", "internship_db");

if(!$conn){
    die("CONNECTION ERROR !".mysqli_connect_error());
}

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
    echo "Warning ⚠️ This Email address ($Email) is already registered! Please use another Email or Login.";
} else {
    $File_name = isset($_FILES['myfile']['name']) ? $_FILES['myfile']['name'] : '';
    $temp_name = isset($_FILES['myfile']['tmp_name']) ? $_FILES['myfile']['tmp_name'] : '';
    $uploads = 'uploads/' . $File_name;

    if(!empty($File_name)) {
        move_uploaded_file($temp_name, $uploads);
    } else {
        $uploads = '';
    }

    $sql = "INSERT INTO users(Name,Email,Password,Phone_No,Gender,Hobbies,Address,File_Path,City) 
    VALUES ('$Name','$Email','$Password','$Phone_No','$Gender','$Hobbies','$Address','$uploads','$City')";

    if(mysqli_query($conn,$sql)){
        echo "Dear ".$Name." Your Form Has Been Submitted Successfully! 🎉";
    }
    else{
        echo "Error ❌ Form is not submitted!";
    }
}

mysqli_close($conn);

?>