<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['User'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized! Please login first.']);
    exit();
}

$currentUser = $_SESSION['User'];
$current_user_id = (int)$currentUser['id'];

$action = $_REQUEST['action'] ?? '';

// ----------------------------------------------------
// 1. FETCH ALL USERS WITH CONNECTION REQUEST STATUS
// ----------------------------------------------------
if ($action === 'fetch_all_users') {
    $search = mysqli_real_escape_string($conn, trim($_GET['search'] ?? ''));
    $searchSql = "";
    if (!empty($search)) {
        $searchSql = " AND (Name LIKE '%$search%' OR Email LIKE '%$search%' OR City LIKE '%$search%')";
    }

    $sql = "SELECT id, Name, Email, Phone_No, Gender, City, File_Path FROM users WHERE id != $current_user_id $searchSql ORDER BY Name ASC";
    $res = mysqli_query($conn, $sql);

    $users = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $other_id = (int)$row['id'];

        // Check request status between current_user_id and other_id
        $req_sql = "SELECT * FROM chat_requests 
                    WHERE (sender_id = $current_user_id AND receiver_id = $other_id) 
                       OR (sender_id = $other_id AND receiver_id = $current_user_id) 
                    LIMIT 1";
        $req_res = mysqli_query($conn, $req_sql);

        $rel_status = 'none'; // 'none', 'sent_pending', 'received_pending', 'accepted', 'rejected'
        $request_id = null;
        $sender_id = null;

        if ($req_res && mysqli_num_rows($req_res) > 0) {
            $req_row = mysqli_fetch_assoc($req_res);
            $request_id = (int)$req_row['id'];
            $sender_id = (int)$req_row['sender_id'];

            if ($req_row['status'] === 'accepted') {
                $rel_status = 'accepted';
            } elseif ($req_row['status'] === 'pending') {
                if ($sender_id === $current_user_id) {
                    $rel_status = 'sent_pending';
                } else {
                    $rel_status = 'received_pending';
                }
            } elseif ($req_row['status'] === 'rejected') {
                $rel_status = ($sender_id === $current_user_id) ? 'rejected_sent' : 'rejected_received';
            }
        }

        $row['rel_status'] = $rel_status;
        $row['request_id'] = $request_id;
        $row['sender_id'] = $sender_id;
        $users[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $users]);
    exit();
}

// ----------------------------------------------------
// 2. SEND CONNECTION REQUEST
// ----------------------------------------------------
if ($action === 'send_request') {
    $receiver_id = (int)($_POST['receiver_id'] ?? 0);

    if ($receiver_id <= 0 || $receiver_id === $current_user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid receiver user ID.']);
        exit();
    }

    // Check if request already exists
    $check_sql = "SELECT * FROM chat_requests 
                  WHERE (sender_id = $current_user_id AND receiver_id = $receiver_id) 
                     OR (sender_id = $receiver_id AND receiver_id = $current_user_id) 
                  LIMIT 1";
    $check_res = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_res) > 0) {
        $existing = mysqli_fetch_assoc($check_res);
        if ($existing['status'] === 'accepted') {
            echo json_encode(['status' => 'info', 'message' => 'You are already connected with this user!']);
        } elseif ($existing['status'] === 'pending') {
            echo json_encode(['status' => 'info', 'message' => 'A pending connection request already exists.']);
        } else {
            // Re-send request if previously rejected
            $upd_sql = "UPDATE chat_requests SET sender_id = $current_user_id, receiver_id = $receiver_id, status = 'pending', updated_at = NOW() WHERE id = " . (int)$existing['id'];
            if (mysqli_query($conn, $upd_sql)) {
                echo json_encode(['status' => 'success', 'message' => 'Chat request sent successfully! 📩']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to re-send request: ' . mysqli_error($conn)]);
            }
        }
        exit();
    }

    $ins_sql = "INSERT INTO chat_requests (sender_id, receiver_id, status) VALUES ($current_user_id, $receiver_id, 'pending')";
    if (mysqli_query($conn, $ins_sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Connection request sent successfully! 📩']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . mysqli_error($conn)]);
    }
    exit();
}

// ----------------------------------------------------
// 3. RESPOND TO REQUEST (ACCEPT / REJECT)
// ----------------------------------------------------
if ($action === 'respond_request') {
    $request_id = (int)($_POST['request_id'] ?? 0);
    $response = mysqli_real_escape_string($conn, $_POST['response'] ?? '');

    if (!in_array($response, ['accept', 'reject'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action response.']);
        exit();
    }

    $status = ($response === 'accept') ? 'accepted' : 'rejected';

    // Ensure current user is the receiver of the request
    $check_sql = "SELECT * FROM chat_requests WHERE id = $request_id AND receiver_id = $current_user_id LIMIT 1";
    $check_res = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_res) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Request not found or permission denied.']);
        exit();
    }

    $upd_sql = "UPDATE chat_requests SET status = '$status', updated_at = NOW() WHERE id = $request_id";
    if (mysqli_query($conn, $upd_sql)) {
        $msg = ($response === 'accept') ? 'Request accepted! You can now chat 🎉' : 'Request rejected.';
        echo json_encode(['status' => 'success', 'message' => $msg]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . mysqli_error($conn)]);
    }
    exit();
}

// ----------------------------------------------------
// 4. CANCEL / DELETE SENT REQUEST
// ----------------------------------------------------
if ($action === 'cancel_request') {
    $request_id = (int)($_POST['request_id'] ?? 0);

    $del_sql = "DELETE FROM chat_requests WHERE id = $request_id AND (sender_id = $current_user_id OR receiver_id = $current_user_id)";
    if (mysqli_query($conn, $del_sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Request cancelled/removed.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . mysqli_error($conn)]);
    }
    exit();
}

// ----------------------------------------------------
// 5. FETCH ACCEPTED FRIENDS / CONNECTIONS LIST
// ----------------------------------------------------
if ($action === 'fetch_friends') {
    $sql = "SELECT u.id, u.Name, u.Email, u.File_Path, u.City,
                (SELECT COUNT(*) FROM chat_messages m WHERE m.sender_id = u.id AND m.receiver_id = $current_user_id AND m.is_read = 0) as unread_count,
                (SELECT message FROM chat_messages m 
                 WHERE (m.sender_id = u.id AND m.receiver_id = $current_user_id) 
                    OR (m.sender_id = $current_user_id AND m.receiver_id = u.id) 
                 ORDER BY m.id DESC LIMIT 1) as last_message,
                (SELECT created_at FROM chat_messages m 
                 WHERE (m.sender_id = u.id AND m.receiver_id = $current_user_id) 
                    OR (m.sender_id = $current_user_id AND m.receiver_id = u.id) 
                 ORDER BY m.id DESC LIMIT 1) as last_time
            FROM users u
            INNER JOIN chat_requests r ON 
                (r.sender_id = u.id AND r.receiver_id = $current_user_id AND r.status = 'accepted')
             OR (r.receiver_id = u.id AND r.sender_id = $current_user_id AND r.status = 'accepted')
            ORDER BY last_time DESC, u.Name ASC";

    $res = mysqli_query($conn, $sql);
    $friends = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $friends[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $friends]);
    exit();
}

// ----------------------------------------------------
// 6. FETCH CONVERSATION MESSAGES WITH SELECTED FRIEND
// ----------------------------------------------------
if ($action === 'fetch_messages') {
    $friend_id = (int)($_GET['friend_id'] ?? 0);

    if ($friend_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid friend ID.']);
        exit();
    }

    // Verify they are accepted friends
    $verify_sql = "SELECT * FROM chat_requests 
                   WHERE ((sender_id = $current_user_id AND receiver_id = $friend_id) 
                      OR (sender_id = $friend_id AND receiver_id = $current_user_id)) 
                     AND status = 'accepted' LIMIT 1";
    $verify_res = mysqli_query($conn, $verify_sql);

    if (mysqli_num_rows($verify_res) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'You are not connected with this user.']);
        exit();
    }

    // Mark unread incoming messages as read
    $read_sql = "UPDATE chat_messages SET is_read = 1 WHERE sender_id = $friend_id AND receiver_id = $current_user_id AND is_read = 0";
    mysqli_query($conn, $read_sql);

    // Fetch messages history
    $msg_sql = "SELECT m.*, 
                       DATE_FORMAT(m.created_at, '%h:%i %p | %b %d') as formatted_time
                FROM chat_messages m
                WHERE (m.sender_id = $current_user_id AND m.receiver_id = $friend_id)
                   OR (m.sender_id = $friend_id AND m.receiver_id = $current_user_id)
                ORDER BY m.id ASC";

    $msg_res = mysqli_query($conn, $msg_sql);
    $messages = [];

    while ($row = mysqli_fetch_assoc($msg_res)) {
        $row['is_mine'] = ((int)$row['sender_id'] === $current_user_id);
        $messages[] = $row;
    }

    // Fetch friend details
    $friend_info_sql = "SELECT id, Name, Email, Phone_No, File_Path, City FROM users WHERE id = $friend_id LIMIT 1";
    $friend_info_res = mysqli_query($conn, $friend_info_sql);
    $friend_info = mysqli_fetch_assoc($friend_info_res);

    echo json_encode([
        'status' => 'success',
        'friend' => $friend_info,
        'messages' => $messages
    ]);
    exit();
}

// ----------------------------------------------------
// 7. SEND MESSAGE TO FRIEND
// ----------------------------------------------------
if ($action === 'send_message') {
    $receiver_id = (int)($_POST['receiver_id'] ?? 0);
    $message = mysqli_real_escape_string($conn, trim($_POST['message'] ?? ''));

    if ($receiver_id <= 0 || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Please type a valid message.']);
        exit();
    }

    // Verify connection status
    $verify_sql = "SELECT * FROM chat_requests 
                   WHERE ((sender_id = $current_user_id AND receiver_id = $receiver_id) 
                      OR (sender_id = $receiver_id AND receiver_id = $current_user_id)) 
                     AND status = 'accepted' LIMIT 1";
    $verify_res = mysqli_query($conn, $verify_sql);

    if (mysqli_num_rows($verify_res) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'You cannot send messages to this user until the connection request is accepted.']);
        exit();
    }

    $sql = "INSERT INTO chat_messages (sender_id, receiver_id, message, is_read) 
            VALUES ($current_user_id, $receiver_id, '$message', 0)";

    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            'status' => 'success',
            'message_id' => mysqli_insert_id($conn)
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message: ' . mysqli_error($conn)]);
    }
    exit();
}

// ----------------------------------------------------
// 8. UNREAD BADGE COUNTER FOR NAVBAR / OVERALL SYSTEM
// ----------------------------------------------------
if ($action === 'get_total_unread') {
    $unread_sql = "SELECT COUNT(*) as unread FROM chat_messages WHERE receiver_id = $current_user_id AND is_read = 0";
    $unread_res = mysqli_query($conn, $unread_sql);
    $unread_row = mysqli_fetch_assoc($unread_res);

    $pending_sql = "SELECT COUNT(*) as pending_req FROM chat_requests WHERE receiver_id = $current_user_id AND status = 'pending'";
    $pending_res = mysqli_query($conn, $pending_sql);
    $pending_row = mysqli_fetch_assoc($pending_res);

    echo json_encode([
        'status' => 'success',
        'unread_messages' => (int)$unread_row['unread'],
        'pending_requests' => (int)$pending_row['pending_req']
    ]);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Invalid Action']);
exit();
?>
