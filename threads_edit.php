<?php
require_once "./classes/DBC.php";
session_start();

if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: threads_page.php?error=CSRF token mismatch');
    exit();
}

if (isset($_POST['post_id']) && isset($_POST['text_edit'])) {
    $postId = $_POST['post_id'];
    $textEdit = trim($_POST['text_edit']);

    if (!empty($textEdit)) {
        $textEdit = htmlspecialchars($textEdit, ENT_QUOTES, 'UTF-8');
        edit($postId, $textEdit);
    } else {
        header('Location: threads_page.php?error=Text cannot be empty');
        exit();
    }
}

header('Location: threads_page.php');
exit();

function edit(int $id, string $text)
{
    try {
        $query = DBC::getConnection()->prepare("UPDATE blogs SET text = :text WHERE ID = :id");
        $query->bindParam(':text', $text, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    } catch (Exception $e) {
        header('Location: threads_page.php?error=Unable to edit post');
        exit();
    }
}
