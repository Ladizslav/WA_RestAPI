<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="logout.php">Log out</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Threads</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<form action="threads.php" method="post">
    <textarea name="text" rows="3" cols="50" class="form-control"></textarea><br>
    <input type="submit" value="Přidat příspěvek" class="btn btn-primary">
</form>

<?php
session_start();
require_once "./classes/DBC.php";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
}

if (isset($_POST['text']) && !empty($_POST['text'])) {
    $text = $_POST['text'];
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    $query = DBC::getConnection()->prepare("CALL addblog(:text, :username)");
    $query->bindParam(':text', $text, PDO::PARAM_STR);
    $query->bindParam(':username', $_SESSION["username"], PDO::PARAM_STR);
    $query->execute();
}

$query = DBC::getConnection()->prepare("CALL viewblogs(:username)");
$query->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
$query->execute();
$threads = $query->fetchAll();

foreach ($threads as $post) {
    echo '<div class="post mb-3 p-3 border rounded">';
    echo '<p>' . htmlspecialchars($post['text'], ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p>Autor: ' . htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p>Datum: ' . htmlspecialchars($post['date'], ENT_QUOTES, 'UTF-8') . '</p>';

    if ($_SESSION['username'] == $post['username'] || $_SESSION['admin'] == 1) {
        echo '<form action="threads_edit.php" method="post" class="d-inline-block">';
        echo '<input type="text" name="text_edit" class="form-control" placeholder="Upravit příspěvek">';
        echo '<input type="hidden" name="post_id" value="' . $post['ID'] . '">';
        echo '<input type="submit" value="Upravit" class="btn btn-warning btn-sm mt-2">';
        echo '</form>';

        echo '<form action="threads_access.php" method="post" class="d-inline-block ms-2">';
        echo '<input type="text" name="user_add" class="form-control" placeholder="Přidat uživatele">';
        echo '<input type="hidden" name="post_id" value="' . $post['ID'] . '">';
        echo '<input type="submit" value="Přidat uživatele" class="btn btn-info btn-sm mt-2">';
        echo '</form>';

        echo '<form action="threads_delete.php" method="post" class="d-inline-block ms-2">';
        echo '<input type="hidden" name="post_id" value="' . $post['ID'] . '">';
        echo '<input type="submit" value="Smazat" class="btn btn-danger btn-sm mt-2">';
        echo '</form>';
    }

    echo '</div><br>';
}
?>
</body>
</html>
