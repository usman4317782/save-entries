<?php
include __DIR__.'/../classes/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $db = Database::getInstance()->getConnection();
    $delete_id = htmlspecialchars($_POST['delete_id']);

    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Failed to delete user!";
    }
}
?>
