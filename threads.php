<?php
require_once "./classes/DBC.php";
require_once "./classes/User.php";
session_start();

if (isset($_POST['text']) && !empty($_POST['text'])) {
    $text = trim($_POST['text']);

    if (isset($_SESSION["username"])) {
        $query = DBC::getConnection()->prepare("CALL addblog(:text, :username)");
        $query->bindParam(':text', $text, PDO::PARAM_STR);
        $query->bindParam(':username', $_SESSION["username"], PDO::PARAM_STR);

        if ($query->execute()) {
            header('Location: threads_page.php');
            exit();
        } else {
            echo "Chyba při vkládání příspěvku.";
        }
    } else {
        echo "Prosím, přihlaste se před přidáním příspěvku.";
    }
} else {
    echo "Text příspěvku nemůže být prázdný.";
}
?>
