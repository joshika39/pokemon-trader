<?php

// Register a new user

require "db/stores.php";

$users = new UserStorage();

if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["name"]) && isset($_POST["email"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $name = $_POST["name"];
    $email = $_POST["email"];

    $user = $users->findOne(["username" => $username]);

    if ($user) {
        echo "A felhasználónév már foglalt!";
    }
    else{
        $users->add(["username" => $username, "password" => $password, "email" => $email, "name" => $name, "balance" => 1000, "cards" => []]);
        header("Location: login.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IKémon | Regisztráció</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
<header>
    <h1><a href="index.php">IKémon</a> > Regisztráció</h1>
</header>
<div id="content">
    <form action="register.php" method="post">
        <label for="username">Felhasználónév</label>
        <input type="text" name="username" id="username">
        <label for="email">Email</label>
        <input type="email" name="email" id="email">
        <label for="password">Jelszó</label>
        <input type="password" name="password" id="password">
        <label for="name">Név</label>
        <input type="text" name="name" id="name">
        <button type="submit">Regisztráció</button>
    </form>
</div>
<footer>
    <p>IKémon | ELTE IK Webprogramozás</p>
</footer>
</body>
</html>
