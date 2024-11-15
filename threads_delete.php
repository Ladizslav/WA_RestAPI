<?php
require_once "./classes/DBC.php";
session_start();


if (isset($_POST['post_id'])) {
    $postId = $_POST['post_id'];

    delete($postId);
}

header('Location: threads_page.php');
exit();

function delete(int $id)
{
    try {
        $query = DBC::getConnection()->prepare("DELETE FROM blogs WHERE ID = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    } catch (Exception $e) {
        header('Location: threads_page.php?error=Unable to delete post');
        exit();
    }
}
