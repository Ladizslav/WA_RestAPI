<?php
require_once "./classes/User.php";
require_once "./classes/DBC.php";
session_start();

if (empty($_POST["username"]) || empty($_POST["password"])) {
    header('Location: index.php');
    exit();
}

$username = $_POST["username"];
$password = $_POST["password"];

$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

$hash = password_hash($password, PASSWORD_DEFAULT);

$query = DBC::getConnection()->prepare("INSERT INTO uzivatel (username, password) VALUES (:username, :password)");
$query->bindParam(':username', $username, PDO::PARAM_STR);
$query->bindParam(':password', $hash, PDO::PARAM_STR);

$query->execute();

$_SESSION['username'] = $username;
$_SESSION["loggedin"] = true;

header('Location: index.php');
exit();
