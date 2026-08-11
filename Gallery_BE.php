<?php
session_start();
require_once 'db.php';
require_once 'gdrive_helper.php';

$action = $_REQUEST['action'] ?? '';

// ----------------------------------------------------
// 1. UPLOAD IMAGE (Public or Private)
// ----------------------------------------------------
if ($action === 'upload') {
    if (!isset($_SESSION['User'])) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized! Please login to upload images.']);
        exit();
    }

    $user = $_SESSION['User'];
    $user_id = (int)$user['id'];
    $user_name = mysqli_real_escape_string($conn, $user['Name']);
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $visibility = mysqli_real_escape_string($conn, trim($_POST['visibility'] ?? 'public'));

    // Validate inputs
    if (empty($description)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter an image description.']);
        exit();
    }

    if (!in_array($visibility, ['public', 'private'])) {
        $visibility = 'public';
    }

    if (!isset($_FILES['gallery_image']) || $_FILES['gallery_image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'Please select a valid image file to upload.']);
        exit();
    }

    $file = $_FILES['gallery_image'];
    $fileName = basename($file['name']);
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic'];
    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(['status' => 'error', 'message' => 'Only JPG, JPEG, PNG, GIF, WEBP, and HEIC image files are allowed.']);
        exit();
    }

    if ($fileSize > 10 * 1024 * 1024) { // 10MB limit
        echo json_encode(['status' => 'error', 'message' => 'File size exceeds maximum limit of 10MB.']);
        exit();
    }

    // Save locally to uploads/ folder
    $newFileName = time() . '_' . rand(1000, 9999) . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $fileName);
    $uploadPath = 'uploads/' . $newFileName;

    if (!move_uploaded_file($fileTmp, $uploadPath)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save image to uploads folder.']);
        exit();
    }

    // Upload / Sync to Google Drive with strict privacy controls
    $gdriveRes = uploadToGoogleDrive($uploadPath, $newFileName, $file['type'], $visibility);
    $gdrive_link = mysqli_real_escape_string($conn, $gdriveRes['view_link']);

    // Save into Database
    $sql = "INSERT INTO gallery (user_id, user_name, description, image_path, visibility, gdrive_link) 
            VALUES ('$user_id', '$user_name', '$description', '$uploadPath', '$visibility', '$gdrive_link')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Image uploaded successfully to server & Google Drive! 🎉',
            'visibility' => $visibility
        ]);
    } else {
        // Delete uploaded file if DB query fails
        @unlink($uploadPath);
        echo json_encode(['status' => 'error', 'message' => 'Database Insertion Error: ' . mysqli_error($conn)]);
    }
    exit();
}

// ----------------------------------------------------
// 2. FETCH PUBLIC GALLERY IMAGES (For Public Gallery.php)
// ----------------------------------------------------
if ($action === 'fetch_public') {
    $sql = "SELECT * FROM gallery WHERE visibility = 'public' ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $images = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $images[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $images]);
    exit();
}

// ----------------------------------------------------
// 3. FETCH USER GALLERY IMAGES (For Dashboard User_Gallery.php)
// ----------------------------------------------------
if ($action === 'fetch_user') {
    if (!isset($_SESSION['User'])) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized!']);
        exit();
    }

    $user_id = (int)$_SESSION['User']['id'];
    $filter = mysqli_real_escape_string($conn, $_GET['filter'] ?? 'all');

    $filterSql = "";
    if ($filter === 'public') {
        $filterSql = " AND visibility = 'public'";
    } elseif ($filter === 'private') {
        $filterSql = " AND visibility = 'private'";
    }

    $sql = "SELECT * FROM gallery WHERE user_id = $user_id $filterSql ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $images = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $images[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $images]);
    exit();
}

// ----------------------------------------------------
// 4. DELETE PRIVATE / OWNED IMAGE
// ----------------------------------------------------
if ($action === 'delete') {
    if (!isset($_SESSION['User'])) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized! Please login first.']);
        exit();
    }

    $user_id = (int)$_SESSION['User']['id'];
    $id = (int)($_POST['id'] ?? 0);

    // Verify ownership
    $checkSql = "SELECT * FROM gallery WHERE id = $id AND user_id = $user_id";
    $checkRes = mysqli_query($conn, $checkSql);

    if ($checkRes && mysqli_num_rows($checkRes) > 0) {
        $imgData = mysqli_fetch_assoc($checkRes);
        
        // Delete image file from server if exists
        if (!empty($imgData['image_path']) && file_exists($imgData['image_path'])) {
            @unlink($imgData['image_path']);
        }

        // Delete from Database
        $deleteSql = "DELETE FROM gallery WHERE id = $id AND user_id = $user_id";
        if (mysqli_query($conn, $deleteSql)) {
            echo json_encode(['status' => 'success', 'message' => 'Image deleted successfully! 🗑️']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete from database.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Image not found or you do not have permission to delete it.']);
    }
    exit();
}
?>
