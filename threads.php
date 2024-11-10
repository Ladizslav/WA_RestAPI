<?php
require_once "./classes/DBC.php";
require_once "./classes/User.php";
session_start();


if (isset($_POST['text']) && !empty($_POST['text'])) {
    $text = trim($_POST['text']);
    
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    if (isset($_SESSION["username"])) {
        $query = DBC::getConnection()->prepare("CALL addblog(:text, :username)");
        $query->bindParam(':text', $text, PDO::PARAM_STR);
        $query->bindParam(':username', $_SESSION["username"], PDO::PARAM_STR);

        try {
            if ($query->execute()) {
                header('Location: threads_page.php?success=Post created successfully');
                exit();
            } else {
                header('Location: threads_page.php?error=Error adding post');
                exit();
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            header('Location: threads_page.php?error=Database error');
            exit();
        }
    } else {
        header('Location: login_page.php');
        exit();
    }
} else {
    header('Location: threads_page.php?error=Text cannot be empty');
    exit();
}
?>
