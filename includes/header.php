<link rel="stylesheet" href="/Cinema_Booking_System/assets/css/header.css?v=20250811">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<header>
    <div class="logo">
        <i class="fas fa-film"></i> AuraCinema
    </div>
    <nav class="nav-links">
        <a href="/Cinema_Booking_System/index.php">Home</a>
        <a href="/Cinema_Booking_System/user/movie_details.php">Movies</a>
        <a href="/Cinema_Booking_System/user/select_seats.php">Bookings</a>
        <a href="/Cinema_Booking_System/user/contact.php">Contact</a>
    </nav>
    <div class="profile">
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
        $firstLetter = strtoupper(substr($_SESSION['user_name'], 0, 1));
        $fullName = htmlspecialchars($_SESSION['user_name']);
        $userEmail = isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : '';
        $userContact = isset($_SESSION['user_contact']) ? htmlspecialchars($_SESSION['user_contact']) : '';
        echo '<div class="sidepanel-user-menu" id="sidepanelUserMenu">';
        echo '<div class="user-initial-large" id="userInitialLarge">' . $firstLetter . '</div>';
        echo '<div class="user-info">';
        echo '<span class="welcome">Welcome</span>';
        echo '<span class="user-fullname">' . $fullName . '</span>';
        if ($userEmail) echo '<span class="user-email">' . $userEmail . '</span>';
        if ($userContact) echo '<span class="user-contact">' . $userContact . '</span>';
        echo '</div>';
        echo '<div class="sidepanel-links">';
        echo '<a href="#" class="sidepanel-link">Profile</a>';
        echo '<a href="#" class="sidepanel-link">QuikPay</a>';
        echo '<a href="/Cinema_Booking_System/user/select_seats.php" class="sidepanel-link">Booking History</a>';
        echo '<a href="#" class="sidepanel-link">Support</a>';
        echo '<a href="#" class="sidepanel-link">Settings</a>';
        echo '</div>';
        echo '<a href="/Cinema_Booking_System/user/userlogin/logout.php" class="sidepanel-signout">Sign Out</a>';
        echo '</div>';
        echo '<div class="sidepanel-overlay" id="sidepanelOverlay"></div>';
        echo '<div class="user-initial" id="userInitial" title="Account">' . $firstLetter . '</div>';
    } else {
        echo '<a href="/Cinema_Booking_System/user/userlogin/userlogin.html" class="login-btn">Login</a>';
    }
    ?>
    </div>
</header>
<script>
// Sidepanel open/close logic
document.addEventListener('DOMContentLoaded', function() {
    var userInitial = document.getElementById('userInitial');
    var sidepanel = document.getElementById('sidepanelUserMenu');
    var overlay = document.getElementById('sidepanelOverlay');
    if(userInitial && sidepanel && overlay) {
        userInitial.addEventListener('click', function(e) {
            e.stopPropagation();
            sidepanel.classList.add('open');
            overlay.classList.add('show');
        });
        overlay.addEventListener('click', function() {
            sidepanel.classList.remove('open');
            overlay.classList.remove('show');
        });
    }
});
</script>
<script>
// Toggle dropdown on click
document.addEventListener('DOMContentLoaded', function() {
    var userInitial = document.getElementById('userInitial');
    var dropdownContent = document.getElementById('dropdownContent');
    if(userInitial && dropdownContent) {
        userInitial.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
        });
        document.addEventListener('click', function() {
            dropdownContent.style.display = 'none';
        });
    }
});
</script>
<style>
    .user-contact {
        color: #888;
        font-size: 0.95rem;
        display: block;
        margin-bottom: 10px;
    }
    .user-initial {
        width: 40px;
        height: 40px;
        background: #5a0404;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin-left: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        cursor: pointer;
        transition: background 0.3s;
        position: relative;
        z-index: 1101;
    }
    .user-initial-large {
        width: 60px;
        height: 60px;
        background: #e0e0e0;
        color: #5a0404;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        font-weight: bold;
        margin: 30px auto 10px auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    }
    .sidepanel-user-menu {
        position: fixed;
        top: 0;
        right: -350px;
        width: 350px;
        height: 100vh;
        background: #fff;
        box-shadow: -2px 0 16px rgba(0,0,0,0.18);
        z-index: 1200;
        transition: right 0.3s cubic-bezier(.4,0,.2,1);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0;
    }
    .sidepanel-user-menu.open {
        right: 0;
    }
    .sidepanel-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0,0,0,0.25);
        z-index: 1199;
    }
    .sidepanel-overlay.show {
        display: block;
    }
    .user-info {
        text-align: center;
        margin-bottom: 20px;
    }
    .welcome {
        color: #888;
        font-size: 1rem;
        display: block;
        margin-bottom: 2px;
    }
    .user-fullname {
        color: #222;
        font-size: 1.2rem;
        font-weight: bold;
        display: block;
        margin-bottom: 2px;
    }
    .user-email {
        color: #888;
        font-size: 0.95rem;
        display: block;
        margin-bottom: 10px;
    }
    .sidepanel-links {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 0;
        margin-bottom: 30px;
    }
    .sidepanel-link {
        width: 100%;
        color: #222;
        background: none;
        padding: 18px 30px;
        text-decoration: none;
        font-size: 1.08rem;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s, color 0.2s;
        text-align: left;
        font-weight: 500;
    }
    .sidepanel-link:hover {
        background: #f5eaea;
        color: #5a0404;
    }
    .sidepanel-signout {
        width: 90%;
        margin: 30px auto 0 auto;
        display: block;
        color: #fff;
        background: #1976d2;
        padding: 15px 0;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.1rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        transition: background 0.2s;
    }
    .sidepanel-signout:hover {
        background: #0d47a1;
    }
</style>
<style>
.user-initial {
    width: 40px;
    height: 40px;
    background: #5a0404;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    margin-left: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
</style>
