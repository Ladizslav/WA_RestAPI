<?php
require_once "./classes/DBC.php";
session_start();

if (isset($_POST['post_id']) && isset($_POST['text_edit'])) {
    $postId = $_POST['post_id'];
    $textEdit = trim($_POST['text_edit']); 

    $textEdit = htmlspecialchars($textEdit, ENT_QUOTES, 'UTF-8');
    edit($postId, $textEdit);
}

header('Location: threads_page.php');
exit();

function edit(int $id, string $text)
{
    $query = DBC::getConnection()->prepare("UPDATE blogs SET text = :text WHERE ID = :id");
    $query->bindParam(':text', $text, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
}
?>
