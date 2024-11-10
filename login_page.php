<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Home</a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php
                    session_start();
                    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                        echo '<li class="nav-item"><a class="nav-link active" href="logout.php">Log out</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link active" href="login_page.php">Login</a></li>';
                        echo '<li class="nav-item"><a class="nav-link active" href="register_page.php">Register</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Login</h2>
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($_GET['error']) . '</div>';
        }
        ?>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
