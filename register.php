<?php
require_once ('pdo.php');

session_start();

if (isset($_SESSION['username'])) {
   header("Location: home.php");
}



if (isset($_POST['subm'])){
    $username = ($_POST['username']) ;
    $firstname = ($_POST['fname']) ;
    $lastname = ($_POST['lname']) ;
    $email = ($_POST['email']) ;
    $password = ($_POST['password']) ;

    if($_POST['gender_id'] == 'male'){
        $gender_id = 2 ;
    } elseif($_POST['gender_id'] == 'female') {
        $gender_id = 1 ;
    }

    //echo $email , $firstname , $lastname , $password , $username , $gender_id;

    $errors = [];

    if (strlen($username) < 6) {
        array_push($errors, "Username must be more than 6 characters!");
    }
    elseif (strlen($username) >100 ){
        array_push($errors, "Username must be less than or equal 100 characters!");
    }

    if (strlen($password) < 8) {
       array_push($errors, "Password must be more than or equal 8 characters!");
    }

    if (strlen($email) == 0){
        array_push($errors, "Please enter an email !");
    }

    if (count($errors) <= 0) {
        $query = "SELECT COUNT(*) AS K FROM users WHERE username=:usn;";
        $query2 = "SELECT COUNT(*) AS K2 FROM users WHERE email=:emal;";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ":usn" => $username,
          //  ":emal" => $email
        ]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record["K"] > 0) {
            array_push($errors, "username is already taken");
        }

        $stmt2 = $pdo->prepare($query2);
        $stmt2->execute([
            ":emal" => $email,
        ]);
        $record2 = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($record2["K2"] > 0) {
            array_push($errors, "email is already taken");
        }

    }

    if(count($errors) == 0){

        $query3 = "INSERT INTO `users`(`firstname`, `lastname`, `username`, `email`, `password`, `gender_id`) 
                   VALUES ('$firstname', '$lastname ', '$username' , '$email' , '$password' , $gender_id);";

        $stmt3 = $pdo -> prepare($query3) ;
        $stmt3->execute();


        $_SESSION['username'] = $username;

        header("Location: home.php");
    }

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Social App</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/all.min.css">
</head>
<body>

<div class="container my-5">
    <div class="row">
        <div class="col-12 col-lg-8 col-xl-6 offset-0 offset-lg-2 offset-xl-3">
            <div class="card shadow shadow-lg">
                <div class="card-body">
                    <h2 class="text-center font-weight-bold">
                        New Account
                    </h2>
                    <form action="" method="POST">
                        <div class="form-group row">
                            <label for="fname" class="col-form-label col-6">
                                First Name
                            </label>
                            <label for="lname" class="col-form-label col-6">
                                Last Name
                            </label>
                            <div class="col-6">
                                <input type="text" class="form-control"
                                       id="fname"
                                       name="fname">
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control"
                                       id="lname"
                                       name="lname">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-form-label col-12">
                                E-mail
                            </label>
                            <div class="col-12">
                                <input type="text" class="form-control"
                                       id="email"
                                       name="email">
                            </div>
                        </div>
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
                        <div class="form-group row">
                            <label for="gender" class="col-form-label col-12">
                                Gender
                            </label>
                            <div class="col-12">
                                <input type="radio" id="male" name="gender_id" value="male">
                                <label for="male">Male</label>
                                <input class="ml-5" type="radio" id="female" name="gender_id" value="female">
                                <label for="female">Female</label>
                            </div>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-success btn-block" type="submit" name="subm">
                                Create an account
                            </button>
                        </div>
                        <div class="text-right">
                            <small>
                                <a  class="mt-5" href="/login.php">already have an account?</a>
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