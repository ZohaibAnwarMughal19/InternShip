<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db.php';

// Check search query using simple if statement (No ternary operator ? :)
$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $search = mysqli_real_escape_string($conn, $search);
}

// Build SQL Query using simple if-else
if ($search != "") {
    $sql = "SELECT id, name, email, designation, salary FROM employees WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR designation LIKE '%$search%' ORDER BY id ASC";
} else {
    $sql = "SELECT id, name, email, designation, salary FROM employees ORDER BY id ASC";
}

$result = mysqli_query($conn, $sql);

$employees = [];
$totalSalary = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $row['id'] = (int)$row['id'];
    $row['salary'] = (float)$row['salary'];
    
    $employees[] = $row;
    $totalSalary = $totalSalary + $row['salary'];
}

$totalCount = count($employees);

$avgSalary = 0;
if ($totalCount > 0) {
    $avgSalary = round($totalSalary / $totalCount, 2);
}

echo json_encode([
    'status' => 'success',
    'count' => $totalCount,
    'stats' => [
        'total' => $totalCount,
        'payroll' => $totalSalary,
        'avg' => $avgSalary
    ],
    'data' => $employees
]);
?>
