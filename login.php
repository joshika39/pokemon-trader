<?php

// Login page
session_start();

require("db/stores.php");

$users = new UserStorage();

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $user = $users->findOne(["username" => $username]);

    if ($user && $user["password"] == $password) {
        $_SESSION["user"] = $username;
        header("Location: index.php");
        exit();
    }
    else{
        echo "Hibás felhasználónév vagy jelszó!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IKémon | Bejelentkezés</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
<header>
    <h1><a href="index.php">IKémon</a> > Bejelentkezés</h1>
</header>
<div id="content">
    <form action="login.php" method="post">
        <label for="username">Felhasználónév</label>
        <input type="text" name="username" id="username">
        <label for="password">Jelszó</label>
        <input type="password" name="password" id="password">
        <button type="submit">Bejelentkezés</button>
    </form>
</div>
<footer>
    <p>IKémon | ELTE IK Webprogramozás</p>
</footer>
</body>
</html>