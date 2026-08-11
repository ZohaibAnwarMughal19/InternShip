<?php
session_start();
if (!isset($_SESSION['User'])) {
    header("Location: login.php");
    exit();
}
$currentUser = $_SESSION['User'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Chat & Network - Baba Guru Nanak University</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #faf5ef;
            color: #3b0764;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-wrapper {
            width: 95%;
            max-width: 1200px;
            margin: 10px auto 40px auto;
            flex: 1;
        }

        /* Hero Header Section */
        .chat-hero {
            background: linear-gradient(135deg, #2e1065 0%, #4c1d95 50%, #6b21a8 100%);
            color: white;
            padding: 30px 25px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.25);
            border: 1px solid #c084fc;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .chat-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 5px;
            background: linear-gradient(90deg, #fbbf24 0%, #d97706 50%, #f59e0b 100%);
        }

        .chat-hero h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .chat-hero p {
            font-size: 15px;
            color: #e9d5ff;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Nav Tabs */
        .tab-nav {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            border-bottom: 2px solid #e9d5ff;
            padding-bottom: 10px;
            flex-wrap: wrap;
        }

        .tab-btn {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #475569;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.04);
        }

        .tab-btn:hover {
            border-color: #7e22ce;
            color: #7e22ce;
            transform: translateY(-1px);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #4c1d95 0%, #6b21a8 100%);
            color: #ffffff;
            border-color: #6b21a8;
            box-shadow: 0 6px 18px rgba(107, 33, 168, 0.3);
        }

        .badge-count {
            background: #f59e0b;
            color: #ffffff;
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 800;
        }

        .badge-count.hidden { display: none; }

        /* Container Content Panels */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* ----------------------------------------------------------------- */
        /* TAB 1: NETWORK & REQUESTS STYLING */
        /* ----------------------------------------------------------------- */
        .network-grid {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .section-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }

        .section-title {
            font-size: 20px;
            font-weight: 800;
            color: #3b0764;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #faf5ef;
            padding-bottom: 12px;
        }

        .search-bar-box {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .search-bar-box input {
            flex: 1;
            padding: 12px 18px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 15px;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-bar-box input:focus {
            border-color: #7e22ce;
            box-shadow: 0 0 0 3px rgba(126, 34, 206, 0.15);
        }

        .users-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 18px;
        }

        .user-card {
            background: #faf5ef;
            border-radius: 14px;
            padding: 18px;
            border: 1px solid #e9d5ff;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            transition: all 0.25s ease;
        }

        .user-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(76, 29, 149, 0.12);
            border-color: #c084fc;
        }

        .user-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fbbf24;
            margin-bottom: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .user-avatar-placeholder {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            border: 3px solid #fbbf24;
            margin-bottom: 12px;
        }

        .user-name {
            font-size: 17px;
            font-weight: 700;
            color: #2e1065;
            margin-bottom: 4px;
        }

        .user-email {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 4px;
            word-break: break-all;
        }

        .user-city {
            font-size: 12px;
            color: #d97706;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .btn-act-request {
            width: 100%;
            padding: 9px 15px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-send {
            background: linear-gradient(135deg, #4c1d95 0%, #6b21a8 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(107, 33, 168, 0.25);
        }
        .btn-send:hover { opacity: 0.9; transform: translateY(-1px); }

        .btn-accept {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            margin-bottom: 6px;
        }
        .btn-accept:hover { opacity: 0.9; }

        .btn-reject {
            background: #ef4444;
            color: white;
        }
        .btn-reject:hover { background: #dc2626; }

        .btn-cancel {
            background: #cbd5e1;
            color: #334155;
        }
        .btn-cancel:hover { background: #94a3b8; color: white; }

        .status-badge-connected {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            width: 100%;
            text-align: center;
        }

        /* ----------------------------------------------------------------- */
        /* TAB 2: ACTIVE CHAT WINDOW STYLING */
        /* ----------------------------------------------------------------- */
        .chat-container {
            display: flex;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            height: 650px;
            overflow: hidden;
        }

        /* Left Friends Sidebar */
        .chat-sidebar {
            width: 340px;
            background: #faf5ef;
            border-right: 1px solid #e9d5ff;
            display: flex;
            flex-direction: column;
        }

        .chat-sidebar-header {
            padding: 18px 20px;
            background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);
            color: white;
            font-size: 17px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .friends-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .friend-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 6px;
            border: 1px solid transparent;
        }

        .friend-item:hover {
            background: #f3e8ff;
            border-color: #d8b4fe;
        }

        .friend-item.active {
            background: linear-gradient(135deg, #4c1d95 0%, #6b21a8 100%);
            color: white;
            border-color: #6b21a8;
        }

        .friend-item.active .friend-name { color: #ffffff; }
        .friend-item.active .friend-preview { color: #e9d5ff; }

        .friend-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fbbf24;
            flex-shrink: 0;
        }

        .friend-avatar-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #cbd5e1;
            color: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #fbbf24;
            flex-shrink: 0;
        }

        .friend-info {
            flex: 1;
            overflow: hidden;
        }

        .friend-name {
            font-size: 15px;
            font-weight: 700;
            color: #2e1065;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .friend-preview {
            font-size: 12px;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
        }

        .unread-badge {
            background: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 12px;
        }

        /* Right Active Chat Box */
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #ffffff;
        }

        .chat-header {
            padding: 16px 25px;
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .chat-header-info h3 {
            font-size: 18px;
            color: #2e1065;
            font-weight: 700;
        }

        .chat-header-info p {
            font-size: 13px;
            color: #64748b;
        }

        .messages-area {
            flex: 1;
            padding: 20px 25px;
            overflow-y: auto;
            background: #faf5ef;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .empty-chat-state {
            margin: auto;
            text-align: center;
            color: #64748b;
        }

        .empty-chat-state font {
            font-size: 50px;
            display: block;
            margin-bottom: 10px;
        }

        /* Message Bubbles */
        .message-bubble {
            max-width: 65%;
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 15px;
            line-height: 1.5;
            position: relative;
            word-wrap: break-word;
            box-shadow: 0 3px 8px rgba(0,0,0,0.04);
        }

        .message-bubble.mine {
            align-self: flex-end;
            background: linear-gradient(135deg, #4c1d95 0%, #6b21a8 100%);
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }

        .message-bubble.other {
            align-self: flex-start;
            background: #ffffff;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 10px;
            margin-top: 6px;
            display: block;
            text-align: right;
            opacity: 0.85;
        }

        .message-bubble.mine .message-time { color: #fef08a; }
        .message-bubble.other .message-time { color: #94a3b8; }

        /* Chat Input Box */
        .chat-input-area {
            padding: 16px 20px;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .chat-input-area input {
            flex: 1;
            padding: 12px 18px;
            border-radius: 25px;
            border: 1px solid #cbd5e1;
            font-size: 15px;
            outline: none;
            transition: all 0.2s ease;
        }

        .chat-input-area input:focus {
            border-color: #7e22ce;
            box-shadow: 0 0 0 3px rgba(126, 34, 206, 0.12);
        }

        .btn-send-msg {
            background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-send-msg:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
        }

        /* Alert notification toast */
        #toast-alert {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            padding: 14px 22px;
            border-radius: 12px;
            color: white;
            font-weight: bold;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            display: none;
            max-width: 350px;
        }

        .btn-back-nav {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            color: #4c1d95;
            border: 1.5px solid #c084fc;
            padding: 10px 22px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(76, 29, 149, 0.08);
            margin-bottom: 18px;
            cursor: pointer;
        }

        .btn-back-nav:hover {
            background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);
            color: #ffffff;
            border-color: #4c1d95;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(76, 29, 149, 0.25);
        }

        @media (max-width: 768px) {
            .chat-container {
                flex-direction: column;
                height: auto;
            }
            .chat-sidebar {
                width: 100%;
                height: 220px;
            }
            .chat-main {
                height: 450px;
            }
        }
    </style>
</head>
<body>

    <!-- Header & Menu Navigation -->
    <?php include('header.php'); ?>
    <?php include('menu.php'); ?>

    <div class="main-wrapper">
        
        <!-- Back Navigation Button -->
        <a href="Dashbord.php" class="btn-back-nav">
            ⬅ Back to Dashboard
        </a>

        <!-- Hero Header -->
        <div class="chat-hero">
            <h1>💬 BGNU Student Network & Chat</h1>
            <p>Connect with fellow students, send connection requests, and start instant conversations in core PHP.</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="tab-nav">
            <button class="tab-btn active" id="tab-btn-chat" onclick="switchTab('chat')">
                💬 Active Messages 
                <span class="badge-count hidden" id="unread-msg-badge">0</span>
            </button>
            <button class="tab-btn" id="tab-btn-network" onclick="switchTab('network')">
                👥 Find Connections & Requests
                <span class="badge-count hidden" id="pending-req-badge">0</span>
            </button>
        </div>

        <!-- TAB 1: CHAT WINDOW -->
        <div class="tab-content active" id="tab-chat">
            <div class="chat-container">
                
                <!-- Left Connections Sidebar -->
                <div class="chat-sidebar">
                    <div class="chat-sidebar-header">
                        <span>🤝 My Connections</span>
                        <span style="font-size: 13px; font-weight: normal; opacity: 0.8;" id="friends-count">0 Friends</span>
                    </div>
                    <div class="friends-list" id="friends-list-box">
                        <div style="text-align:center; padding: 30px; color:#64748b;">Loading connections...</div>
                    </div>
                </div>

                <!-- Right Main Conversation Panel -->
                <div class="chat-main">
                    
                    <!-- Header of selected friend -->
                    <div class="chat-header" id="chat-header-box" style="display: none;">
                        <div id="chat-header-avatar-box"></div>
                        <div class="chat-header-info">
                            <h3 id="chat-header-name">Student Name</h3>
                            <p id="chat-header-details">Email & City</p>
                        </div>
                    </div>

                    <!-- Messages feed -->
                    <div class="messages-area" id="messages-area">
                        <div class="empty-chat-state">
                            <font>💬</font>
                            <h3>Select a connection from the left to start chatting!</h3>
                            <p style="margin-top:5px; font-size:14px;">If you don't have friends yet, click <b>"Find Connections & Requests"</b> above to send requests.</p>
                        </div>
                    </div>

                    <!-- Input bar -->
                    <div class="chat-input-area" id="chat-input-box" style="display: none;">
                        <input type="text" id="msg-input" placeholder="Type your message here..." autocomplete="off">
                        <button class="btn-send-msg" onclick="sendMessage()">Send 📤</button>
                    </div>

                </div>

            </div>
        </div>

        <!-- TAB 2: NETWORK & REQUESTS -->
        <div class="tab-content" id="tab-network">
            <div class="network-grid">
                
                <!-- Incoming & Sent Requests Card -->
                <div class="section-card">
                    <div class="section-title">
                        <span>📩 Incoming & Pending Requests</span>
                    </div>
                    <div class="users-list" id="requests-list-box">
                        <div style="text-align:center; padding:20px; color:#64748b; grid-column:1/-1;">Checking requests...</div>
                    </div>
                </div>

                <!-- All Students Directory -->
                <div class="section-card">
                    <div class="section-title">
                        <span>🌐 BGNU Students Directory</span>
                    </div>
                    <div class="search-bar-box">
                        <input type="text" id="user-search-input" placeholder="🔍 Search students by Name, Email, or City..." onkeyup="fetchNetworkUsers()">
                    </div>
                    <div class="users-list" id="all-users-box">
                        <div style="text-align:center; padding:20px; color:#64748b; grid-column:1/-1;">Loading student directory...</div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Footer -->
    <?php include('footer.php'); ?>

    <!-- Toast alert for dynamic messages -->
    <div id="toast-alert"></div>

    <script>
        let currentFriendId = null;
        let pollInterval = null;

        $(document).ready(function() {
            fetchFriendsList();
            fetchNetworkUsers();
            checkBadges();

            // Set auto polling every 3 seconds for realtime updates
            pollInterval = setInterval(function() {
                checkBadges();
                if (currentFriendId) {
                    fetchMessages(currentFriendId, false); // silent refresh
                }
                fetchFriendsList(false);
            }, 3000);

            // Press enter to send message
            $('#msg-input').on('keypress', function(e) {
                if (e.which === 13) {
                    sendMessage();
                }
            });
        });

        // Switch between Chat and Network Tabs
        function switchTab(tabName) {
            $('.tab-btn').removeClass('active');
            $('.tab-content').removeClass('active');

            if (tabName === 'chat') {
                $('#tab-btn-chat').addClass('active');
                $('#tab-chat').addClass('active');
                fetchFriendsList();
            } else {
                $('#tab-btn-network').addClass('active');
                $('#tab-network').addClass('active');
                fetchNetworkUsers();
            }
        }

        // Show toast notification
        function showToast(message, isSuccess = true) {
            const toast = $('#toast-alert');
            toast.css('background', isSuccess ? '#16a34a' : '#dc2626');
            toast.text(message).fadeIn(300);
            setTimeout(() => toast.fadeOut(300), 3500);
        }

        // Check unread badges
        function checkBadges() {
            $.getJSON('Chat_BE.php?action=get_total_unread', function(res) {
                if (res.status === 'success') {
                    if (res.unread_messages > 0) {
                        $('#unread-msg-badge').text(res.unread_messages).removeClass('hidden');
                    } else {
                        $('#unread-msg-badge').addClass('hidden');
                    }

                    if (res.pending_requests > 0) {
                        $('#pending-req-badge').text(res.pending_requests).removeClass('hidden');
                    } else {
                        $('#pending-req-badge').addClass('hidden');
                    }
                }
            });
        }

        // ---------------------------------------------------------------
        // NETWORK USERS & REQUESTS LOGIC
        // ---------------------------------------------------------------
        function fetchNetworkUsers() {
            const searchVal = $('#user-search-input').val();
            $.getJSON('Chat_BE.php?action=fetch_all_users&search=' + encodeURIComponent(searchVal), function(res) {
                if (res.status === 'success') {
                    renderNetworkCards(res.data);
                }
            });
        }

        function renderNetworkCards(users) {
            const allUsersBox = $('#all-users-box');
            const requestsBox = $('#requests-list-box');

            allUsersBox.empty();
            requestsBox.empty();

            let pendingRequestsCount = 0;

            if (users.length === 0) {
                allUsersBox.html('<div style="text-align:center; padding:20px; color:#64748b; grid-column:1/-1;">No students found matching search criteria.</div>');
            }

            users.forEach(user => {
                const avatarHtml = user.File_Path && user.File_Path.trim() !== '' 
                    ? `<img src="${user.File_Path}" class="user-avatar" alt="${user.Name}">`
                    : `<div class="user-avatar-placeholder">👤</div>`;

                let actionButtonHtml = '';

                if (user.rel_status === 'none' || user.rel_status === 'rejected_sent' || user.rel_status === 'rejected_received') {
                    actionButtonHtml = `<button class="btn-act-request btn-send" onclick="sendRequest(${user.id})">➕ Send Request</button>`;
                } else if (user.rel_status === 'sent_pending') {
                    actionButtonHtml = `<button class="btn-act-request btn-cancel" onclick="cancelRequest(${user.request_id})">⌛ Cancel Request</button>`;
                } else if (user.rel_status === 'received_pending') {
                    actionButtonHtml = `
                        <button class="btn-act-request btn-accept" onclick="respondRequest(${user.request_id}, 'accept')">✔ Accept</button>
                        <button class="btn-act-request btn-reject" onclick="respondRequest(${user.request_id}, 'reject')">✖ Reject</button>
                    `;
                    pendingRequestsCount++;

                    // Add to incoming requests box as well
                    const reqCard = `
                        <div class="user-card">
                            ${avatarHtml}
                            <div class="user-name">${user.Name}</div>
                            <div class="user-email">📧 ${user.Email}</div>
                            <div class="user-city">🏙️ ${user.City || 'BGNU Student'}</div>
                            <div style="width:100%; margin-top:8px;">
                                ${actionButtonHtml}
                            </div>
                        </div>
                    `;
                    requestsBox.append(reqCard);
                } else if (user.rel_status === 'accepted') {
                    actionButtonHtml = `<div class="status-badge-connected">🤝 Connected</div>`;
                }

                const userCard = `
                    <div class="user-card">
                        ${avatarHtml}
                        <div class="user-name">${user.Name}</div>
                        <div class="user-email">📧 ${user.Email}</div>
                        <div class="user-city">🏙️ ${user.City || 'BGNU Student'}</div>
                        <div style="width:100%; margin-top:8px;">
                            ${actionButtonHtml}
                        </div>
                    </div>
                `;
                allUsersBox.append(userCard);
            });

            if (pendingRequestsCount === 0) {
                requestsBox.html('<div style="text-align:center; padding:15px; color:#64748b; grid-column:1/-1;">No incoming connection requests at the moment.</div>');
            }
        }

        function sendRequest(receiverId) {
            $.post('Chat_BE.php', { action: 'send_request', receiver_id: receiverId }, function(res) {
                if (res.status === 'success' || res.status === 'info') {
                    showToast(res.message, true);
                    fetchNetworkUsers();
                    checkBadges();
                } else {
                    showToast(res.message, false);
                }
            }, 'json');
        }

        function respondRequest(requestId, responseType) {
            $.post('Chat_BE.php', { action: 'respond_request', request_id: requestId, response: responseType }, function(res) {
                if (res.status === 'success') {
                    showToast(res.message, true);
                    fetchNetworkUsers();
                    fetchFriendsList();
                    checkBadges();
                } else {
                    showToast(res.message, false);
                }
            }, 'json');
        }

        function cancelRequest(requestId) {
            $.post('Chat_BE.php', { action: 'cancel_request', request_id: requestId }, function(res) {
                if (res.status === 'success') {
                    showToast(res.message, true);
                    fetchNetworkUsers();
                    checkBadges();
                } else {
                    showToast(res.message, false);
                }
            }, 'json');
        }

        // ---------------------------------------------------------------
        // FRIENDS & MESSAGING LOGIC
        // ---------------------------------------------------------------
        function fetchFriendsList(updateUI = true) {
            $.getJSON('Chat_BE.php?action=fetch_friends', function(res) {
                if (res.status === 'success') {
                    const friends = res.data;
                    $('#friends-count').text(friends.length + ' Connections');
                    const friendsBox = $('#friends-list-box');

                    if (friends.length === 0) {
                        friendsBox.html('<div style="text-align:center; padding:30px; color:#64748b;">No connections yet.<br><br>Go to <b>"Find Connections"</b> tab to add friends!</div>');
                        return;
                    }

                    if (updateUI) {
                        friendsBox.empty();
                        friends.forEach(friend => {
                            const avatarHtml = friend.File_Path && friend.File_Path.trim() !== ''
                                ? `<img src="${friend.File_Path}" class="friend-avatar">`
                                : `<div class="friend-avatar-placeholder">👤</div>`;

                            const unreadBadge = friend.unread_count > 0 
                                ? `<span class="unread-badge">${friend.unread_count}</span>` 
                                : '';

                            const activeClass = (currentFriendId && currentFriendId == friend.id) ? 'active' : '';

                            const item = `
                                <div class="friend-item ${activeClass}" id="friend-item-${friend.id}" onclick="selectFriend(${friend.id})">
                                    ${avatarHtml}
                                    <div class="friend-info">
                                        <div class="friend-name">${friend.Name}</div>
                                        <div class="friend-preview">${friend.last_message || 'Start conversation...'}</div>
                                    </div>
                                    ${unreadBadge}
                                </div>
                            `;
                            friendsBox.append(item);
                        });
                    }
                }
            });
        }

        function selectFriend(friendId) {
            currentFriendId = friendId;
            $('.friend-item').removeClass('active');
            $(`#friend-item-${friendId}`).addClass('active');
            
            fetchMessages(friendId, true);
        }

        function fetchMessages(friendId, scrollBottom = false) {
            $.getJSON('Chat_BE.php?action=fetch_messages&friend_id=' + friendId, function(res) {
                if (res.status === 'success') {
                    const friend = res.friend;
                    const messages = res.messages;

                    // Render header
                    const avatarHtml = friend.File_Path && friend.File_Path.trim() !== ''
                        ? `<img src="${friend.File_Path}" style="width:45px; height:45px; border-radius:50%; object-fit:cover; border:2px solid #fbbf24;">`
                        : `<div style="width:45px; height:45px; border-radius:50%; background:#e2e8f0; display:flex; align-items:center; justify-content:center; font-size:22px; border:2px solid #fbbf24;">👤</div>`;

                    $('#chat-header-avatar-box').html(avatarHtml);
                    $('#chat-header-name').text(friend.Name);
                    $('#chat-header-details').text(`📧 ${friend.Email} | 🏙️ ${friend.City || 'BGNU Student'}`);
                    
                    $('#chat-header-box').show();
                    $('#chat-input-box').show();

                    // Render messages
                    const area = $('#messages-area');
                    area.empty();

                    if (messages.length === 0) {
                        area.html(`
                            <div class="empty-chat-state">
                                <font>👋</font>
                                <h3>Say Hi to ${friend.Name}!</h3>
                                <p style="margin-top:5px; font-size:14px;">This is the beginning of your conversation.</p>
                            </div>
                        `);
                    } else {
                        messages.forEach(msg => {
                            const bubbleClass = msg.is_mine ? 'mine' : 'other';
                            const bubbleHtml = `
                                <div class="message-bubble ${bubbleClass}">
                                    ${escapeHtml(msg.message)}
                                    <span class="message-time">${msg.formatted_time}</span>
                                </div>
                            `;
                            area.append(bubbleHtml);
                        });
                    }

                    if (scrollBottom) {
                        area.scrollTop(area[0].scrollHeight);
                    }
                }
            });
        }

        function sendMessage() {
            if (!currentFriendId) {
                showToast('Please select a friend to chat with!', false);
                return;
            }

            const input = $('#msg-input');
            const text = input.val().trim();

            if (text === '') return;

            $.post('Chat_BE.php', {
                action: 'send_message',
                receiver_id: currentFriendId,
                message: text
            }, function(res) {
                if (res.status === 'success') {
                    input.val('');
                    fetchMessages(currentFriendId, true);
                    fetchFriendsList(true);
                } else {
                    showToast(res.message, false);
                }
            }, 'json');
        }

        function escapeHtml(text) {
            return $('<div>').text(text).html();
        }
    </script>
</body>
</html>
