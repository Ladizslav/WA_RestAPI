<?php
require_once "./classes/DBC.php";
session_start();

if (empty($_POST["username"]) || empty($_POST["password"])) {
    header('Location: login_page.php?error=Please fill out both fields');
    exit();
}

$username = $_POST["username"];
$password = $_POST["password"];

verifyUser($username, $password);

function verifyUser(string $username, string $password): void
{
    $connection = DBC::getConnection();
    
    $statement = $connection->prepare("SELECT id, username, admin, password FROM uzivatel WHERE username = :username LIMIT 1");
    $statement->execute([":username" => $username]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result && password_verify($password, $result["password"])) {
        $_SESSION["user_id"] = $result["id"];
        $_SESSION["user_name"] = $result["username"];
        $_SESSION["admin"] = $result["admin"];
        $_SESSION["loggedin"] = true;

        header("Location: index.php"); 
        exit();
    } else {
        header("Location: login_page.php?error=Invalid username or password");
        exit();
    }
}
?>
