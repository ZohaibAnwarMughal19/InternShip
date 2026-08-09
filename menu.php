<style>
.bgnu-menu-bar {
    width: 95%;
    max-width: 1200px;
    margin: 10px auto 25px auto;
    background: linear-gradient(135deg, #2e1065 0%, #4c1d95 50%, #6b21a8 100%);
    border-radius: 14px;
    padding: 10px 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.25);
    border: 1px solid #c084fc;
}

.bgnu-menu-link {
    color: #ffffff;
    text-decoration: none;
    font-weight: 700;
    font-size: 15px;
    padding: 9px 22px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

.bgnu-menu-link:hover {
    background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
    color: #ffffff;
    border-color: #fbbf24;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(245, 158, 11, 0.4);
}

@media (max-width: 600px) {
    .bgnu-menu-bar {
        padding: 10px;
        gap: 8px;
    }
    .bgnu-menu-link {
        font-size: 13px;
        padding: 7px 12px;
        flex: 1 1 40%;
        justify-content: center;
        text-align: center;
    }
}
</style>

<nav class="bgnu-menu-bar">
    <a href="Dashbord.php" class="bgnu-menu-link">🏠 Dashboard</a>
    <a href="Contact_List.php" class="bgnu-menu-link">📇 Contact List</a>
    <a href="login.php" class="bgnu-menu-link">🔑 Login</a>
    <a href="SignUp.php" class="bgnu-menu-link">📝 Register</a>
</nav>