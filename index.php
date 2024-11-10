<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
            <?php
            session_start();
              if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                echo '<li class="nav-item">';
                echo  '<a class="nav-link active" aria-current="page" href="logout.php">Log out</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a class="nav-link" href="threads_page.php">Threads</a>';
                echo ' </li>';
              }else{
                echo '<li class="nav-item">';
                echo  '<a class="nav-link active" aria-current="page" href="login_page.php">Login</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo  '<a class="nav-link active" aria-current="page" href="register_page.php">Register</a>';
                echo '</li>';
              }
              ?>
            </ul>
          </div>
        </div>
      </nav>

      <div class="container mt-5">
        <h1>Vítejte na naší komunitní platformě!</h1>
        <p>Jsme tu, abychom vás inspirovali a poskytli prostor pro vaše nápady, diskuse a kreativitu. Na této stránce najdete prostor pro sdílení myšlenek, příběhů, a debat s lidmi, kteří mají podobné zájmy.</p>
        <p>Nezáleží na tom, zda chcete diskutovat o aktuálních trendech, hledáte inspiraci pro váš další projekt nebo se jen chcete podělit o své myšlenky – tady jste správně. Připojte se k našim <a href="threads_page.php">vlákům</a>, podělte se o své příspěvky, a zapojte se do komunity!</p>
        <p>Připojte se k nám ještě dnes a společně vytvoříme prostor, kde mohou růst vaše nápady.</p>
    </div>
</body>
</html>
