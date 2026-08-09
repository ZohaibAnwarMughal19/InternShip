<?php
include('db.php');

// 1. Fetch Table Rows (Ordered by Favorites first, then newest)
if (isset($_GET['action']) && $_GET['action'] === 'fetch') {
    $result = mysqli_query($conn, "SELECT * FROM contacts ORDER BY is_favorite DESC, id DESC");
    
    if ($result && mysqli_num_rows($result) > 0) {
        $sr = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $phone = preg_replace('/[^0-9]/', '', $row['mobile_no']);
            $wa_phone = (substr($phone, 0, 2) === '03') ? '92' . substr($phone, 1) : $phone;
            
            // Avatar / Profile Pic
            if (!empty($row['profile_pic']) && file_exists($row['profile_pic'])) {
                $img = "<img src='{$row['profile_pic']}' class='avatar-img'>";
            } else {
                $first_letter = strtoupper(substr($row['name'], 0, 1));
                $img = "<div class='avatar-placeholder'>{$first_letter}</div>";
            }

            // Favorite Star Icon
            $is_fav = ($row['is_favorite'] == 1);
            $star_class = $is_fav ? 'btn-star active' : 'btn-star';
            $star_label = $is_fav ? '⭐ Fav' : '☆ Fav';
            $fav_title = $is_fav ? 'Unstar Contact' : 'Mark as Favorite';

            // Favorite row highlight style
            $row_class = $is_fav ? 'fav-row' : '';

            echo "<tr class='{$row_class}'>
                <td><b>{$sr}</b></td>
                <td>{$img}</td>
                <td>
                    <b>" . htmlspecialchars($row['name']) . "</b>
                    " . ($is_fav ? "<span class='fav-badge'>⭐ Favorite</span>" : "") . "
                </td>
                <td><a href='mailto:{$row['email']}' style='color:#003366; text-decoration:none;'>" . htmlspecialchars($row['email']) . "</a></td>
                <td>" . htmlspecialchars($row['mobile_no']) . "</td>
                <td>" . htmlspecialchars($row['gender']) . "</td>
                <td>
                    <div class='action-buttons'>
                        <button type='button' onclick='toggleFavorite({$id})' class='btn-act {$star_class}' title='{$fav_title}'>{$star_label}</button>
                        <a href='https://wa.me/{$wa_phone}' target='_blank' class='btn-act btn-wa' title='Chat on WhatsApp'>💬 WA</a>
                        <a href='mailto:{$row['email']}' class='btn-act btn-email' title='Send Email'>✉️ Email</a>
                        <button type='button' onclick=\"makeCall('{$row['mobile_no']}')\" class='btn-act btn-call' title='Call Contact'>📞 Call</button>
                        <button type='button' onclick='openEditModal({$id})' class='btn-act btn-edit' title='Edit Contact'>✏️ Edit</button>
                        <button type='button' onclick=\"deleteContact({$id}, '" . addslashes(htmlspecialchars($row['name'])) . "')\" class='btn-act btn-delete' title='Delete Contact'>🗑️ Delete</button>
                    </div>
                </td>
            </tr>";
            $sr++;
        }
    } else {
        echo "<tr><td colspan='7' class='no-data'>No contacts found. Fill out the form above to add your first contact!</td></tr>";
    }
    exit;
}

// 2. Fetch Single Contact JSON for Edit Modal
if (isset($_GET['action']) && $_GET['action'] === 'get_single' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $res = mysqli_query($conn, "SELECT * FROM contacts WHERE id = $id");
    if ($res && mysqli_num_rows($res) > 0) {
        $contact = mysqli_fetch_assoc($res);
        header('Content-Type: application/json');
        echo json_encode($contact);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Contact not found']);
    }
    exit;
}

// 3. Toggle Favorite Status
if (isset($_GET['action']) && $_GET['action'] === 'toggle_favorite' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $check = mysqli_query($conn, "SELECT is_favorite FROM contacts WHERE id = $id");
    if ($check && mysqli_num_rows($check) > 0) {
        $curr = mysqli_fetch_assoc($check)['is_favorite'];
        $new_fav = ($curr == 1) ? 0 : 1;
        mysqli_query($conn, "UPDATE contacts SET is_favorite = $new_fav WHERE id = $id");
        echo 'success';
    }
    exit;
}

// 4. Delete Contact
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $check = mysqli_query($conn, "SELECT profile_pic FROM contacts WHERE id = $id");
    if ($check && mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        if (!empty($row['profile_pic']) && file_exists($row['profile_pic'])) {
            @unlink($row['profile_pic']); // Remove image file
        }
        mysqli_query($conn, "DELETE FROM contacts WHERE id = $id");
        echo 'success';
    } else {
        echo 'Contact not found';
    }
    exit;
}

// 5. Update Contact (Edit Form Submit)
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)($_POST['edit_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile_no = trim($_POST['mobile_no'] ?? '');
    $gender = trim($_POST['gender'] ?? '');

    if (!$id || !$name || !$email || !$mobile_no || !$gender) {
        echo "Please fill in all required fields!";
        exit;
    }

    $pic_sql = "";
    if (!empty($_FILES['edit_myfile']['name'])) {
        $new_path = 'uploads/' . time() . '_' . basename($_FILES['edit_myfile']['name']);
        if (move_uploaded_file($_FILES['edit_myfile']['tmp_name'], $new_path)) {
            // Delete old picture if exists
            $old_res = mysqli_query($conn, "SELECT profile_pic FROM contacts WHERE id = $id");
            if ($old_res && $old_row = mysqli_fetch_assoc($old_res)) {
                if (!empty($old_row['profile_pic']) && file_exists($old_row['profile_pic'])) {
                    @unlink($old_row['profile_pic']);
                }
            }
            $pic_sql = ", profile_pic = '$new_path'";
        }
    }

    $sql = "UPDATE contacts SET name = '$name', email = '$email', mobile_no = '$mobile_no', gender = '$gender' {$pic_sql} WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "Failed to update contact!";
    }
    exit;
}

// 6. Save New Contact (Form Submit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile_no = trim($_POST['mobile_no'] ?? '');
    $gender = trim($_POST['gender'] ?? '');

    if (!$name || !$email || !$mobile_no || !$gender) {
        echo "Please fill in all required fields!";
        exit;
    }

    $uploads_path = '';
    if (!empty($_FILES['myfile']['name'])) {
        $uploads_path = 'uploads/' . time() . '_' . basename($_FILES['myfile']['name']);
        move_uploaded_file($_FILES['myfile']['tmp_name'], $uploads_path);
    }

    $sql = "INSERT INTO contacts (name, email, mobile_no, gender, profile_pic) VALUES ('$name', '$email', '$mobile_no', '$gender', '$uploads_path')";
    
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "Database error occurred!";
    }
}
?>
