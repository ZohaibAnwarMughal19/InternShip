<?php
// Step 1: Tell browser response type is JSON
header('Content-Type: application/json');

// Step 2: Connect to Database (Host, User, Pass, DB Name)
$conn = mysqli_connect("localhost", "root", "", "company_db");

if (!$conn) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

// Step 3: Write SQL Query to select data from Database table
$sql = "SELECT id, name, email, designation, salary FROM employees LIMIT 5";
$result = mysqli_query($conn, $sql);

// Step 4: Loop through DB results and store into PHP Array
$employeesList = [];
while ($row = mysqli_fetch_assoc($result)) {
    $employeesList[] = $row; // Push row into array
}

// Step 5: Convert Array to JSON & Echo (Send Response to Browser)
echo json_encode([
    "status" => "success",
    "total"  => count($employeesList),
    "data"   => $employeesList
], JSON_PRETTY_PRINT);
?>
