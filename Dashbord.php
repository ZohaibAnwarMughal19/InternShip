<?php
session_start();
if (!isset($_SESSION['User'])) {
    header("Location: login.php");
    exit();
}
$userData = $_SESSION['User'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo htmlspecialchars($userData['Name']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background: #faf5ef;
            min-height: 100vh;
            color: #3b0764;
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        /* LEFT SIDEBAR */
        .Left {
            width: 380px; 
            background: linear-gradient(180deg, #2e1065 0%, #4c1d95 50%, #6b21a8 100%);
            color: white;
            padding: 40px 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 5px 0 25px rgba(76,29,149,0.15);
            position: relative;
        }
        
        .profile-container {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .profile-pic {
            width: 160px; 
            height: 160px;
            border-radius: 50%;
            border: 4px solid #fbbf24;
            object-fit: cover;
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
            transition: transform 0.3s ease;
        }
        
        .profile-pic:hover {
            transform: scale(1.05);
        }
        
        .avatar-placeholder {
            width: 160px;
            height: 160px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 70px;
            border: 4px solid #fbbf24;
            margin: 0 auto;
        }
        
        .welcome-text {
            font-size: 18px; 
            color: #fef08a;
            margin-top: 10px;
            font-weight: 600;
            text-align: center;
        }

        .user-name-title {
            font-size: 26px;
            font-weight: 800;
            margin: 8px 0 20px 0;
            text-align: center;
            color: #ffffff;
        }

        /* User Details Card Box */
        .info-card {
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 14px;
            padding: 22px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 20px;
        }

        .info-item {
            margin-bottom: 14px;
            font-size: 15px;
            line-height: 1.4;
            word-break: break-word;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.75);
            font-weight: 700;
            display: block;
            margin-bottom: 2px;
        }

        .info-value {
            color: #ffffff;
            font-weight: 600;
            font-size: 15px;
        }

        .info-value.highlight-yellow { color: #fde047; }
        .info-value.highlight-green { color: #86efac; }
        .info-value.highlight-orange { color: #fb923c; }
        .info-value.highlight-pink { color: #f472b6; }

        .btn-nav-contacts {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
            border: 1px solid #fbbf24;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            display: block;
            margin-bottom: 15px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
        }

        .btn-nav-contacts:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(245, 158, 11, 0.45);
        }

        .logout-form {
            width: 100%;
            margin-top: auto;
        }

        .btn-logout {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(220, 38, 38, 0.35);
        }
        
        /* RIGHT CONTENT PANEL */
        .Right {
            flex: 1;
            background: #faf5ef;
            padding: 50px 40px;
            overflow-y: auto;
        }
        
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        #H1S {
            text-align: center;
            color: #3b0764;
            font-size: 38px;
            margin-bottom: 40px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            padding-bottom: 15px;
        }
        
        #H1S::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #3b0764 0%, #7e22ce 50%, #f59e0b 100%);
            border-radius: 2px;
        }
        
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .card {
            background: #ffffff;
            padding: 30px 25px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.08);
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e9d5ff;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #3b0764 0%, #7e22ce 50%, #f59e0b 100%);
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(76, 29, 149, 0.15);
            border-color: #c084fc;
        }
        
        .card h3 {
            color: #3b0764;
            margin-bottom: 12px;
            font-size: 22px;
            font-weight: 800;
        }
        
        .card p {
            color: #4b5563;
            font-size: 15px;
            line-height: 1.6;
        }
        
        /* RESPONSIVE */
        @media (max-width: 992px) {
            .container {
                flex-direction: column;
            }
            .Left {
                width: 100%;
                padding: 30px 20px;
            }
            .info-card {
                max-width: 500px;
            }
            .Right {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    
    <div class="container">
        <!-- LEFT SIDEBAR -->
        <div class="Left">
            <div class="profile-container">
                <?php if (!empty($userData['File_Path'])) { ?>
                    <img src="<?php echo htmlspecialchars($userData['File_Path']); ?>" class="profile-pic" alt="Profile Picture">
                <?php } else { ?>
                    <div class="avatar-placeholder">👤</div>
                <?php } ?>
            </div>

            <div class="welcome-text">🎉 Welcome Back!</div>
            <div class="user-name-title"><?php echo htmlspecialchars($userData['Name']); ?></div>

            <!-- User Information Box -->
            <div class="info-card">
                <div class="info-item">
                    <span class="info-label">📧 Email Address</span>
                    <span class="info-value"><?php echo htmlspecialchars($userData['Email']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">📱 Phone Number</span>
                    <span class="info-value highlight-yellow"><?php echo htmlspecialchars($userData['Phone_No']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">👫 Gender</span>
                    <span class="info-value highlight-green"><?php echo htmlspecialchars($userData['Gender']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">⚽ Hobbies</span>
                    <span class="info-value highlight-orange"><?php echo htmlspecialchars($userData['Hobbies']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">🏠 Address</span>
                    <span class="info-value highlight-pink"><?php echo htmlspecialchars($userData['Address']); ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">🏙️ City</span>
                    <span class="info-value"><?php echo htmlspecialchars($userData['City']); ?></span>
                </div>
            </div>

            <!-- Contact List Navigation Button -->
            <a href="Contact_List.php" class="btn-nav-contacts">📇 My Contact Directory</a>
            
            <!-- Gallery Navigation Button -->
            <a href="User_Gallery.php" class="btn-nav-contacts" style="background: linear-gradient(135deg, #7e22ce 0%, #a855f7 100%); border-color: #c084fc;">🖼️ My Photo Gallery & Drive</a>

            <!-- Chat Navigation Button -->
            <a href="Chat.php" class="btn-nav-contacts" style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); border-color: #60a5fa;">💬 Live Chat & Requests</a>

            <!-- Logout Form -->
            <form method="POST" action="Logout.php" class="logout-form">
                <button type="submit" name="logout" class="btn-logout">Logout</button>
            </form>
        </div>
                        
        <!-- RIGHT CONTENT -->
        <div class="Right">
            <div class="content-wrapper">
                <h1 id="H1S">Student Dashboard</h1>
                
                <div class="dashboard-cards">
                    <div class="card" onclick="window.location.href='Chat.php'">
                        <h3>💬 Realtime Chat & Requests</h3>
                        <p>Send connection requests, accept/reject requests, and chat in real-time with fellow students.</p>
                    </div>
                    <div class="card" onclick="window.location.href='User_Gallery.php'">
                        <h3>🖼️ Photo Gallery & Drive</h3>
                        <p>Upload public & private photos, view image descriptions, sync to Google Drive, and manage or delete private photos.</p>
                    </div>
                    <div class="card" onclick="window.location.href='Contact_List.php'">
                        <h3>📇 Contact Directory</h3>
                        <p>Manage your personal contacts, search, WhatsApp, email, call, and star favorite contacts.</p>
                    </div>
                    <div class="card">
                        <h3>📊 Profile Info</h3>
                        <p>View and manage your personal details and account information.</p>
                    </div>
                    <div class="card">
                        <h3>📝 Admission Application</h3>
                        <p>Check your admission status and submission updates.</p>
                    </div>
                    <div class="card">
                        <h3>📚 Courses & Programs</h3>
                        <p>Explore available degree programs and course offerings.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</body>
</html>