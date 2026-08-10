<?php
header('Content-Type: application/json');
require_once 'db.php';

// Sample data for generating random employees
$firstNames = ['Ali', 'Zohaib', 'Hamza', 'Usman', 'Bilal', 'Ahmad', 'Saad', 'Sara', 'Fatima', 'Ayesha'];
$lastNames  = ['Khan', 'Mughal', 'Anwar', 'Malik', 'Sheikh', 'Bhatti', 'Raza', 'Shah', 'Qureshi', 'Javed'];
$designations = ['Software Engineer', 'Frontend Developer', 'Backend Engineer', 'UI/UX Designer', 'Project Manager'];

// 1. Generate 100 people data into array
$employees = [];

for ($i = 1; $i <= 100; $i++) {
    $fn = $firstNames[array_rand($firstNames)];
    $ln = $lastNames[array_rand($lastNames)];
    $name = $fn . ' ' . $ln;
    $email = strtolower($fn . '.' . $ln . $i . '@mail.com');
    $desig = $designations[array_rand($designations)];
    $salary = rand(50, 250) * 1000;

    $employees[] = "('$name', '$email', '$desig', $salary)";
}

// 2. Clear old data and insert 100 records into database
mysqli_query($conn, "TRUNCATE TABLE employees");

$sql = "INSERT INTO employees (name, email, designation, salary) VALUES " . implode(", ", $employees);

if (mysqli_query($conn, $sql)) {
    echo json_encode([
        'status' => 'success',
        'message' => '100 Records generated and saved into DB successfully!'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => mysqli_error($conn)
    ]);
}
?>
