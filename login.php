<?php
require_once ('pdo.php');

session_start();

if (isset($_SESSION['username'])) {
    header("Location: home.php");
}



if (isset($_POST['username']) &&
    isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $errors = [];

    if (strlen($username) == 0) {
        array_push($errors, "Please enter valid username !");
    }
    if (strlen($password) == 0) {
        array_push($errors, "Please enter a password !");
    }

    if (count($errors) <= 0) {
        $query = "SELECT COUNT(*) AS K FROM users WHERE username=:usn AND password=:pwd;";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ":usn" => $username,
            ":pwd" => $password
        ]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record["K"] > 0) {
            $_SESSION['username'] = $username;
            $_SESSION['token'] = sha1(time());
            session_commit();

            header("Location: home.php");
        } else {
            array_push($errors, "Authentication failed.");
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Social App</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/all.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-lg-8 col-xl-6 offset-0 offset-lg-2 offset-xl-3">
            <div class="card shadow shadow-lg">
                <div class="card-body">
                    <h2 class="text-center font-weight-bold">
                        Login
                    </h2>
                    <hr>
                    <form action="" method="POST">
                        <div class="form-group row">
                            <label for="username" class="col-form-label col-12">
                                Username
                            </label>
                            <div class="col-12">
                                <input type="text" class="form-control"
                                       id="username"
                                       name="username">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-form-label col-12">
                                Password
                            </label>
                            <div class="col-12">
                                <input type="password" class="form-control"
                                       id="password"
                                       name="password">
                            </div>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-success btn-block" type="submit">
                                Login
                            </button>
                        </div>
                        <div class="text-right">
                            <small>
                                <a  class="mt-5" href="/register.php">New account ?</a>
                            </small>
                        </div>
                    </form>

                    <div class="mt-2 mb-0">
                        <ul class="list-unstyled">
                            <?php if (isset($errors)) { ?>
                                <?php for ($i = 0; $i < count($errors); $i++) { ?>
                                    <li class="text-danger">
                                        <i class="fa fa-caret-right"></i>
                                        <?php echo $errors[$i]; ?>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="/assets/js/jquery-3.4.1.min.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script src="/assets/js/fontawesome.min.js"></script>
</body>
</html>
