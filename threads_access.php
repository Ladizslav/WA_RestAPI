<?php
require_once "./classes/DBC.php";
session_start();

if (isset($_POST['post_id']) && isset($_POST['user_add'])) {
    $postId = $_POST['post_id'];
    $userAdd = $_POST['user_add'];

    addaccess($postId, $userAdd);
}

header('Location: threads_page.php');
exit();

function addaccess(int $id, string $user)
{
    $user = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');

    $query = DBC::getConnection()->prepare("CALL addaccess(:id, :user)");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->bindParam(':user', $user, PDO::PARAM_STR);
    $query->execute();
}
