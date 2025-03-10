<?php
session_start();

class Logout {
    public function __construct() {
        $this->logoutUser();
    }

    public function logoutUser() {
        if (isset($_SESSION['user_id'])) {
            session_unset();
            session_destroy();
        }
        header("Location: ../index.php");
        exit();
    }
}

// Initialize Logout
new Logout();
?>
