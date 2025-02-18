<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your title</title>
    <link rel="stylesheet" href="./assets/style.css">
</head>
<body>


<?php
    $isConnected = isset($_SESSION["id"]);
?>

<header>
    <nav>
        <ul>
            <li class="home"><a href="./">Home</a></li>          
            <?php  if($isConnected){ ?>
                <li><a href="./create.php">Create</a></li>
                <li><a href="./logout.php">Logout</a></li>
            <?php } else {?>
                <li><a href="./login.php">Login</a></li>
                <li><a href="./subscription.php">Signin</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>