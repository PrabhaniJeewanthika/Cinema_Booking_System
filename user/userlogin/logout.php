<?php
class UserLogout {
    public function logout() {
        session_start();
        // Destroy session and redirect to home page
        session_unset();
        session_destroy();
        header("Location: /Cinema_Booking_System/index.php");
        exit();
    }
}

$logout = new UserLogout();
$logout->logout();
?>
