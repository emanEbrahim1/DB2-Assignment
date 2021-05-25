
<?php
require_once('pdo.php');
session_start();


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    return;
}

$username = $_SESSION['username'];

$query = "SELECT id FROM users WHERE username=:usn ;" ;
$stmt = $pdo->prepare($query) ;
$stmt->execute([
        ":usn" => $username
]);

$id = $stmt-> fetch() ;


$query2 = "SELECT * FROM posts WHERE user_id =:id ;" ;
$stmt2 = $pdo->prepare($query2) ;
$stmt2->execute([
    ":id" => $id[0]
]);

$posts = $stmt2 -> fetchAll() ;



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Social App</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/all.min.css">
</head>
<body>
<?php include_once('common/navbar.php'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6 offset-0 offset-md-2 offset-lg-3">
            <div class="card shadow shadow-lg">
                <div class="card-body">
                    <h3 class="text-center display-4">
                        Your Posts
                    </h3>

                    <ul>
                        <?php foreach($posts as $i => $posts){?>
                        <li class="mt-4">
                            <?php echo $posts['content'] ;?>
                            <br>
                            <p>
                                Likes :-
                            </p>
                            <?php
                            $pst = $posts['id'] ;
                            $query3 = "SELECT * FROM likes WHERE post_id =:pst ;" ;
                            $stmt3 = $pdo->prepare($query3) ;
                            $stmt3->execute([
                                ":pst" => $pst
                            ]);

                            $likes = $stmt3->fetchAll() ;

                            foreach ($likes as $i => $like){
                                $query4 = "SELECT username FROM users where id =:usid" ;
                                $stmt4 = $pdo->prepare($query4) ;
                                $stmt4->execute([
                                        ":usid" => $like['user_id']
                                ]);

                                $user = $stmt4->fetch() ;
                                echo $user['username']." , ";
                            }

                            ?>

                            <br>
                            <br>
                            <p>
                                comments :-
                            </p>
                            <?php
                            $pst = $posts['id'] ;
                            $query3 = "SELECT * FROM comments WHERE post_id =:pst ;" ;
                            $stmt3 = $pdo->prepare($query3) ;
                            $stmt3->execute([
                                ":pst" => $pst
                            ]);

                            $comments = $stmt3->fetchAll() ;

                            ?>

                            <ul>
                                <?php foreach ($comments as $i => $comment){?>
                                <li>
                                   <?php echo $comment['content'] ; ?>
                                </li>
                                <?php }?>
                            </ul>

                        </li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="my-5"></div>
</div>

<script src="/assets/js/jquery-3.4.1.min.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script src="/assets/js/fontawesome.min.js"></script>
</body>
</html>
