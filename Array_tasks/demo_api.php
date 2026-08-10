<?php
// Step 1: Tell the browser/client that output will be JSON format
header('Content-Type: application/json');

// Step 2: Create your PHP Data Array (This can come from Database or Manual)
$responseArray = [
    "status"  => "success",
    "message" => "Hello! Yeh meri pehli PHP REST API hai!",
    "data"    => [
        "name"   => "Zohaib Anwar",
        "role"   => "Full Stack Developer",
        "city"   => "Lahore",
        "skills" => ["PHP", "MySQL", "jQuery", "JSON API"]
    ]
];

// Step 3: Convert PHP Array to JSON string & echo it!
echo json_encode($responseArray, JSON_PRETTY_PRINT);
?>
