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
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            color: #1e293b;
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        /* LEFT SIDEBAR */
        .Left {
            width: 380px; 
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 40px 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 5px 0 20px rgba(0,0,0,0.15);
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
            border: 4px solid rgba(255,255,255,0.6);
            object-fit: cover;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
        }
        
        .profile-pic:hover {
            transform: scale(1.05);
        }
        
        .avatar-placeholder {
            width: 160px;
            height: 160px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 70px;
            border: 4px solid rgba(255,255,255,0.6);
            margin: 0 auto;
        }
        
        .welcome-text {
            font-size: 18px; 
            opacity: 0.9;
            margin-top: 10px;
            font-weight: 500;
            text-align: center;
        }

        .user-name-title {
            font-size: 26px;
            font-weight: 700;
            margin: 10px 0 20px 0;
            text-align: center;
            color: #ffffff;
        }

        /* User Details Card Box */
        .info-card {
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 25px;
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
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
        }

        .info-value {
            color: #ffffff;
            font-weight: 500;
            font-size: 15px;
        }

        .info-value.highlight-yellow { color: #fde047; }
        .info-value.highlight-green { color: #4ade80; }
        .info-value.highlight-orange { color: #fb923c; }
        .info-value.highlight-pink { color: #f472b6; }

        .logout-form {
            width: 100%;
            margin-top: auto;
        }

        .btn-logout {
            width: 100%;
            padding: 12px;
            background-color: #ef4444;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .btn-logout:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
        }
        
        /* RIGHT CONTENT PANEL */
        .Right {
            flex: 1;
            background: #f8fafc;
            padding: 50px 40px;
            overflow-y: auto;
        }
        
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        #H1S {
            text-align: center;
            color: #1e293b;
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
            background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 2px;
        }
        
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .card {
            background: white;
            padding: 30px 25px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        
        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.1);
            border-color: #2a5298;
        }
        
        .card h3 {
            color: #1e293b;
            margin-bottom: 12px;
            font-size: 22px;
            font-weight: 700;
        }
        
        .card p {
            color: #64748b;
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
                    <div class="card">
                        <h3>⚙️ Account Settings</h3>
                        <p>Update security options and profile preferences.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</body>
</html>